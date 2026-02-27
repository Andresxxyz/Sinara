<?php
session_start();
require_once("conexao.php"); // Adjust path if needed

$conn = conexao();

$response = ['success' => false, 'message' => 'Erro desconhecido.'];
$idProjeto = null; // Initialize idProjeto

try {
    // Basic validation
    if (!isset($_POST['idProjeto']) || empty($_FILES['imagensProjeto'])) {
        throw new Exception('ID do projeto ou arquivos não recebidos.');
    }

    $idProjeto = filter_var($_POST['idProjeto'], FILTER_VALIDATE_INT);
    if ($idProjeto === false) {
        throw new Exception('ID do projeto inválido.');
    }

    // --- Configuration ---
    $uploadDir = '../../uploads/projetos/'; // Relative path from THIS script
    $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    $maxFileSize = 5 * 1024 * 1024; // 5 MB
    // ---------------------

    // Create upload directory if it doesn't exist
    if (!file_exists($uploadDir) && !mkdir($uploadDir, 0777, true)) {
       throw new Exception('Falha ao criar diretório de uploads.');
    }

    $uploadedFilesInfo = [];
    $errors = [];

    // --- Process each uploaded file ---
    foreach ($_FILES['imagensProjeto']['name'] as $key => $name) {
        if ($_FILES['imagensProjeto']['error'][$key] === UPLOAD_ERR_OK) {
            $tmpName = $_FILES['imagensProjeto']['tmp_name'][$key];
            $fileType = $_FILES['imagensProjeto']['type'][$key];
            $fileSize = $_FILES['imagensProjeto']['size'][$key];

            // Validate file type
            if (!in_array($fileType, $allowedTypes)) {
                $errors[] = "Arquivo '$name': Tipo de arquivo não permitido ($fileType). Permitidos: JPEG, PNG, WEBP, GIF.";
                continue; // Skip this file
            }

            // Validate file size
            if ($fileSize > $maxFileSize) {
                $errors[] = "Arquivo '$name': Tamanho excede o limite de " . ($maxFileSize / 1024 / 1024) . " MB.";
                continue; // Skip this file
            }

            // Generate unique filename
            $fileExtension = pathinfo($name, PATHINFO_EXTENSION);
            $newFileName = uniqid('proj_' . $idProjeto . '_', true) . '.' . strtolower($fileExtension);
            $destinationPath = $uploadDir . $newFileName;
            $relativePath = 'uploads/projetos/' . $newFileName; // Path to store in DB

            // Move the file
            if (move_uploaded_file($tmpName, $destinationPath)) {
                // Prepare to insert into DB
                 $uploadedFilesInfo[] = $relativePath;
            } else {
                $errors[] = "Arquivo '$name': Falha ao mover o arquivo para o destino.";
            }

        } elseif ($_FILES['imagensProjeto']['error'][$key] !== UPLOAD_ERR_NO_FILE) {
            // Handle other upload errors if needed
            $errors[] = "Arquivo '$name': Erro no upload (Código: " . $_FILES['imagensProjeto']['error'][$key] . ").";
        }
    }

    // --- Insert into Database ---
    if (!empty($uploadedFilesInfo)) {
        $conn->begin_transaction(); // Start transaction

        $sql_insert_img = "INSERT INTO Imagens (urlImagem) VALUES (?)";
        $stmt_insert_img = $conn->prepare($sql_insert_img);

        $sql_link_proj = "INSERT INTO ImagensProjeto (idImagem, idProjeto) VALUES (?, ?)";
        $stmt_link_proj = $conn->prepare($sql_link_proj);

        foreach ($uploadedFilesInfo as $path) {
            // Insert into Imagens table
            $stmt_insert_img->bind_param("s", $path);
            if (!$stmt_insert_img->execute()) {
                 throw new Exception("Erro ao inserir imagem '$path' no banco de dados (Imagens): " . $stmt_insert_img->error);
            }
            $idImagem = $conn->insert_id; // Get the ID of the inserted image

            // Insert into ImagensProjeto table
            $stmt_link_proj->bind_param("ii", $idImagem, $idProjeto);
             if (!$stmt_link_proj->execute()) {
                 throw new Exception("Erro ao vincular imagem ID $idImagem ao projeto ID $idProjeto (ImagensProjeto): " . $stmt_link_proj->error);
            }
        }

        $stmt_insert_img->close();
        $stmt_link_proj->close();
        $conn->commit(); // Commit transaction if all inserts were successful
        $response['success'] = true;
        $response['message'] = 'Imagens enviadas com sucesso!';

    } elseif (empty($errors)) {
         throw new Exception('Nenhum arquivo válido foi enviado ou selecionado.');
    }

    // Add any non-fatal errors to the success message if needed
    if ($response['success'] && !empty($errors)) {
         $response['message'] .= ' Alguns arquivos tiveram problemas: ' . implode(' ', $errors);
    } elseif (!$response['success'] && !empty($errors)) {
         throw new Exception(implode(' ', $errors)); // Throw fatal errors
    }


} catch (Exception $e) {
    if ($conn->in_transaction) {
        $conn->rollback(); // Rollback transaction on error
    }
    $response['message'] = $e->getMessage();
} finally {
    if ($conn) {
        $conn->close();
    }
    // Redirect back with status
    $status = $response['success'] ? 'success' : 'error';
    $message = urlencode($response['message']);
    // Ensure idProjeto is available for redirect, even if an early error occurred
    $redirectId = isset($_POST['idProjeto']) ? filter_var($_POST['idProjeto'], FILTER_VALIDATE_INT) : $idProjeto;
     if ($redirectId) {
         header("Location: ../../perfilMeuProjeto.php?id={$redirectId}&upload_status={$status}&msg={$message}");
     } else {
         // Fallback redirect if ID is missing (should ideally not happen)
         header("Location: ../../index.php?upload_status={$status}&msg={$message}"); // Or maybe login.php?
     }
    exit();
}
?>
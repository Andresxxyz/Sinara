<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
 

session_start(); 
require_once("conexao.php");

if (!isset($_SESSION['user_matricula'])) {
    die("DEBUG: Erro Crítico - idUsuario NÃO ENCONTRADO na sessão."); 
} else {
    echo "DEBUG: idUsuario da Sessão = " . $_SESSION['user_matricula'] . "<br>"; 
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {

   
    $conn = conexao();

    $idProjeto = isset($_POST['idProjeto']) ? (int)$_POST['idProjeto'] : 0;
    $nomeProjeto = trim($_POST['nomeProjeto'] ?? '');
    $descricaoProjeto = trim($_POST['descricao-projeto'] ?? ''); // Correct name
    $palavrasChaves = trim($_POST['palavras_chaves-projeto'] ?? ''); // Correct name
    $linhaPesquisa = trim($_POST['linhaPesquisa-projeto'] ?? ''); // Correct name
    $colaboradores = trim($_POST['colaboradores-projeto'] ?? ''); // Correct name
    $datasProjeto = trim($_POST['datasProjeto'] ?? '');
    $urlInstagram = trim($_POST['urlInstagram'] ?? '');
    $urlYoutube = trim($_POST['urlYoutube'] ?? '');
    $areasSelecionadas = isset($_POST['areas']) && is_array($_POST['areas']) ? $_POST['areas'] : [];

    // --- 2. Security Check: Verify if the logged-in user owns this project ---
    $sql_check_owner = "SELECT idUsuario FROM Projeto WHERE idProjeto = ?";
    $stmt_check = $conn->prepare($sql_check_owner);
    if(!$stmt_check) { die("DEBUG: Erro ao preparar security check: ".$conn->error); } // Check prepare
    $stmt_check->bind_param("i", $idProjeto);
    if(!$stmt_check->execute()) { die("DEBUG: Erro ao executar security check: ".$stmt_check->error); } // Check execute
    $result_check = $stmt_check->get_result();
    $project_owner = $result_check->fetch_assoc();
    $stmt_check->close();

    // --- 3. Handle Logo Upload (Optional) ---
    $logoPath = null;
    if (isset($_FILES['logo-projeto']) && $_FILES['logo-projeto']['error'] == UPLOAD_ERR_OK) {
        echo "DEBUG: Arquivo de logo recebido.<br>";
        $uploadDir = '../../uploads/logos/';
        echo "DEBUG: Tentando salvar em: " . realpath($uploadDir) . "<br>"; // Show absolute path attempt
         if (!is_dir($uploadDir)) {
             echo "DEBUG: Diretório de upload não existe, tentando criar...<br>";
            if (!mkdir($uploadDir, 0777, true)) {
                 echo "DEBUG: FALHA ao criar diretório de upload!<br>";
            } else {
                 echo "DEBUG: Diretório de upload criado.<br>";
            }
        }
         if (!is_writable($uploadDir)) { // Check permissions explicitly
            echo "DEBUG: ERRO - Diretório de upload NÃO TEM permissão de escrita!<br>";
        } else {
             echo "DEBUG: Diretório de upload tem permissão de escrita.<br>";
        }


        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
        $fileType = $_FILES['logo-projeto']['type'];
        $fileSize = $_FILES['logo-projeto']['size'];
        $maxSize = 5 * 1024 * 1024; // 5 MB

        if (in_array($fileType, $allowedTypes) && $fileSize <= $maxSize) {
             echo "DEBUG: Tipo e tamanho do arquivo OK.<br>";
            $fileName = uniqid('logo_' . $idProjeto . '_') . '.' . pathinfo($_FILES['logo-projeto']['name'], PATHINFO_EXTENSION);
            $targetPath = $uploadDir . $fileName;
             echo "DEBUG: Caminho de destino do arquivo: " . $targetPath . "<br>";

            if (move_uploaded_file($_FILES['logo-projeto']['tmp_name'], $targetPath)) {
                $logoPath = 'uploads/logos/' . $fileName;
                echo "DEBUG: Arquivo movido com sucesso! Path para DB: " . $logoPath . "<br>";
            } else {
                $_SESSION['edit_error'] = "Erro ao mover o arquivo da logo.";
                 echo "DEBUG: FALHA ao mover o arquivo! Erro PHP: " . $_FILES['logo-projeto']['error'] . "<br>";
            }
        } else {
            $_SESSION['edit_error'] = "Erro: Arquivo da logo inválido (tipo ou tamanho).";
             echo "DEBUG: Arquivo inválido. Tipo: " . $fileType . ", Tamanho: " . $fileSize . "<br>";
        }
    } else if (isset($_FILES['logo-projeto']) && $_FILES['logo-projeto']['error'] != UPLOAD_ERR_NO_FILE) {
         echo "DEBUG: Erro no upload do arquivo logo: Código " . $_FILES['logo-projeto']['error'] . "<br>";
    } else {
         echo "DEBUG: Nenhum arquivo de logo enviado ou erro UPLOAD_ERR_NO_FILE.<br>";
    }

    // --- 4. Update Project Data in Database ---
     echo "DEBUG: Iniciando transação.<br>";
    $conn->begin_transaction();

    try {
        $sql_update_projeto = "UPDATE Projeto SET
            nomeProj = ?,
            descricaoProj = ?,
            palavrasChavesProj = ?,
            linhaPesquisaProj = ?,
            colaboradores = ?,
            dtEncontros = ?,
            urlInstagram = ?,
            urlYoutube = ?";

        $params = [$nomeProjeto, $descricaoProjeto, $palavrasChaves, $linhaPesquisa, $colaboradores, $datasProjeto, $urlInstagram, $urlYoutube];
        $types = "ssssssss";

        if ($logoPath !== null) {
            $sql_update_projeto .= ", urlLogo = ?";
            $params[] = $logoPath;
            $types .= "s";
             echo "DEBUG: Adicionando logo ao UPDATE.<br>";
        }

        $sql_update_projeto .= " WHERE idProjeto = ?";
        $params[] = $idProjeto;
        $types .= "i";

         echo "DEBUG: SQL Update Projeto: " . $sql_update_projeto . "<br>";
         echo "DEBUG: Tipos: " . $types . "<br>";
         echo "DEBUG: Params: <pre>"; var_dump($params); echo "</pre><br>";


        $stmt_update = $conn->prepare($sql_update_projeto);
        if (!$stmt_update) {
             throw new Exception("DEBUG: Erro ao preparar UPDATE Projeto: " . $conn->error);
        }

        $stmt_update->bind_param($types, ...$params);

        if (!$stmt_update->execute()) {
             throw new Exception("DEBUG: Erro ao executar UPDATE Projeto: " . $stmt_update->error);
        } else {
             echo "DEBUG: UPDATE Projeto executado. Linhas afetadas: " . $stmt_update->affected_rows . "<br>";
        }
        $stmt_update->close();


        // --- 5. Update Project Areas ---
         echo "DEBUG: Excluindo áreas antigas...<br>";
        $sql_delete_areas = "DELETE FROM ProjetoArea WHERE idProjeto = ?";
        $stmt_delete = $conn->prepare($sql_delete_areas);
         if (!$stmt_delete) {
             throw new Exception("DEBUG: Erro ao preparar DELETE Areas: " . $conn->error);
        }
        $stmt_delete->bind_param("i", $idProjeto);
        if (!$stmt_delete->execute()) {
             throw new Exception("DEBUG: Erro ao executar DELETE Areas: " . $stmt_delete->error);
        } else {
            echo "DEBUG: DELETE Areas executado. Linhas afetadas: " . $stmt_delete->affected_rows . "<br>";
        }
        $stmt_delete->close();

        if (!empty($areasSelecionadas)) {
             echo "DEBUG: Inserindo novas áreas...<br>";
            $sql_insert_area = "INSERT INTO ProjetoArea (idProjeto, idAreaEstudo) VALUES (?, ?)";
            $stmt_insert = $conn->prepare($sql_insert_area);
            if (!$stmt_insert) {
                 throw new Exception("DEBUG: Erro ao preparar INSERT Area: " . $conn->error);
            }

            foreach ($areasSelecionadas as $idArea) {
                $idAreaInt = (int)$idArea;
                 echo "DEBUG: Inserindo area ID: " . $idAreaInt . "<br>";
                $stmt_insert->bind_param("ii", $idProjeto, $idAreaInt);
                if (!$stmt_insert->execute()) {
                    // Don't throw exception here immediately, maybe log it
                    echo "DEBUG WARNING: Falha ao inserir área ID " . $idAreaInt . ": " . $stmt_insert->error . "<br>";
                    // Consider adding this error to a list to show the user
                } else {
                    echo "DEBUG: Área ID " . $idAreaInt . " inserida. Linhas afetadas: " . $stmt_insert->affected_rows . "<br>";
                }
            }
            $stmt_insert->close();
        } else {
             echo "DEBUG: Nenhuma área selecionada para inserir.<br>";
        }

         echo "DEBUG: Tentando commit.<br>";
        $conn->commit();
        $_SESSION['edit_success'] = "Projeto atualizado com sucesso!";
         echo "DEBUG: Commit OK!<br>";

    } catch (Exception $e) {
         echo "DEBUG: ERRO NA TRANSAÇÃO! Fazendo rollback...<br>";
         echo "DEBUG: Mensagem de Erro: " . $e->getMessage() . "<br>";
        $conn->rollback();
        $_SESSION['edit_error'] = "Erro ao atualizar o projeto: " . $e->getMessage();
    }

    $conn->close();
     echo "DEBUG: Conexão fechada.<br>";
    // --- Temporarily disable redirect ---
    echo "<br><br>DEBUG: Fim do script. Redirecionamento desativado.";

    header("Location: ../../perfilMeuProjeto.php?id=" . $idProjeto);
     exit(); // Make sure script stops here during debug

} else {
     echo "DEBUG: Requisição não é POST.";
    // header("Location: ../../index.php");
    // exit();
}
?>
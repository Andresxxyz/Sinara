<?php
session_start();
require_once('conexao.php'); 
$conn = conexao();

if (!isset($_SESSION['user_matricula'])) {
    die("Acesso negado: Usuário não está logado.");
}

if ($_SERVER["REQUEST_METHOD"] != "POST" || empty($_POST['idTrabalho'])) {
    die("Erro: Requisição inválida.");
}

$idTrabalho = $_POST['idTrabalho'];
$titulo = $_POST['titulo'];
$autores = $_POST['autores'];
$ano_trabalho = $_POST['ano_trabalho'];
$palavras_chaves = $_POST['palavras_chaves'];
$resumo = $_POST['resumo'];
$abstract = $_POST['abstract'];
$selected_subjects = isset($_POST['subject']) ? $_POST['subject'] : [];
$caminhoArquivoDB = $_POST['arquivo_existente'];

$caminhoUploadServidor = null; 

$conn->begin_transaction();

try {
    if (isset($_FILES['arquivo-trabalho']) && $_FILES['arquivo-trabalho']['error'] == UPLOAD_ERR_OK) {
        
        $arquivo = $_FILES['arquivo-trabalho'];
        $target_dir = "../../uploadsTrabalhos/"; 

        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $extensao = strtolower(pathinfo($arquivo["name"], PATHINFO_EXTENSION));
        if ($extensao != 'pdf') {
            throw new Exception("Tipo de arquivo não permitido. Apenas PDF é aceito.");
        }

        $novoNomeArquivo = "trabalho_" . $idTrabalho . "_" . uniqid() . ".pdf";
        $caminhoUploadServidor = $target_dir . $novoNomeArquivo;

        if (move_uploaded_file($arquivo["tmp_name"], $caminhoUploadServidor)) {
            $caminhoArquivoDB = "uploadsTrabalhos/" . $novoNomeArquivo;

            $arquivoAntigoServidor = "../../" . $_POST['arquivo_existente'];
            if (!empty($_POST['arquivo_existente']) && file_exists($arquivoAntigoServidor)) {
                unlink($arquivoAntigoServidor);
            }
        } else {
            throw new Exception("Erro ao mover o novo arquivo. Verifique as permissões da pasta.");
        }
    }

    $sql_update_trabalho = "UPDATE Trabalho SET 
                                titulo = ?, 
                                nomePesquisador = ?, 
                                anoTrab = ?, 
                                palavrasChaves = ?, 
                                resumo = ?, 
                                abstract = ?, 
                                arquivoTrabalho = ? 
                            WHERE idTrabalho = ?";
    
    $stmt_update = $conn->prepare($sql_update_trabalho);
    $stmt_update->bind_param("ssissssi", $titulo, $autores, $ano_trabalho, $palavras_chaves, $resumo, $abstract, $caminhoArquivoDB, $idTrabalho);
    $stmt_update->execute();
    $stmt_update->close();

    $sql_delete_areas = "DELETE FROM TrabalhoArea WHERE idTrabalho = ?";
    $stmt_delete = $conn->prepare($sql_delete_areas);
    $stmt_delete->bind_param("i", $idTrabalho);
    $stmt_delete->execute();
    $stmt_delete->close();

    if (!empty($selected_subjects)) {
        $sql_insert_area = "INSERT INTO TrabalhoArea(idTrabalho, idLinhaPesquisa) VALUES (?, ?)";
        $stmt_insert = $conn->prepare($sql_insert_area);
        
        foreach ($selected_subjects as $subject_name) {
            $sql_area_id = "SELECT idLinhaPesquisa FROM linhaPesquisa WHERE nome = ?";
            $stmt_area_id = $conn->prepare($sql_area_id);
            $stmt_area_id->bind_param("s", $subject_name);
            $stmt_area_id->execute();
            $result_area = $stmt_area_id->get_result();
            
            if ($row_area = $result_area->fetch_assoc()) {
                $idArea = $row_area['idLinhaPesquisa'];
                $stmt_insert->bind_param("ii", $idTrabalho, $idArea);
                $stmt_insert->execute();
            }
            $stmt_area_id->close();
        }
        $stmt_insert->close();
    }

    $conn->commit();
    header("Location: ../../sucessoEdicaoSubmissao.php");

} catch (Exception $e) {
    $conn->rollback();

    if ($caminhoUploadServidor && file_exists($caminhoUploadServidor)) {
        unlink($caminhoUploadServidor);
    }
    
    die("Erro ao atualizar a pesquisa: " . $e->getMessage());
}

$conn->close();
exit();
?>
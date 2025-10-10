<?php
session_start();
require_once('conexao.php');
$conn = conexao();

header('Content-Type: application/json');

if (!isset($_SESSION['user_matricula'])) {
    echo json_encode(['success' => false, 'message' => 'Acesso negado. Você precisa estar logado.']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['idTrabalho'])) {
    echo json_encode(['success' => false, 'message' => 'Requisição inválida.']);
    exit();
}

$idTrabalho = $_POST['idTrabalho'];
$matricula = $_SESSION['user_matricula'];

$conn->begin_transaction();

try {
    $sql_info = "SELECT t.arquivoTrabalho, u.idUsuario FROM Trabalho t 
                 JOIN Usuario u ON t.idUsuario = u.idUsuario 
                 WHERE t.idTrabalho = ? AND u.matricula = ?";
    
    $stmt_info = $conn->prepare($sql_info);
    $stmt_info->bind_param("is", $idTrabalho, $matricula);
    $stmt_info->execute();
    $result_info = $stmt_info->get_result();
    $trabalho_info = $result_info->fetch_assoc();
    $stmt_info->close();

    if (!$trabalho_info) {
        throw new Exception('Você não tem permissão para apagar esta pesquisa.');
    }

    $sql_delete_areas = "DELETE FROM TrabalhoArea WHERE idTrabalho = ?";
    $stmt_delete_areas = $conn->prepare($sql_delete_areas);
    $stmt_delete_areas->bind_param("i", $idTrabalho);
    $stmt_delete_areas->execute();
    $stmt_delete_areas->close();

    $sql_delete_trabalho = "DELETE FROM Trabalho WHERE idTrabalho = ?";
    $stmt_delete_trabalho = $conn->prepare($sql_delete_trabalho);
    $stmt_delete_trabalho->bind_param("i", $idTrabalho);
    $stmt_delete_trabalho->execute();
    
    if ($stmt_delete_trabalho->affected_rows > 0) {
        $caminhoArquivoServidor = "../../" . $trabalho_info['arquivoTrabalho'];
        if (!empty($trabalho_info['arquivoTrabalho']) && file_exists($caminhoArquivoServidor)) {
            unlink($caminhoArquivoServidor);
        }
        
        $conn->commit();
        echo json_encode(['success' => true, 'message' => 'Pesquisa apagada com sucesso!']);
    } else {
        throw new Exception('O trabalho não foi encontrado ou já foi apagado.');
    }

    $stmt_delete_trabalho->close();

} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => 'Erro ao apagar a pesquisa: ' . $e->getMessage()]);
}

$conn->close();
?>
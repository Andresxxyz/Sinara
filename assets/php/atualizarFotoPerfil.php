<?php
session_start();
include_once('conexao.php');
$conn = conexao();

if (!isset($_SESSION['user_matricula'])) {
    header("Location: ../../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matricula = $_SESSION['user_matricula'];
    $caminho_final_foto_fisico = null;

    $conn->begin_transaction();

    try {
        $sql_id = "SELECT idUsuario, fotoPerfil FROM Usuario WHERE matricula = ?";
        $stmt_id = $conn->prepare($sql_id);
        if (!$stmt_id) {
            throw new Exception("Erro ao preparar consulta: " . $conn->error);
        }
        $stmt_id->bind_param("s", $matricula);
        $stmt_id->execute();
        $result_id = $stmt_id->get_result();
        if ($result_id->num_rows === 0) {
            throw new Exception("Usuário não encontrado.");
        }
        $user_row = $result_id->fetch_assoc();
        $idUser = $user_row['idUsuario'];
        $caminho_foto_antiga = $user_row['fotoPerfil'];
        $stmt_id->close();
        
        $caminho_para_db = $caminho_foto_antiga;

        if (isset($_FILES["foto-perfil"]) && $_FILES["foto-perfil"]["error"] == UPLOAD_ERR_OK) {
            $target_dir = "../../uploads/";
            $foto_perfil = $_FILES["foto-perfil"];

            $extensao_arquivo = strtolower(pathinfo($foto_perfil["name"], PATHINFO_EXTENSION));
            $tipos_permitidos = ['jpg', 'jpeg', 'png', 'webp'];
            if (!in_array($extensao_arquivo, $tipos_permitidos)) {
                throw new Exception("Tipo de arquivo não permitido.");
            }

            $novo_nome_arquivo = "user_" . $idUser . "_" . uniqid() . "." . $extensao_arquivo;
            $caminho_final_foto_fisico = $target_dir . $novo_nome_arquivo;

            if (!move_uploaded_file($foto_perfil["tmp_name"], $caminho_final_foto_fisico)) {
                throw new Exception("Erro ao mover o arquivo. Verifique as permissões da pasta 'uploads'.");
            }
            $caminho_para_db = "uploads/" . $novo_nome_arquivo;
        }

        if ($caminho_para_db !== $caminho_foto_antiga) {
            $sql_update = "UPDATE Usuario SET fotoPerfil = ? WHERE matricula = ?";
            $stmt_update = $conn->prepare($sql_update);
            if (!$stmt_update) {
                throw new Exception("Erro ao preparar atualização: " . $conn->error);
            }
            $stmt_update->bind_param("ss", $caminho_para_db, $matricula);
            $stmt_update->execute();
            $stmt_update->close();
        }
        
        $conn->commit();

        fecharConexao($conn);
        header("Location: ../../meu_perfil.php?status=sucesso");
        exit();

    } catch (Exception $e) {
        $conn->rollback();
        
        if ($caminho_final_foto_fisico && file_exists($caminho_final_foto_fisico)) {
            unlink($caminho_final_foto_fisico);
        }
        
        fecharConexao($conn);
        die("Erro ao atualizar o perfil: " . $e->getMessage());
    }
}
?>
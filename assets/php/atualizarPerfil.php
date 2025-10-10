<?php
session_start();
include_once('conexao.php');
$conn = conexao();

if (!isset($_SESSION['user_matricula'])) {
    die("Erro: Usuário não logado.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matricula = $_SESSION['user_matricula'];
    $sql_id = "SELECT idUsuario FROM Usuario WHERE matricula = ?";
    $stmt_id = $conn->prepare($sql_id);
    $stmt_id->bind_param("s", $matricula);
    $stmt_id->execute();
    $result_id = $stmt_id->get_result();
    $user_row = $result_id->fetch_assoc();
    $idUser = $user_row['idUsuario'];
    $stmt_id->close();

    $nome = $_POST['nome'];
    $email = $_POST['emailInst'];
    $lattes = $_POST['lattes'];
    $sobreMim = $_POST['sobreMim'];
    
    $caminho_final_foto = null; 

    if (isset($_FILES["foto-perfil"]) && $_FILES["foto-perfil"]["error"] == 0) {
        $target_dir = "../../uploads/"; 
        $foto_perfil = $_FILES["foto-perfil"];

        $extensao_arquivo = strtolower(pathinfo($foto_perfil["name"], PATHINFO_EXTENSION));
        $tipos_permitidos = ['jpg', 'jpeg', 'png'];
        if (!in_array($extensao_arquivo, $tipos_permitidos)) {
            die("Erro: Tipo de arquivo não permitido.");
        }

        $novo_nome_arquivo = "user_" . $idUser . "_" . uniqid() . "." . $extensao_arquivo;
        $caminho_final_foto = $target_dir . $novo_nome_arquivo;

        if (!move_uploaded_file($foto_perfil["tmp_name"], $caminho_final_foto)) {
            die("Erro ao mover o arquivo de imagem.");
        }
        $caminho_para_db = "uploads/" . $novo_nome_arquivo;

    } else {
        $sql_foto_antiga = "SELECT fotoPerfil FROM Usuario WHERE idUsuario = ?";
        $stmt_foto = $conn->prepare($sql_foto_antiga);
        $stmt_foto->bind_param("i", $idUser);
        $stmt_foto->execute();
        $result_foto = $stmt_foto->get_result();
        $foto_row = $result_foto->fetch_assoc();
        $caminho_para_db = $foto_row['fotoPerfil'];
        $stmt_foto->close();
    }


    $conn->begin_transaction();

    try {
        $sql_update = "UPDATE Usuario SET nome = ?, emailInstitucional = ?, linkCurriculo = ?, sobre = ?, dtAtual = NOW(), fotoPerfil = ? WHERE matricula = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("ssssss", $nome, $email, $lattes, $sobreMim, $caminho_para_db, $matricula);
        $stmt_update->execute();
        $stmt_update->close();

        $sql_delete_areas = "DELETE FROM areaEstudo WHERE idUsuario = ?";
        $stmt_delete = $conn->prepare($sql_delete_areas);
        $stmt_delete->bind_param("i", $idUser);
        $stmt_delete->execute();
        $stmt_delete->close();

        if (isset($_POST['subject']) && is_array($_POST['subject'])) {
            $selected_subjects = $_POST['subject'];
            $sql_insert_area = "INSERT INTO areaEstudo(idUsuario, idLinhaPesquisa) VALUES (?, ?)";
            $stmt_insert = $conn->prepare($sql_insert_area);
            foreach ($selected_subjects as $subject_name) {
                $sql_area_id = "SELECT idLinhaPesquisa FROM linhaPesquisa WHERE nome = ?";
                $stmt_area_id = $conn->prepare($sql_area_id);
                $stmt_area_id->bind_param("s", $subject_name);
                $stmt_area_id->execute();
                $result_area = $stmt_area_id->get_result();
                if ($row_area = $result_area->fetch_assoc()) {
                    $idArea = $row_area['idLinhaPesquisa'];
                    $stmt_insert->bind_param("ii", $idUser, $idArea);
                    $stmt_insert->execute();
                }
                $stmt_area_id->close();
            }
            $stmt_insert->close();
        }

        $conn->commit();
        echo '<span id="atualizacao-concluida"></span>'; 
    } catch (Exception $e) {
        $conn->rollback();
        if ($caminho_final_foto && file_exists($caminho_final_foto)) {
            unlink($caminho_final_foto);
        }
        die("Erro ao atualizar o perfil: " . $e->getMessage());
    }

    fecharConexao($conn);
    exit();
}
<?php
session_start();
if (!isset($_SESSION["user_matricula"])) {
    die("Acesso negado. Você precisa estar logado para submeter um trabalho.");
}

include_once("conexao.php");
$conn = conexao();

if ($conn->connect_error) {
    die('Erro na conexão com o banco de dados: ' . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $matricula = $_SESSION["user_matricula"];
    $titulo = $_POST["titulo"];
    $autores = $_POST["autores"]; 
    $palavraschave = $_POST["palavras_chaves"];
    $anoTrab = $_POST["ano_trabalho"];
    $resumo = $_POST["resumo"];
    $abstract = $_POST["abstract"];
    $selected_subjects = $_POST['subject']; 

    $caminho_final_arquivo = null;

    $conn->begin_transaction();

    try {

        if (isset($_FILES["arquivo-trabalho"]) && $_FILES["arquivo-trabalho"]["error"] == UPLOAD_ERR_OK) {
            $target_dir = "../../uploadsTrabalhos/";
            $arquivo_trabalho = $_FILES["arquivo-trabalho"];
            $extensao_arquivo = strtolower(pathinfo($arquivo_trabalho["name"], PATHINFO_EXTENSION));

            if ($extensao_arquivo !== 'pdf') {
                throw new Exception("Tipo de arquivo não permitido. Apenas PDF é aceito.");
            }

            $novo_nome_arquivo = "trabalho_" . uniqid() . "." . $extensao_arquivo;
            $caminho_final_arquivo = $target_dir . $novo_nome_arquivo;

            if (!move_uploaded_file($arquivo_trabalho["tmp_name"], $caminho_final_arquivo)) {
                throw new Exception("Erro ao mover o arquivo. Verifique as permissões da pasta 'uploadsTrabalhos'.");
            }
            $caminho_para_db = "uploadsTrabalhos/" . $novo_nome_arquivo;
        } else {
            throw new Exception("Erro no upload do arquivo ou nenhum arquivo enviado.");
        }

        $sql_user = "SELECT idUsuario FROM Usuario WHERE matricula = ?";
        $stmt_user = $conn->prepare($sql_user);
        $stmt_user->bind_param("s", $matricula);
        $stmt_user->execute();
        $result_user = $stmt_user->get_result();
        if ($row_user = $result_user->fetch_assoc()) {
            $idUsuario = $row_user['idUsuario'];
        } else {
            throw new Exception("Usuário não encontrado no banco de dados.");
        }
        $stmt_user->close();

        $sql_insert_trabalho = "INSERT INTO trabalho(idUsuario, arquivoTrabalho, titulo, palavrasChaves, resumo, abstract, anoTrab, nomePesquisador, curriculoAluno, cursoAluno, dtPubli) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        date_default_timezone_set('America/Sao_Paulo');
        $dtPubli = date("Y-m-d-H-i-s");
        $curriculoAluno = $_POST['curriculoAluno']; 
        $cursoAluno = $_POST['cursoAluno'];

        $stmt_insert = $conn->prepare($sql_insert_trabalho);
        $stmt_insert->bind_param(
            "isssssissss",
            $idUsuario,
            $caminho_para_db,
            $titulo,
            $palavraschave,
            $resumo,
            $abstract,
            $anoTrab,
            $autores,
            $curriculoAluno,
            $cursoAluno,
            $dtPubli
        );
        $stmt_insert->execute();

        $idTrabalho = $conn->insert_id;
        $stmt_insert->close();

        $sql_area_id = "SELECT idLinhaPesquisa FROM linhapesquisa WHERE nome = ?";
        $stmt_area_id = $conn->prepare($sql_area_id);
        
        $sql_insert_area = "INSERT INTO TrabalhoArea (idTrabalho, idLinhaPesquisa) VALUES (?, ?)";
        $stmt_insert_area = $conn->prepare($sql_insert_area);

        foreach ($selected_subjects as $areaNome) {
            $stmt_area_id->bind_param("s", $areaNome);
            $stmt_area_id->execute();
            $result_area = $stmt_area_id->get_result();
            if ($row_area = $result_area->fetch_assoc()) {
                $idArea = $row_area['idLinhaPesquisa'];
            
                $stmt_insert_area->bind_param("ii", $idTrabalho, $idArea);
                $stmt_insert_area->execute();
            }
        }
        $stmt_area_id->close();
        $stmt_insert_area->close();

        $conn->commit();

        header("Location: ../../sucessoSubmissao.php");
        exit();

    } catch (Exception $e) {
        $conn->rollback();
        
        if ($caminho_final_arquivo && file_exists($caminho_final_arquivo)) {
            unlink($caminho_final_arquivo);
        }
    
        echo "Erro ao processar a submissão: " . $e->getMessage();
    }
} else {
    die("Acesso inválido. Por favor, envie o formulário.");
}

fecharConexao($conn);
?>
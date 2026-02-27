<?php
session_start();
if (!isset($_SESSION["user_matricula"])) {
    die("Acesso negado. Você precisa estar logado para cadastrar um projeto.");
}

include_once("conexao.php"); // Garanta que este caminho está correto
$conn = conexao();

if ($conn->connect_error) {
    die('Erro na conexão com o banco de dados: ' . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 1. Obter dados da sessão e do formulário
    $matricula = $_SESSION["user_matricula"];
    $nomeProjeto = $_POST["nomeProjeto"];
    $descricao = $_POST["descricao-projeto"];
    $palavras_chaves = $_POST["palavras_chaves-projeto"];
    $linhaPesquisa = $_POST["linhaPesquisa-projeto"];
    $colaboradores = $_POST["colaboradores-projeto"];
    $datasEncontros = !empty($_POST["datasProjeto"]) ? $_POST["datasProjeto"] : null; // Campo opcional
    $selected_subjects = $_POST['subject']; // Áreas de estudo

    $caminho_final_logo = null; // Para rollback
    $caminho_para_db_logo = null; // Para inserir no DB

    $conn->begin_transaction();

    try {

        // 2. Lógica de Upload da Logo (Opcional)
        if (isset($_FILES["logo-projeto"]) && $_FILES["logo-projeto"]["error"] == UPLOAD_ERR_OK && $_FILES["logo-projeto"]["size"] > 0) {
            
            // ATENÇÃO: Crie esta pasta no seu servidor!
            $target_dir = "../../uploadsLogosProjetos/"; 
            
            $logo_projeto = $_FILES["logo-projeto"];
            $extensao_arquivo = strtolower(pathinfo($logo_projeto["name"], PATHINFO_EXTENSION));
            $allowed_types = ['png', 'jpeg', 'jpg', 'webp'];

            if (!in_array($extensao_arquivo, $allowed_types)) {
                throw new Exception("Tipo de arquivo não permitido para a logo. Apenas PNG, JPEG ou WEBP.");
            }

            $novo_nome_arquivo = "logo_proj_" . uniqid() . "." . $extensao_arquivo;
            $caminho_final_logo = $target_dir . $novo_nome_arquivo;

            if (!move_uploaded_file($logo_projeto["tmp_name"], $caminho_final_logo)) {
                throw new Exception("Erro ao mover o arquivo da logo. Verifique as permissões da pasta 'uploadsLogosProjetos'.");
            }
            
            // Caminho relativo para salvar no banco de dados
            $caminho_para_db_logo = "uploadsLogosProjetos/" . $novo_nome_arquivo; 
        }

        // 3. Buscar o idUsuario com base na matrícula da sessão
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

        // 4. Inserir na tabela 'Projeto'
        $sql_insert_projeto = "INSERT INTO Projeto (idUsuario, nomeProj, descricaoProj, palavrasChavesProj, linhaPesquisaProj, colaboradores, dtEncontros, urlLogo) 
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt_insert = $conn->prepare($sql_insert_projeto);
        $stmt_insert->bind_param(
            "isssssss",
            $idUsuario,
            $nomeProjeto,
            $descricao,
            $palavras_chaves,
            $linhaPesquisa,
            $colaboradores,
            $datasEncontros,
            $caminho_para_db_logo 
        );
        $stmt_insert->execute();

        // 5. Obter o ID do projeto que acabou de ser inserido
        $idProjeto = $conn->insert_id;
        $stmt_insert->close();
        
        if (empty($idProjeto)) {
             throw new Exception("Falha ao obter o ID do novo projeto.");
        }

        // 6. Inserir as áreas de estudo na tabela 'ProjetoArea'
        if (empty($selected_subjects)) {
             throw new Exception("Selecione pelo menos uma área de estudo.");
        }
        
        $sql_area_id = "SELECT idAreaEstudo FROM areaestudo WHERE nome = ?";
        $stmt_area_id = $conn->prepare($sql_area_id);
        
        $sql_insert_area = "INSERT INTO ProjetoArea (idProjeto, idAreaEstudo) VALUES (?, ?)";
        $stmt_insert_area = $conn->prepare($sql_insert_area);

        foreach ($selected_subjects as $areaNome) {
            $stmt_area_id->bind_param("s", $areaNome);
            $stmt_area_id->execute();
            $result_area = $stmt_area_id->get_result();
            
            if ($row_area = $result_area->fetch_assoc()) {
                $idArea = $row_area['idAreaEstudo'];
                
                // Vincula o projeto com a área
                $stmt_insert_area->bind_param("ii", $idProjeto, $idArea);
                $stmt_insert_area->execute();
            } else {
                // Opcional: pode lançar exceção se uma área não for encontrada
                // throw new Exception("Área de estudo '{$areaNome}' não encontrada.");
            }
        }
        $stmt_area_id->close();
        $stmt_insert_area->close();

        // 7. Se tudo deu certo, comita a transação
        $conn->commit();

        // 8. Redireciona para uma página de sucesso
        // (Estou usando a mesma do seu exemplo, mas você pode criar uma "sucessoProjeto.php")
        header("Location: ../../sucessoSubmissao.php");
        exit();

    } catch (Exception $e) {
        // 9. Se algo deu errado, faz o rollback
        $conn->rollback();
        
        // Se uma logo foi salva, remove ela
        if ($caminho_final_logo && file_exists($caminho_final_logo)) {
            unlink($caminho_final_logo);
        }
    
        // Exibe o erro
        echo "Erro ao processar o cadastro do projeto: " . $e->getMessage();
    }
} else {
    die("Acesso inválido. Por favor, envie o formulário.");
}

fecharConexao($conn);
?>
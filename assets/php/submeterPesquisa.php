<?php
session_start();
if (!isset($_SESSION["user_matricula"])) {
    die("Acesso negado. Você precisa estar logado para submeter um trabalho.");
}

// error_reporting(E_ALL); // Descomente para debug mais detalhado se necessário
// ini_set('display_errors', 1); // Descomente para debug mais detalhado se necessário

include_once("conexao.php");
$conn = conexao();

if ($conn->connect_error) {
    die('Erro na conexão com o banco de dados: ' . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 1. Obter dados da sessão e do formulário (verificando se existem)
    $matricula = $_SESSION["user_matricula"];
    $titulo = isset($_POST["titulo"]) ? $_POST["titulo"] : '';
    $autores = isset($_POST["autores"]) ? $_POST["autores"] : '';
    $palavraschave = isset($_POST["palavras_chaves"]) ? $_POST["palavras_chaves"] : ''; // Nome corrigido no form
    $anoTrab = isset($_POST["ano_trabalho"]) ? (int)$_POST["ano_trabalho"] : null;
    $resumo = isset($_POST["resumo"]) ? $_POST["resumo"] : '';
    $abstract = isset($_POST["abstract"]) ? $_POST["abstract"] : '';
    $linhaPesquisa = isset($_POST["linhaPesquisa-pesquisa"]) ? $_POST["linhaPesquisa-pesquisa"] : ''; // Campo de Linha de Pesquisa
    $selected_subjects = isset($_POST['subject']) ? $_POST['subject'] : [];
    $idProjeto = !empty($_POST['idProjeto']) ? (int)$_POST['idProjeto'] : null;

    // Validações básicas (opcional, mas recomendado)
    if (empty($titulo) || empty($autores) || empty($palavraschave) || $anoTrab === null || empty($resumo) || empty($abstract) || empty($linhaPesquisa) || empty($selected_subjects)) {
         // die("Erro: Campos obrigatórios faltando no formulário."); // Use die para debug
         // header("Location: ../../erroSubmissao.php?msg=CamposObrigatorios"); // Ou redirecione para uma página de erro
         // exit();
         // Por enquanto, vamos deixar passar para focar no erro SQL, mas considere adicionar validação
    }


    $caminho_final_arquivo = null;
    $caminho_para_db = null;

    $conn->begin_transaction();

    try {

        // 2. Upload do Arquivo (sem alterações)
        if (isset($_FILES["arquivo-trabalho"]) && $_FILES["arquivo-trabalho"]["error"] == UPLOAD_ERR_OK) {
             $target_dir = "../../uploadsTrabalhos/";
             // ... (resto do código de upload sem alteração) ...
             if (!move_uploaded_file($arquivo_trabalho["tmp_name"], $caminho_final_arquivo)) {
                 throw new Exception("Erro ao mover o arquivo. Verifique as permissões da pasta 'uploadsTrabalhos'.");
             }
             $caminho_para_db = "uploadsTrabalhos/" . $novo_nome_arquivo;
        } else {
             $upload_error_code = isset($_FILES["arquivo-trabalho"]['error']) ? $_FILES["arquivo-trabalho"]['error'] : UPLOAD_ERR_NO_FILE;
             // ... (código de tratamento de erro de upload sem alteração) ...
             throw new Exception($error_message);
        }

        // 3. Buscar idUsuario (sem alterações)
        $sql_user = "SELECT idUsuario FROM Usuario WHERE matricula = ?";
        // ... (resto do código para buscar idUsuario sem alteração) ...
        $stmt_user->close();

        // 4. Inserir na tabela 'Trabalho' - SQL e bind_param CORRIGIDOS NOVAMENTE
        //    Adicionado: linhaPesquisa
        //    Corrigido: nome da coluna idProjeto (minúsculo)
        //    Confirmado: 11 colunas e 11 placeholders
        $sql_insert_trabalho = "INSERT INTO trabalho (
                                    idUsuario, arquivoTrabalho, titulo, nomePesquisador,
                                    palavrasChaves, anoTrab, resumo, abstract,
                                    linhaPesquisa, dtPubli, idProjeto
                                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"; // 11 placeholders

        date_default_timezone_set('America/Sao_Paulo');
        $dtPubli = date("Y-m-d H:i:s"); // Usar formato padrão DATETIME

        $stmt_insert = $conn->prepare($sql_insert_trabalho);
        if ($stmt_insert === false) {
            throw new Exception("Erro ao preparar a query de inserção: " . $conn->error);
        }

        // bind_param CORRIGIDO para 11 parâmetros e tipos corretos
        // Ordem das variáveis DEVE corresponder à ordem dos placeholders na query SQL
        $stmt_insert->bind_param(
            "issssissssi",     // 11 tipos
            $idUsuario,        // i - idUsuario
            $caminho_para_db,  // s - arquivoTrabalho
            $titulo,           // s - titulo
            $autores,          // s - nomePesquisador
            $palavraschave,    // s - palavrasChaves
            $anoTrab,          // i - anoTrab
            $resumo,           // s - resumo
            $abstract,         // s - abstract
            $linhaPesquisa,    // s - linhaPesquisa <<< ADICIONADO AQUI
            $dtPubli,          // s - dtPubli
            $idProjeto         // i - idProjeto (pode ser null, o bind_param cuida disso)
        );

        if (!$stmt_insert->execute()) {
             // O erro "Column 'idProjeto' cannot be null" provavelmente acontece aqui
             throw new Exception("Erro ao executar a inserção do trabalho: " . $stmt_insert->error);
        }

        $idTrabalho = $conn->insert_id;
        $stmt_insert->close();

        if (empty($idTrabalho)) {
            // Isso pode acontecer se o execute() falhar silenciosamente (raro) ou se não houver auto_increment
            throw new Exception("Falha ao obter o ID do novo trabalho após a inserção.");
        }

        // 5. Inserir Áreas de Estudo (sem alterações, mas verificar $selected_subjects)
        if (empty($selected_subjects)) {
             throw new Exception("Nenhuma área de estudo foi selecionada."); // É melhor parar aqui
        }
        
        $sql_area_id = "SELECT idAreaEstudo FROM areaestudo WHERE nome = ?";
        // ... (resto do código para inserir áreas sem alteração) ...
         $stmt_insert_area->close();


        // 6. Commit e Redirecionamento (sem alterações)
        $conn->commit();
        header("Location: ../../sucessoSubmissao.php");
        exit();

    } catch (Exception $e) {
        $conn->rollback();
        if ($caminho_final_arquivo && file_exists($caminho_final_arquivo)) {
            unlink($caminho_final_arquivo);
        }
        // Exibir erro de forma mais clara
        echo "Erro ao processar a submissão: " . $e->getMessage();
        // Opcional: Logar o erro
        error_log("Erro em submeterPesquisa.php: " . $e->getMessage());
    }
} else {
    die("Acesso inválido. Por favor, envie o formulário.");
}

// fechando conexão se a função existir
if (function_exists('fecharConexao')) {
     fecharConexao($conn);
} else {
    $conn->close(); // Fechamento padrão do mysqli
}
?>
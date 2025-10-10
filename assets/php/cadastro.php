<?php

include_once("conexao.php");

function validarSenhaSegura($senha)
{
    $minimo = 8;
    $maximo = 20;
    $senhaErro = "";

    if (strlen($senha) < $minimo || strlen($senha) > $maximo) {
        $senhaErro .= "• A senha deve ter entre $minimo e $maximo caracteres.\n";
    }

    if (!preg_match('/[\W_]/', $senha)) {
        $senhaErro .= "• A senha deve conter pelo menos um símbolo.\n";
    }
    if (!preg_match('/[0-9]/', $senha)) {
        $senhaErro .= "• A senha deve conter pelo menos um número.\n";
    }

    if (!preg_match('/[A-Z]/', $senha)) {
        $senhaErro .= "• A senha deve conter pelo menos uma letra maiúscula.\n";
    }

    if (!preg_match('/[a-z]/', $senha)) {
        $senhaErro .= "• A senha deve conter pelo menos uma letra minúscula.\n";
    }

    return $senhaErro;

}


$conn = conexao();

if ($conn->connect_error) {
    die('deu erro tropa: ' . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $matricula = $_POST['matricula'];
    $nome = $_POST['nome'];
    $senha = $_POST['senha'];
    $confSenha = $_POST['confSenha'];
    $email = $_POST['emailInst'];
    $lattes = $_POST['lattes'];
    $sobre = $_POST['sobreMim'];
    $sql_check = "SELECT idUsuario, senha FROM Usuario WHERE matricula = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $matricula);
    $stmt_check->execute();
    $result = $stmt_check->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $idUser = $row['idUsuario'];
        if (empty($row['senha'])) {
            if ($senha == $confSenha) {
                $validaSenha = validarSenhaSegura($senha);
                if (empty($validaSenha)) {
                    $hashSenha = password_hash($senha, PASSWORD_DEFAULT);
                    $sql_update = "UPDATE Usuario SET nome = ?, senha = ?, emailInstitucional = ?, linkCurriculo = ?, sobre = ?, dtAtual = NOW() WHERE matricula = ?";
                    $stmt_update = $conn->prepare($sql_update);
                    $stmt_update->bind_param("ssssss", $nome, $hashSenha, $email, $lattes, $sobre, $matricula);
                } else {
                    setcookie("erroSenhaInsegura", $validaSenha, time() + 3, "/");
                    header("Location: ../../cadastro.php");
                    exit();
                }

                if (isset($_POST['subject'])) {
                    $selected_subjects = $_POST['subject'];
                    foreach ($selected_subjects as $subject) {
                        $sql_area = "SELECT idLinhaPesquisa FROM linhapesquisa WHERE nome = ?";
                        $stmt_area = $conn->prepare($sql_area);
                        $stmt_area->bind_param("s", $subject);
                        $stmt_area->execute();
                        $result_area = $stmt_area->get_result();
                        $row = $result_area->fetch_assoc();
                        $idArea = $row['idLinhaPesquisa'];
                        $sql_insert = "INSERT INTO areaestudo(idUsuario, idLinhaPesquisa) VALUES (?, ?)";
                        $stmt_insert = $conn->prepare($sql_insert);
                        $stmt_insert->bind_param("ii", $idUser, $idArea);
                        $stmt_insert->execute();
                        $stmt_insert->close();
                    }
                }

                if ($stmt_update->execute()) {
                    $stmt_update->close();
                    $stmt_check->close();
                    echo '<meta http-equiv="refresh" content="0;url=/Sinara/login.php">';
                    exit();
                } else {
                    die("Erro ao atualizar o registro: " . $stmt_update->error);
                }
                $stmt_update->close();
            } else {
                setcookie("erroSenhaDiferente", "erroCadastro", time() + 2, "/");
                header("Location: ../../cadastro.php");
                exit();
            }


        } else {
            setcookie("erroJaCadastrado", "erroCadastro", time() + 2, "/");
            header("Location: ../../cadastro.php");
            exit();
        }


    } else {
        setcookie("erroMatNaoExiste", "erroCadastro", time() + 2, "/");
        header("Location: ../../cadastro.php");
        exit();
    }

    $stmt_check->close();
} else {
    die("Acesso inválido. Por favor, envie o formulário.");
}

fecharConexao($conn);
?>
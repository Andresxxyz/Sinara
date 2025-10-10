<?php 
session_start();
include_once("conexao.php");
$conn = conexao();

if ($conn->connect_error) {
    die("Erro de conexão");
} else {
    $matricula = $_POST['matricula'];
    $senha = $_POST['senha'];

    $sql_check = "SELECT idUsuario, senha FROM Usuario WHERE matricula = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $matricula);
    $stmt_check->execute();
    $result = $stmt_check->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashSenha = $row['senha'];

        // Verifica se a senha digitada confere com o hash do banco
        if (password_verify($senha, $hashSenha)) {
            $_SESSION['user_matricula'] = $matricula;
            $_SESSION['user_id'] = $row['idUsuario'];
            header("Location: ../../meu_perfil.php"); 
            exit();
        } else {
            // Senha incorreta
            setcookie("erroLogin", true, time() + 5, "/");
            header("Location: ../../login.php"); 
            exit();
        }
    } else {
        // Matrícula não encontrada
        setcookie("erroLogin", true, time() + 5, "/");
        header("Location: ../../login.php"); 
        exit();
    }
}

fecharConexao($conn);
?>

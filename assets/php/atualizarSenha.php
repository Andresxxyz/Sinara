<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    include_once('conexao.php');
    $conn = conexao();

    $token = $_POST['token'];
    $nova_senha = $_POST['nova_senha'];
    $confirmar_senha = $_POST['confirmar_senha'];

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

    if ($nova_senha !== $confirmar_senha) {
        setcookie('senhaNaoCoincide', true, time() + 2, '/');
        header("Location: ../../definirNovaSenha.php?token=" . urlencode($token));
    } else {
        $validaSenha = validarSenhaSegura($nova_senha);
        if (empty($validaSenha)) {
            $sql = "SELECT matricula FROM usuario WHERE token_recuperacao = ? AND token_expiracao > NOW()";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $token);
            $stmt->execute();
            $resultado = $stmt->get_result();
            if ($resultado && $resultado->num_rows > 0) {
                $senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
                $sql_update = "UPDATE usuario SET senha = ?, token_recuperacao = NULL, token_expiracao = NULL WHERE token_recuperacao = ?";
                $stmt_update = $conn->prepare($sql_update);
                $stmt_update->bind_param("ss", $senha_hash, $token);

                if ($stmt_update->execute()) {
                    header("Location: ../../sucessoSenhaAlterada.php");
                    exit();
                } else {
                    setcookie("erroAtualizar", true, time() + 3, "/");
                    header("Location: ../../definirNovaSenha.php?token=" . urlencode($token));
                }
            } else {
                setcookie("erroTokenExpirado", $validaSenha, time() + 3, "/");
                header("Location: ../../definirNovaSenha.php?token=" . urlencode($token));
            }
        } else {
            setcookie("erroSenhaInsegura", $validaSenha, time() + 3, "/");
            header("Location: ../../definirNovaSenha.php?token=" . urlencode($token));
            exit();
        }



    }
}

?>
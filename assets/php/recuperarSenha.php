<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
include_once('conexao.php');
$conn = conexao();

if ($conn->connect_error) {
    die('deu erro tropa: ' . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $matricula = $_POST['matricula'];
    $sql_cadastro = "SELECT emailInstitucional FROM usuario WHERE matricula = ? AND senha IS NOT NULL AND senha <> ''";
    $stmt_cadastro = $conn->prepare($sql_cadastro);
    $stmt_cadastro->bind_param("s", $matricula);
    $stmt_cadastro->execute();
    $resultado = $stmt_cadastro->get_result();

    if (!($resultado && $resultado->num_rows > 0)) {
        setcookie("erroMatNaoExiste", true, time() + 2, "/");
        header("Location: ../../recuperarSenha.php");
        exit();
    }

    $usuario = $resultado->fetch_assoc();
    $email_usuario = $usuario['emailInstitucional'];

    $token = bin2hex(random_bytes(32));
    date_default_timezone_set('America/Sao_Paulo');
    $expiracao = date("Y-m-d H:i:s", time() + 600);
    $sql_update_token = "UPDATE usuario SET token_recuperacao = ?, token_expiracao = ? WHERE matricula = ?";
    $stmt_update = $conn->prepare($sql_update_token);
    $stmt_update->bind_param("sss", $token, $expiracao, $matricula);

    if ($stmt_update->execute()) {
    } else {
        echo "Erro ao gerar o link de recuperação. Tente novamente.";
    }
    require __DIR__ . '/../../vendor/autoload.php';
    $mail = new PHPMailer(true);

    try {
        // Configurações do servidor SMTP (ex: Gmail, SendGrid, etc.
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'sinararepositorio@gmail.com';
        $mail->Password = 'ebzw rslw qmjj jjmj';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->CharSet = 'UTF-8';

        $mail->setFrom('nao-responda@seusistema.com', 'Sistema de Recuperação');
        $mail->addAddress($email_usuario);
        $mail->isHTML(true);
        $mail->Subject = 'Recuperação de Senha';

        $link = "localhost/sinara/definirNovaSenha.php?token=" . $token;

        $mail->Body = "Olá,<br><br>Recebemos uma solicitação para redefinir sua senha. Clique no link abaixo para criar uma nova senha:<br><br>";
        $mail->Body .= "<a href='" . $link . "'>Redefinir Minha Senha</a><br><br>";
        $mail->Body .= "Se você não solicitou isso, por favor, ignore este e-mail.<br>";
        $mail->AltBody = 'Para redefinir sua senha, copie e cole este link em seu navegador: ' . $link;
        $mail->send();
        setcookie("emailEnviado", true, time() + 3, "/");
        header("Location: ../../sucessoEmailEnviado.php");
        exit();

    } catch (Exception $e) {
        echo "A mensagem não pôde ser enviada. Erro do Mailer: {$mail->ErrorInfo}";
    }

}
?>
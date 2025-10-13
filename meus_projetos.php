<?php session_start();
if (!isset($_SESSION['user_matricula'])) {
  header("Location: login.php");
  exit();
}
require_once("assets/php/conexao.php");
$conn = conexao();
$matricula = $_SESSION['user_matricula'];
$sql_usuario = "SELECT idUsuario, nome, emailInstitucional, linkCurriculo, sobre, fotoPerfil FROM usuario WHERE matricula = ?";
$stmt_usuario = $conn->prepare($sql_usuario);
$stmt_usuario->bind_param("s", $matricula);
$stmt_usuario->execute();
$result_usuario = $stmt_usuario->get_result();
$usuario = $result_usuario->fetch_assoc();
$idUsuario = $usuario['idUsuario'];
$stmt_usuario->close();
$areas_de_estudo = [];
$sql_areas = "SELECT lp.nome FROM Usuario u INNER JOIN areaEstudo ae ON u.idUsuario = ae.idUsuario INNER JOIN linhaPesquisa lp ON ae.idLinhaPesquisa = lp.idLinhaPesquisa WHERE u.matricula = ?";
$stmt_areas = $conn->prepare($sql_areas);
if ($stmt_areas) {
  $stmt_areas->bind_param("s", $matricula);
  $stmt_areas->execute();
  $result_areas = $stmt_areas->get_result();
  while ($row = $result_areas->fetch_assoc()) {
    $areas_de_estudo[] = $row['nome'];
  }
  $stmt_areas->close();
}


$sql_trabalhos = "
    SELECT 
        t.idTrabalho, t.idUsuario, t.titulo, t.nomePesquisador, t.dtPubli, t.anoTrab,
        t.palavrasChaves, t.resumo, t.abstract, t.arquivoTrabalho,
        GROUP_CONCAT(lp.nome SEPARATOR ', ') as areas_de_estudo
    FROM Trabalho t
    LEFT JOIN TrabalhoArea ta ON t.idTrabalho = ta.idTrabalho
    LEFT JOIN LinhaPesquisa lp ON ta.idLinhaPesquisa = lp.idLinhaPesquisa
    WHERE t.idUsuario = ?
    GROUP BY t.idTrabalho
    ORDER BY t.dtPubli DESC;
";
$stmt_trabalhos = $conn->prepare($sql_trabalhos);
$stmt_trabalhos->bind_param("i", $idUsuario);
$stmt_trabalhos->execute();
$resultado_trabalhos = $stmt_trabalhos->get_result();

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Meu Perfil</title>
  <meta name="description" content="">
  <meta name="keywords" content="">
  <link href="assets/img/iconTab.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
    rel="stylesheet">
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/css/main.css" rel="stylesheet">
  <style>
    .perfilContainer {
      display: flex;
      padding: 2rem 10rem;
      justify-content: center;
      gap: 4rem;
      align-items: stretch;
      height: 80vh;
    }

    .trabalhos {
      width: 55%;
      display: flex;
      flex-direction: column;
      height: 100%;
    }

    .trabalhosContainers {
      flex: 1;
      overflow-y: auto;
      padding-right: 10px;
      display: block;
      width: 100%;
    }

    .trabalhosContainers::-webkit-scrollbar,
    .profile .card-body::-webkit-scrollbar {
      width: 8px;
      background-color: #00000000;
    }

    ::-webkit-scrollbar-thumb {
      background-color: #0f846e;
      border-radius: 20px;
    }

    .profile {
      width: 30%;
    }

    .profile>.container,
    .profile .request-info {
      height: 100%;
      position: relative;
    }

    .profile .request-info .card {
      background-color: #ffffff;
      border: none;
      box-shadow: none;
      width: 100%;
      margin: 0;
      height: 100%;
      display: flex;
      flex-direction: column;
    }

    .profile .request-info .card .card-body {
      padding: 30px 15px 30px 0;
      text-align: left !important;
      flex: 1;
      overflow-y: auto;
      overflow-x: hidden;
    }

    .profile .card-divisions {
      display: flex;
      flex-direction: column;
      padding: 0 !important;
    }

    .profile .imgName {
      display: flex;
      align-items: center;
      gap: 0px;
    }

    .image-profile {
      position: relative;
    }

    .profile .image-profile i {
      font-size: 80px;
      padding: 0;
      color: #333;
    }

    .image-profile img {
      width: 140px;
      height: 140px;
      border-radius: 50%;
      object-fit: cover;
    }

    .profile .imgName>.textProfile {
      font-size: 23px;
      font-weight: 500;
      color: #000000;
      word-wrap: normal;
    }

    .dadosObrigatorios p {
      margin: 0;
    }

    .imgName {
      display: flex;
    }

    .dadosObrigatorios {
      gap: 0.75rem;
    }

    .btnEditar-container {
      padding: 0 0 0 2rem !important;
    }

    .trabalhosContainers a {
      display: block;
      width: 100%;
      margin-bottom: 1rem;
      transition: transform 0.2s ease-in-out;
    }




    .trabalhosContainer {
      width: 100%;
      background-color: #e3f2ec;
      border: 1px solid #0f846e;
      border-radius: 8px;
      padding: 1.25rem;
      position: relative;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      min-height: 140px;
      color: #333;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }


    .trabalhosContainer h3 {
      font-size: 1.25rem;
      color: #0d7360;
      font-weight: 600;
      margin-bottom: 0.25rem;
    }

    .trabalhosContainer .author {
      font-size: 0.9rem;
      color: #555;
      margin-bottom: 1rem;
    }


    .event-meta {
      display: flex;
      gap: 30px;
      align-items: center;
      width: 100%;
      font-size: 0.85rem;
      color: #444;
      padding-top: 0.75rem;
    }

    .event-meta p {
      margin: 0;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .trabalhosContainer p {
      position: static;
    }


    .telaEditarPerfil {
      background-color: #fff;
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      width: 50%;
      height: 85%;
      z-index: 1000;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
      display: none;
    }

    .overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.6);
      z-index: 999;
      display: none;
    }

    .fechar-popup {
      position: absolute;
      top: 10px;
      right: 15px;
      font-size: 30px;
      font-weight: bold;
      border: none;
      background: none;
      cursor: pointer;
      color: #333;
      z-index: 10;
    }

    #fecharPopupButton {
      background-color: #00000000;
      border: none;
      color: #0f846e;
      font-weight: bold;
      font-size: 30PX;
      position: absolute;
      right: 5%;
    }

    .formEditarPerfil {
      padding: 1rem 2rem;
    }

    .editarFoto {
      flex: 2;
    }

    .editarDados {
      flex: 3;
    }

    #sobreMim {
      word-wrap: break-word;
      white-space: pre-wrap;
    }

    .submission-form .form-control,
    .telaEditarPerfil .form-control,
    .telaEditarPerfil .form-select,
    #telaSubmissaoPesquisa .form-select,
    #telaSubmissaoPesquisa .form-control,
    #telaEdicaoPesquisa .form-control,
    #telaEdicaoPesquisa .form-select,
    #telaCadastroProjeto .form-control,
    #telaCadastroProjeto .form-select {
      border: 2px solid #000;
      border-radius: 12px;
      padding: 10px 15px;
      height: auto;
      font-size: 1rem;
    }

    .submission-form .form-control:focus,
    .telaEditarPerfil .form-control:focus,
    .telaEditarPerfil .form-select:focus,
    #telaSubmissaoPesquisa .form-select:focus,
    #telaSubmissaoPesquisa .form-control:focus,
    #telaEdicaoPesquisa .form-control:focus,
    #telaEdicaoPesquisa .form-select:focus,
    #telaCadastroProjeto .form-control:focus,
    #telaCadastroProjeto .form-select:focus {
      border-color: #008069;
      box-shadow: none;
    }

    .submission-form input[type="file"],
    .telaEditarPerfil input[type="file"] {
      padding: 5px;
    }

    .submission-form input[type="file"]::file-selector-button,
    .telaEditarPerfil input[type="file"]::file-selector-button {
      background-color: #e9ecef;
      border: none;
      border-radius: 8px;
      padding: 10px 15px;
      color: #495057;
      cursor: pointer;
      transition: background-color .15s ease-in-out;
      margin-right: 15px;
    }

    .submission-form input[type="file"]::file-selector-button:hover,
    .telaEditarPerfil input[type="file"]::file-selector-button:hover {
      background-color: #dde1e5;
    }

    .btn-custom {
      background-color: #BCCEC9;
      border: none;
      color: #3f3f3f;
      padding: 10px 40px;
      border-radius: 20px;
      font-weight: 600;
      font-size: 1rem;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .buttonsEdtPerfil button {
      background-color: #BCCEC9;
      border: none;
      color: #3f3f3f;
      width: 100%;
      padding: 5px 10px;
      border-radius: 10px;
      margin: 5px 0;
      font-weight: 600;
      font-size: 1rem;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .buttonsEdtPerfil button:hover {
      background-color: #a9bfb9;
    }

    .btn-custom:hover {
      background-color: #a9bfb9;
    }

    #telaSubmissaoPesquisa,
    #telaEdicaoPesquisa,
    #telaCadastroProjeto {
      background-color: #fff;
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      width: 50%;
      height: 80%;
      z-index: 1000;
      border-radius: 8px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
      display: none;
      overflow-y: auto;
    }

    .submission-card-inner {
      text-align: center;
      padding: 20px 40px;
    }

    .submission-card-inner .card-title {
      position: relative;
      padding-bottom: 0.75rem;
      margin-bottom: 2rem;
    }

    .submission-card-inner .card-title::after {
      content: "";
      position: absolute;
      left: 50%;
      bottom: 0;
      width: 80px;
      height: 3px;
      transform: translateX(-50%);
      background-color: #008069;
    }

    .submission-card-inner .intro-text {
      padding-bottom: 30px;
      font-size: 1rem;
      color: #333;
    }

    .submission-card-inner .intro-text p {
      margin-bottom: 0.5rem;
    }

    .submission-card-inner .form-header {
      height: 25px;
      background-color: #008069;
      margin: 0 -40px 40px -40px;
    }

    .submission-form .form-group {
      margin-bottom: 25px;
    }

    .submission-form label:not(.form-check-label) {
      font-weight: 600;
      color: #212529;
      font-size: 1.1rem;
      padding-right: 15px;
      text-align: right;
    }

    .form-buttons {
      margin-top: 40px;
      display: flex;
      justify-content: center;
      gap: 20px;
    }

    .dropdown-menu {
      max-height: 250px;
      overflow-y: auto;
    }

    .btn-primary,
    .btn-primary:active,
    .btn-primary:focus {
      background-color: var(--accent-color);
      border-color: var(--accent-color);
      color: var(--contrast-color);
      box-shadow: none;
    }

    .btn-primary:hover {
      background-color: color-mix(in srgb, var(--accent-color), transparent 20%);
      border-color: color-mix(in srgb, var(--accent-color), transparent 20%);
    }

    .btn-submeter-custom {
      background-color: #0f846e;
      border: 1px solid #0f846e;
      color: #ffffff;
      padding: .375rem .75rem;
      font-size: 1rem;
      border-radius: .25rem;
      transition: background-color .15s ease-in-out, border-color .15s ease-in-out;
      cursor: pointer;
    }

    .btn-submeter-custom:hover {
      background-color: #0d7360;
      border-color: #0c6656;
      color: #ffffff;
    }

    .buttonsEdtPerfil button:active,
    .buttonsEdtPerfil button:focus {
      background-color: #0f846e;
      border-color: #0f846e;
      color: #ffffff;
      box-shadow: none;
    }

    .popup-editar-foto {
      display: none;
      position: fixed;
      z-index: 1001;
      left: 50%;
      top: 50%;
      transform: translate(-50%, -50%);
      background-color: #ffffff;
      border-radius: 8px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
      width: 380px;
      font-family: 'Poppins', sans-serif;
    }

    .popup-editar-foto .popup-content {
      padding: 20px 25px;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .popup-editar-foto .popup-header {
      width: 100%;
      display: flex;
      justify-content: center;
      position: relative;
      margin-bottom: 20px;
    }

    .popup-editar-foto .popup-header h3 {
      margin: 0;
      font-size: 22px;
      font-weight: 500;
      color: #333;
    }

    .popup-editar-foto .fechar-popup {
      position: absolute;
      top: -10px;
      right: -10px;
      font-size: 28px;
      font-weight: normal;
      color: #666;
      background: none;
      border: none;
      cursor: pointer;
    }

    .popup-editar-foto .popup-body {
      display: flex;
      flex-direction: column;
      align-items: center;
      width: 100%;
    }

    .popup-editar-foto .image-preview-container {
      width: 150px;
      height: 150px;
      border-radius: 50%;
      background-color: #f0f0f0;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
      margin-bottom: 25px;
      border: 1px solid #ddd;
    }

    .popup-editar-foto #previewFotoPopup {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .popup-editar-foto .file-input-wrapper {
      display: flex;
      border: 1px solid #ccc;
      border-radius: 5px;
      width: 100%;
      overflow: hidden;
      margin-bottom: 25px;
      font-size: 14px;
      align-items: center;
    }

    .popup-editar-foto .file-input-label {
      background-color: #f7f7f7;
      padding: 8px 12px;
      cursor: pointer;
      border-right: 1px solid #ccc;
      color: #555;
      white-space: nowrap;
    }

    .popup-editar-foto #fileNamePopup {
      padding: 8px 12px;
      color: #777;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      flex-grow: 1;
    }

    .popup-editar-foto .popup-footer {
      display: flex;
      gap: 15px;
      justify-content: center;
    }

    button {
      padding: 10px 25px;
      border-radius: 20px;
      border: none;
      font-weight: 600;
      font-size: 16px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .btn-salvar {
      background-color: #BCCEC9;
      color: #3f3f3f;
    }

    .btn-salvar:hover {
      background-color: #a9bfb9;
    }

    .btn-cancelar {
      background-color: #6c757d;
      color: white;
    }

    .btn-cancelar:hover {
      background-color: #5a6268;
    }

    .modal-content {
      background-color: #e3f2ec;
      border: none;
      border-radius: 15px;
      padding: 20px;
    }


    .modal-header {
      border-bottom: none;
      padding: 0;
      margin-bottom: 0;
    }


    #modal-title {
      font-weight: 700;
      color: #212529;
      line-height: 1.2;
    }

    #modal-areas.text-muted {
      font-size: 1rem;
      color: #555 !important;
      margin-bottom: 1rem;
    }


    .btn-visualizar-arquivo {
      background-color: #cad6d5;
      color: #3f514f;
      font-weight: 500;
      border: none;
      border-radius: 20px;
      padding: 8px 25px;
      transition: background-color 0.3s;
    }

    .btn-visualizar-arquivo:hover {
      background-color: #b8c6c5;
      color: #3f514f;
    }


    .info-group .info-item,
    .abstract-group {
      background-color: #cad6d5;
      padding: 12px 20px;
      margin-bottom: 8px;
      border-radius: 8px;
      font-size: 1rem;
    }


    .info-group .info-item {
      border-bottom: none;
    }

    .info-group .info-item strong,
    .abstract-group strong {
      color: #3f514f;
    }

    .abstract-group p {
      margin-top: 5px;
      text-align: justify;
      line-height: 1.6;
    }


    .modal-header .btn-close {
      position: absolute;
      top: 1.5rem;
      right: 1.5rem;
      z-index: 10;
    }

    .editarApagar-container {
      position: absolute;
      right: 0;
      top: 0;
      z-index: 100;
    }

    .btnEditarApagar {
      position: relative;
      background-color: #00000000;
      font-size: 25px;
    }

    .dropdownEditarApagar {
      position: absolute;
      top: 80%;
      right: 40%;
      width: 120px;
      z-index: 100;
      display: none;
      flex-direction: column;
      text-align: left;
      color: #000;
      background: var(--surface-color);
      border: 1px solid var(--accent-color);
      padding: 10px;
      border-radius: 5px;
    }

    .dropdownEditarApagar ul {
      list-style: none;
      padding: 0;
      width: 100%;
      margin: 0;
      display: flex;
      flex-direction: column;
    }

    .dropdownEditarApagar ul li {
      margin: 0;
      display: flex;
      align-items: center;
      gap: 6px;
      cursor: pointer;
      transition: 0.3s ease;

    }

    .dropdownEditarApagar ul li:hover {
      transform: scale(102%);
    }

    .dropdownEditarApagar ul p {
      margin: 0;
    }

    .dropdownEditarApagar ul hr {
      margin: 10px 0;
    }


    .triangulo {
      width: 1rem;
      height: 1rem !important;
      transform: rotate(45deg) !important;
      left: 50%;
      transform: translate(-50%, 0);
      background-color: #e3f2ec;
      z-index: 9999;
    }

    .retangulo {
      width: 50%;
      position: absolute;
      background-color: #e3f2ec;
      left: 50%;
      margin-top: 0.5rem;
      z-index: 9999;
      transform: translate(-50%, 0);
      text-align: center !important;
      padding: 1rem 1.5rem;
    }

    .buttonsSubmeter-container {
      display: flex;
      justify-content: space-between;
    }

    .buttonsSubmeter-container button {
      width: 48%;
    }


    @media (max-width: 767px) {
      .submission-form label {
        text-align: left;
        margin-bottom: 8px;
      }

      #telaSubmissaoPesquisa,
      .telaEditarPerfil {
        width: 90%;
      }
    }
  </style>
</head>

<body class="starter-page-page"> <?php include('assets/php/navbar.php'); ?>
  <main class="main">
    <div class="page-title dark-background">
      <div class="container position-relative">
        <h1>Perfil</h1>
      </div>
    </div>
    <div class="perfilContainer">
      <section id="profile" class="profile section">
        <div class="container" data-aos="fade-up" data-aos-delay="100">
          <div class="request-info" data-aos="fade-up">
            <div class="card">
              <div class="card-body text-center">
                <div class="card-divisions d-flex p-5">
                  <div class="imgName">
                    <div class="image-profile"> <img
                        src="<?php echo htmlspecialchars($usuario['fotoPerfil']) . '?v=' . time(); ?>"
                        alt="Foto de Perfil"> </div>
                    <div class="buttonsEdtPerfil"> <button type="button" onclick="abrirPopupEditar()" class="btn">Editar
                        Informações</button> <button id="btnEditarImg" class="btn" type="button">Editar Imagem</button> <button class="btn" type="button">Visualizar/Editar Projeto</button>
                    </div>
                  </div>
                  <div class="dadosObrigatorios text-start d-flex row align-items-center px-4">
                    <div class="matriculaItems px-4">
                      <p class="titleProfile">Nome</p>
                      <p class="textProfile" id="nomeText"><?php echo htmlspecialchars($usuario['nome']); ?></p>
                    </div>
                    <div class="matriculaItems px-4">
                      <p class="titleProfile">Matrícula</p>
                      <p class="textProfile" id="textMatricula"><?php echo htmlspecialchars($matricula); ?></p>
                    </div>
                    <div class="areaItems px-4">
                      <p class="titleProfile">Área(s)</p>
                      <p class="textProfile"><?php echo htmlspecialchars(implode(', ', $areas_de_estudo)); ?></p>
                    </div>
                    <div class="emailItems px-4">
                      <p class="titleProfile">Email</p>
                      <p class="textProfile" id="emailText">
                        <?php echo htmlspecialchars($usuario['emailInstitucional']); ?>
                      </p>
                    </div>
                    <div class="lattesItems px-4">
                      <p class="titleProfile">Currículo</p> <a
                        href="<?php echo htmlspecialchars($usuario['linkCurriculo']); ?>" target="_blank"
                        class="textProfile" id="lattesText">
                        <?php if (empty($usuario['linkCurriculo'])) {
                          echo "Não Informado.";
                        } else {
                          echo htmlspecialchars($usuario['linkCurriculo']);
                        } ?>
                      </a>
                    </div>
                    <div class="lattesItems px-4">
                      <p class="titleProfile">Sobre mim</p>
                      <p class="textProfile" style="text-align: justify;" id="sobreText"><?php if (empty($usuario['sobre'])) {
                                                                                            echo "Não Informado.";
                                                                                          } else {
                                                                                            echo htmlspecialchars($usuario['sobre']);
                                                                                          } ?> </p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <section class="trabalhos" data-aos="fade-up" data-aos-delay="100">
        <h2 class="mb-4">Minhas Publicações</h2>
        <div class="trabalhosContainers">

          <?php if ($resultado_trabalhos->num_rows > 0): ?>
            <?php while ($trabalho = $resultado_trabalhos->fetch_assoc()):
              $sql_nomeOrientador = "SELECT nome FROM usuario WHERE idUsuario = ?";
              $stmt_nomeOrientador = $conn->prepare($sql_nomeOrientador);
              $stmt_nomeOrientador->bind_param("i", $trabalho["idUsuario"]);
              $stmt_nomeOrientador->execute();
              $resultado_orientador = $stmt_nomeOrientador->get_result();
              $orientador = $resultado_orientador->fetch_assoc();
              $areas_array = explode(', ', $trabalho['areas_de_estudo']);
            ?>
              <div style="position: relative;" class="trabalho-item"
                data-id-trabalho="<?php echo $trabalho['idTrabalho']; ?>"
                data-titulo="<?php echo htmlspecialchars($trabalho['titulo']); ?>"
                data-autores="<?php echo htmlspecialchars($trabalho['nomePesquisador']); ?>"
                data-ano="<?php echo htmlspecialchars($trabalho['anoTrab']); ?>"
                data-palavras-chaves="<?php echo htmlspecialchars($trabalho['palavrasChaves']); ?>"
                data-resumo="<?php echo htmlspecialchars($trabalho['resumo']); ?>"
                data-abstract="<?php echo htmlspecialchars($trabalho['abstract']); ?>"
                data-arquivo-trabalho="<?php echo htmlspecialchars($trabalho['arquivoTrabalho']); ?>"
                data-areas='<?php echo json_encode($areas_array); ?>'>

                <div class="editarApagar-container">
                  <button class="btnEditarApagar"><i class="bi bi-three-dots-vertical"></i></button>
                  <div class="dropdownEditarApagar">
                    <ul>
                      <li onclick="abrirPopupEdicao(this)"><i class="bi bi-pencil-square"></i>
                        <p>Editar</p>
                      </li>
                      <hr>
                      <li onclick="apagarPesquisa(<?php echo $trabalho['idTrabalho']; ?>)"><i class="bi bi-trash3"></i>
                        <p>Apagar</p>
                      </li>
                    </ul>
                  </div>
                </div>

                <a href="#" class="trabalho-clickable text-decoration-none" data-bs-toggle="modal"
                  data-bs-target="#trabalhoModal" data-titulo="<?php echo htmlspecialchars($trabalho['titulo']); ?>"
                  data-autor="<?php echo htmlspecialchars($trabalho['nomePesquisador']); ?>"
                  data-ano="<?php echo htmlspecialchars($trabalho['anoTrab']); ?>"
                  data-publicacao="<?php echo date("d/m/Y", strtotime($trabalho['dtPubli'])); ?>"
                  data-keywords="<?php echo htmlspecialchars($trabalho['palavrasChaves']); ?>"
                  data-resumo="<?php echo htmlspecialchars($trabalho['resumo']); ?>"
                  data-abstract="<?php echo htmlspecialchars($trabalho['abstract']); ?>"
                  data-file-url="<?php echo htmlspecialchars($trabalho['arquivoTrabalho']); ?>"
                  data-areas="<?php echo htmlspecialchars($trabalho['areas_de_estudo']); ?>"
                  data-orientador="<?php echo htmlspecialchars($orientador['nome']) ?>">
                  <div class="trabalhosContainer">
                    <div>
                      <h3><?php echo htmlspecialchars($trabalho['titulo']); ?></h3>
                      <p class="author"><?php echo htmlspecialchars($trabalho['nomePesquisador']); ?></p>
                    </div>
                    <div class="event-meta">
                      <p><i class="bi bi-folder2-open"></i> <?php echo htmlspecialchars($trabalho['areas_de_estudo']); ?>
                      </p>
                      <p><i class="bi bi-person-workspace"></i> <?php echo htmlspecialchars($orientador['nome']) ?> </p>
                      <p><i class="bi bi-calendar"></i> <?php echo date("d.m.Y", strtotime($trabalho['dtPubli'])); ?></p>
                    </div>
                  </div>
                </a>
              </div>
            <?php endwhile; ?>
          <?php else: ?>
            <div class="alert alert-secondary text-center">
              Você ainda não publicou nenhuma pesquisa.
            </div>
          <?php endif; ?>
        </div>
        <div class="buttonsSubmeter-container">
          <button class="btn-submeter-custom mt-4" onclick="abrirPopupSubmissao()">Submeter Nova Pesquisa</button>
          <button class="btn-submeter-custom mt-4" onclick="abrirPopupCadProj()">Cadastrar Projeto</button>
        </div>


      </section>
    </div>
    <div id="confirmarApagarPopup"
      style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.6); z-index: 1050; align-items: center; justify-content: center;">
      <div
        style="background: #fff; padding: 25px; border-radius: 8px; text-align: center; box-shadow: 0 5px 15px rgba(0,0,0,0.3); width: 90%; max-width: 400px;">
        <h5 class="mb-3">Confirmar Exclusão</h5>
        <p>Tem certeza de que deseja apagar esta pesquisa? Esta ação não pode ser desfeita.</p>
        <div class="mt-4">
          <button type="button" class="btn btn-cancelar" onclick="fecharConfirmacaoApagar()">Cancelar</button>
          <button type="button" class="btn btn-salvar" id="btnConfirmarApagar">Sim, Apagar</button>
        </div>
      </div>
    </div>

    <div class="modal fade" id="trabalhoModal" tabindex="-1" aria-labelledby="trabalhoModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <h2 id="modal-title">Título do Trabalho</h2>
            <p class="text-muted" id="modal-areas">Áreas do Trabalho: áreas</p>
            <a href="#" id="modal-file-link" class="btn btn-visualizar-arquivo" target="_blank">Visualizar arquivo</a>

            <div class="info-group mt-4">
              <div class="info-item">
                <strong>Autor(es):</strong> <span id="modal-author"></span>
              </div>
              <div class="info-item">
                <strong>Orientador:</strong> <span id="modal-orientador"></span>
              </div>
              <div class="info-item">
                <strong>Data da Publicação:</strong> <span id="modal-date"></span>
              </div>
              <div class="info-item">
                <strong>Ano da Pesquisa:</strong> <span id="modal-ano"></span>
              </div>
              <div class="info-item">
                <strong>Palavras Chave:</strong> <span id="modal-keywords"></span>
              </div>
              <div class="info-item">
                <strong>Resumo:</strong> <span id="modal-summary"></span>
              </div>
            </div>
            <div class="abstract-group mt-3">
              <strong>Abstract:</strong>
              <p id="modal-abstract"></p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
  <div class="overlay" id="overlay"></div>
  <div class="telaEditarPerfil text-center" id="telaEditarPerfil">
    <button class="fechar-popup" onclick="fecharPopups()">×</button>
    <h2>Editar Informações</h2>
    <form class="php-email-form mt-2 d-flex formEditarPerfil" action="assets/php/atualizarPerfil.php" method="POST"
      id="formEditarPerfil">
      <div class="editarDados">
        <div class="mb-3">
          <h4 class="text-start">Nome</h4> <input type="text" name="nome" placeholder="Nome" class="form-control">
        </div>
        <div class="dropdown mb-3">
          <h4 class="text-start">Áreas de Estudo</h4>
          <div class="dropdown position-relative"> <button class="form-select text-start" type="button"
              id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false"> <span
                id="dropdownMenuText">Selecione uma ou mais áreas de estudo...</span> </button>
            <div class="triangulo position-absolute" id="triang_1" style="display: none;"> </div>
            <div class="retangulo" style="display: none;" id="retang_1">
              <p class="mb-0" style="color:#000;font-weight: bold; font-size: 15px;">Selecione pelo menos uma área</p>
            </div>
            <?php $todas_as_areas = ["Artes", "Audiovisual", "Biologia", "Design", "Educação Física", "Filosofia", "Física", "Geografia", "História", "Informática", "Letras", "Matemática", "Química", "Sociologia"]; ?>
            <ul class="dropdown-menu w-100" id="areas_cb" aria-labelledby="dropdownMenuButton"
              data-bs-auto-close="outside">
              <?php foreach ($todas_as_areas as $area): ?>
                <li>
                  <div class="dropdown-item">
                    <div class="form-check"> <input class="form-check-input" type="checkbox" name="subject[]"
                        value="<?php echo htmlspecialchars($area); ?>"
                        id="check<?php echo htmlspecialchars(str_replace(' ', '', $area)); ?>" <?php if (in_array($area, $areas_de_estudo)) {
                                                                                                  echo 'checked';
                                                                                                } ?>> <label class="form-check-label w-100"
                        for="check<?php echo htmlspecialchars(str_replace(' ', '', $area)); ?>">
                        <?php echo htmlspecialchars($area); ?> </label> </div>
                  </div>
                </li> <?php endforeach; ?>
            </ul>
          </div>
        </div>
        <div class="mb-3">
          <h4 class="text-start">Email Instituicional</h4> <input type="email" name="emailInst" class="form-control"
            placeholder="Email Instituicional">
        </div>
        <div class="mb-3">
          <h4 class="text-start">Currículo Lattes</h4> <input type="url" name="lattes" class="form-control"
            placeholder="Curriculo Lattes">
        </div>
        <div class="mb-2">
          <h4 class="text-start">Sobre Mim</h4> <textarea style="resize: none;" id="sobreMimInput" rows="5"
            name="sobreMim" class="form-control" placeholder="Digite algo sobre você!" maxlength="700"
            oninput="atualizarContador()"></textarea>
          <div class="form-text" style="text-align: right;" id="contador">0 / 700</div>
        </div> <input type="hidden" name="matricula" id="matricula">
        <div class="my-0">
          <div class="loading">Carregando</div>
          <div class="error-message"></div>
          <div class="sent-message">Sua atualização foi salva com sucesso!</div>
        </div> <input type="hidden" name="matricula" id="matricula"> <button type="submit"
          class="btn-salvar">Salvar</button> <button type="button" onclick="fecharPopups()"
          class="btn-cancelar">Cancelar</button>
      </div>
    </form>
  </div>
  <div class="popup-editar-foto" id="popupEditarFoto">
    <form action="assets/php/atualizarFotoPerfil.php" method="POST" enctype="multipart/form-data" class="popup-content">
      <input type="hidden" name="action" value="update_photo">
      <div class="popup-header">
        <h3>Editar Foto de Perfil</h3> <button type="button" class="fechar-popup" onclick="fecharPopups()">×</button>
      </div>
      <div class="popup-body">
        <div class="image-preview-container"> <img id="previewFotoPopup"
            src="<?php echo htmlspecialchars($usuario['fotoPerfil']) ?>" alt="Pré-visualização da foto de perfil">
        </div>
        <div class="file-input-wrapper"> <label for="inputFotoPopup" class="file-input-label">Escolher arquivo</label>
          <input type="file" id="inputFotoPopup" name="foto-perfil" accept="image/png, image/jpeg, image/webp"
            style="display: none;"> <span id="fileNamePopup">Nenhum arquivo escolhido</span>
        </div>
      </div>
      <div class="popup-footer"> <button type="submit" class="btn-salvar">Salvar Foto</button> <button type="button"
          class="btn-cancelar" onclick="fecharPopups()">Cancelar</button> </div>
    </form>
  </div>
  <div id="telaEdicaoPesquisa" style="overflow-y: auto;">
    <button class="fechar-popup" onclick="fecharPopups()">×</button>
    <div class="submission-card-inner">
      <h3 class="card-title">Editar Pesquisa</h3>

      <form method="post" class="submission-form text-start" action="assets/php/editarSubmissao.php"
        enctype="multipart/form-data" id="formEditarSubmissao">

        <input type="hidden" name="idTrabalho" id="edit-idTrabalho">
        <input type="hidden" name="arquivo_existente" id="edit-arquivo_existente">

        <div class="form-group row align-items-center">
          <div class="col-md-3"> <label for="edit-file-upload-popup">Arquivo (PDF):</label> </div>
          <div class="col-md-9">
            <input type="file" class="form-control" id="edit-file-upload-popup" name="arquivo-trabalho" accept=".pdf">
            <small class="form-text text-muted">Envie um novo arquivo apenas se quiser substituí-lo.</small>
          </div>
        </div>
        <div class="form-group row align-items-center">
          <div class="col-md-3"> <label for="edit-title-popup">Título:</label> </div>
          <div class="col-md-9"> <input type="text" class="form-control" id="edit-title-popup" name="titulo" required>
          </div>
        </div>
        <div class="form-group row align-items-center">
          <div class="col-md-3"> <label for="edit-authors-popup">Autor(es):</label> </div>
          <div class="col-md-9"> <input type="text" class="form-control" id="edit-authors-popup" name="autores"
              required></div>
        </div>

        <div class="form-group row align-items-center">
          <div class="col-md-3"> <label for="dropdownMenuButton_2">Áreas de Estudo:</label> </div>
          <div class="col-md-9">
            <div class="dropdown position-relative">
              <button class="form-select text-start" type="button" id="dropdownMenuButton_2" data-bs-toggle="dropdown"
                aria-expanded="false">
                <span id="dropdownMenuText2">Selecione uma ou mais áreas de estudo...</span>
              </button>
              <div class="triangulo position-absolute" id="triang_3" style="display: none;">
              </div>
              <div class="retangulo" style="display: none;" id="retang_3">
                <p class="mb-0" style="color:#000;font-weight: bold; font-size: 15px;">Selecione pelo menos uma
                  área</p>
              </div>
              <ul class="dropdown-menu w-100" id="areas_cb_2" aria-labelledby="dropdownMenuButton_2"
                data-bs-auto-close="outside">
                <?php foreach ($todas_as_areas as $area): ?>
                  <li>
                    <div class="dropdown-item">
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="subject[]"
                          value="<?php echo htmlspecialchars($area); ?>"
                          id="check<?php echo htmlspecialchars(str_replace(' ', '', $area)); ?>_2">
                        <label class="form-check-label w-100 ms-2"
                          for="check<?php echo htmlspecialchars(str_replace(' ', '', $area)); ?>_2"><?php echo htmlspecialchars($area); ?></label>
                      </div>
                    </div>
                  </li>
                <?php endforeach; ?>
              </ul>
            </div>
          </div>
        </div>
        <div class="form-group row align-items-center">
          <div class="col-md-3"> <label for="edit-keywords-popup">Palavras-chave:</label> </div>
          <div class="col-md-9"> <input type="text" class="form-control" id="edit-keywords-popup" name="palavras_chaves"
              required></div>
        </div>
        <div class="form-group row align-items-center">
          <div class="col-md-3"> <label for="edit-doc-date-popup">Ano do documento:</label> </div>
          <div class="col-md-9"> <input type="number" class="form-control" id="edit-doc-date-popup" name="ano_trabalho"
              required></div>
        </div>
        <div class="form-group row">
          <div class="col-md-3"> <label for="edit-resumo-popup">Resumo:</label> </div>
          <div class="col-md-9"> <textarea class="form-control" id="edit-resumo-popup" name="resumo" rows="5"
              required></textarea></div>
        </div>
        <div class="form-group row">
          <div class="col-md-3"> <label for="edit-abstract-popup">Abstract:</label> </div>
          <div class="col-md-9"> <textarea class="form-control" id="edit-abstract-popup" name="abstract" rows="5"
              required></textarea></div>
        </div>
        <div class="form-buttons">
          <button type="submit" class="btn-salvar">Salvar Alterações</button>
          <button type="button" class="btn-cancelar" onclick="fecharPopups()">Cancelar</button>
        </div>
      </form>
    </div>
  </div>
  <div id="telaSubmissaoPesquisa">
    <button class="fechar-popup" onclick="fecharPopups()">×</button>
    <div class="submission-card-inner">
      <h3 class="card-title">Submissão de Pesquisa</h3>
      <div class="intro-text">
        <p><strong>Caro(a) docente,</strong></p>
        <p>Por gentileza, utilize o formulário abaixo para realizar a submissão da sua pesquisa.</p>
        <p>Após o envio, as informações fornecidas serão exibidas em seu perfil, tornando o conteúdo acessível
          para visualização e consulta.</p>
      </div>
      <div class="form-header"></div>
      <form method="post" class="submission-form" action="assets/php/submeterPesquisa.php" enctype="multipart/form-data"
        id="formSubmeterPesquisa">
        <div class="form-group row align-items-center">
          <div class="col-md-3"> <label for="file-upload-popup">Arquivo:</label> </div>
          <div class="col-md-9"> <input type="file" class="form-control" id="file-upload-popup"
              title="Escolha um arquivo" name="arquivo-trabalho" accept=".pdf" required> </div>
        </div>
        <div class="form-group row align-items-center">
          <div class="col-md-3"> <label for="title-popup">Título:</label> </div>
          <div class="col-md-9"> <input type="text" class="form-control" id="title-popup" name="titulo" required>
          </div>
        </div>
        <div class="form-group row align-items-center">
          <div class="col-md-3"> <label for="authors-popup">Autor(es):</label> </div>
          <div class="col-md-9"> <input type="text" class="form-control" id="authors-popup" name="autores" required>
          </div>
        </div>

        <div class="form-group row align-items-center">
          <div class="col-md-3"> <label for="dropdownMenuButton_3">Áreas de Estudo:</label> </div>
          <div class="col-md-9">
            <div class="dropdown position-relative">
              <button class="form-select text-start" type="button" id="dropdownMenuButton_3" data-bs-toggle="dropdown"
                aria-expanded="false">
                <span id="dropdownMenuText3">Selecione uma ou mais áreas de estudo...</span>
              </button>
              <div class="triangulo position-absolute" id="triang_2" style="display: none;"> </div>
              <div class="retangulo" style="display: none;" id="retang_2">
                <p class="mb-0" style="color:#000;font-weight: bold; font-size: 15px;">Selecione pelo
                  menos uma área</p>
              </div>
              <?php $todas_as_areas = ["Artes", "Audiovisual", "Biologia", "Design", "Educação Física", "Filosofia", "Física", "Geografia", "História", "Informática", "Letras", "Matemática", "Química", "Sociologia"]; ?>
              <ul class="dropdown-menu w-100" id="areas_cb_3" aria-labelledby="dropdownMenuButton_3"
                data-bs-auto-close="outside">
                <?php foreach ($todas_as_areas as $area): ?>
                  <li>
                    <div class="dropdown-item">
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="subject[]"
                          value="<?php echo htmlspecialchars($area); ?>"
                          id="check<?php echo htmlspecialchars(str_replace(' ', '', $area)); ?>_3">
                        <label class="form-check-label w-100"
                          for="check<?php echo htmlspecialchars(str_replace(' ', '', $area)); ?>_3">
                          <?php echo htmlspecialchars($area); ?> </label>
                      </div>
                  </li>
                <?php endforeach; ?>
              </ul>
            </div>
          </div>
        </div>
        <div class="form-group row align-items-center">
          <div class="col-md-3"> <label for="linhaPesqiosa-pesquisa">Linha de Pesquisa:</label> </div>
          <div class="col-md-9"> <input type="text" class="form-control" id="linhaPesquisa-pesquisa" name="linhaPesquisa-pesquisa" required>
          </div>
        </div>
        <div class="form-group row align-items-center">
          <div class="col-md-3"> <label for="doc-date-popup">Ano do documento:</label> </div>
          <div class="col-md-9"> <input type="number" class="form-control" id="doc-date-popup" name="ano_trabalho"
              required>
          </div>
        </div>
        <div class="form-group row">
          <div class="col-md-3"> <label for="resumo-popup">Resumo:</label> </div>
          <div class="col-md-9"> <textarea class="form-control" id="resumo-popup" name="resumo" rows="5"
              required></textarea>
          </div>
        </div>
        <div class="form-group row">
          <div class="col-md-3"> <label for="abstract-popup">Abstract:</label> </div>
          <div class="col-md-9"> <textarea class="form-control" id="abstract-popup" name="abstract" rows="5"
              required></textarea>
          </div>
        </div>
        <div class="form-buttons"> <button type="submit" class="btn-salvar">Enviar</button> <button type="reset"
            class="btn-cancelar" onclick="fecharPopups()">Cancelar</button> </div>
      </form>
    </div>
  </div>

  <div id="telaCadastroProjeto">
    <button class="fechar-popup" onclick="fecharPopups()">×</button>
    <div class="submission-card-inner">
      <h3 class="card-title">Cadastro de Projeto</h3>
      <div class="intro-text">
        <p><strong>Caro(a) docente,</strong></p>
        <p>Por gentileza, utilize o formulário abaixo para realizar a submissão da sua pesquisa.</p>
        <p>Após o envio, as informações fornecidas serão exibidas em seu perfil, tornando o conteúdo acessível
          para visualização e consulta.</p>
      </div>
      <div class="form-header"></div>
      <form method="post" class="submission-form" action="assets/php/submeterPesquisa.php" enctype="multipart/form-data"
        id="formCadProj">
        <div class="form-group row align-items-center">
          <div class="col-md-3"> <label for="logo-projeto">Logo (Opcional):</label> </div>
          <div class="col-md-9"> <input type="file" class="form-control" id="file-upload-popup"
              title="Escolha um arquivo" name="logo-projeto" accept="image/png, image/jpeg, image/webp" required> </div>
        </div>
        <div class="form-group row align-items-center">
          <div class="col-md-3"> <label for="nomeProjeto">Nome:</label> </div>
          <div class="col-md-9"> <input type="text" class="form-control" id="nome-popup" name="nomeProjeto" required>
          </div>
        </div>
        <div class="form-group row">
          <div class="col-md-3"> <label for="descricao-projeto">Descrição:</label> </div>
          <div class="col-md-9"> <textarea class="form-control" id="descricao-popup" name="descricao-projeto" rows="5"
              required></textarea>
          </div>
        </div>
        <div class="form-group row align-items-center">
          <div class="col-md-3"> <label for="palavras_chaves-projeto">Palavras chave:</label> </div>
          <div class="col-md-9"> <input type="text" class="form-control" id="keywords-popup" name="palavras_chaves-projeto"
              required>
          </div>
        </div>
        <div class="form-group row align-items-center">
          <div class="col-md-3"> <label for="dropdownMenuButton_4">Áreas de Estudo:</label> </div>
          <div class="col-md-9">
            <div class="dropdown position-relative">
              <button class="form-select text-start" type="button" id="dropdownMenuButton_4" data-bs-toggle="dropdown"
                aria-expanded="false">
                <span id="dropdownMenuText4">Selecione uma ou mais áreas de estudo...</span>
              </button>
              <div class="triangulo position-absolute" id="triang_4" style="display: none;"> </div>
              <div class="retangulo" style="display: none;" id="retang_4">
                <p class="mb-0" style="color:#000;font-weight: bold; font-size: 15px;">Selecione pelo
                  menos uma área</p>
              </div>
              <?php $todas_as_areas = ["Artes", "Audiovisual", "Biologia", "Design", "Educação Física", "Filosofia", "Física", "Geografia", "História", "Informática", "Letras", "Matemática", "Química", "Sociologia"]; ?>
              <ul class="dropdown-menu w-100" id="areas_cb_4" aria-labelledby="dropdownMenuButton_4"
                data-bs-auto-close="outside">
                <?php foreach ($todas_as_areas as $area): ?>
                  <li>
                    <div class="dropdown-item">
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="subject[]"
                          value="<?php echo htmlspecialchars($area); ?>"
                          id="check<?php echo htmlspecialchars(str_replace(' ', '', $area)); ?>_4">
                        <label class="form-check-label w-100"
                          for="check<?php echo htmlspecialchars(str_replace(' ', '', $area)); ?>_4">
                          <?php echo htmlspecialchars($area); ?> </label>
                      </div>
                  </li>
                <?php endforeach; ?>
              </ul>
            </div>
          </div>
        </div>
        <div class="form-group row align-items-center">
          <div class="col-md-3"> <label for="linhaPesqiosa-projeto">Linha de Pesquisa:</label> </div>
          <div class="col-md-9"> <input type="text" class="form-control" id="linhaPesquisa-projeto" name="linhaPesquisa-projeto" required>
          </div>
        </div>
        <div class="form-group row align-items-center">
          <div class="col-md-3"> <label for="colaboradores-projeto">Colaborador(es):</label> </div>
          <div class="col-md-9"> <input type="text" class="form-control" id="colaboradores-projeto" name="colaboradores-projeto" required>
          </div>
        </div>
        <div class="form-group row">
          <div class="col-md-3"> <label for="datas-projeto">Datas dos encontros (Opcional):</label> </div>
          <div class="col-md-9"> <textarea class="form-control" id="datas-projeto" name="datasProjeto" rows="2"
              required></textarea>
          </div>
        </div>
        <div class="form-buttons"> <button type="submit" class="btn-salvar">Enviar</button> <button type="reset"
            class="btn-cancelar" onclick="fecharPopups()">Cancelar</button> </div>
      </form>
    </div>
  </div>
  <footer id="footer" class="footer position-relative dark-background text-center text-white py-5">
    <div class="container footer-top">
      <div class="row gy-4 justify-content-center">
        <div class="col-lg-4 col-md-6">
          <div class="footer-logo mb-3"> <span class="sitename fs-4 fw-bold">Sinara</span> </div>
          <div class="footer-contact">
            <p>Rua Tenente Miguel Délia, 105</p>
            <p>São Miguel Paulista - SP</p>
          </div>
        </div>
        <div class="col-lg-4 col-md-6">
          <h5>Contato (IFSP)</h5>
          <div class="footer-contact pt-2">
            <p><strong>Telefone:</strong> <span> (11) 98614-0826</span></p>
            <p><strong>Email:</strong> <span>cra.smp@ifsp.edu.br</span></p>
          </div>
        </div>
        <div class="col-lg-4 col-md-6">
          <h5>Siga o IF nas redes sociais:</h5>
          <div class="social-links d-flex justify-content-center gap-3 mt-3"> <a href="https://smp.ifsp.edu.br/"
              aria-label="Site"> <img src="assets/img/logo if.png" alt="Logo do site"
                style="width: 20px; height: 24px; filter: brightness(0) invert(1);"></a> <a
              href="https://www.facebook.com/smp.ifsp/?locale=pt_BR" aria-label="Facebook"><i
                class="bi bi-facebook text-white fs-5"></i></a> <a href="https://www.instagram.com/ifspsmp/#"
              aria-label="Instagram"><i class="bi bi-instagram text-white fs-5"></i></a> <a
              href="https://www.youtube.com/channel/UCInQs4AhmS6MC3qJzihkiGw" aria-label="YouTube"><i
                class="bi bi-youtube text-white fs-5"></i></a> </div>
        </div>
      </div>
    </div>
    <div class="container copyright text-center mt-4">
      <p>© <span>Copyright</span> <strong class="px-1 sitename">MyWebsite</strong> <span>All Rights Reserved</span></p>
      <div class="credits"> Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a> </div>
    </div>
  </footer> <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>
  <div id="preloader"></div>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/confirmarLogout.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/js/main.js"></script>
  <script src="assets/js/checkboxAreas.js"></script>
  <script src="assets/js/modalTrabalho.js"></script>
  <script src="assets/js/btnEditarApagar.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const body = document.querySelector('body');
      if (body) {
        const observer = new MutationObserver(function(mutations) {
          mutations.forEach(function(mutation) {
            if (mutation.addedNodes.length > 0) {
              const successNode = document.getElementById('atualizacao-concluida');
              if (successNode) {
                console.log('Sinal de sucesso do PHP detectado! Recarregando a página...');
                setTimeout(() => {
                  window.location.reload();
                }, 0);
                observer.disconnect();
              }
            }
          });
        });
        observer.observe(body, {
          childList: true,
          subtree: true
        });
      }
    });
  </script>
  <script>
    function atualizarContador() {
      const textarea = document.querySelector('textarea[name="sobreMim"]');
      const contador = document.getElementById('contador');
      contador.textContent = `${textarea.value.length} / 700`;
    }
    document.addEventListener('DOMContentLoaded', function() {
      const popupEditar = document.getElementById('telaEditarPerfil');
      const popupSubmissao = document.getElementById('telaSubmissaoPesquisa');
      const popupCadProj = document.getElementById('telaCadastroProjeto');
      const popupFoto = document.getElementById('popupEditarFoto');
      const overlay = document.getElementById('overlay');
      document.getElementById('btnEditarImg').addEventListener('click', function() {
        if (popupFoto) popupFoto.style.display = 'block';
        if (overlay) overlay.style.display = 'block';
      });
      const inputFoto = document.getElementById('inputFotoPopup');
      const previewFoto = document.getElementById('previewFotoPopup');
      const fileNameDisplay = document.getElementById('fileNamePopup');
      const originalSrc = previewFoto.src;
      if (inputFoto) {
        inputFoto.addEventListener('change', function(event) {
          const file = event.target.files[0];
          if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
              previewFoto.src = e.target.result;
            }
            reader.readAsDataURL(file);
            fileNameDisplay.textContent = file.name;
          }
        });
      }
      window.abrirPopupEditar = function() {
        const textoNome = document.getElementById('nomeText').textContent;
        const textoEmail = document.getElementById('emailText').textContent;
        const textoLattes = document.getElementById('lattesText').textContent.trim();
        const textoSobre = document.getElementById('sobreText').textContent.trim();
        const textoMatricula = document.getElementById('textMatricula').textContent.trim();
        const formEditar = document.querySelector('#telaEditarPerfil form');
        if (formEditar) {
          formEditar.querySelector('input[name="nome"]').value = textoNome;
          formEditar.querySelector('input[name="emailInst"]').value = textoEmail;
          if (textoLattes !== "Não Informado.") {
            formEditar.querySelector('input[name="lattes"]').value = textoLattes;
          } else {
            formEditar.querySelector('input[name="lattes"]').value = "";
          }
          if (textoSobre !== "Não Informado.") {
            formEditar.querySelector('textarea[name="sobreMim"]').value = textoSobre;
          } else {
            formEditar.querySelector('textarea[name="sobreMim"]').value = "";
          }
          const matriculaInput = formEditar.querySelector('#matricula');
          if (matriculaInput) matriculaInput.value = textoMatricula;
        }
        popupEditar.style.display = 'block';
        overlay.style.display = 'block';
      }
      window.abrirPopupSubmissao = function() {
        popupSubmissao.style.display = 'block';
        overlay.style.display = 'block';
      }
      window.abrirPopupCadProj = function() {
        popupCadProj.style.display = 'block';
        overlay.style.display = 'block';
      }
      window.fecharPopups = function() {
        if (popupEditar) popupEditar.style.display = 'none';
        if (popupSubmissao) popupSubmissao.style.display = 'none';
        if (popupCadProj) popupCadProj.style.display = 'none';
        if (popupFoto) {
          popupFoto.style.display = 'none';
          previewFoto.src = originalSrc;
          fileNameDisplay.textContent = 'Nenhum arquivo escolhido';
          if (inputFoto) inputFoto.value = '';
        }
        if (overlay) overlay.style.display = 'none';
      }
      if (overlay) {
        overlay.addEventListener('click', fecharPopups);
      }
    });
  </script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const popupEdicaoPesquisa = document.getElementById('telaEdicaoPesquisa');


      const originalFecharPopups = window.fecharPopups;


      window.fecharPopups = function() {
        originalFecharPopups();
        if (popupEdicaoPesquisa) popupEdicaoPesquisa.style.display = 'none';
      };


      const overlay = document.getElementById('overlay');
      if (overlay) {
        overlay.removeEventListener('click', originalFecharPopups);
        overlay.addEventListener('click', window.fecharPopups);
      }
    });

    function abrirPopupEdicao(element) {
      const item = element.closest('.trabalho-item');
      const form = document.querySelector('#telaEdicaoPesquisa form');
      const overlay = document.getElementById('overlay');
      const popup = document.getElementById('telaEdicaoPesquisa');


      form.querySelector('#edit-idTrabalho').value = item.dataset.idTrabalho;
      form.querySelector('#edit-arquivo_existente').value = item.dataset.arquivoTrabalho;


      form.querySelector('#edit-title-popup').value = item.dataset.titulo;
      form.querySelector('#edit-authors-popup').value = item.dataset.autores;
      form.querySelector('#edit-keywords-popup').value = item.dataset.palavrasChaves;
      form.querySelector('#edit-doc-date-popup').value = item.dataset.ano;
      form.querySelector('#edit-resumo-popup').value = item.dataset.resumo;
      form.querySelector('#edit-abstract-popup').value = item.dataset.abstract;


      const checkboxes = form.querySelectorAll('#areas_cb_2 .form-check-input');
      checkboxes.forEach(cb => cb.checked = false);


      const areas = JSON.parse(item.dataset.areas);
      areas.forEach(areaNome => {
        const checkbox = form.querySelector(`input[value="${areaNome}"]`);
        if (checkbox) {
          checkbox.checked = true;
        }
      });

      checkboxes[0].dispatchEvent(new Event('change'));

      popup.style.display = 'block';
      overlay.style.display = 'block';
    }

    function apagarPesquisa(idTrabalho) {
      const popup = document.getElementById('confirmarApagarPopup');
      const btnConfirmar = document.getElementById('btnConfirmarApagar');
      btnConfirmar.dataset.idTrabalho = idTrabalho;
      popup.style.display = 'flex';
    }

    function fecharConfirmacaoApagar() {
      const popup = document.getElementById('confirmarApagarPopup');
      popup.style.display = 'none';
    }
    document.addEventListener('DOMContentLoaded', function() {
      const btnConfirmar = document.getElementById('btnConfirmarApagar');
      if (btnConfirmar) {
        btnConfirmar.addEventListener('click', function() {
          const idTrabalho = this.dataset.idTrabalho;

          const formData = new FormData();
          formData.append('idTrabalho', idTrabalho);

          fetch('assets/php/apagarPesquisa.php', {
              method: 'POST',
              body: formData
            })
            .then(response => response.json())
            .then(data => {
              if (data.success) {
                window.location.href = 'sucessoDelSubmissao.php';
              } else {
                alert('Erro: ' + data.message);
                fecharConfirmacaoApagar();
              }
            })
            .catch(error => {
              console.error('Erro na requisição:', error);
              alert('Ocorreu um erro de comunicação. Tente novamente.');
              fecharConfirmacaoApagar();
            });
        });
      }
    });
  </script>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const form = document.getElementById("formCadProj");

      form.addEventListener("submit", function(e) {
        const checkBoxes = form.querySelectorAll('input[name="subject[]"]');
        let marcado = false;

        checkBoxes.forEach(function(checkbox) {
          if (checkbox.checked) {
            marcado = true;
          }
        });

        if (!marcado) {
          e.preventDefault();
          e.stopImmediatePropagation();

          const tri = document.getElementById("triang_4");
          const ret = document.getElementById("retang_4");
          tri.style.display = "block";
          ret.style.display = "block";

        }
      }, true);
      document.addEventListener("click", function() {
        const tri = document.getElementById("triang_4");
        const ret = document.getElementById("retang_4");
        tri.style.display = "none";
        ret.style.display = "none";
      });
    });
  </script>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const form = document.getElementById("formEditarSubmissao");

      form.addEventListener("submit", function(e) {
        const checkBoxes = form.querySelectorAll('input[name="subject[]"]');
        let marcado = false;

        checkBoxes.forEach(function(checkbox) {
          if (checkbox.checked) {
            marcado = true;
          }
        });

        if (!marcado) {
          e.preventDefault();
          e.stopImmediatePropagation();

          const tri = document.getElementById("triang_3");
          const ret = document.getElementById("retang_3");
          tri.style.display = "block";
          ret.style.display = "block";

        }
      }, true);
      document.addEventListener("click", function() {
        const tri = document.getElementById("triang_3");
        const ret = document.getElementById("retang_3");
        tri.style.display = "none";
        ret.style.display = "none";
      });
    });
  </script>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const form = document.getElementById("formSubmeterPesquisa");

      form.addEventListener("submit", function(e) {
        const checkBoxes = form.querySelectorAll('input[name="subject[]"]');
        let marcado = false;

        checkBoxes.forEach(function(checkbox) {
          if (checkbox.checked) {
            marcado = true;
          }
        });

        if (!marcado) {
          e.preventDefault();
          e.stopImmediatePropagation();

          const tri = document.getElementById("triang_2");
          const ret = document.getElementById("retang_2");
          tri.style.display = "block";
          ret.style.display = "block";

        }
      }, true);
      document.addEventListener("click", function() {
        const tri = document.getElementById("triang_2");
        const ret = document.getElementById("retang_2");
        tri.style.display = "none";
        ret.style.display = "none";
      });
    });
  </script>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const form = document.getElementById("formEditarPerfil");

      form.addEventListener("submit", function(e) {
        const checkBoxes = form.querySelectorAll('input[name="subject[]"]');
        let marcado = false;

        checkBoxes.forEach(function(checkbox) {
          if (checkbox.checked) {
            marcado = true;
          }
        });

        if (!marcado) {
          e.preventDefault();
          e.stopImmediatePropagation();

          const tri = document.getElementById("triang_1");
          const ret = document.getElementById("retang_1");
          tri.style.display = "block";
          ret.style.display = "block";

        }
      }, true);
      document.addEventListener("click", function() {
        const tri = document.getElementById("triang_1");
        const ret = document.getElementById("retang_1");
        tri.style.display = "none";
        ret.style.display = "none";
      });
    });
  </script>

</body>

</html>
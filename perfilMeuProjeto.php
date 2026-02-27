<?php
session_start();
if (isset($_GET['id'])) {
  $idProjeto = $_GET['id'];
}
require_once("assets/php/conexao.php");
$conn = conexao();
$sql_projeto = "SELECT idUsuario, nomeProj, descricaoProj, palavrasChavesProj, linhaPesquisaProj, colaboradores, dtEncontros, urlLogo FROM projeto where idProjeto = ?";
$stmt_projeto = $conn->prepare($sql_projeto);
$stmt_projeto->bind_param("s", $idProjeto);
$stmt_projeto->execute();
$result_projeto = $stmt_projeto->get_result();
$projeto = $result_projeto->fetch_assoc();

$idUsuario = $projeto['idUsuario'];
$sql_usuario = "SELECT nome FROM usuario where idUsuario = ?";
$stmt_usuario = $conn->prepare($sql_usuario);
$stmt_usuario->bind_param("s", $idUsuario);
$stmt_usuario->execute();
$result_usuario = $stmt_usuario->get_result();
$usuario = $result_usuario->fetch_assoc();

$areas_de_estudo = [];
$sql_areas = "SELECT ae.nome FROM projeto p INNER JOIN projetoArea pa ON p.idProjeto = pa.idProjeto INNER JOIN areaEstudo ae ON pa.idAreaEstudo = ae.idAreaEstudo WHERE p.idProjeto = ?";
$stmt_areas = $conn->prepare($sql_areas);
if ($stmt_areas) {
  $stmt_areas->bind_param("s", $idProjeto);
  $stmt_areas->execute();
  $result_areas = $stmt_areas->get_result();
  while ($row = $result_areas->fetch_assoc()) {
    $areas_de_estudo[] = $row['nome'];
  }
  $stmt_areas->close();
}

$all_areas = [];
$sql_all_areas = "SELECT idAreaEstudo, nome FROM AreaEstudo ORDER BY nome";
$result_all_areas = $conn->query($sql_all_areas);
if ($result_all_areas) {
  while ($row = $result_all_areas->fetch_assoc()) {
    $all_areas[] = $row;
  }
}

// --- NEW: Query for project images ---
$imagens_projeto = [];
$sql_imagens = "SELECT i.urlImagem FROM Imagens i JOIN ImagensProjeto ip ON i.idImagem = ip.idImagem WHERE ip.idProjeto = ? ORDER BY i.idImagem ASC"; // Order as needed
$stmt_imagens = $conn->prepare($sql_imagens);
if ($stmt_imagens) {
  $stmt_imagens->bind_param("i", $idProjeto);
  $stmt_imagens->execute();
  $result_imagens = $stmt_imagens->get_result();
  while ($row = $result_imagens->fetch_assoc()) {
    $imagens_projeto[] = $row['urlImagem']; // Store just the URL
  }
  $stmt_imagens->close();
}
// --- END NEW ---



?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Perfil do projeto</title>
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
      padding: 0;
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
      width: 50%;
      padding: 0;
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
      overflow: hidden;
      /* Importante para que as bordas arredondadas do card cortem a imagem */
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

    .buttonsEditarProjeto {
      display: flex;
      padding: 0 3.5rem !important;
      gap: 1rem;
    }

    .buttonsEditarProjeto button {
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

    .buttonsEditarProjeto button:hover {
      background-color: #a9bfb9;
    }


    .profile .imgName {
      display: flex;
      align-items: center;
      gap: 0px;
    }

    .image-profile {
      position: relative;
      display: flex;
      justify-content: center;
      flex-direction: column;
      align-items: center;
    }

    .image-profile img {
      width: 150px;
      height: 150px;
      border-radius: 50%;
      object-fit: cover;
      border: 4px solid #000;
      display: flex;
      justify-content: center;
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

    /* --- ESTILOS CORRIGIDOS E UNIFICADOS PARA O CARROSSEL --- */
    .profile .carousel-item {
      height: 350px;
      /* <<-- AJUSTE A ALTURA DESEJADA AQUI */
      background-color: #00000000;
      /* Cor de fundo suave */
    }

    .profile .carousel-item img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      object-position: center;
    }

    /* --- FIM DOS ESTILOS DO CARROSSEL --- */

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


    .profile .carousel-control-prev,
    .profile .carousel-control-next {
      width: 45px;
      height: 45px;
      top: 50%;
      transform: translateY(-50%);
      opacity: 0.8;
      transition: opacity 0.2s ease-in-out;
    }

    .profile .carousel-control-prev {
      left: 15px;
    }

    .profile .carousel-control-next {
      right: 15px;
    }


    .profile .carousel-control-prev-icon {
      background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%230f846e'%3e%3cpath d='M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z'/%3e%3c/svg%3e");
    }

    .profile .carousel-control-next-icon {
      background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%230f846e'%3e%3cpath d='M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
    }

    /* ============================================== */
    /* === ESTILOS DOS POP-UPS (COPIADOS DO PERFIL) === */
    /* ============================================== */

    /* --- Estilo Geral do Pop-up --- */
    #telaEditarProjeto,
    #telaSubmeterImagens {
      background-color: #fff;
      position: fixed;
      /* Mudar de absolute para fixed */
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      width: 70%;
      /* Ajuste a largura conforme necessário */
      height: 80%;
      /* Ajuste a altura conforme necessário */
      z-index: 1000;
      border-radius: 8px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
      display: none;
      /* Mantém escondido inicialmente */
      overflow-y: auto;
      /* Permite rolar se o conteúdo for maior */
    }

    /* --- Container Interno --- */
    .submission-card-inner {
      text-align: center;
      padding: 20px 40px;
      /* Padding interno */
    }

    /* --- Título do Pop-up --- */
    .submission-card-inner .card-title {
      position: relative;
      padding-bottom: 0.75rem;
      margin-bottom: 2rem;
      font-weight: 600;
      /* Pode ajustar se quiser */
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
      /* Cor da linha abaixo do título */
    }

    /* --- Texto Introdutório --- */
    .submission-card-inner .intro-text {
      padding-bottom: 30px;
      /* O padding horizontal gigante foi removido, ajuste se precisar */
      font-size: 1rem;
      color: #333;
    }

    .submission-card-inner .intro-text p {
      margin-bottom: 0.5rem;
    }

    .submission-card-inner .intro-text p strong {
      color: #008069;
    }

    /* --- Barra Verde Abaixo da Introdução --- */
    .submission-card-inner .form-header {
      height: 25px;
      background-color: #008069;
      margin: 0 -40px 40px -40px;
      /* Para esticar até as bordas do padding */
    }

    /* --- Formulário --- */
    .submission-form {
      /* O padding horizontal gigante foi removido, ajuste se precisar */
      text-align: left;
      /* Alinha os labels e inputs à esquerda */
    }

    /* --- Grupos de Formulário (Label + Input) --- */
    .submission-form .form-group {
      margin-bottom: 25px;
    }

    /* --- Labels --- */
    .submission-form label:not(.form-check-label) {
      font-weight: 600;
      color: #212529;
      font-size: 1.1rem;
      text-align: right;
      /* Alinha labels à direita (como no exemplo) */
      width: 100%;
      /* Garante que o alinhamento funcione */
    }

    /* --- Inputs, Selects, Textareas --- */
    /* (Combina os seletores para aplicar a todos os pop-ups) */
    #telaEditarProjeto .form-control,
    #telaSubmeterImagens .form-control,
    #telaEditarProjeto .form-select,
    #telaSubmeterImagens .form-select {
      border: 2px solid #000;
      border-radius: 12px;
      padding: 10px 15px;
      height: auto;
      font-size: 1rem;
    }

    #telaEditarProjeto .form-control:focus,
    #telaSubmeterImagens .form-control:focus,
    #telaEditarProjeto .form-select:focus,
    #telaSubmeterImagens .form-select:focus {
      border-color: #008069;
      /* Cor da borda ao focar */
      box-shadow: none;
      /* Remove o brilho padrão */
    }

    /* --- Input de Arquivo --- */
    #telaEditarProjeto input[type="file"],
    #telaSubmeterImagens input[type="file"] {
      padding: 5px;
      /* Padding menor para input de arquivo */
    }

    #telaEditarProjeto input[type="file"]::file-selector-button,
    #telaSubmeterImagens input[type="file"]::file-selector-button {
      background-color: #e9ecef;
      border: none;
      border-radius: 8px;
      padding: 10px 15px;
      color: #495057;
      cursor: pointer;
      transition: background-color .15s ease-in-out;
      margin-right: 15px;
    }

    #telaEditarProjeto input[type="file"]::file-selector-button:hover,
    #telaSubmeterImagens input[type="file"]::file-selector-button:hover {
      background-color: #dde1e5;
    }

    /* --- Dropdown de Checkbox --- */
    .dropdown-menu {
      max-height: 250px;
      overflow-y: auto;
    }

    /* Estilo para que o clique no item não feche o dropdown */
    .dropdown-item .form-check {
      cursor: pointer;
    }

    .dropdown-item .form-check label {
      text-align: left;
      /* Label do checkbox alinhado à esquerda */
    }


    /* --- Botões Salvar/Cancelar --- */
    .form-buttons {
      margin-top: 40px;
      display: flex;
      justify-content: center;
      gap: 20px;
    }

    .btn-salvar {
      background-color: #008069;
      /* Verde */
      color: #ffffff;
      padding: 10px 25px;
      border-radius: 20px;
      border: none;
      font-weight: 600;
      font-size: 16px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .btn-salvar:hover {
      background-color: #006b5a;
      /* Verde mais escuro no hover */
    }

    .btn-cancelar {
      background-color: #BCCEC9;
      /* Cinza claro */
      color: #3f3f3f;
      padding: 10px 25px;
      border-radius: 20px;
      border: none;
      font-weight: 600;
      font-size: 16px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .btn-cancelar:hover {
      background-color: #a9bfb9;
      /* Cinza um pouco mais escuro no hover */
    }

    /* --- Botão de Fechar (X) --- */
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
      /* Garante que fique acima do conteúdo */
    }

    /* --- Overlay (Fundo Escuro) --- */
    .overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.6);
      /* Fundo preto semitransparente */
      z-index: 999;
      /* Abaixo do pop-up, acima do resto */
      display: none;
      /* Começa escondido */
    }

    /* --- Estilos para o Pop-up de Edição de Foto (Aplicado a #telaSubmeterImagens também) --- */
    .popup-editar-foto {
      display: none;
      position: fixed;
      z-index: 1001;
      /* Acima do overlay */
      left: 50%;
      top: 50%;
      height: 40vh!important;
      transform: translate(-50%, -50%);
      background-color: #ffffff;
      border-radius: 8px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
      width: 380px;
      /* Largura padrão - Pode ser sobrescrita inline se necessário */
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

    /* Reutiliza o estilo .fechar-popup que já deve existir */
    .popup-editar-foto .fechar-popup {
      position: absolute;
      top: -10px;
      /* Ajusta posição se necessário */
      right: -10px;
      /* Ajusta posição se necessário */
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

    /* Container de Preview (Removido do HTML de #telaSubmeterImagens, mas mantido aqui se usar em outro lugar) */
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
      /* ID específico do preview */
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    /* Estilo do Input de Arquivo Customizado */
    .popup-editar-foto .file-input-wrapper {
      display: flex;
      border: 1px solid #ccc;
      border-radius: 5px;
      width: 100%;
      overflow: hidden;
      margin-bottom: 25px;
      /* Ajustado inline no HTML para #telaSubmeterImagens */
      font-size: 14px;
      align-items: center;
      /* Alinha verticalmente */
    }

    .popup-editar-foto .file-input-label {
      background-color: #f7f7f7;
      padding: 8px 12px;
      cursor: pointer;
      border-right: 1px solid #ccc;
      color: #555;
      white-space: nowrap;
      /* Impede quebra de linha */
    }

    .popup-editar-foto .file-input-label:hover {
      background-color: #eee;
    }

    /* Span que mostra o nome do arquivo */
    .popup-editar-foto #fileNamePopup,
    /* ID específico do nome do arquivo (foto perfil) */
    #fileNamesCarousel

    /* ID para o span de nomes do carrossel */
      {
      padding: 8px 12px;
      color: #777;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      flex-grow: 1;
      /* Ocupa espaço restante */
      text-align: left;
      /* Alinha o nome à esquerda */
    }

    /* Footer com botões */
    .popup-editar-foto .popup-footer {
      display: flex;
      gap: 15px;
      justify-content: center;
      margin-top: 20px;
      /* Adiciona espaço acima dos botões */
      width: 100%;
    }

    /* Estilos .btn-salvar e .btn-cancelar são globais */

    /* Ajuste Responsivo Básico (igual ao do perfil) */
    @media (max-width: 767px) {

      #telaEditarProjeto,
      #telaSubmeterImagens {
        width: 90%;
        /* Pop-ups mais largos em telas pequenas */
        height: 90%;
        /* Pop-ups mais altos em telas pequenas */
      }

      .submission-form {
        padding: 0 1rem;
        /* Menos padding lateral no form em telas pequenas */
      }

      .submission-form label:not(.form-check-label) {
        text-align: left;
        /* Alinha labels à esquerda em telas pequenas */
        margin-bottom: 8px;
      }

      .submission-card-inner .intro-text {
        padding: 0 1rem;
        /* Ajusta padding do texto intro */
      }
    }



    @media (max-width: 767px) {
      .perfilContainer {
        padding: 1rem;
        flex-direction: column;
        height: auto;
      }

      .profile,
      .trabalhos {
        width: 100%;
      }

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

<body class="starter-page-page">
  <?php include('assets/php/navbar.php'); ?>
  <main class="main">
    <div class="page-title dark-background">
      <div class="container position-relative">
        <h1>Projeto</h1>
      </div>
    </div>
    <div class="perfilContainer">
      <section id="profile" class="profile section">
        <div class="container" data-aos="fade-up" data-aos-delay="100">
          <div class="request-info" data-aos="fade-up">

            <div class="card">

              <div class="card-body text-center">
                <div class="card-divisions d-flex p-5">
                  <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">

                      <?php if (!empty($imagens_projeto)): ?>
                        <?php foreach ($imagens_projeto as $index => $img_url): ?>
                          <div class="carousel-item <?php echo ($index === 0) ? 'active' : ''; ?>">
                            <img class="d-block w-100" src="<?php echo htmlspecialchars($img_url); ?>"
                              alt="Imagem <?php echo $index + 1; ?> do Projeto">
                          </div>
                        <?php endforeach; ?>
                      <?php else: ?>
                        <div class="carousel-item active">
                          <img class="d-block w-100" src="assets/img/placeholder1.jpg" alt="Aguardando imagens 1">
                        </div>
                        <div class="carousel-item">
                          <img class="d-block w-100" src="assets/img/placeholder2.jpg" alt="Aguardando imagens 2">
                        </div>
                        <div class="carousel-item">
                          <img class="d-block w-100" src="assets/img/placeholder3.jpg" alt="Aguardando imagens 3">
                        </div>
                      <?php endif; ?>

                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls"
                      data-bs-slide="prev">
                      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                      <span class="visually-hidden">Anterior</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls"
                      data-bs-slide="next">
                      <span class="carousel-control-next-icon" aria-hidden="true"></span>
                      <span class="visually-hidden">Próximo</span>
                    </button>
                  </div>
                  <div class="buttonsEditarProjeto">
                    <button class="btn" onclick="abrirPopup('telaSubmeterImagens')">
                      Submeter Imagens
                    </button>

                    <button class="btn" onclick="abrirPopup('telaEditarProjeto')">
                      Editar Projeto
                    </button>
                  </div>
                  <div class="imgName d-flex flex-column align-items-center">

                    <div class="image-profile" style="z-index: 2;">
                      <img src="<?php echo htmlspecialchars($projeto['urlLogo']) ?>" alt="Foto de Perfil">
                      <div class="matriculaItems">
                        <p class="textProfile" id="nomeText"><?php echo htmlspecialchars($projeto['nomeProj']) ?></p>
                      </div>
                    </div>

                  </div>
                  <div class="dadosObrigatorios text-start d-flex row align-items-center px-4">
                    <div class="sobreProjItems px-4">
                      <p class="titleProfile"> Descrição do projeto:</p>
                      <p class="textProfile" style="text-align: justify;" id="sobreProjText">
                        <?php echo htmlspecialchars($projeto['descricaoProj']) ?>.
                      </p>
                    </div>
                    <div class="areaItems px-4">
                      <p class="titleProfile">Área(s)</p>
                      <p class="textProfile"><?php echo htmlspecialchars(implode(', ', $areas_de_estudo)); ?>.</p>
                    </div>
                    <div class="coordenadorItems px-4">
                      <p class="titleProfile">Coordenador</p>
                      <p class="textProfile">
                        <?php echo htmlspecialchars($usuario['nome']) ?>
                      </p>
                    </div>
                    <div class="colaboradoresItems px-4">
                      <p class="titleProfile">Colaboradores</p>
                      <p class="textProfile" id="colaboradoresText">
                        <?php echo htmlspecialchars($projeto['colaboradores']) ?>
                      </p>
                    </div>
                    <div class="redesItems px-4">
                      <p class="titleProfile">Redes sociais</p> <a href="#" target="_blank" class="textProfile"
                        id="redesText">
                        Link para rede social
                      </a>
                    </div>
                    <div class="encontrosItems px-4">
                      <p class="titleProfile">Encontros</p>
                      <p class="textProfile" style="text-align: justify;" id="encontrosText">
                        <?php echo htmlspecialchars($projeto['dtEncontros']) ?>
                      </p>
                    </div>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
      </section>

      <section class="trabalhos" data-aos="fade-up" data-aos-delay="100">
        <h2 class="mb-4">Publicações</h2>
        <div class="trabalhosContainers">

          <a href="#" class="trabalho-clickable text-decoration-none" data-bs-toggle="modal"
            data-bs-target="#trabalhoModal">
            <div class="trabalhosContainer">
              <div>
                <h3>Título da Publicação Exemplo</h3>
                <p class="author">Nome do Autor Exemplo</p>
              </div>
              <div class="event-meta">
                <p><i class="bi bi-folder2-open"></i> Área de Estudo</p>
                <p><i class="bi bi-person-workspace"></i> Nome Orientador</p>
                <p><i class="bi bi-calendar"></i> 13/10/2025</p>
              </div>
            </div>
          </a>

        </div>
      </section>
    </div>

    <div class="modal fade" id="trabalhoModal" tabindex="-1" aria-labelledby="trabalhoModalLabel" aria-hidden="true">
    </div>
  </main>
  <div id="telaEditarProjeto" style="display: none;">
    <button class="fechar-popup" onclick="fecharPopups()">×</button>
    <div class="submission-card-inner">
      <h3 class="card-title">Editar Projeto</h3>
      <div class="intro-text">
        <p><strong>Caro(a) docente,</strong></p>
        <p>Utilize o formulário abaixo para editar as informações do seu projeto.</p>
      </div>
      <div class="form-header"></div>

      <form method="post" class="submission-form" action="assets/php/editarProjeto.php" enctype="multipart/form-data"
        id="formEditProj">

        <input type="hidden" name="idProjeto" value="<?php echo htmlspecialchars($idProjeto); ?>">

        <div class="form-group row align-items-center">
          <div class="col-md-3"> <label for="logo-projeto-edit">Trocar Logo (Opcional):</label> </div>
          <div class="col-md-9"> <input type="file" class="form-control" id="logo-projeto-edit"
              title="Escolha um arquivo" name="logo-projeto" accept="image/png, image/jpeg, image/webp">
          </div>
        </div>

        <div class="form-group row align-items-center">
          <div class="col-md-3"> <label for="nomeProjeto-edit">Nome:</label> </div>
          <div class="col-md-9"> <input type="text" class="form-control" id="nomeProjeto-edit" name="nomeProjeto"
              required value="<?php echo htmlspecialchars($projeto['nomeProj']); ?>">
          </div>
        </div>

        <div class="form-group row">
          <div class="col-md-3"> <label for="descricao-projeto-edit">Descrição:</label> </div>
          <div class="col-md-9"> <textarea class="form-control" id="descricao-projeto-edit" name="descricao-projeto"
              rows="5" required><?php echo htmlspecialchars($projeto['descricaoProj']); ?></textarea>
          </div>
        </div>

        <div class="form-group row align-items-center">
          <div class="col-md-3"> <label for="palavras_chaves-projeto-edit">Palavras chave:</label> </div>
          <div class="col-md-9"> <input type="text" class="form-control" id="palavras_chaves-projeto-edit"
              name="palavras_chaves-projeto" required
              value="<?php echo htmlspecialchars($projeto['palavrasChavesProj']); ?>">
          </div>
        </div>

        <div class="form-group row align-items-center">
          <div class="col-md-3"> <label for="dropdownMenuButton_edit">Áreas de Estudo:</label> </div>
          <div class="col-md-9">
            <div class="dropdown position-relative">
              <button class="form-select text-start" type="button" id="dropdownMenuButton_edit"
                data-bs-toggle="dropdown" aria-expanded="false">
                <span id="dropdownMenuText_edit">Selecione uma ou mais áreas...</span>
              </button>
              <ul class="dropdown-menu w-100" id="areas_cb_edit" aria-labelledby="dropdownMenuButton_edit"
                data-bs-auto-close="outside">
                <?php foreach ($todas_as_areas_bd as $area): ?>
                  <?php
                  $checked = in_array($area['nome'], $areas_de_estudo_selecionadas) ? 'checked' : '';
                  ?>
                  <li>
                    <div class="dropdown-item">
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="areas[]"
                          value="<?php echo $area['idAreaEstudo']; ?>" id="checkEdit<?php echo $area['idAreaEstudo']; ?>"
                          <?php echo $checked; ?>>
                        <label class="form-check-label w-100" for="checkEdit<?php echo $area['idAreaEstudo']; ?>">
                          <?php echo htmlspecialchars($area['nome']); ?> </label>
                      </div>
                    </div>
                  </li>
                <?php endforeach; ?>

              </ul>
            </div>
          </div>
        </div>

        <div class="form-group row align-items-center">
          <div class="col-md-3"> <label for="linhaPesquisa-projeto-edit">Linha de Pesquisa:</label> </div>
          <div class="col-md-9"> <input type="text" class="form-control" id="linhaPesquisa-projeto-edit"
              name="linhaPesquisa-projeto" required
              value="<?php echo htmlspecialchars($projeto['linhaPesquisaProj']); ?>">
          </div>
        </div>

        <div class="form-group row align-items-center">
          <div class="col-md-3"> <label for="colaboradores-projeto-edit">Colaborador(es):</label> </div>
          <div class="col-md-9"> <input type="text" class="form-control" id="colaboradores-projeto-edit"
              name="colaboradores-projeto" required value="<?php echo htmlspecialchars($projeto['colaboradores']); ?>">
          </div>
        </div>

        <div class="form-group row">
          <div class="col-md-3"> <label for="datas-projeto-edit">Datas dos encontros (Opcional):</label>
          </div>
          <div class="col-md-9"> <textarea class="form-control" id="datas-projeto-edit" name="datasProjeto"
              rows="2"><?php echo htmlspecialchars($projeto['dtEncontros']); ?></textarea>
          </div>
        </div>

        <div class="form-group row align-items-center">
          <div class="col-md-3"> <label for="urlInstagram-edit">Instagram URL:</label> </div>
          <div class="col-md-9"> <input type="url" class="form-control" id="urlInstagram-edit" name="urlInstagram"
              value="<?php echo htmlspecialchars($projeto['urlInstagram']); ?>">
          </div>
        </div>
        <div class="form-group row align-items-center">
          <div class="col-md-3"> <label for="urlYoutube-edit">YouTube URL:</label> </div>
          <div class="col-md-9"> <input type="url" class="form-control" id="urlYoutube-edit" name="urlYoutube"
              value="<?php echo htmlspecialchars($projeto['urlYoutube']); ?>">
          </div>
        </div>
        <div class="form-buttons">
          <button type="submit" class="btn-salvar">Salvar Alterações</button>
          <button type="button" class="btn-cancelar" onclick="fecharPopups()">Cancelar</button>
        </div>
      </form>
    </div>
  </div>
  <div class="popup-editar-foto" id="telaSubmeterImagens" style="display: none; width: 450px;">
    <form action="assets/php/uploadImagens.php" method="POST" enctype="multipart/form-data" class="popup-content"
      id="formUploadImagens">
      <input type="hidden" name="idProjeto" value="<?php echo htmlspecialchars($idProjeto); ?>">
      <div class="popup-header">
        <h3>Gerenciar Imagens do Projeto</h3>
        <button type="button" class="fechar-popup" onclick="fecharPopups()">×</button>
      </div>
      <div class="popup-body">
        <p style="text-align: center; color: #555; margin-bottom: 20px;">Selecione uma ou mais imagens para adicionar ao
          carrossel.</p>

        <div class="file-input-wrapper" style="margin-bottom: 10px;"> <label for="inputImagensCarrossel"
            class="file-input-label">Escolher arquivos</label>
          <input type="file" id="inputImagensCarrossel" name="imagensProjeto[]"
            accept="image/png, image/jpeg, image/webp" style="display: none;" multiple required
            onchange="displayFileNames(this.files)">
          <span id="fileNamesCarousel"
            style="padding: 8px 12px; color: #777; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; flex-grow: 1;">Nenhum
            arquivo escolhido</span>
        </div>
        <small class="form-text text-muted mb-3 d-block" style="text-align:center;">Você pode selecionar múltiplos
          arquivos.</small>


      </div>
      <div class="popup-footer">
        <button type="submit" class="btn-salvar">Enviar Imagens</button>
        <button type="button" class="btn-cancelar" onclick="fecharPopups()">Cancelar</button>
      </div>
    </form>
  </div>
  <div class="overlay" id="overlay"></div>

  <footer id="footer" class="footer position-relative dark-background text-center text-white py-5">

    <div class="container footer-top">
      <div class="row gy-4 justify-content-center">

        <div class="col-lg-4 col-md-6">
          <div class="footer-logo mb-3">
            <span class="sitename fs-4 fw-bold">Sinara</span>
          </div>
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
          <div class="social-links d-flex justify-content-center gap-3 mt-3">
            <a href="https://smp.ifsp.edu.br/" aria-label="Site"> <img src="assets/img/logo if.png" alt="Logo do site"
                style="width: 20px; height: 24px; filter: brightness(0) invert(1);"></a>
            <a href="https://www.facebook.com/smp.ifsp/?locale=pt_BR" aria-label="Facebook"><i
                class="bi bi-facebook text-white fs-5"></i></a>
            <a href="https://www.instagram.com/ifspsmp/#" aria-label="Instagram"><i
                class="bi bi-instagram text-white fs-5"></i></a>
            <a href="https://www.youtube.com/channel/UCInQs4AhmS6MC3qJzihkiGw" aria-label="YouTube"><i
                class="bi bi-youtube text-white fs-5"></i></a>
          </div>
        </div>

      </div>
    </div>

    <div class="container copyright text-center mt-4">
      <p>© <span>Copyright</span> <strong class="px-1 sitename">MyWebsite</strong> <span>All Rights Reserved</span></p>
      <div class="credits">
        <!-- All the links in the footer should remain intact. -->
        <!-- You can delete the links only if you've purchased the pro version. -->
        <!-- Licensing information: https://bootstrapmade.com/license/ -->
        <!-- Purchase the pro version with working PHP/AJAX contact form: [buy-url] -->
        Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
      </div>
    </div>

  </footer>


  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>
  <div id="preloader"></div>

  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>

  <script src="assets/js/main.js"></script>

  <script src="assets/js/confirmarLogout.js"></script>
  <script src="assets/js/checkboxAreas.js"></script>
  <script src="assets/js/modalTrabalho.js"></script>
  <script>
    // Pega o overlay
    const overlay = document.getElementById('overlay');

    /**
     * Fecha TODOS os pop-ups e o overlay.
     */
    function fecharPopups() {
      // Esconde os pop-ups
      document.getElementById('telaEditarProjeto').style.display = 'none';
      document.getElementById('telaSubmeterImagens').style.display = 'none';

      // Adicione aqui os IDs de OUTROS pop-ups que você tenha (ex: cadastro)
      // document.getElementById('telaCadastroProjeto').style.display = 'none';

      // Esconde o overlay
      if (overlay) overlay.style.display = 'none';
    }

    /**
    * Abre um pop-up específico e o overlay.
    * @param {string} popupId O ID do elemento pop-up (ex: 'telaEditarProjeto')
          */
    function abrirPopup(popupId) {
      // Fecha todos os outros primeiro, para garantir
      fecharPopups();

      const popup = document.getElementById(popupId);
      if (popup) {
        popup.style.display = 'block'; // Mostra o pop-up
        if (overlay) overlay.style.display = 'block'; // Mostra o overlay
      }
    }

    /**
     * Controla o texto do dropdown de checkboxes do pop-up de EDIÇÃO.
     */
    document.addEventListener('DOMContentLoaded', function () {
      // Pega os elementos do dropdown de EDIÇÃO
      const dropdownMenu = document.getElementById('areas_cb_edit');
      const dropdownText = document.getElementById('dropdownMenuText_edit');

      if (dropdownMenu && dropdownText) {
        // Função para atualizar o texto do botão
        function updateDropdownText() {
          const selectedCheckboxes = dropdownMenu.querySelectorAll('input[type="checkbox"]:checked');
          let selectedNames = [];

          selectedCheckboxes.forEach(checkbox => {
            // Pega o texto do <label> associado ao checkbox
            const label = checkbox.nextElementSibling; // Assume que o label é o próximo elemento
            if (label) {
              selectedNames.push(label.textContent.trim());
            }
          });

          if (selectedNames.length === 0) {
            dropdownText.textContent = 'Selecione uma ou mais áreas...';
          } else if (selectedNames.length > 2) {
            dropdownText.textContent = selectedNames.slice(0, 2).join(', ') + ` e +${selectedNames.length - 2}`;
          } else {
            dropdownText.textContent = selectedNames.join(', ');
          }
        }

        // Adiciona o 'listener' para cada checkbox dentro do dropdown
        const checkboxes = dropdownMenu.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(checkbox => {
          checkbox.addEventListener('change', updateDropdownText);
        });

        // Chama a função uma vez no início para definir o texto inicial
        // baseado nos checkboxes que já vêm marcados (checked)
        updateDropdownText();
      }
    });

    /**
 * Exibe os nomes dos arquivos selecionados no input de múltiplas imagens.
 * @param {FileList} files A lista de arquivos do input.
 */
    function displayFileNames(files) {
      const fileNameSpan = document.getElementById('fileNamesCarousel');
      if (!fileNameSpan) return;

      if (!files || files.length === 0) {
        fileNameSpan.textContent = 'Nenhum arquivo escolhido';
      } else if (files.length === 1) {
        fileNameSpan.textContent = files[0].name;
      } else {
        fileNameSpan.textContent = `${files.length} arquivos selecionados`;
      }
    }

    // Também garanta que fecharPopups() limpe o span de nomes:
    const originalFecharPopups = window.fecharPopups; // Guarda a função original
    window.fecharPopups = function () {
      originalFecharPopups(); // Chama a função original para fechar os popups

      // Limpa o input e o span de nomes do pop-up de imagens do carrossel
      const inputCarousel = document.getElementById('inputImagensCarrossel');
      const fileNameSpanCarousel = document.getElementById('fileNamesCarousel');
      if (inputCarousel) inputCarousel.value = ''; // Limpa a seleção de arquivos
      if (fileNameSpanCarousel) fileNameSpanCarousel.textContent = 'Nenhum arquivo escolhido';
    };

    // Se você já tem um event listener no overlay, certifique-se que ele chama a nova fecharPopups
    const overlayElement = document.getElementById('overlay');
    if (overlayElement) {
      // Remove listener antigo se existir para evitar duplicação
      // Idealmente, você só adicionaria o listener uma vez
      overlayElement.removeEventListener('click', originalFecharPopups); // Remove o antigo se ele foi adicionado diretamente
      overlayElement.addEventListener('click', window.fecharPopups); // Adiciona o novo
    }
  </script>

</body>

</html>
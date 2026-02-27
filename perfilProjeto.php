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
                      <div class="carousel-item active">
                        <img class="d-block w-100" src="assets/img/teste 1.jpg" alt="Primeira foto">
                      </div>
                      <div class="carousel-item">
                        <img class="d-block w-100" src="assets/img/teste 2.jpg" alt="Segunda foto">
                      </div>
                      <div class="carousel-item">
                        <img class="d-block w-100" src="assets/img/teste 3.png" alt="Terceira foto">
                      </div>
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
                  <div class="imgName d-flex flex-column align-items-center">

                    <div class="image-profile" style="z-index: 2;">
                      <img src="<?php echo htmlspecialchars($projeto['urlLogo'])?>" alt="Foto de Perfil">
                      <div class="matriculaItems">
                        <p class="textProfile" id="nomeText"<p><?php echo htmlspecialchars($projeto['nomeProj'])?></p>
                      </div>
                    </div>

                  </div>
                  <div class="dadosObrigatorios text-start d-flex row align-items-center px-4">
                    <div class="sobreProjItems px-4">
                      <p class="titleProfile"> Descrição do projeto:</p>
                      <p class="textProfile" style="text-align: justify;" id="sobreProjText"><?php echo htmlspecialchars($projeto['descricaoProj'])?>.</p>
                    </div>
                    <div class="areaItems px-4">
                      <p class="titleProfile">Área(s)</p>
                      <p class="textProfile"><?php echo htmlspecialchars(implode(', ', $areas_de_estudo)); ?>.</p>
                    </div>
                    <div class="coordenadorItems px-4">
                      <p class="titleProfile">Coordenador</p>
                      <p class="textProfile">
                        <?php echo htmlspecialchars($usuario['nome'])?>
                      </p>
                    </div>
                    <div class="colaboradoresItems px-4">
                      <p class="titleProfile">Colaboradores</p>
                      <p class="textProfile" id="colaboradoresText">
                        <?php echo htmlspecialchars($projeto['colaboradores'])?>
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
                      <p class="textProfile" style="text-align: justify;" id="encontrosText"><?php echo htmlspecialchars($projeto['dtEncontros'])?>
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
    // Seus outros scripts de pop-up etc. podem continuar aqui
  </script>

</body>

</html>
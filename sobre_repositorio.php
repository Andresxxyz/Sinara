<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Sobre o Repositório</title>
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
    .sobreTitulo {
      color: #2c5252;
    }

    .imageSinara img {
      width: 300px;
    }

    .history .container {
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .history .row {
      flex: 1;
    }

    .history .imageSinara {
      flex-shrink: 0;
    }

    .history .imageSinara img {
      max-width: 500px;
      height: auto;
      display: block;
      margin: 0 auto;
    }

    .imageSinara #imgEnsinar{
      width: 100%;
    }


    @media (max-width: 991px) {
      .history .container {
        flex-direction: column;
        text-align: center;
      }

      .history .imageSinara {
        margin: 30px 0 0 0;
      }
    }

    section.valores {
      padding: 60px 0;
    }

    section.valores h2, section.leadership h2{
      font-size: 36px;
      font-weight: 700;
      color: #2c5252;
      margin-bottom: 40px;
    }

    .value-card {
      padding: 30px;
      background-color: #ffffff;
      border-radius: 10px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
      text-align: center;
      border: none;
    }

    .value-card .card-icon {
      margin: 0 auto 20px auto;
      width: 64px;
      height: 64px;
      border: 2px solid #0f846e;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .value-card .card-icon i {
      color: #0f846e;
      font-size: 28px;
    }

    .value-card h3 {
      font-weight: 700;
      margin: 0 0 15px 0;
      font-size: 20px;
      color: #2c5252;
    }

    .value-card p {
      color: #555;
      font-size: 15px;
      line-height: 1.6;
    }

    .repo-title {
      position: relative;
      padding-bottom: 0.6rem;
      width: fit-content;
    }

    .repo-title:after {
      content: "";
      position: absolute;
      left: 50%;
      bottom: 0;
      width: 100%;
      height: 3px;
      transform: translate(-50%);
      background-color: var(--accent-color);
    }

    .leader-card{
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .leader-info{
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .leadership{
      padding: 60px 320px;
    }


  </style>
</head>

<body class="about-page">

  <?php include('assets/php/navbar.php'); ?>

  <main class="main">

    <div class="page-title" style="background-image: url(assets/img/campus.jpg);">
      <div class="container position-relative">
        <h1 style="color: #ffffff;">Sobre o repositório</h1>
        <nav class="breadcrumbs">
        </nav>
      </div>
    </div>
    <section id="history" class="history section">

      <div class="container d-flex justify-content-around" data-aos="fade-up" data-aos-delay="100">

        <div class="w-50">
          <div class="col-lg-6 w-100">
            <div class="about-content w-100" data-aos="fade-up" data-aos-delay="200">
              <h3 class="sobreSubtitulo">Sobre nós</h3>
              <h2 class="repo-title">O Repositório</h2>
              <div>
                <p><span style="font-weight: bold;">Sinara</span> é a sigla do Sistema de Inovação e Repositório
                  Acadêmico do IFSP - Campus São Miguel Paulista. Além disso, possui em seu nome a palavra sina, a qual
                  designa um destino irremediável, uma missão que deve ser cumprida, como a produção e divulgação
                  científica é para o Instituto Federal.
                  À partir da precariedade da divulgação acadêmica na instituição, alunos da turma de 2022 - concluintes
                  no ano de 2025 - do Curso Integrado ao Ensino Médio de Informática para Internet idealizaram, em seu
                  Projeto Integrador, um repositório de pesquisas e projetos dos discentes do IFSP-SMP.</p>
              </div>
            </div>
          </div>
        </div>
        <div class="imageSinara">
          <img src="assets/img/telas sinara.png" alt="">
        </div>
      </div>

    </section>
    <section class="about valores">
      <div class="container" data-aos="fade-up">

        <h2 class="text-center">Valores</h2>

        <div class="row g-4">

          <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
            <div class="value-card h-100">
              <div class="card-icon">
                <i class="bi bi-people"></i>
              </div>
              <h3>Democratização</h3>
              <p>Garantir o pleno acesso à ciência produzida na instituição</p>
            </div>
          </div>

          <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
            <div class="value-card h-100">
              <div class="card-icon">
                <i class="bi bi-lightbulb"></i>
              </div>
              <h3>Inovação</h3>
              <p>Promover a importância do pensamento científico</p>
            </div>
          </div>

          <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
            <div class="value-card h-100">
              <div class="card-icon">
                <i class="bi bi-journal-text"></i>
              </div>
              <h3>Educação</h3>
              <p>Fortalecer a educação e incentivar a produção acadêmica</p>
            </div>
          </div>

          <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
            <div class="value-card h-100">
              <div class="card-icon">
                <i class="bi bi-search"></i>
              </div>
              <h3>Conexão</h3>
              <p>Romper os obstáculos entre o meio científico e a comunidade</p>
            </div>
          </div>

        </div>

      </div>
    </section>

    <section id="history" class="history section">

      <div class="container d-flex justify-content-around" data-aos="fade-up" data-aos-delay="100">

        <div class="w-50">
          <div class="col-lg-6 w-100">
            <div class="about-content w-100" data-aos="fade-up" data-aos-delay="200">
              <h3 class="sobreSubtitulo">A iniciativa</h3>
              <h2 class="repo-title">O Surgimento</h2>
              <div>
                <p>Para que as pesquisas e o incentivo à ciência
                  perdurem, o <span style="font-weight: bold;">Repositório Sinara</span> surge como uma rede de apoio à divulgação desses projetos. Assim, o
                  projeto espera gerar o devido reconhecimento aos pesquisadores e docentes envolvidos, e, também,
                  fortalecer as ligações entre instituição de ensino e comunidade, tal qual seu acesso à informação e à
                  ciência. Por meio disso, pretende-se, ainda, aumentar a visibilidade para demais ações exercidas no
                  Campus, como as atividades de extensão, que são igualmente contribuintes para a promoção da educação
                  na Zona Leste de São Paulo.</p>
              </div>
            </div>
          </div>
        </div>
        <div class="imageSinara">
          <img id="imgEnsinar" src="assets/img/telas ensinar.png" alt="">
        </div>
      </div>

    </section>

<section id="leadership" class="leadership section ">

<div class="container" data-aos="fade-up" data-aos-delay="100">
    <h2 class="repo-title">Desenvolvedores</h2>
        <div class="leadership-team">
          <div class="row justify-content-center gy-4"  data-aos="fade-up" data-aos-delay="200">

            <div class="col-lg-4 col-md-6 px-3 aos-init aos-animate" data-aos="fade-up" data-aos-delay="100">
              <div class="leader-card" style="height: 100px;">
                <div class="leader-info">
                  <h4>Estella Duarte Souza</h4>
                </div>
              </div>
            </div>

            <div class="col-lg-4 col-md-6 px-3 aos-init aos-animate" data-aos="fade-up" data-aos-delay="100">
              <div class="leader-card" style="height: 100px;">
                <div class="leader-info">
                  <h4>Fabianne Jesus Silva</h4>
                </div>
              </div>
            </div>

            <div class="col-lg-4 col-md-6 px-3 aos-init aos-animate" data-aos="fade-up" data-aos-delay="100">
              <div class="leader-card" style="height: 100px;">
                <div class="leader-info">
                  <h4>Helena Alves Lima</h4>
                </div>
              </div>
            </div>

            <div class="col-lg-4 col-md-6 px-3 aos-init aos-animate" data-aos="fade-up" data-aos-delay="100">
              <div class="leader-card" style="height: 100px;">
                <div class="leader-info">
                  <h4>Hellem Cavalcante Santos</h4>
                </div>
              </div>
            </div>

            <div class="col-lg-4 col-md-6 px-3 aos-init aos-animate" data-aos="fade-up" data-aos-delay="100">
              <div class="leader-card" style="height: 100px;">
                <div class="leader-info">
                  <h4>Leticia Borges Petronilo</h4>
                </div>
              </div>
            </div>

            <div class="col-lg-4 col-md-6 px-3 aos-init aos-animate" data-aos="fade-up" data-aos-delay="100">
              <div class="leader-card" style="height: 100px;">
                <div class="leader-info">
                  <h4>Rafael Andres da Silva</h4>
                </div>
              </div>
            </div>

          </div>
        </div>

      </div>

</section>
  </main>

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
          <a href="https://www.facebook.com/smp.ifsp/?locale=pt_BR" aria-label="Facebook"><i class="bi bi-facebook text-white fs-5"></i></a>
          <a href="https://www.instagram.com/ifspsmp/#" aria-label="Instagram"><i class="bi bi-instagram text-white fs-5"></i></a>
          <a href="https://www.youtube.com/channel/UCInQs4AhmS6MC3qJzihkiGw" aria-label="YouTube"><i class="bi bi-youtube text-white fs-5"></i></a>
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
  <script src="assets/js/confirmarLogout.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>

  <script src="assets/js/main.js"></script>

</body>

</html>
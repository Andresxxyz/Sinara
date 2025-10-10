<?php
require_once('assets/php/conexao.php');
$conn = conexao();


$sql = "
    SELECT 
        t.idTrabalho,
        t.idUsuario,
        t.titulo,
        t.nomePesquisador,
        t.dtPubli,
        t.anoTrab,
        t.palavrasChaves,
        t.resumo,
        t.abstract,
        t.arquivoTrabalho,
        GROUP_CONCAT(lp.nome SEPARATOR ', ') as areas_de_estudo 
    FROM 
        Trabalho t
    LEFT JOIN 
        TrabalhoArea ta ON t.idTrabalho = ta.idTrabalho
    LEFT JOIN 
        LinhaPesquisa lp ON ta.idLinhaPesquisa = lp.idLinhaPesquisa
    GROUP BY
        t.idTrabalho
    ORDER BY
        t.dtPubli DESC;
";


$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Trabalho</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="assets/img/iconTab.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
    rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: NiceSchool
  * Template URL: https://bootstrapmade.com/nice-school-bootstrap-education-template/
  * Updated: May 10 2025 with Bootstrap v5.3.6
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->

  <style>
    .event-item.hidden {
      display: none;
    }

    .event-item {
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      background: #fff;
      border-radius: 8px;
      padding: 0px;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
      min-height: 180px;
      cursor: pointer;
    }

    .event-content {
      display: flex;
      flex-direction: column;
      height: 100%;
    }

    .event-title {
      margin: 0;
      font-size: 18px;
      font-weight: bold;
    }

    .author {
      margin: 5px 0 15px 0;
      color: #009688;
    }

    .event-meta {
      margin-top: auto;
      display: flex;
      gap: 20px;
      font-size: 14px;
      color: #666;
    }


    .modal-content {
      background-color: #f0f5f4;
      border: none;
      border-radius: 15px;
      padding: 20px;
    }

    .modal-header {
      border-bottom: none;
      padding: 0;
    }

    .modal-header .btn-close {
      position: absolute;
      top: 25px;
      right: 25px;
    }

    #modal-title {
      font-weight: 700;
      color: #333;
    }

    .btn-visualizar-arquivo {
      background-color: #a7bfbc;
      color: #3f514f;
      font-weight: 500;
      border: none;
      border-radius: 20px;
      padding: 8px 25px;
      transition: background-color 0.3s;
    }

    .btn-visualizar-arquivo:hover {
      background-color: #93aca9;
      color: #3f514f;
    }

    .info-group .info-item {
      background-color: #cad6d5;
      padding: 12px 15px;
      margin-bottom: 8px;
      border-radius: 5px;
      font-size: 1rem;
    }

    .abstract-group {
      background-color: #cad6d5;
      padding: 15px;
      border-radius: 5px;
    }

    .abstract-group p {
      margin-top: 5px;
      text-align: justify;
    }

    .modal-header .btn-close {
      z-index: 10;

      padding: 0.5rem;
    }
  </style>
</head>

<body class="events-page">

  <?php include('assets/php/navbar.php'); ?>

  <main class="main">

    <!-- Page Title -->
    <section id="hero" class="hero section dark-background">

      <div class="container text-center">

        <img src="assets/img/SinaraLogo.svg" alt="Logo do Repositório" class="hero-logo mb-4">

        <div class="search-and-filter-container">

          <div class="search-bar">
            <form class="search-bar" action="assets/php/pesquisa.php" method="post">
              <input type="text" class="form-control search-input" placeholder="Pesquisar no repositório...">
              <button class="btn search-button" type="submit" aria-label="Pesquisar">
                <i class="bi bi-search"></i> </button>
            </form>
          </div>

          <div class="filter-area">
            <select class="form-select filter-select" aria-label="Filtrar por área de estudo">
              <option selected disabled>Filtrar por área de estudo</option>
              <option value="Artes">Artes</option>
              <option value="Audiovisual">Audiovisual</option>
              <option value="Biologia">Biologia</option>
              <option value="Design">Design</option>
              <option value="Educação Física">Educação Física</option>
              <option value="Filosofia">Filosofia</option>
              <option value="Física">Física</option>
              <option value="Geografia">Geografia</option>
              <option value="História">História</option>
              <option value="Informática">Informática</option>
              <option value="Letras">Letras</option>
              <option value="Matemática">Matemática</option>
              <option value="Química">Química</option>
              <option value="Sociologia">Sociologia</option>
            </select>
          </div>

        </div>
      </div>
    </section>

    <section id="events-2" class="events-2 section">
      <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="row g-4">
          <div class="">
            <div class="events-list">
              <h3>Buscando resultados para <span style="font-weight: bold;">"todas as pesquisas"</span> | Filtro: <span
                  style="font-weight: bold;">"todas as áreas"</span></h3>

              <?php
              if ($resultado && $resultado->num_rows > 0):
                while ($trabalho = $resultado->fetch_assoc()):
                  $sql_nomeOrientador = "SELECT nome FROM usuario WHERE idUsuario = ?";
                  $stmt_nomeOrientador = $conn->prepare($sql_nomeOrientador);
                  $stmt_nomeOrientador->bind_param("i", $trabalho["idUsuario"]);
                  $stmt_nomeOrientador->execute();
                  $resultado_orientador = $stmt_nomeOrientador->get_result();
                  $orientador = $resultado_orientador->fetch_assoc();
                  ?>

                  <div class="event-item trabalho-clickable" data-aos="fade-up"
                    data-ano="<?php echo htmlspecialchars($trabalho['anoTrab']); ?>"
                    data-keywords="<?php echo htmlspecialchars($trabalho['palavrasChaves']); ?>"
                    data-resumo="<?php echo htmlspecialchars($trabalho['resumo']); ?>"
                    data-abstract="<?php echo htmlspecialchars($trabalho['abstract']); ?>"
                    data-file-url="<?php echo htmlspecialchars($trabalho['arquivoTrabalho']); ?>"
                    data-areas="<?php echo htmlspecialchars($trabalho['areas_de_estudo']); ?>"
                    data-orientador="<?php echo htmlspecialchars($orientador['nome']) ?>">



                    <div class="event-content p-4">
                      <h3><?php echo htmlspecialchars($trabalho['titulo']); ?></h3>
                      <p class="author"><?php echo htmlspecialchars($trabalho['nomePesquisador']); ?></p>
                      <div class="event-meta">
                        <p><i class="bi bi-folder2-open"></i> <?php echo htmlspecialchars($trabalho['areas_de_estudo']); ?>
                        </p>
                        <p><i class="bi bi-person-workspace"></i> <?php echo htmlspecialchars($orientador['nome'])?> </p>
                        <p><i class="bi bi-calendar"></i> <?php echo date("d.m.Y", strtotime($trabalho['dtPubli'])); ?></p>
                      </div>
                    </div>
                  </div>
                  <?php
                endwhile;
              else:
                echo '<p class="text-center">Nenhum trabalho encontrado no repositório.</p>';
              endif;
              $conn->close();
              ?>

            </div>
          </div>
          <div class="pagination-wrapper">
            <ul class="pagination justify-content-center"></ul>
          </div>
        </div>
      </div>
    </section>


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

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/confirmarLogout.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>

  <!-- Main JS File -->
  <script src="assets/js/main.js"></script>
  <script src="assets/js/paginacaoTrabalhos.js"></script>
  <script src="assets/js/modalTrabalho.js"></script>

</body>

</html>
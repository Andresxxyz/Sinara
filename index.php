<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Página Inicial</title>
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
</head>

<body class="index-page">

  <?php include('assets/php/navbar.php'); ?>
  <main class="main">


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


    <!-- About Section -->
    <section id="about" class="about section">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

      

        <div class="row mission-vision-row g-4">
          <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
            <div class="value-card h-100">
              <div class="card-icon">
                <i class="bi bi-journal-bookmark"></i>
              </div>
              <h3>Ensino</h3>
              <p>Os Projetos de Ensino são pensados e construídos a partir dos docentes e discentes (bolsistas e voluntários) por meio de atividades acadêmicas de ensino para o público interno. Suas atividades de estudo contribuem para a formação integral e para o aprimoramento educacional e profissional da comunidade interna. </p>
            </div>
          </div>
          <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
            <div class="value-card h-100">
              <div class="card-icon">
                <i class="bi bi-eye"></i>
              </div>
              <h3>Pesquisa</h3>
              <p>Os projetos de pesquisa visam, principalmente, promover o conhecimento científico e desenvolver a capacidade crítica dos estudantes. Além disso, têm como objetivo contribuir com o avanço da ciência, da tecnologia e da inovação, muitas vezes com foco em melhorias sociais, educacionais, ambientais ou econômicas que beneficiem a comunidade local e regional. Suas atividades são compostas por docente e discente (bolsista), por meio da produção de um artigo científico.</p>
            </div>
          </div>
          <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
            <div class="value-card h-100">
              <div class="card-icon">
                <i class="bi bi-globe2"></i>
              </div>
              <h3>Extensão</h3>
              <p>O Projeto de Extensão é o conjunto de atividades interdisciplinares de caráter educativo, tecnológico, artístico, social e cultural, desenvolvido e aplicado na interação com a comunidade interna e externa, com objetivos específicos e prazos determinados, visando à interação transformadora entre a comunidade acadêmica e a sociedade, tratando-se de ação processual e contínua. 
</p>
            </div>
          </div>
        </div>

      </div>

    </section><!-- /About Section -->

    <!-- Featured Programs Section -->
<section id="featured-programs" class="featured-programs section">

  <div class="container section-title" data-aos="fade-up">
    <h2>Áreas do Conhecimento</h2>
    <p>Explore as diversas áreas de estudo e pesquisa. Use os filtros para navegar pelas categorias e encontrar o que procura.</p>
  </div><div class="container" data-aos="fade-up" data-aos-delay="100">

    <div class="isotope-layout" data-default-filter="*" data-layout="masonry" data-sort="original-order">

      <ul class="program-filters isotope-filters">
        <li data-filter="*" class="filter-active">Todas</li>
        <li data-filter=".filter-exatas">Exatas</li>
        <li data-filter=".filter-humanas">Humanas</li>
        <li data-filter=".filter-linguagens">Linguagens</li>
        <li data-filter=".filter-biologicas">Biológicas e Saúde</li>
        <li data-filter=".filter-tecnico">Técnico</li>
      </ul><div class="row g-4 isotope-container">

        <div class="col-lg-6 isotope-item filter-exatas">
          <div class="program-item">
            <div class="row g-0 h-100 h-100">
              <div class="col-md-4"><div class="program-image-wrapper"><img src="assets/img/areas/fisica.jpeg" class="img-fluid" alt="Física"></div></div>
              <div class="col-md-8"><div class="program-content"><h3>Física</h3><p style="text-align: justify;">A física investiga os fenômenos naturais a partir da observação e experimentação, explicando o universo com conceitos como movimento, energia e matéria.</p></div></div>
            </div>
          </div>
        </div>
        <div class="col-lg-6 isotope-item filter-tecnico">
          <div class="program-item">
            <div class="row g-0 h-100">
              <div class="col-md-4"><div class="program-image-wrapper"><img src="assets/img/areas/informática.jpeg" class="img-fluid" alt="Informática"></div></div>
              <div class="col-md-8"><div class="program-content"><h3>Informática</h3><p style="text-align: justify;">Estuda o processamento, armazenamento e transmissão de informações por meio de sistemas computacionais, envolvendo programação, redes e software.</p></div></div>
            </div>
          </div>
        </div>
        <div class="col-lg-6 isotope-item filter-exatas">
          <div class="program-item">
            <div class="row g-0 h-100">
              <div class="col-md-4"><div class="program-image-wrapper"><img src="assets/img/areas/matematica.jpeg" class="img-fluid" alt="Matemática"></div></div>
              <div class="col-md-8"><div class="program-content"><h3>Matemática</h3><p style="text-align: justify;">Ciência exata que busca a resolução de problemas através da sistematização de quantidades por meio da aritmética, álgebra, geometria, estatística e do cálculo.</p></div></div>
            </div>
          </div>
        </div>
        <div class="col-lg-6 isotope-item filter-exatas">
          <div class="program-item">
            <div class="row g-0 h-100">
              <div class="col-md-4"><div class="program-image-wrapper"><img src="assets/img/areas/quimica.jpeg" class="img-fluid" alt="Química"></div></div>
              <div class="col-md-8"><div class="program-content"><h3>Química</h3><p style="text-align: justify;">Ciência que estuda a composição, estrutura, propriedades e transformações da matéria, fundamental para áreas como saúde, agricultura e tecnologia.</p></div></div>
            </div>
          </div>
        </div>

        <div class="col-lg-6 isotope-item filter-humanas">
          <div class="program-item">
            <div class="row g-0 h-100">
              <div class="col-md-4"><div class="program-image-wrapper"><img src="assets/img/areas/filosofia.jpeg" class="img-fluid" alt="Filosofia"></div></div>
              <div class="col-md-8"><div class="program-content"><h3>Filosofia</h3><p style="text-align: justify;">A filosofia, que significa amor pela sabedoria, é um campo do conhecimento que estuda o ser humano através da análise racional de suas questões.</p></div></div>
            </div>
          </div>
        </div>
        <div class="col-lg-6 isotope-item filter-humanas">
          <div class="program-item">
            <div class="row g-0 h-100">
              <div class="col-md-4"><div class="program-image-wrapper"><img src="assets/img/areas/geografia.jpeg" class="img-fluid" alt="Geografia"></div></div>
              <div class="col-md-8"><div class="program-content"><h3>Geografia</h3><p style="text-align: justify;">A geografia se dedica à compreensão da relação entre ser humano e a natureza para entender a configuração do espaço geográfico e suas transformações.</p></div></div>
            </div>
          </div>
        </div>
        <div class="col-lg-6 isotope-item filter-humanas">
          <div class="program-item">
            <div class="row g-0 h-100">
              <div class="col-md-4"><div class="program-image-wrapper"><img src="assets/img/areas/historia.jpeg" class="img-fluid" alt="História"></div></div>
              <div class="col-md-8"><div class="program-content"><h3>História</h3><p style="text-align: justify;">O estudo das ações humanas ao longo do tempo a partir de fontes históricas, como documentos, artefatos, e tudo que sirva como vestígio do passado.</p></div></div>
            </div>
          </div>
        </div>
        <div class="col-lg-6 isotope-item filter-humanas">
          <div class="program-item">
            <div class="row g-0 h-100">
              <div class="col-md-4"><div class="program-image-wrapper"><img src="assets/img/areas/sociologia.jpeg" class="img-fluid" alt="Sociologia"></div></div>
              <div class="col-md-8"><div class="program-content"><h3>Sociologia</h3><p style="text-align: justify;">As ciências sociais abordam temas voltados ao estudo da sociedade, identificando, classificando e analisando a organização social como um todo.</p></div></div>
            </div>
          </div>
        </div>
        
        <div class="col-lg-6 isotope-item filter-linguagens">
          <div class="program-item">
            <div class="row g-0 h-100">
              <div class="col-md-4"><div class="program-image-wrapper"><img src="assets/img/areas/artes.jpeg"class="img-fluid" alt="Artes"></div></div>
              <div class="col-md-8"><div class="program-content"><h3>Artes</h3><p style="text-align: justify;">Manifestações humanas que expressam ideias, sentimentos e visões de mundo por meio de diversas linguagens, como a pintura, escultura, música e dança.</p></div></div>
            </div>
          </div>
        </div>
        <div class="col-lg-6 isotope-item filter-linguagens">
          <div class="program-item">
            <div class="row g-0 h-100">
              <div class="col-md-4"><div class="program-image-wrapper"><img src="assets/img/areas/letras.jpeg" class="img-fluid" alt="Letras"></div></div>
              <div class="col-md-8"><div class="program-content"><h3>Letras</h3><p style="text-align: justify;">Se dedica ao estudo das línguas, suas estruturas, literaturas e manifestações culturais, promovendo o desenvolvimento da comunicação e da interpretação crítica.</p></div></div>
            </div>
          </div>
        </div>
        <div class="col-lg-6 isotope-item filter-tecnico" >
          <div class="program-item">
            <div class="row g-0 h-100">
              <div class="col-md-4"><div class="program-image-wrapper"><img src="assets/img/areas/audiovisual.jpg" class="img-fluid" alt="Audiovisual"></div></div>
              <div class="col-md-8"><div class="program-content"><h3>Audiovisual</h3><p style="text-align: justify;">Área dedicada à produção de conteúdo por meio de som e imagem, abrangendo cinema, televisão, vídeos e mídias digitais. Une criatividade e técnica.</p></div></div>
            </div>
          </div>
        </div>
        <div class="col-lg-6 isotope-item filter-tecnico">
          <div class="program-item">
            <div class="row g-0 h-100">
              <div class="col-md-4"><div class="program-image-wrapper"><img src="assets/img/areas/design.jpeg" class="img-fluid" alt="Design"></div></div>
              <div class="col-md-8"><div class="program-content"><h3>Design</h3><p style="text-align: justify;">Disciplina que projeta soluções visuais, funcionais e criativas para produtos, serviços e experiências, aliando estética, funcionalidade e inovação.</p></div></div>
            </div>
          </div>
        </div>

        <div class="col-lg-6 isotope-item filter-biologicas">
          <div class="program-item">
            <div class="row g-0 h-100">
              <div class="col-md-4"><div class="program-image-wrapper"><img src="assets/img/areas/biologia.jpeg" class="img-fluid" alt="Biologia"></div></div>
              <div class="col-md-8"><div class="program-content"><h3>Biologia</h3><p style="text-align: justify;">A área de estudo destinada a compreender todas as formas da vida, desde microrganismos até organismos complexos, investigando suas características e evolução.</p></div></div>
            </div>
          </div>
        </div>
        <div class="col-lg-6 isotope-item filter-biologicas" >
          <div class="program-item">
            <div class="row g-0 h-100">
              <div class="col-md-4"><div class="program-image-wrapper"><img src="assets/img/areas/educaçãoFisica.jpg" class="img-fluid" alt="Educação Física"></div></div>
              <div class="col-md-8"><div class="program-content"><h3>Educação Física</h3><p style="text-align: justify;">Área voltada para o estudo e a promoção da saúde e do bem-estar por meio da prática de atividades corporais, esportes, recreação e desenvolvimento humano.</p></div></div>
            </div>
          </div>
        </div>
        
      </div></div>

  </div>

</section><!-- /Featured Programs Section -->

   

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

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/confirmarLogout.js"></script>

  <!-- Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>
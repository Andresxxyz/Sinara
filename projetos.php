<?php require_once('assets/php/conexao.php');
$conn = conexao();

// ---- SUA CONSULTA ANTIGA (BUSCAVA ORIENTADORES) ----
// $sql_usuario = "SELECT u.nome, u.fotoPerfil, u.matricula, GROUP_CONCAT(lp.nome ORDER BY lp.nome SEPARATOR ', ') as areas_orientador FROM usuario u 
// LEFT JOIN areaestudo ae ON u.idUsuario = ae.idUsuario
// LEFT JOIN linhapesquisa lp ON ae.idLinhaPesquisa = lp.idLinhaPesquisa
// WHERE senha IS NOT NULL AND senha <> ''
// GROUP BY  u.idUsuario, u.nome, u.fotoPerfil, u.matricula
// ORDER BY nome ASC";

// ---- NOVA CONSULTA (BUSCA PROJETOS) ----
$sql_projetos = "SELECT 
                    p.idProjeto,
                    p.nomeProj,
                    p.descricaoProj,
                    p.urlLogo,
                    u.nome AS nomeOrientador,
                    u.matricula AS matriculaOrientador,
                    GROUP_CONCAT(ae.nome ORDER BY ae.nome SEPARATOR ', ') AS areas_projeto
                FROM 
                    Projeto p
                JOIN 
                    Usuario u ON p.idUsuario = u.idUsuario
                LEFT JOIN 
                    ProjetoArea pa ON p.idProjeto = pa.idProjeto
                LEFT JOIN 
                    AreaEstudo ae ON pa.idAreaEstudo = ae.idAreaEstudo
                GROUP BY 
                    p.idProjeto, p.nomeProj, p.descricaoProj, p.urlLogo, u.nome, u.matricula
                ORDER BY 
                    p.nomeProj ASC";

$resultado = $conn->query($sql_projetos); 
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Projetos</title>
  <meta name="description" content="">
  <meta name="keywords" content=""> <link href="assets/img/iconTab.png" rel="icon">
  <link href="assets.img/apple-touch-icon.png" rel="apple-touch-icon"> <link href="https.googleapis.com" rel="preconnect">
  <link href="https.gstatic.com" rel="preconnect" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
    rel="stylesheet"> <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet"> <link href="assets/css/main.css" rel="stylesheet">
  <style>
    .event-content {
      display: flex;
      align-items: center;
      gap: 50px;
    }

    .imgOrientador { /* Renomeei para imgProjeto, mas mantive o nome da classe antigo para compatibilidade */
      width: 80px;
      height: 80px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid #eee; /* Adiciona uma borda sutil */
    }

    .nomeAreasOrientador{ /* O nome da classe não é ideal, mas mantive */
      display: flex;
      flex-direction: column;
      height: 100%;
      justify-content:space-evenly;
    }

    .nomeAreasOrientador h3{
      margin-bottom: 0!important;
    }
  </style>
</head>

<body class="events-page"> <?php include('assets/php/navbar.php'); ?>
  <div class="page-title dark-background">
    <div class="container position-relative">
      <h1>Projetos</h1>
    </div>
  </div>
  <main class="main"> <section id="events-2" class="events-2 section">
      <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="row g-4">
          <div class="">
            <div class="events-list">
              <?php if ($resultado && $resultado->num_rows > 0): ?>
                <?php while ($projeto = $resultado->fetch_assoc()): ?>
                  
                  <?php
                    // Define a imagem (logo do projeto ou padrão)
                    $logoSrc = !empty($projeto['urlLogo']) 
                               ? htmlspecialchars($projeto['urlLogo']) 
                               : 'assets/img/projetoSemLogo.png'; // Crie esta imagem padrão
                  ?>

                  <a href="perfilProjeto.php?id=<?php echo htmlspecialchars($projeto['idProjeto']); ?>"
                    class="trabalho-clickable-link">
                    <div class="event-item" data-aos="fade-up">
                      <div class="event-content p-4">
                        
                        <img src="<?php echo $logoSrc; ?>" class="imgOrientador"
                          alt="Logo do Projeto">
                        
                          <div class="nomeAreasOrientador">
                          
                          <h3><?php echo htmlspecialchars($projeto['nomeProj']); ?></h3>
                          
                          <h3 style="font-size: 12px">Área(s) de Estudo: <span style="color: #2d465eaa;"><?php echo htmlspecialchars($projeto['areas_projeto'] ?? 'Nenhuma área definida'); ?></span></h3>
                          
                          <h3 style="font-size: 12px">Orientador: <span style="color: #2d465eaa;"><?php echo htmlspecialchars($projeto['nomeOrientador']); ?></span></h3>
                        
                        </div>
                        
                        </div>
                    </div>
                  </a>
                <?php endwhile; ?>
              <?php else: ?>
                <?php echo '<p class="text-center">Nenhum projeto cadastrado ainda.</p>'; ?>
              <?php endif; ?>
              <?php $conn->close(); ?>
            </div>
          </div>
          <div class="pagination-wrapper">
            <ul class="pagination justify-content-center"></ul>
          </div>
        </div>
      </div>
    </section>
  </main>
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
      <div class="credits"> Designed by <a
          href="https.bootstrapmade.com/">BootstrapMade</a>
      </div>
    </div>
  </footer> <a href="#" id="scroll-top"
    class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
  <div id="preloader"></div> <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/confirmarLogout.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script> <script src="assets/js/main.js"></script>
  <script src="assets/js/paginacaoTrabalhos.js"></script>
  <script src="assets/js/modalTrabalho.js"></script>
</body>

</html>
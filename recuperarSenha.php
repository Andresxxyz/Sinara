<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Recuperar Senha</title>
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

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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

  <Style>
    .toggle-password-visibility {
      cursor: pointer;
      user-select: none;
      position: relative;
      z-index: 5;
    }

    .toggle-password-visibility i {
      pointer-events: none;
    }

    /* força a exibição quando o input for text */
    /* garante posição relativa para input-group e overlay que mostra a senha */
    .input-group {
      position: relative;
    }

    /* overlay que mostra a senha em texto claro */
    .password-reveal-overlay {
      position: absolute;
      inset: 0;
      /* top:0; right:0; bottom:0; left:0 */
      display: none;
      align-items: center;
      padding: .375rem .75rem;
      /* mesmo padding do .form-control do Bootstrap */
      pointer-events: none;
      /* deixa clique atravessar para o input */
      white-space: pre;
      /* preserva espaços e evita quebra */
      overflow: hidden;
      text-overflow: ellipsis;
      background: transparent;
      color: inherit;
      font-family: inherit;
      font-size: inherit;
      line-height: inherit;
    }

    /* classe que mostra o overlay */
    .password-reveal-overlay.show {
      display: flex;
    }


    /* o click sempre bate no span */
  </Style>
</head>

<body class="admissions-page">

  <?php include('assets/php/navbar.php'); ?>

  <main class="main">

    <!-- Page Title -->
    <div class="page-title dark-background">
      <div class="container position-relative">
        <h1>Recuperação de Senha</h1>
      </div>
    </div><!-- End Page Title -->

    <!-- Admissions Section -->
    <section id="admissions" class="admissions section py-5">
      <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="request-info" data-aos="fade-up">
          <div class="card">
            <div class="card-body text-center" style="padding: 5% 20%;">
              <h3 class="card-title">Recuperar Senha</h3>
              <p>Digite sua matrícula e aguarde a mensagem no email cadastrado.</p>

              <form class="php-email-form mt-4" action="assets/php/recuperarSenha.php" method="post">
                <div class="mb-3">
                  <input type="text" name="matricula" class="form-control" placeholder="Matrícula" required="">
                </div>

                <div class="loading">Loading</div>
                <div class="error-message"></div>
                <div class="sent-message">Your request has been sent. Thank you!</div>
                <div class="text-center">
                  <button type="submit" class="btn btn-primary">Enviar Email</button>
                </div>
                <?php
                if (isset($_COOKIE["erroMatNaoExiste"])) {
                  echo ("<div id='mensagem-erro2' style='color: red; margin-top: 15px; font-weight: bold;'>
                  Matrícula não encontrada ou cadastro ainda não realizado.
                  </div>");
                  }
                ?>


                <div class="temCadastro mt-2">
                  <p>Não tem cadastro? <a href="cadastro.php">Cadastre-se</a>.</p>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>

    </section><!-- /Admissions Section -->

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

        <div class="col-lg-4 col-md-F6">
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
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/js/msgLoginErro.js"></script>

  <script src="assets/js/main.js"></script>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      console.log('[reveal-overlay] iniciado');

      document.querySelectorAll('.toggle-password-visibility').forEach(toggle => {
        const targetId = toggle.getAttribute('data-target');
        let input = targetId ? document.getElementById(targetId) : null;
        if (!input) input = toggle.closest('.input-group')?.querySelector('input');

        if (!input) {
          console.warn('[reveal-overlay] nenhum input encontrado para', toggle);
          return;
        }

        const group = input.closest('.input-group') || input.parentElement;

        if (getComputedStyle(group).position === 'static') {
          group.style.position = 'relative';
        }


        let overlay = group.querySelector('.password-reveal-overlay');
        if (!overlay) {
          overlay = document.createElement('div');
          overlay.className = 'password-reveal-overlay';
          group.appendChild(overlay);
        }

        const updateOverlay = () => {

          overlay.textContent = input.value || '';
        };

        input.addEventListener('input', updateOverlay);


        let revealed = false;

        const setRevealed = (state) => {
          revealed = !!state;
          if (revealed) {
            updateOverlay();
            overlay.classList.add('show');
            toggle.setAttribute('aria-pressed', 'true');
            toggle.setAttribute('aria-label', 'Ocultar senha');
            const icon = toggle.querySelector('i');
            if (icon) {
              icon.classList.remove('bi-eye-slash');
              icon.classList.add('bi-eye');
            }
          } else {
            overlay.classList.remove('show');
            toggle.setAttribute('aria-pressed', 'false');
            toggle.setAttribute('aria-label', 'Mostrar senha');
            const icon = toggle.querySelector('i');
            if (icon) {
              icon.classList.remove('bi-eye');
              icon.classList.add('bi-eye-slash');
            }
          }
        };

        toggle.addEventListener('click', (e) => {
          e.preventDefault();
          setRevealed(!revealed);
          input.focus();
          const len = input.value.length;
          try {
            input.setSelectionRange(len, len);
          } catch (err) {}
          console.log('[reveal-overlay] toggled, revealed=', revealed);
        });

        toggle.addEventListener('keydown', (e) => {
          if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            toggle.click();
          }
        });

        input.form?.addEventListener('submit', () => setRevealed(false));
      });
    });
  </script>




</body>

</html>
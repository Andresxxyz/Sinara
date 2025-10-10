<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Cadastro</title>
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
  <style>
    .dropdown {
      width: 100%;
    }

    .dropdown-menu {
      max-height: 250px;
      overflow-y: auto;
    }

    .triangulo {
      width: 1rem;
      height: 1rem !important;
      transform: rotate(45deg) !important;
      left: 50%;
      transform: translate(-50%, 0);
      background-color: #e3f2ec;
    }

    .retangulo {
      width: 50%;
      position: absolute;
      background-color: #e3f2ec;
      left: 50%;
      margin-top: 0.5rem;
      z-index: 999;
      transform: translate(-50%, 0);
    }

    /* Toggle eye */
    .toggle-password-visibility {
      cursor: pointer;
      user-select: none;
      position: relative;
      z-index: 5;
    }

    .toggle-password-visibility i {
      pointer-events: none;
    }


    .input-group {
      position: relative;
    }


    .password-reveal-overlay {
      position: absolute;
      inset: 0;
      display: none;
      align-items: center;
      padding: .375rem .75rem;
      pointer-events: none;
      white-space: pre;
      overflow: hidden;
      text-overflow: ellipsis;
      background: transparent;
      color: inherit;
      font-family: inherit;
      font-size: inherit;
      line-height: inherit;
    }

    .password-reveal-overlay.show {
      display: flex;
    }
    input.showing-password[type="text"] {
      -webkit-text-security: none !important;
      text-security: none !important;
    }
    #mensagem-erro{
      margin-bottom: 1rem;
    }
  </style>

  <!-- =======================================================
  * Template Name: NiceSchool
  * Template URL: https://bootstrapmade.com/nice-school-bootstrap-education-template/
  ======================================================== -->
</head>

<body class="admissions-page">

  <?php include('assets/php/navbar.php'); ?>

  <main class="main">

    <!-- Page Title -->
    <div class="page-title dark-background">
      <div class="container position-relative">
        <h1>Cadastro</h1>
      </div>
    </div><!-- End Page Title -->

    <!-- Admissions Section -->
    <section id="admissions" class="admissions section py-3">
      <div class="container my-2"  data-aos="fade-up" data-aos-delay="100">
        <div class="request-info " data-aos="fade-up">
          <div class="card">
            <div class="card-body text-center" style="padding: 5% 5%;">
              <h3 class="card-title">Cadastro de Docente</h3>
              <p>Junte-se a outros educadores criando sua conta para compartilhar conhecimento.</p>

              <form class="php-email-form mt-4" action="assets/php/cadastro.php" method="POST" id="formCadastro">
                <div class="mb-3">
                  <input type="text" name="nome" class="form-control" placeholder="Nome" required="">
                </div>

                <div class="row g-3 mb-3">
                  <div class="col-md-6">
                    <input type="text" name="matricula" class="form-control" placeholder="Matrícula" required="">
                  </div>
                  <div class="col-md-6">
                    <div class="dropdown position-relative">
                      <button class="form-select text-start w-100" type="button" id="dropdownMenuButton"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <span id="dropdownMenuText">Selecione uma ou mais áreas de estudo...</span>
                      </button>

                      <div class="triangulo position-absolute" id="triang" style="display: none;">
                      </div>
                      <div class="retangulo" style="display: none;" id="retang">
                        <p class="mb-0" style="color:#000;font-weight: bold; font-size: 15px;">Selecione pelo menos uma
                          área</p>
                      </div>

                      <ul class="dropdown-menu w-100" id="areas_cb" aria-labelledby="dropdownMenuButton"
                        data-bs-auto-close="outside">
                        <li>
                          <div class="dropdown-item">
                            <div class="form-check"><input class="form-check-input" type="checkbox" name="subject[]"
                                value="Artes" id="checkArtes"><label class="form-check-label w-100"
                                for="checkArtes">Artes</label></div>
                          </div>
                        </li>
                        <li>
                          <div class="dropdown-item">
                            <div class="form-check"><input class="form-check-input" type="checkbox" name="subject[]"
                                value="Audiovisual" id="checkAudiovisual"><label class="form-check-label w-100"
                                for="checkAudiovisual">Audiovisual</label></div>
                          </div>
                        </li>
                        <li>
                          <div class="dropdown-item">
                            <div class="form-check"><input class="form-check-input" type="checkbox" name="subject[]"
                                value="Biologia" id="checkBiologia"><label class="form-check-label w-100"
                                for="checkBiologia">Biologia</label></div>
                          </div>
                        </li>
                        <li>
                          <div class="dropdown-item">
                            <div class="form-check"><input class="form-check-input" type="checkbox" name="subject[]"
                                value="Design" id="checkDesign"><label class="form-check-label w-100"
                                for="checkDesign">Design</label></div>
                          </div>
                        </li>
                        <li>
                          <div class="dropdown-item">
                            <div class="form-check"><input class="form-check-input" type="checkbox" name="subject[]"
                                value="Educação Física" id="checkEducacaoFisica"><label class="form-check-label w-100"
                                for="checkEducacaoFisica">Educação Física</label></div>
                          </div>
                        </li>
                        <li>
                          <div class="dropdown-item">
                            <div class="form-check"><input class="form-check-input" type="checkbox" name="subject[]"
                                value="Filosofia" id="checkFilosofia"><label class="form-check-label w-100"
                                for="checkFilosofia">Filosofia</label></div>
                          </div>
                        </li>
                        <li>
                          <div class="dropdown-item">
                            <div class="form-check"><input class="form-check-input" type="checkbox" name="subject[]"
                                value="Física" id="checkFisica"><label class="form-check-label w-100"
                                for="checkFisica">Física</label></div>
                          </div>
                        </li>
                        <li>
                          <div class="dropdown-item">
                            <div class="form-check"><input class="form-check-input" type="checkbox" name="subject[]"
                                value="Geografia" id="checkGeografia"><label class="form-check-label w-100"
                                for="checkGeografia">Geografia</label></div>
                          </div>
                        </li>
                        <li>
                          <div class="dropdown-item">
                            <div class="form-check"><input class="form-check-input" type="checkbox" name="subject[]"
                                value="História" id="checkHistoria"><label class="form-check-label w-100"
                                for="checkHistoria">História</label></div>
                          </div>
                        </li>
                        <li>
                          <div class="dropdown-item">
                            <div class="form-check"><input class="form-check-input" type="checkbox" name="subject[]"
                                value="Informática" id="checkInformatica"><label class="form-check-label w-100"
                                for="checkInformatica">Informática</label></div>
                          </div>
                        </li>
                        <li>
                          <div class="dropdown-item">
                            <div class="form-check"><input class="form-check-input" type="checkbox" name="subject[]"
                                value="Letras" id="checkLetras"><label class="form-check-label w-100"
                                for="checkLetras">Letras</label></div>
                          </div>
                        </li>
                        <li>
                          <div class="dropdown-item">
                            <div class="form-check"><input class="form-check-input" type="checkbox" name="subject[]"
                                value="Matemática" id="checkMatematica"><label class="form-check-label w-100"
                                for="checkMatematica">Matemática</label></div>
                          </div>
                        </li>
                        <li>
                          <div class="dropdown-item">
                            <div class="form-check"><input class="form-check-input" type="checkbox" name="subject[]"
                                value="Química" id="checkQuimica"><label class="form-check-label w-100"
                                for="checkQuimica">Química</label></div>
                          </div>
                        </li>
                        <li>
                          <div class="dropdown-item">
                            <div class="form-check"><input class="form-check-input" type="checkbox" name="subject[]"
                                value="Sociologia" id="checkSociologia"><label class="form-check-label w-100"
                                for="checkSociologia">Sociologia</label></div>
                          </div>
                        </li>
                      </ul>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="input-group">
                      <input id="senhaCadastro" type="password" name="senha" class="form-control" placeholder="Senha"
                        required="">
                      <span class="input-group-text toggle-password-visibility" role="button" tabindex="0"
                        aria-pressed="false" aria-label="Mostrar senha" data-target="senhaCadastro">
                        <i class="bi bi-eye-slash"></i>
                      </span>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="input-group">
                      <input id="confSenhaCadastro" type="password" name="confSenha" class="form-control"
                        placeholder="Confirmar Senha" required="">
                      <span class="input-group-text toggle-password-visibility" role="button" tabindex="0"
                        aria-pressed="false" aria-label="Mostrar senha" data-target="confSenhaCadastro">
                        <i class="bi bi-eye-slash"></i>
                      </span>
                    </div>
                  </div>
                </div>
                <div class="mb-3">
                  <input type="email" name="emailInst" class="form-control" placeholder="Email Instituicional"
                    required="">
                </div>
                <?php
                  if (isset($_COOKIE['erroSenhaInsegura'])) {
                      echo ("<div id='mensagem-erro' style='color: #2d465eaa; margin-top: 15px; font-weight: bold; text-align: left;'>" 
                      .nl2br(htmlspecialchars($_COOKIE['erroSenhaInsegura']))."
                    </div>");
                  }
                ?>
                
                <hr style="margin: 30px auto;">
                <h3 class="card-title">Dados Opcionais</h3>
                <div class="mb-3">
                  <input type="url" name="lattes" class="form-control" placeholder="Currículo Lattes (Link)">
                </div>
                <div class="mb-3">
                  <textarea name="sobreMim" class="form-control" placeholder="Sobre mim" maxlength="700" rows="5"
                    oninput="atualizarContador()"></textarea>
                  <div class="form-text" style="text-align: right;" id="contador">0 / 700</div>
                </div>

                <div class="loading">Loading</div>
                <div class="error-message"></div>
                <div class="sent-message">Your request has been sent. Thank you!</div>
                 <?php
                  if(isset($_COOKIE["erroJaCadastrado"])){
                    echo ("<div id='mensagem-erro' style='color: red; margin-top: 15px; font-weight: bold;'>
                    Você já tem cadastro! Entre com sua conta.
                    </div>");
                  }

                  if(isset($_COOKIE["erroMatNaoExiste"])){
                    echo ("<div id='mensagem-erro' style='color: red; margin-top: 15px; font-weight: bold;'>
                  Matrícula não encontrada. Verifique o número digitado e tente novamente.
                </div>");
                  }

                  if(isset($_COOKIE["erroSenhaDiferente"])){
                    echo ("<div id='mensagem-erro' style='color: red; margin-top: 15px; font-weight: bold;'>
                    As senhas não coincidem.
                    </div>");
                  }
                ?>
                <div class="text-center">
                  <button type="submit" class="btn btn-primary">Cadastrar</button>
                </div>
                <div class="temCadastro mt-2">
                  <p>Já tem cadastro? <a href="login.php">Entre</a>.</p>
                </div>
              </form>
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
  <script src="assets/vendor/php-email-form/validate.js.bkp"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/js/checkboxAreas.js"></script>

  <script src="assets/js/msgLoginErro.js"></script>

  <!-- Main JS File -->
  <script src="assets/js/main.js"></script>

  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const form = document.getElementById("formCadastro");

      form.addEventListener("submit", function (e) {
        const checkBoxes = document.querySelectorAll('input[name="subject[]"]');
        let marcado = false;

        checkBoxes.forEach(function (checkbox) {
          if (checkbox.checked) {
            marcado = true;
          }
        });

        if (!marcado) {
          e.preventDefault();
          e.stopImmediatePropagation();

          const tri = document.getElementById("triang");
          const ret = document.getElementById("retang");
          tri.style.display = "block";
          ret.style.display = "block";

        }
      }, true);
      document.addEventListener("click", function () {
        const tri = document.getElementById("triang");
        const ret = document.getElementById("retang");
        tri.style.display = "none";
        ret.style.display = "none";
      });
    });
  </script>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      document.querySelectorAll('.toggle-password-visibility').forEach(toggle => {
        const targetId = toggle.getAttribute('data-target');
        let input = targetId ? document.getElementById(targetId) : null;
        if (!input) input = toggle.closest('.input-group')?.querySelector('input');
        if (!input) return;

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
        updateOverlay();

        let revealed = false;

        const setRevealed = (state) => {
          revealed = !!state;
          const icon = toggle.querySelector('i');
          if (revealed) {
            updateOverlay();
            overlay.classList.add('show');
            toggle.setAttribute('aria-pressed', 'true');
            toggle.setAttribute('aria-label', 'Ocultar senha');
            if (icon) { icon.classList.remove('bi-eye-slash'); icon.classList.add('bi-eye'); }
          } else {
            overlay.classList.remove('show');
            toggle.setAttribute('aria-pressed', 'false');
            toggle.setAttribute('aria-label', 'Mostrar senha');
            if (icon) { icon.classList.remove('bi-eye'); icon.classList.add('bi-eye-slash'); }
          }
        };

        toggle.addEventListener('click', (e) => {
          e.preventDefault();
          setRevealed(!revealed);
          input.focus();
          const len = input.value.length;
          try { input.setSelectionRange(len, len); } catch (err) { }
        });


        input.addEventListener('blur', () => setRevealed(false));

        input.form?.addEventListener('submit', () => setRevealed(false));
      });
    });
  </script>

</body>

<script>
  function atualizarContador() {
    const textarea = document.querySelector('textarea[name="sobreMim"]');
    const contador = document.getElementById('contador');
    contador.textContent = `${textarea.value.length} / 700`;
  }
</script>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("formCadastro");
    const senhaInput = document.getElementById("senhaCadastro");
    const confSenhaInput = document.getElementById("confSenhaCadastro");

    const erroSenhaDiv = document.createElement('div');
    erroSenhaDiv.id = "mensagem-erro-senha";
    erroSenhaDiv.style.color = "red";
    erroSenhaDiv.style.marginTop = "15px";
    erroSenhaDiv.style.fontWeight = "bold";
    erroSenhaDiv.style.display = "none";
    erroSenhaDiv.textContent = "As senhas não coincidem. Tente novamente.";

    const submitButton = form.querySelector('button[type="submit"]');
    submitButton.parentElement.insertAdjacentElement('beforebegin', erroSenhaDiv);

    form.addEventListener("submit", function (e) {
      erroSenhaDiv.style.display = "none";

      if (senhaInput.value !== confSenhaInput.value) {
        e.preventDefault(); 
        erroSenhaDiv.style.display = "block"; 
      }
    });

    function hideErrorOnChange() {
      if (erroSenhaDiv.style.display === "block") {
        erroSenhaDiv.style.display = "none";
      }
    }
    senhaInput.addEventListener('input', hideErrorOnChange);
    confSenhaInput.addEventListener('input', hideErrorOnChange);
  });
</script>

</html>
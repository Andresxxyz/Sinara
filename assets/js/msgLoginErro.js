document.addEventListener('DOMContentLoaded', function () {
    const togglePasswordButtons = document.querySelectorAll('.toggle-password-visibility');

    togglePasswordButtons.forEach(button => {
      button.addEventListener('click', function () {
        const passwordInput = this.previousElementSibling;
        const icon = this.querySelector('i');

        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);

        icon.classList.toggle('bi-eye');
        icon.classList.toggle('bi-eye-slash');
      });
    });
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('erro') === '1') {
      const mensagemErroDiv = document.getElementById('mensagem-erro1');
      if (mensagemErroDiv) {
        mensagemErroDiv.style.display = 'block';
      }
    } else
    if (urlParams.get('erro') === '2') {
      const mensagemErroDiv = document.getElementById('mensagem-erro2');
      if (mensagemErroDiv) {
        mensagemErroDiv.style.display = 'block';
      }
    }
  });
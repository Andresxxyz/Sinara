document.addEventListener('DOMContentLoaded', function() {

    function fecharTodosDropdowns() {
        document.querySelectorAll('.dropdownEditarApagar').forEach(function(dropdown) {
            dropdown.style.display = 'none';
        });
        document.removeEventListener('click', handleClickFora);
    }

    function handleClickFora(event) {
        const dropdownAberto = document.querySelector('.dropdownEditarApagar[style*="display: flex"]');
        const botaoGatilho = dropdownAberto ? dropdownAberto.previousElementSibling : null;

        if (dropdownAberto && !dropdownAberto.contains(event.target) && !botaoGatilho.contains(event.target)) {
            fecharTodosDropdowns();
        }
    }

    document.querySelectorAll('.btnEditarApagar').forEach(function(button) {
        button.addEventListener('click', function(event) {
            event.stopPropagation();

            const dropdown = this.nextElementSibling;
            const menuEstaAberto = dropdown.style.display === 'flex';
            fecharTodosDropdowns();
            if (!menuEstaAberto) {
                dropdown.style.display = 'flex';
                document.addEventListener('click', handleClickFora);
            }
        });
    });

});

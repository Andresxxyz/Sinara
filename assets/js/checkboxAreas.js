document.addEventListener('DOMContentLoaded', function () {
    function inicializarDropdown(ulId, dropdownTextId) {
        const container = document.getElementById(ulId);
        if (!container) return;

        const checkboxes = container.querySelectorAll('.form-check-input');
        const dropdownText = document.getElementById(dropdownTextId);

        checkboxes.forEach(function (checkbox) {
            checkbox.addEventListener('change', function () {
                const selectedLabels = [];
                checkboxes.forEach(function (cb) {
                    if (cb.checked) {
                        const label = container.querySelector(`label[for="${cb.id}"]`);
                        if (label) selectedLabels.push(label.innerText);
                    }
                });

                if (!dropdownText) return;

                if (selectedLabels.length === 0) {
                    dropdownText.innerText = 'Selecione uma ou mais áreas...';
                } else if (selectedLabels.length === 1) {
                    dropdownText.innerText = selectedLabels[0];
                } else {
                    dropdownText.innerText = `${selectedLabels.length} áreas selecionadas`;
                }
            });
        });

        const dropdownItems = container.querySelectorAll('.dropdown-item');
        dropdownItems.forEach(function (item) {
            item.addEventListener('click', function (event) {
                event.stopPropagation();
            });
        });
    }

    inicializarDropdown('areas_cb', 'dropdownMenuText');
    inicializarDropdown('areas_cb_2', 'dropdownMenuText2');
    inicializarDropdown('areas_cb_3', 'dropdownMenuText3');
    inicializarDropdown('areas_cb_4', 'dropdownMenuText4');
});
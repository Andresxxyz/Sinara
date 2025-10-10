document.addEventListener('DOMContentLoaded', () => {

  const trabalhoModal = new bootstrap.Modal(document.getElementById('trabalhoModal'));


  const clickableItems = document.querySelectorAll('.trabalho-clickable');


  clickableItems.forEach(item => {
    item.addEventListener('click', function () {

      const title = this.querySelector('h3').textContent;
      const author = this.querySelector('.author').textContent;
      const date = this.querySelector('.bi-calendar').nextSibling.textContent.trim();

      const keywords = this.dataset.keywords || 'Não disponível';
      const summary = this.dataset.resumo || 'Não disponível';
      const abstract = this.dataset.abstract || 'Não disponível';
      const fileUrl = this.dataset.fileUrl || '#';
      const ano = this.dataset.ano;
      const areas = this.dataset.areas;
      const orientador = this.dataset.orientador;

      document.getElementById('modal-title').textContent = title;
      document.getElementById('modal-author').textContent = author;
      document.getElementById('modal-date').textContent = date;
      document.getElementById('modal-keywords').textContent = keywords;
      document.getElementById('modal-summary').textContent = summary;
      document.getElementById('modal-abstract').textContent = abstract;
      document.getElementById('modal-ano').textContent = ano;
      document.getElementById('modal-areas').textContent = areas;
      document.getElementById('modal-file-link').setAttribute('href', fileUrl);
      document.getElementById('modal-orientador').textContent = orientador;


      trabalhoModal.show();
    });
  });
});
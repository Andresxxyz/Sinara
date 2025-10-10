document.addEventListener('DOMContentLoaded', function () {
  const itemsPerPage = 6;
  const eventsList = document.querySelector('.events-list');
  const paginationContainer = document.querySelector('.pagination-wrapper .pagination');

  if (!eventsList || !paginationContainer) {
    console.error("Erro: '.events-list' ou '.pagination-wrapper .pagination' não encontrados.");
    return;
  }

  const eventItems = Array.from(eventsList.querySelectorAll('.event-item'));
  if (eventItems.length === 0) {
    console.warn("Nenhum .event-item encontrado.");
    return;
  }

  const originalDisplay = eventItems.map(item => {
    const cs = window.getComputedStyle(item);
    return cs.display && cs.display !== 'none' ? cs.display : 'block';
  });

  const totalPages = Math.ceil(eventItems.length / itemsPerPage);
  let currentPage = 1;
  function showPage(page) {
    if (page < 1) page = 1;
    if (page > totalPages) page = totalPages;
    currentPage = page;

    const start = (page - 1) * itemsPerPage;
    const end = start + itemsPerPage;

    eventItems.forEach((item, idx) => {
      if (idx >= start && idx < end) {
        item.style.display = originalDisplay[idx] || '';
        item.classList.remove('hidden');
      } else {
        item.style.display = 'none';
        item.classList.add('hidden');
      }
    });

    if (typeof AOS !== 'undefined') AOS.refresh();
    updatePaginationButtons();

  }

  // const section = document.getElementById('events-2');
  // if (section) {
  //   section.scrollIntoView({ behavior: 'smooth' });
  // } else {
  //   window.scrollTo({ top: 0, behavior: 'smooth' });
  // }

  function setupPagination() {
    paginationContainer.innerHTML = '';
    if (totalPages <= 1) return;

    function makeLi(label, pageNumber = null, isArrow = false) {
      const li = document.createElement('li');
      li.className = 'page-item';
      const a = document.createElement('a');
      a.className = 'page-link';
      a.href = '#';
      a.innerHTML = label;
      li.appendChild(a);
      a.addEventListener('click', function (e) {
        const section = document.getElementById('events-2');
        if (section) {
          section.scrollIntoView({ behavior: 'smooth' });
        } else {
          window.scrollTo({ top: 0, behavior: 'smooth' });
        }
      });

      if (isArrow) {
        li.addEventListener('click', e => {
          e.preventDefault();
          if (label === '«' && currentPage > 1) showPage(currentPage - 1);
          if (label === '»' && currentPage < totalPages) showPage(currentPage + 1);
        });
      } else if (pageNumber !== null) {
        li.dataset.page = pageNumber;
        li.addEventListener('click', e => {
          e.preventDefault();
          showPage(pageNumber);
        });
      }

      return li;
    }

    paginationContainer.appendChild(makeLi('«', null, true));
    for (let i = 1; i <= totalPages; i++) {
      paginationContainer.appendChild(makeLi(i, i, false));
    }
    paginationContainer.appendChild(makeLi('»', null, true));
  }

  function updatePaginationButtons() {
    const pageItems = Array.from(paginationContainer.querySelectorAll('.page-item'));
    pageItems.forEach(li => {
      li.classList.remove('active', 'disabled');
    });

    if (pageItems.length === 0) return;
    const prev = pageItems[0];
    const next = pageItems[pageItems.length - 1];

    if (currentPage === 1) prev.classList.add('disabled');
    if (currentPage === totalPages) next.classList.add('disabled');

    pageItems.forEach(li => {
      if (li.dataset.page && Number(li.dataset.page) === currentPage) {
        li.classList.add('active');
      }
    });
  }

  setupPagination();
  showPage(1);
});

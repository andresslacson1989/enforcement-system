/**
 * app-academy-course Script
 */

'use strict';

$(function () {
  const searchInput = document.getElementById('formSearch');
  const formCards = document.querySelectorAll('#forms-grid .col');
  const categoryLinks = document.querySelectorAll('.list-group-item-action');

  // Search functionality
  searchInput.addEventListener('keyup', function () {
    const searchTerm = searchInput.value.toLowerCase();
    formCards.forEach(card => {
      const title = card.querySelector('.card-title').textContent.toLowerCase();
      const description = card.querySelector('.card-text').textContent.toLowerCase();
      if (title.includes(searchTerm) || description.includes(searchTerm)) {
        card.style.display = 'block';
      } else {
        card.style.display = 'none';
      }
    });
  });

  // Category filter functionality
  categoryLinks.forEach(link => {
    link.addEventListener('click', function (e) {
      e.preventDefault();

      // Manage active state
      categoryLinks.forEach(l => l.classList.remove('active'));
      this.classList.add('active');

      const selectedCategory = this.getAttribute('data-category');

      formCards.forEach(card => {
        if (selectedCategory === 'all' || card.getAttribute('data-category') === selectedCategory) {
          card.style.display = 'block';
        } else {
          card.style.display = 'none';
        }
      });
    });
  });
});

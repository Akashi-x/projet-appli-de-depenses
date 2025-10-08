// Fonction pour le menu déroulant
function toggleDropdown() {
  const dropdown = document.getElementById('userDropdown');
  dropdown.classList.toggle('show');
}

// Fermer le menu si on clique ailleurs
window.onclick = function(event) {
  if (!event.target.matches('.user-info') && !event.target.closest('.user-dropdown')) {
    const dropdown = document.getElementById('userDropdown');
    if (dropdown.classList.contains('show')) {
      dropdown.classList.remove('show');
    }
  }
}


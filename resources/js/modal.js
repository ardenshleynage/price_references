document.addEventListener('DOMContentLoaded', function () {

    // Ouvrir la modal
    function openModal(event) {
        if (event) event.preventDefault();
        const modal = document.getElementById('modalOverlay');
        if (modal) {
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    }
    // Fermer la modal
    function closeModal(event) {
        const modal = document.getElementById('modalOverlay');
        if (modal && (event === undefined || event.target === modal || event.target.classList.contains('modal-close'))) {
            modal.classList.remove('active');
            document.body.style.overflow = '';
        }
    }
    // Fermer avec ESC
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeModal();
        }
    });
    // Rendre les fonctions disponibles globalement
    window.openModal = openModal;
    window.closeModal = closeModal;

});

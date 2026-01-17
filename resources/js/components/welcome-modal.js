const STORAGE_KEY = 'edufocus_welcome_seen';

function showModal() {
    const modal = document.getElementById('welcome-modal');
    if (!modal) return;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function hideModal() {
    const modal = document.getElementById('welcome-modal');
    if (!modal) return;
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

window.addEventListener('DOMContentLoaded', () => {
    const force = window.location.hash === '#welcome';
    if (force || !localStorage.getItem(STORAGE_KEY)) {
        showModal();
    }

    const closeBtn = document.getElementById('welcome-modal-close');
    const continueBtn = document.getElementById('welcome-modal-continue');
    const backdrop = document.querySelector('#welcome-modal > .absolute');

    if (closeBtn) closeBtn.addEventListener('click', hideModal);
    if (backdrop) backdrop.addEventListener('click', hideModal);
    if (continueBtn) continueBtn.addEventListener('click', () => {
        localStorage.setItem(STORAGE_KEY, '1');
        hideModal();
    });
});



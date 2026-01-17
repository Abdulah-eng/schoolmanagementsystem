export function openModal({ title = '', html = '' }) {
    let root = document.getElementById('app-modal');
    if (!root) return;
    document.getElementById('app-modal-title').textContent = title;
    document.getElementById('app-modal-body').innerHTML = html;
    root.classList.remove('hidden');
}

export function closeModal() {
    const root = document.getElementById('app-modal');
    if (!root) return;
    root.classList.add('hidden');
}

if (typeof window !== 'undefined') {
    window.openModal = openModal;
    window.closeModal = closeModal;
    document.addEventListener('DOMContentLoaded', () => {
        const close = document.getElementById('app-modal-close');
        const overlay = document.getElementById('app-modal-overlay');
        close?.addEventListener('click', closeModal);
        overlay?.addEventListener('click', closeModal);
    });
}



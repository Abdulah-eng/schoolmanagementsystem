export function showToast(message, type = 'success', timeoutMs = 3000) {
    const root = document.getElementById('toast-root');
    let mount = root;
    if (!mount) {
        // create root if missing
        mount = document.createElement('div');
        mount.id = 'toast-root';
        mount.className = 'fixed z-[60] bottom-6 right-6 space-y-3 pointer-events-none';
        document.body.appendChild(mount);
    }
    const bg = type === 'success' ? 'bg-green-600' : type === 'error' ? 'bg-red-600' : 'bg-gray-800';
    const el = document.createElement('div');
    el.className = `pointer-events-auto text-white ${bg} shadow-lg rounded-lg px-4 py-3 transform translate-y-4 opacity-0 transition-all duration-200`;
    el.textContent = message;
    mount.appendChild(el);
    requestAnimationFrame(() => {
        el.classList.remove('translate-y-4','opacity-0');
    });
    setTimeout(() => {
        el.classList.add('opacity-0','translate-y-4');
        setTimeout(() => el.remove(), 200);
    }, timeoutMs);
}

// Expose globally for inline blade scripts
if (typeof window !== 'undefined') {
    window.showToast = showToast;
}



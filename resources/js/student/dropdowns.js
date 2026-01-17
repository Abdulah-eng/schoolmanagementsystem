// Student dropdown functionality
document.addEventListener('DOMContentLoaded', function() {
    // Sidebar toggle
    const sidebar = document.getElementById('student-sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    const menuBtn = document.getElementById('mobile-menu-btn');
    function openSidebar() {
        if (sidebar && overlay) {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
        }
    }
    function closeSidebar() {
        if (sidebar && overlay) {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        }
    }
    if (menuBtn) menuBtn.addEventListener('click', openSidebar);
    if (overlay) overlay.addEventListener('click', closeSidebar);

    // Quick Start dropdown
    const quickStartBtn = document.querySelector('[data-dropdown="quick-start"]');
    const quickStartMenu = document.querySelector('[data-dropdown-menu="quick-start"]');
    
    if (quickStartBtn && quickStartMenu) {
        quickStartBtn.addEventListener('click', function() {
            quickStartMenu.classList.toggle('hidden');
        });
        
        // Close when clicking outside
        document.addEventListener('click', function(event) {
            if (!quickStartBtn.contains(event.target) && !quickStartMenu.contains(event.target)) {
                quickStartMenu.classList.add('hidden');
            }
        });
    }
    
    // User dropdown
    const userBtn = document.querySelector('[data-dropdown="user"]');
    const userMenu = document.querySelector('[data-dropdown-menu="user"]');
    
    if (userBtn && userMenu) {
        userBtn.addEventListener('click', function() {
            userMenu.classList.toggle('hidden');
        });
        
        // Close when clicking outside
        document.addEventListener('click', function(event) {
            if (!userBtn.contains(event.target) && !userMenu.contains(event.target)) {
                userMenu.classList.add('hidden');
            }
        });
    }
});

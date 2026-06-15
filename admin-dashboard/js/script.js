document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('toggle-btn');
    
    // Toggle sidebar collapse on desktop
    toggleBtn.addEventListener('click', () => {
        if (window.innerWidth > 768) {
            sidebar.classList.toggle('collapsed');
        } else {
            sidebar.classList.toggle('active');
        }
    });

    // Handle window resize
    window.addEventListener('resize', () => {
        if (window.innerWidth <= 768) {
            sidebar.classList.remove('collapsed');
        } else {
            sidebar.classList.remove('active');
        }
    });
});
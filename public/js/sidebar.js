// Sidebar Toggle Function
function toggleSidebar() {
    const sidebar = document.getElementById("sidebar");
    const isMobile = window.innerWidth <= 768;

    if (isMobile) {
        // Toggle sidebar on mobile
        document.body.classList.toggle("sidebar-open");

        // Add overlay for mobile
        if (document.body.classList.contains("sidebar-open")) {
            const overlay = document.createElement('div');
            overlay.className = 'sidebar-overlay';
            overlay.onclick = function() {
                document.body.classList.remove("sidebar-open");
                this.remove();
            };
            document.body.appendChild(overlay);
        } else {
            const overlay = document.querySelector('.sidebar-overlay');
            if (overlay) overlay.remove();
        }
    } else {
        // Desktop toggle logic
        sidebar.classList.toggle("collapsed");

        if (sidebar.classList.contains("collapsed")) {
            document.body.classList.add("sidebar-collapsed");
            document.body.classList.remove("sidebar-expanded");

            // Close all open submenus when collapsing
            const openSubmenus = document.querySelectorAll('.has-submenu.open');
            openSubmenus.forEach(menu => menu.classList.remove('open'));

            // Store collapsed state in localStorage
            localStorage.setItem('sidebarCollapsed', 'true');
        } else {
            document.body.classList.add("sidebar-expanded");
            document.body.classList.remove("sidebar-collapsed");

            // Store expanded state in localStorage
            localStorage.setItem('sidebarCollapsed', 'false');
        }
    }
}

// Reset sidebar on window resize
function handleResize() {
    const sidebar = document.getElementById("sidebar");
    const overlay = document.querySelector('.sidebar-overlay');

    if (window.innerWidth <= 768) {
        // Reset desktop classes on mobile
        document.body.classList.remove("sidebar-expanded", "sidebar-collapsed");
        sidebar.classList.remove("collapsed");

        // Remove overlay if switching from desktop to mobile
        if (overlay) overlay.remove();

        // Ensure sidebar is hidden by default on mobile
        document.body.classList.remove("sidebar-open");
    } else {
        // Remove mobile overlay and classes when resizing to desktop
        document.body.classList.remove("sidebar-open");
        if (overlay) overlay.remove();

        // Apply appropriate desktop state from localStorage
        const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
        if (isCollapsed) {
            sidebar.classList.add("collapsed");
            document.body.classList.add("sidebar-collapsed");
            document.body.classList.remove("sidebar-expanded");
        } else {
            sidebar.classList.remove("collapsed");
            document.body.classList.add("sidebar-expanded");
            document.body.classList.remove("sidebar-collapsed");
        }
    }
}

// Close sidebar when clicking outside on mobile
function handleOutsideClick(e) {
    const sidebar = document.getElementById("sidebar");
    const isMobile = window.innerWidth <= 768;

    if (isMobile && document.body.classList.contains("sidebar-open")) {
        // Check if click is outside sidebar and not on toggle button
        if (!sidebar.contains(e.target) && !e.target.closest('.sidebar-toggle')) {
            document.body.classList.remove("sidebar-open");

            // Remove overlay
            const overlay = document.querySelector('.sidebar-overlay');
            if (overlay) overlay.remove();

            // Close any open submenus
            const openSubmenus = document.querySelectorAll('.has-submenu.open');
            openSubmenus.forEach(menu => menu.classList.remove('open'));
        }
    }
}

// Submenu handling for desktop
function setupSubmenuHandlers() {
    document.querySelectorAll('.has-submenu').forEach(item => {
        const menuRow = item.querySelector('.menu-row');

        menuRow.addEventListener('click', function (e) {
            e.stopPropagation();

            const sidebar = document.getElementById("sidebar");
            const isMobile = window.innerWidth <= 768;

            if (!isMobile && sidebar.classList.contains("collapsed")) {
                return;
            }

            if (!isMobile || (isMobile && document.body.classList.contains("sidebar-open"))) {

                const isOpen = item.classList.contains("open");

                document.querySelectorAll('.has-submenu.open').forEach(openItem => {
                    openItem.classList.remove('open');
                });

                if (!isOpen) {
                    item.classList.add("open");

                    if (item.dataset.menu) {
                        localStorage.setItem('openSubmenu', item.dataset.menu);
                    }
                } else {
                    item.classList.remove("open");
                    localStorage.removeItem('openSubmenu');
                }
            }
        });
    });
}
// Initialize sidebar state
function initSidebar() {
    const savedMenu = localStorage.getItem('openSubmenu');

if (savedMenu) {
    const savedItem = document.querySelector(
        `.has-submenu[data-menu="${savedMenu}"]`
    );

    if (savedItem && window.innerWidth > 768) {
        savedItem.classList.add('open');
    }
}
    const sidebar = document.getElementById("sidebar");

    // Set initial state based on screen size and saved preference
    if (window.innerWidth > 768) {
        const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
        if (isCollapsed) {
            sidebar.classList.add("collapsed");
            document.body.classList.add("sidebar-collapsed");
        } else {
            document.body.classList.add("sidebar-expanded");
        }
    } else {
        // Mobile - start with sidebar closed
        document.body.classList.remove("sidebar-open");
    }

    // Setup event listeners
    setupSubmenuHandlers();

    // Add resize listener
    window.addEventListener('resize', handleResize);

    // Add click listener for outside clicks
    document.addEventListener('click', handleOutsideClick);
}

// Admin dropdown functionality
function initAdminDropdown() {
    const btn = document.getElementById('adminMenuBtn');
    const dropdown = document.getElementById('adminDropdown');

    if (btn && dropdown) {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            dropdown.classList.toggle('show');
        });

        document.addEventListener('click', () => {
            dropdown.classList.remove('show');
        });
    }
}

// Search functionality
function initSearch() {
    const searchInput = document.querySelector('.search-box input');
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const searchTerm = this.value.trim();
                if (searchTerm) {
                    console.log('Searching for:', searchTerm);
                    // Add your search logic here
                    // Example: window.location.href = `/search?q=${encodeURIComponent(searchTerm)}`;
                }
            }
        });
    }
}

// Initialize everything when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    initSidebar();
    initAdminDropdown();
    initSearch();

    // Add keyboard shortcut for toggling sidebar (Ctrl+B or Cmd+B)
    document.addEventListener('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 'b') {
            e.preventDefault();
            toggleSidebar();
        }
    });

    // Toggle icon animation
    const toggleBtn = document.querySelector('.sidebar-toggle');
    if (toggleBtn) {
        toggleBtn.addEventListener('click', function() {
            const icon = this.querySelector('i');
            if (icon) {
                icon.style.transform = 'rotate(90deg)';
                setTimeout(() => {
                    icon.style.transform = 'rotate(0deg)';
                }, 300);
            }
        });
    }
});

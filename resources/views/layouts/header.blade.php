<header class="app-header">
  <div class="header-container">
    <!-- Desktop Toggle Button (visible on all screen sizes) -->
    <button class="sidebar-toggle" onclick="toggleSidebar()">
      <i class="fa fa-bars"></i>
    </button>

    <!-- Search -->
    <div class="search-box">
      <svg
        class="search-icon"
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 24 24"
        stroke="currentColor"
        stroke-width="2"
      >
        <circle cx="11" cy="11" r="8"></circle>
        <path d="m21 21-4.35-4.35"></path>
      </svg>

      <input
        type="text"
        placeholder="Search orders, customers, products..."
      />
    </div>

    <!-- Right -->
    <div class="header-actions">

      <!-- Notification -->
      <button class="notify-btn">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          fill="none"
          viewBox="0 0 24 24"
          stroke="currentColor"
          stroke-width="2"
        >
          <path d="M10.268 21a2 2 0 0 0 3.464 0"></path>
          <path
            d="M3.262 15.326A1 1 0 0 0 4 17h16
               a1 1 0 0 0 .74-1.673
               C19.41 13.956 18 12.499 18 8
               A6 6 0 0 0 6 8
               c0 4.499-1.411 5.956-2.738 7.326"
          ></path>
        </svg>

        <span class="badge">3</span>
      </button>

    </div>
  </div>
</header>

<?php
// Pastikan $page sudah didefinisikan di index.php sebelum include sidebar
?>
<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
  <!-- Sidebar Brand -->
  <div class="sidebar-brand">
    <a href="index.php?page=dashboard" class="brand-link">
      <img
        src="assets/img/AdminLTELogo.png"
        alt="Logo"
        class="brand-image opacity-75 shadow"
      />
      <span class="brand-text fw-light">SIP Hewan</span>
    </a>
  </div>

  <!-- Sidebar Wrapper -->
  <div class="sidebar-wrapper">
    <nav class="mt-2">
      <ul
        class="nav sidebar-menu flex-column"
        data-lte-toggle="treeview"
        role="navigation"
        aria-label="Main navigation"
        data-accordion="false"
      >
        <!-- MENU DASHBOARD -->
        <li class="nav-item">
          <a href="index.php?page=dashboard"
             class="nav-link <?php echo ($page === 'dashboard') ? 'active' : ''; ?>">
            <i class="nav-icon bi bi-speedometer2"></i>
            <p>Dashboard</p>
          </a>
        </li>

        <!-- MENU DATA (DROPDOWN) -->
        <li class="nav-item <?php echo in_array($page, ['hewan','pelanggan','layanan','transaksi']) ? 'menu-open' : ''; ?>">
          <a href="#" class="nav-link <?php echo in_array($page, ['hewan','pelanggan','layanan','transaksi']) ? 'active' : ''; ?>">
            <i class="nav-icon bi bi-archive-fill"></i>
            <p>
              Data
              <i class="nav-arrow bi bi-chevron-right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="index.php?page=hewan"
                 class="nav-link <?php echo ($page === 'hewan') ? 'active' : ''; ?>">
                <i class="nav-icon bi bi-circle"></i>
                <p>Data Hewan</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="index.php?page=pelanggan"
                 class="nav-link <?php echo ($page === 'pelanggan') ? 'active' : ''; ?>">
                <i class="nav-icon bi bi-circle"></i>
                <p>Data Pelanggan</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="index.php?page=layanan"
                 class="nav-link <?php echo ($page === 'layanan') ? 'active' : ''; ?>">
                <i class="nav-icon bi bi-circle"></i>
                <p>Jenis Layanan</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="index.php?page=transaksi"
                 class="nav-link <?php echo ($page === 'transaksi') ? 'active' : ''; ?>">
                <i class="nav-icon bi bi-circle"></i>
                <p>Transaksi Penitipan</p>
              </a>
            </li>
          </ul>
        </li>

        <!-- MENU LAPORAN -->
        <li class="nav-item">
          <a href="index.php?page=laporan"
             class="nav-link <?php echo ($page === 'laporan') ? 'active' : ''; ?>">
            <i class="nav-icon bi bi-file-earmark-bar-graph"></i>
            <p>Laporan</p>
          </a>
        </li>

        <!-- MENU LOGOUT -->
        <li class="nav-item">
          <a href="index.php?page=logout" class="nav-link text-danger">
            <i class="nav-icon bi bi-box-arrow-right"></i>
            <p>Logout</p>
          </a>
        </li>
      </ul>
    </nav>
  </div>
</aside>

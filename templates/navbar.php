<header class="topbar" data-navbarbg="skin6">
  <nav class="navbar top-navbar navbar-expand-md">
    <div class="navbar-header" data-logobg="skin6">
      <!-- This is for the sidebar toggle which is visible on mobile only -->
      <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)"><i class="ti-menu ti-close"></i></a>
      <!-- ============================================================== -->
      <!-- Logo -->
      <!-- ============================================================== -->
      <div class="navbar-brand justify-content-center">
        <!-- Logo icon -->
        <a href="?p=dashboard" class="pb-0">
          <!-- Logo text -->
          <div class="logo-text pb-0">
            <p class="h3 mb-0 font-weight-bold">SMKN 1 MANDUAMAS</p>
            <p class="h3 mb-0 font-weight-bold">V I K O R</p>
            <!-- Dark Logo text -->
            <!-- <img src="assets/images/logo-text.png" alt="homepage" class="dark-logo" /> -->
            <!-- Light Logo text -->
            <!-- <img src="assets/images/logo-light-text.png" class="light-logo" alt="homepage" /> -->
          </div>
        </a>
      </div>
      <!-- ============================================================== -->
      <!-- End Logo -->
      <!-- ============================================================== -->
      <!-- ============================================================== -->
      <!-- Toggle which is visible on mobile only -->
      <!-- ============================================================== -->
      <a class="topbartoggler d-block d-md-none waves-effect waves-light" href="javascript:void(0)" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><i class="ti-more"></i></a>
    </div>
    <!-- ============================================================== -->
    <!-- End Logo -->
    <!-- ============================================================== -->
    <div class="navbar-collapse collapse justify-content-end" id="navbarSupportedContent">
      <ul class="navbar-nav">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="javascript:void(0)" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <img src="assets/images/users/1.jpg" alt="user" class="rounded-circle" width="40">
            <span class="ml-2 d-none d-lg-inline-block"><span>Hello,</span> <span class="text-dark"><?= $_SESSION['nama'] ?? '-' ?></span>
              <i data-feather="chevron-down" class="svg-icon"></i></span>
          </a>
          <div class="dropdown-menu dropdown-menu-right user-dd animated flipInY pb-0">
            <button data-toggle="modal" data-target="#logoutModal" class="dropdown-item">
              <i data-feather="power" class="svg-icon mr-2 ml-1"></i> Logout
            </button>
          </div>
        </li>
        <!-- ============================================================== -->
        <!-- User profile and search -->
        <!-- ============================================================== -->
      </ul>
    </div>
  </nav>
</header>
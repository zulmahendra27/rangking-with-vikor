<!DOCTYPE html>
<html dir="ltr" lang="en">

<?php include_once "./control/cek_login.php"; ?>
<?php include_once "./control/helper.php"; ?>

<head>
  <?php if (isset($_GET['p'])) {
    $title = ucwords(str_replace('_', ' ', $_GET['p']));
    if ($_GET['p'] == 'penilaian_add') {
      $title = 'Penilaian';
    } elseif ($_GET['p'] == 'kepsek') {
      $title = 'Kepala Sekolah';
    }
  } else {
    $title = 'Dashboard';
  }
  ?>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">
  <!-- Favicon icon -->
  <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon.png">
  <title><?= $title ?? '' ?> - VIKOR</title>
  <!-- This page plugin CSS -->
  <link href="assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css" rel="stylesheet">
  <!-- Custom CSS -->
  <link href="assets/dist/css/style.min.css" rel="stylesheet">
  <!-- <link href="assets/libs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"> -->
  <link href="assets/libs/jquery-toast-plugin/dist/jquery.toast.min.css" rel="stylesheet">

  <style>
  @font-face {
    font-family: 'maiandra-gd-regular';
    /*memberikan nama bebas untuk font*/
    src: url('./assets/fonts/Maiandra.ttf');
    /*memanggil file font eksternalnya di folder nexa*/
  }

  body {
    font-family: 'maiandra-gd-regular', sans-serif;
  }
  </style>
</head>

<body>
  <!-- ============================================================== -->
  <!-- Preloader - style you can find in spinners.css -->
  <!-- ============================================================== -->
  <div class="preloader">
    <div class="lds-ripple">
      <div class="lds-pos"></div>
      <div class="lds-pos"></div>
    </div>
  </div>
  <!-- ============================================================== -->
  <!-- Main wrapper - style you can find in pages.scss -->
  <!-- ============================================================== -->
  <div id="main-wrapper" data-theme="light" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed" data-boxed-layout="full">
    <!-- ============================================================== -->
    <!-- Topbar header - style you can find in pages.scss -->
    <!-- ============================================================== -->

    <?php include_once "./templates/navbar.php" ?>

    <?php include_once "./templates/sidebar.php" ?>

    <div class="page-wrapper">

      <div class="page-breadcrumb">
        <div class="row">
          <div class="col-7 align-self-center">
            <h4 class="page-title text-truncate text-dark font-weight-medium mb-1"><?= $title ?? '' ?></h4>
            <div class="d-flex align-items-center">
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb m-0 p-0">
                  <li class="breadcrumb-item"><a href="?p=dashboard" class="text-muted">Home</a></li>
                  <li class="breadcrumb-item text-muted active" aria-current="page"><?= $title ?? '-' ?></li>
                </ol>
              </nav>
            </div>
          </div>
        </div>
      </div>

      <div class="container-fluid">

        <div class="row">
          <div class="col-12">

            <?php if (!isset($_GET['p'])) {
              include_once('dashboard.php');
            } else {
              include_once($_GET['p'] . '.php');
            } ?>

          </div>
        </div>

      </div>

      <!-- <footer class="footer text-center text-muted">
        All Rights Reserved by Adminmart. Designed and Developed by <a href="https://wrappixel.com">WrapPixel</a>.
      </footer> -->

    </div>
  </div>

  <?php include_once "./templates/modal.php" ?>

  <script src="assets/libs/jquery/dist/jquery.min.js"></script>
  <!-- Bootstrap tether Core JavaScript -->
  <script src="assets/libs/popper.js/dist/umd/popper.min.js"></script>
  <script src="assets/libs/bootstrap/dist/js/bootstrap.min.js"></script>
  <!-- apps -->
  <!-- apps -->
  <script src="assets/dist/js/app-style-switcher.js"></script>
  <script src="assets/dist/js/feather.min.js"></script>
  <!-- slimscrollbar scrollbar JavaScript -->
  <script src="assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script>
  <script src="assets/libs/sparkline/sparkline.js"></script>
  <!--Wave Effects -->
  <!-- themejs -->
  <!--Menu sidebar -->
  <script src="assets/dist/js/sidebarmenu.js"></script>
  <!--Custom JavaScript -->
  <script src="assets/dist/js/custom.min.js"></script>
  <!--This page plugins -->
  <script src="assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
  <!-- <script src="assets/dist/js/pages/datatable/datatable-basic.init.js"></script> -->

  <script src="assets/libs/jquery-toast-plugin/dist/jquery.toast.min.js "></script>
  <script src="assets/dist/js/alerts.js"></script>

  <script>
  $('#tabelData').dataTable();

  function changePassword(id) {
    document.getElementById('id_user_for_password').value = id;
    page = document.getElementById('page').value = '<?= $_GET['p'] ?>';
  }
  </script>

  <?php if (isset($_SESSION['alert'])) {
    echo $_SESSION['alert'];
    unset($_SESSION['alert']);
  } ?>
</body>

</html>
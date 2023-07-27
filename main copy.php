<!doctype html>
<html class="no-js" lang="en">

<?php include_once "./control/cek_login.php"; ?>
<?php include_once "./control/helper.php"; ?>

<head>
  <?php if (isset($_GET['p'])) {
    $title = ucwords($_GET['p']);
    if ($_GET['p'] == 'penilaian_add') {
      $title = 'Penilaian';
    }
  } else {
    $title = 'Dashboard';
  }
  ?>

  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title><?= $title ?> - SMP Muhammadiyah 6 Padang</title>
  <meta name="description" content="">
  <meta name="keywords" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="icon" href="images/smp.ico" type="image/x-icon" />

  <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:300,400,600,700,800" rel="stylesheet">

  <link rel="stylesheet" href="assets/plugins/bootstrap/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="assets/plugins/ionicons/dist/css/ionicons.min.css">
  <link rel="stylesheet" href="assets/plugins/icon-kit/dist/css/iconkit.min.css">
  <link rel="stylesheet" href="assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css">
  <link rel="stylesheet" href="assets/plugins/jquery-toast-plugin/dist/jquery.toast.min.css">
  <!-- <link rel="stylesheet" href="assets/plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css"> -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap4.min.css">

  <link rel="stylesheet" href="assets/dist/css/theme.min.css">
  <script src="assets/src/js/vendor/modernizr-2.8.3.min.js"></script>
</head>

<body>
  <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

  <div class="wrapper">

    <?php include_once "templates/navbar.php" ?>

    <div class="page-wrap">

      <?php include_once "templates/sidebar.php" ?>

      <div class="main-content">
        <div class="container-fluid">

          <?php if (!isset($_GET['p'])) {
            include_once('dashboard.php');
          } else {
            include_once($_GET['p'] . '.php');
          } ?>

        </div>
      </div>

      <aside class="right-sidebar">
        <div class="sidebar-chat" data-plugin="chat-sidebar">
          <div class="sidebar-chat-info">
            <h6>Chat List</h6>
            <form class="mr-t-10">
              <div class="form-group">
                <input type="text" class="form-control" placeholder="Search for friends ...">
                <i class="ik ik-search"></i>
              </div>
            </form>
          </div>
          <div class="chat-list">
            <div class="list-group row">
              <a href="javascript:void(0)" class="list-group-item" data-chat-user="Gene Newman">
                <figure class="user--online">
                  <img src="images/users/1.jpg" class="rounded-circle" alt="">
                </figure><span><span class="name">Gene Newman</span> <span class="username">@gene_newman</span> </span>
              </a>
              <a href="javascript:void(0)" class="list-group-item" data-chat-user="Billy Black">
                <figure class="user--online">
                  <img src="images/users/2.jpg" class="rounded-circle" alt="">
                </figure><span><span class="name">Billy Black</span> <span class="username">@billyblack</span> </span>
              </a>
              <a href="javascript:void(0)" class="list-group-item" data-chat-user="Herbert Diaz">
                <figure class="user--online">
                  <img src="images/users/3.jpg" class="rounded-circle" alt="">
                </figure><span><span class="name">Herbert Diaz</span> <span class="username">@herbert</span> </span>
              </a>
              <a href="javascript:void(0)" class="list-group-item" data-chat-user="Sylvia Harvey">
                <figure class="user--busy">
                  <img src="images/users/4.jpg" class="rounded-circle" alt="">
                </figure><span><span class="name">Sylvia Harvey</span> <span class="username">@sylvia</span> </span>
              </a>
              <a href="javascript:void(0)" class="list-group-item active" data-chat-user="Marsha Hoffman">
                <figure class="user--busy">
                  <img src="images/users/5.jpg" class="rounded-circle" alt="">
                </figure><span><span class="name">Marsha Hoffman</span> <span class="username">@m_hoffman</span> </span>
              </a>
              <a href="javascript:void(0)" class="list-group-item" data-chat-user="Mason Grant">
                <figure class="user--offline">
                  <img src="images/users/1.jpg" class="rounded-circle" alt="">
                </figure><span><span class="name">Mason Grant</span> <span class="username">@masongrant</span> </span>
              </a>
              <a href="javascript:void(0)" class="list-group-item" data-chat-user="Shelly Sullivan">
                <figure class="user--offline">
                  <img src="images/users/2.jpg" class="rounded-circle" alt="">
                </figure><span><span class="name">Shelly Sullivan</span> <span class="username">@shelly</span></span>
              </a>
            </div>
          </div>
        </div>
      </aside>

      <div class="chat-panel" hidden>
        <div class="card">
          <div class="card-header d-flex justify-content-between">
            <a href="javascript:void(0);"><i class="ik ik-message-square text-success"></i></a>
            <span class="user-name">John Doe</span>
            <button type="button" class="close" aria-label="Close"><span aria-hidden="true">×</span></button>
          </div>
          <div class="card-body">
            <div class="widget-chat-activity flex-1">
              <div class="messages">
                <div class="message media reply">
                  <figure class="user--online">
                    <a href="#">
                      <img src="images/users/3.jpg" class="rounded-circle" alt="">
                    </a>
                  </figure>
                  <div class="message-body media-body">
                    <p>Epic Cheeseburgers come in all kind of styles.</p>
                  </div>
                </div>
                <div class="message media">
                  <figure class="user--online">
                    <a href="#">
                      <img src="images/users/1.jpg" class="rounded-circle" alt="">
                    </a>
                  </figure>
                  <div class="message-body media-body">
                    <p>Cheeseburgers make your knees weak.</p>
                  </div>
                </div>
                <div class="message media reply">
                  <figure class="user--offline">
                    <a href="#">
                      <img src="images/users/5.jpg" class="rounded-circle" alt="">
                    </a>
                  </figure>
                  <div class="message-body media-body">
                    <p>Cheeseburgers will never let you down.</p>
                    <p>They'll also never run around or desert you.</p>
                  </div>
                </div>
                <div class="message media">
                  <figure class="user--online">
                    <a href="#">
                      <img src="images/users/1.jpg" class="rounded-circle" alt="">
                    </a>
                  </figure>
                  <div class="message-body media-body">
                    <p>A great cheeseburger is a gastronomical event.</p>
                  </div>
                </div>
                <div class="message media reply">
                  <figure class="user--busy">
                    <a href="#">
                      <img src="images/users/5.jpg" class="rounded-circle" alt="">
                    </a>
                  </figure>
                  <div class="message-body media-body">
                    <p>There's a cheesy incarnation waiting for you no matter what you palete preferences are.</p>
                  </div>
                </div>
                <div class="message media">
                  <figure class="user--online">
                    <a href="#">
                      <img src="images/users/1.jpg" class="rounded-circle" alt="">
                    </a>
                  </figure>
                  <div class="message-body media-body">
                    <p>If you are a vegan, we are sorry for you loss.</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <form action="javascript:void(0)" class="card-footer" method="post">
            <div class="d-flex justify-content-end">
              <textarea class="border-0 flex-1" rows="1" placeholder="Type your message here"></textarea>
              <button class="btn btn-icon" type="submit"><i class="ik ik-arrow-right text-success"></i></button>
            </div>
          </form>
        </div>
      </div>

      <footer class="footer">
        <div class="w-100 clearfix">
          <span class="text-center text-sm-left d-md-inline-block">Copyright © 2018 SMP 6 Muhammadiyah Padang. All
            Rights
            Reserved.</span>
        </div>
      </footer>

    </div>
  </div>

  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="logoutModalLabel">Keluar?</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
              aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
          <p>Apakah anda ingin keluar dari sistem?</p>
          <p>Setelah keluar, anda dapat masuk kembali dengan akun masing-masing.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <a href="logout.php" class="btn btn-warning">Keluar</a>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteModalLabel">Hapus Data?</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
              aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
          <p>Apakah anda ingin menghapus data ini?</p>
          <p>Data yang sudah dihapus tidak dapat dikembalikan.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <a id="delButton" class="btn btn-primary text-white">Hapus</a>
        </div>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
  <script>
  window.jQuery || document.write('<script src="assets/src/js/vendor/jquery-3.3.1.min.js"><\/script>')
  </script>
  <script src="assets/plugins/popper.js/dist/umd/popper.min.js"></script>
  <script src="assets/plugins/bootstrap/dist/js/bootstrap.min.js"></script>
  <script src="assets/plugins/perfect-scrollbar/dist/perfect-scrollbar.min.js"></script>
  <script src="assets/plugins/screenfull/dist/screenfull.js"></script>
  <script src="assets/plugins/jquery-toast-plugin/dist/jquery.toast.min.js"></script>
  <!-- <script src="assets/plugins/datatables.net/js/jquery.dataTables.min.js"></script> -->
  <!-- <script src="assets/plugins/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script> -->
  <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap4.min.js"></script>

  <script src="assets/dist/js/theme.min.js"></script>
  <script src="assets/dist/js/alerts.js"></script>

  <?php if (isset($_SESSION['alert'])) {
    echo $_SESSION['alert'];
    unset($_SESSION['alert']);
  } ?>

  <script>
  $(document).ready(function() {
    $('#tabelData').DataTable();
    $('#tabelData2').DataTable();
  });
  </script>

</body>

</html>
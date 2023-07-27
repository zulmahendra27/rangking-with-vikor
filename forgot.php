<?php
session_start();
?>

<!DOCTYPE html>
<html dir="ltr">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">
  <!-- Favicon icon -->
  <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon.png">
  <title>Lupa Password</title>
  <!-- Custom CSS -->
  <link href="assets/dist/css/style.min.css" rel="stylesheet">
  <link href="assets/libs/jquery-toast-plugin/dist/jquery.toast.min.css" rel="stylesheet">
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
  <style>
    @font-face {
      font-family: 'maiandra-gd-regular';
      /*memberikan nama bebas untuk font*/
      src: url('./assets/fonts/Maiandra.ttf');
      /*memanggil file font eksternalnya di folder nexa*/
    }

    body {
      font-family: 'maiandra-gd-regular', sans-serif;
      background-image: url('img/login_image.jpg');
      background-position: center;
      background-repeat: no-repeat;
      background-size: cover;

    }
  </style>
</head>

<body>
  <div class="main-wrapper" style="background-color: rgba(255, 255, 255, 0.4);">
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
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Login box.scss -->
    <!-- ============================================================== -->
    <div class="auth-wrapper d-flex no-block justify-content-center align-items-center position-relative">
      <div class="row p-4 justify-content-center">
        <div class="col-lg-10 col-md-7" style="background-color: #7FEDF1;">
          <div class="p-3">
            <h2 class="mt-3 text-center font-weight-medium text-dark">Lupa Kata Sandi</h2>
            <form method="post" action="./change_password.php" class="mt-4">
              <div class="row">
                <div class="col-lg-12">
                  <div class="form-group">
                    <label class="text-dark" for="username">Masukkan Username atau NIP untuk login</label>
                    <input class="form-control" name="username" id="username" type="text" placeholder="Username" required>
                  </div>
                </div>
                <div class="col-lg-12 text-center">
                  <button type="submit" name="check_username" class="btn btn-block btn-primary">Cek Username</button>
                </div>

              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- ============================================================== -->
    <!-- Login box.scss -->
    <!-- ============================================================== -->
  </div>
  <!-- ============================================================== -->
  <!-- All Required js -->
  <!-- ============================================================== -->
  <script src="assets/libs/jquery/dist/jquery.min.js "></script>
  <!-- Bootstrap tether Core JavaScript -->
  <script src="assets/libs/popper.js/dist/umd/popper.min.js "></script>
  <script src="assets/libs/bootstrap/dist/js/bootstrap.min.js "></script>
  <script src="assets/libs/jquery-toast-plugin/dist/jquery.toast.min.js "></script>
  <script src="assets/dist/js/alerts.js"></script>

  <?php if (isset($_SESSION['alert'])) {
    echo $_SESSION['alert'];
    unset($_SESSION['alert']);
  } ?>
  <!-- ============================================================== -->
  <!-- This page plugin js -->
  <!-- ============================================================== -->
  <script>
    $(".preloader ").fadeOut();
  </script>
</body>

</html>
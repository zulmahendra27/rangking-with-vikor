<?php
$joinGuru = "INNER JOIN users b ON a.id_user=b.id_user";
$username = $_SESSION['username'];
$queryGuru = select($c, 'guru a', ['join' => $joinGuru, 'where' => "b.username=$username", 'select' => "a.*"]);
$id_guru = mysqli_fetch_assoc($queryGuru)['id_guru'];

$hariIni = date("Y-m-d");
$whereAbsensiGuru = "id_guru=$id_guru AND LEFT(waktu, 10)=$hariIni";
$countAbsensiGuru = select($c, 'absensiguru', ['select' => "COUNT(*) AS jumlah", 'where' => $whereAbsensiGuru]);
?>

<style>
  div.h1>svg {
    height: 200px;
    width: 200px;
  }
</style>
<div class="card overflow-hidden" style="border-radius: 7px;">
  <div class="card-header bg-success px-4 py-3 d-flex justify-content-between align-items-center">
    <h4 class="card-title m-0 text-white"><?= $title ?? '' ?></h4>
  </div>
  <div class="card-body text-center">
    <?php if (mysqli_fetch_assoc($countAbsensiGuru)['jumlah'] <= 0) : ?>
      <h3 class="text-dark font-weight-bold mb-4">Silahkan tekan tombol di bawah untuk melakukan absensi pada hari ini.
      </h3>

      <form action="./control/aksi_insert.php" method="post" class="table-responsive">

        <div class="text-dark">
          <div id="tanggal" class="h4 mb-4">
            Tanggal Hari Ini<div id="date" class="h3 font-weight-bold font-weight-bold"></div>
          </div>
          <div id="waktu" class="h4 mb-4">
            Waktu Sekarang<div id="clock" class="h3 font-weight-bold font-weight-bold"></div>
          </div>
        </div>

        <input type="hidden" name="id_guru" value="<?= $id_guru ?>">
        <div class="form-group">
          <button type="submit" name="absensi_guru" class="btn btn-info">Isi Absensi</button>
        </div>

      </form>
    <?php else : ?>
      <div class="text-center">
        <h2 class="text-dark font-weight-bold">Anda Sudah Mengisi Absensi Hari Ini.</h2>
        <div class="h1 mt-4">
          <i data-feather="check-circle" class="feather-icon text-success"></i>
        </div>
      </div>
    <?php endif; ?>
  </div>
</div>
<script>
  function displayTime() {
    var currentTime = new Date();
    var options = {
      timeZone: "Asia/Jakarta",
      hour12: false,
      hour: "numeric",
      minute: "2-digit",
      second: "2-digit"
    };
    var timeString = currentTime.toLocaleTimeString(undefined, options);
    document.getElementById("clock").innerHTML = timeString;
  }

  function displayDate() {
    var currentDate = new Date();
    var options = {
      timeZone: "Asia/Jakarta",
      day: "2-digit",
      month: "2-digit",
      year: "numeric"
    };
    var dateParts = currentDate.toLocaleDateString(undefined, options).split('/');
    var formattedDate = dateParts[1] + "-" + dateParts[0] + "-" + dateParts[2];
    document.getElementById("date").innerHTML = formattedDate;
  }

  // Perbarui waktu setiap detik (1000 ms)
  setInterval(displayTime, 1000);

  // Perbarui tanggal setiap detik (1000 ms)
  setInterval(displayDate, 1000);
</script>
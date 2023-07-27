<?php
$joinSiswa = "INNER JOIN users b ON a.id_user=b.id_user";
$username = $_SESSION['username'];
$querySiswa = select($c, 'siswa a', ['join' => $joinSiswa, 'where' => "b.username=$username", 'select' => "a.*"]);
$id_siswa = mysqli_fetch_assoc($querySiswa)['id_siswa'];

$countNilaiBySiswa = select($c, 'nilaiguru', ['select' => "COUNT(id_nilaiguru) AS jumlahbysiswa", 'where' => "id_siswa=$id_siswa"]);
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
  <div class="card-body">
    <?php if (mysqli_fetch_assoc($countNilaiBySiswa)['jumlahbysiswa'] <= 0) : ?>
    <h4 class="text-dark">Silahkan lakukan penilaian terhadap guru sesuai kriteria yang ada.</h4>

    <form action="./control/aksi_insert.php" method="post" class="table-responsive">
      <?php
        // $queryGuru = select($c, 'guru');
        $queryGuru = select($c, 'guru a', ['join' => 'INNER JOIN users b ON a.id_user=b.id_user', 'where' => "b.level='guru'"]);
        $queryKriteria = select($c, 'kriteria');
        ?>

      <div class="form-group">
        <button type="submit" name="penilaian_guru" class="btn btn-info">Simpan Penilaian</button>
      </div>

      <table width="100%" class="small table table-striped table-bordered nowrap">
        <thead class="text-center align-items-center">
          <tr>
            <th rowspan="2">No</th>
            <th rowspan="2">Nama Guru</th>
            <th colspan="<?= $queryKriteria->num_rows ?>">Kriteria Penilaian</th>
          </tr>
          <tr>
            <?php
              if ($queryKriteria->num_rows > 0) :
                foreach ($queryKriteria as $kriteria) :
              ?>
            <th class="text-wrap"><?= $kriteria['nama_kriteria'] ?></th>
            <?php endforeach;
              endif; ?>
          </tr>
        </thead>
        <tbody>
          <?php $i = 1;
            if ($queryGuru->num_rows > 0) :
              while ($data = mysqli_fetch_assoc($queryGuru)) : ?>
          <tr>
            <td><?= $i ?></td>
            <td><?= $data['nama_guru'] ?></td>
            <?php
                  if ($queryKriteria->num_rows > 0) :
                    foreach ($queryKriteria as $key => $kriteria) :
                  ?>
            <td>
              <select name="kriteria-<?= $key ?>[]" class="form-control form-control-sm">
                <option value="5">Baik Sekali</option>
                <option value="4">Baik</option>
                <option value="3">Cukup</option>
                <option value="2">Kurang</option>
                <option value="1">Kurang Sekali</option>
              </select>
            </td>
            <?php endforeach;
                  endif; ?>
          </tr>
          <?php $i++;
              endwhile;
            endif; ?>
        </tbody>
      </table>
      <div class="form-group">
        <button type="submit" name="penilaian_guru" class="btn btn-info">Simpan Penilaian</button>
      </div>
    </form>
    <?php else : ?>
    <div class="text-center">
      <h4 class="text-dark">Anda sudah melakukan Penilaian Guru.</h4>
      <div class="h1 mt-4">
        <i data-feather="check-circle" class="feather-icon text-success"></i>
      </div>
    </div>
    <?php endif; ?>
  </div>
</div>
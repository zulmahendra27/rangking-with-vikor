<?php
$joinSiswa = "INNER JOIN users b ON a.id_user=b.id_user";
$username = $_SESSION['username'];
$querySiswa = select($c, 'siswa a', ['join' => $joinSiswa, 'where' => "b.username=$username", 'select' => "a.*"]);
$id_siswa = mysqli_fetch_assoc($querySiswa)['id_siswa'];

$queryNilai = select($c, 'nilaidarisiswa', ['where' => "id_siswa=$id_siswa"]);
// $queryGuru = select($c, 'guru');
$queryGuru = select($c, 'guru a', ['join' => 'INNER JOIN users b ON a.id_user=b.id_user', 'where' => "b.level='guru'"]);
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
    <h4 class="text-dark font-weight-500">Kuesioner Penilaian Kinerja Guru</h4>
    <div class="row">
      <div class="col-lg-6">
        <table class="small table table-striped table-bordered text-dark">
          <tr>
            <th>C3</th>
            <th>PROFESSIONAL</th>
          </tr>
          <tr>
            <td>C3.1</td>
            <td>Guru mampu menguasai teori pelajaran dan siswa mudah mengerti</td>
          </tr>
          <tr>
            <td>C3.2</td>
            <td>Guru adil dan tidak memandang perbedaan</td>
          </tr>
        </table>
      </div>
      <div class="col-lg-6">
        <table class="small table table-striped table-bordered text-dark">
          <tr>
            <th>C5</th>
            <th>SOSIAL</th>
          </tr>
          <tr>
            <td>C5.1</td>
            <td>Guru mudah dihubungi ketika diperlukan dalam diskusi</td>
          </tr>
          <tr>
            <td>C5.2</td>
            <td>Guru mudah berbaur dengan siswa</td>
          </tr>
        </table>
      </div>
    </div>
    <h4 class="text-dark font-weight-500">Tingkat Penilaian</h4>
    <div class="row mb-4">
      <div class="col-lg-4">
        <table class="small table table-striped table-bordered text-dark">
          <tr>
            <th>5</th>
            <th>SELALU</th>
          </tr>
          <tr>
            <th>4</th>
            <th>SERING</th>
          </tr>
          <tr>
            <th>3</th>
            <th>KADANG-KADANG</th>
          </tr>
          <tr>
            <th>2</th>
            <th>JARANG</th>
          </tr>
          <tr>
            <th>1</th>
            <th>TIDAK PERNAH</th>
          </tr>
        </table>
      </div>
    </div>

    <form action="./control/aksi_insert.php" method="post">
      <div class="form-group">
        <label for="id_guru">Pilih Guru</label>
        <select name="id_guru" id="id_guru" class="form-control col-lg-4" onchange="changeGuru(event)">
          <option value="-1">-- Pilih Guru --</option>
          <?php
          if ($queryGuru->num_rows > 0) :
            foreach ($queryGuru as $guru) :
              $selected = isset($_GET['g']) ? ($_GET['g'] == $guru['id_guru'] ? 'selected' : '') : '';
          ?>
              <option value="<?= $guru['id_guru'] ?>" <?= $selected ?>><?= $guru['nama_guru'] ?></option>
          <?php endforeach;
          endif; ?>
        </select>
      </div>

      <table width="100%" class="small table table-bordered table-striped">
        <thead>
          <tr>
            <th>C3</th>
            <th colspan="2">PROFESSIONAL</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>C3.1</td>
            <td>Guru mampu menguasai teori pelajaran dan siswa mudah mengerti</td>
            <td>
              <select name="C31" class="form-control form-control-sm">
                <?php for ($i = 5; $i > 0; $i--) : ?>
                  <option value="<?= $i ?>"><?= $i ?></option>
                <?php endfor; ?>
              </select>
            </td>
          </tr>
          <tr>
            <td>C3.2</td>
            <td>Guru adil dan tidak memandang perbedaan</td>
            <td>
              <select name="C32" class="form-control form-control-sm">
                <?php for ($i = 5; $i > 0; $i--) : ?>
                  <option value="<?= $i ?>"><?= $i ?></option>
                <?php endfor; ?>
              </select>
            </td>
          </tr>
        </tbody>
      </table>

      <table width="100%" class="small table table-bordered table-striped">
        <thead>
          <tr>
            <th>C5</th>
            <th colspan="2">SOSIAL</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>C5.1</td>
            <td>Guru mudah dihubungi ketika diperlukan dalam diskusi</td>
            <td>
              <select name="C51" class="form-control form-control-sm">
                <?php for ($i = 5; $i > 0; $i--) : ?>
                  <option value="<?= $i ?>"><?= $i ?></option>
                <?php endfor; ?>
              </select>
            </td>
          </tr>
          <tr>
            <td>C5.2</td>
            <td>Guru mudah berbaur dengan siswa</td>
            <td>
              <select name="C52" class="form-control form-control-sm">
                <?php for ($i = 5; $i > 0; $i--) : ?>
                  <option value="<?= $i ?>"><?= $i ?></option>
                <?php endfor; ?>
              </select>
            </td>
          </tr>
        </tbody>
      </table>

      <div class="form-group">
        <button type="submit" name="kinerja_guru" class="btn btn-info">Simpan Penilaian</button>
      </div>
    </form>

    <?php if ($queryNilai->num_rows > 0) : ?>
      <div class="alert alert-success mb-4 d-flex justify-content-center align-items-center" style="gap: 10px;">
        <div class="h5">
          <i data-feather="check-circle" class="feather-icon text-success mr-2"></i>
        </div>
        <span>
          Anda sudah melakukan Penilaian Guru. Untuk melakukan penilaian ulang, silahkan mengganti nilai sesuai kriteria
          penilaian, lalu simpan dengan menekan tombol "Simpan Penilaian".
        </span>
      </div>
    <?php endif; ?>
    <h4 class="text-dark">Silahkan lakukan penilaian terhadap guru sesuai kriteria yang ada.</h4>

    <form action="./control/aksi_insert.php" method="post" class="table-responsive">

      <div class="form-group">
        <button type="submit" name="kinerja_guru" class="btn btn-info">Simpan Penilaian</button>
      </div>

      <table width="100%" class="small table table-striped table-bordered nowrap">
        <thead class="text-center align-items-center">
          <tr>
            <th rowspan="2">No</th>
            <th rowspan="2">Nama Guru</th>
            <th colspan="4">Kriteria Penilaian</th>
          </tr>
          <tr>
            <th class="text-wrap">C3.1</th>
            <th class="text-wrap">C3.2</th>
            <th class="text-wrap">C5.1</th>
            <th class="text-wrap">C5.2</th>
          </tr>
        </thead>
        <tbody>
          <?php $i = 1;
          if ($queryGuru->num_rows > 0) :
            foreach ($queryGuru as $data) : ?>
              <tr>
                <td><?= $i ?></td>
                <td><?= $data['nama_guru'] ?></td>
                <?php
                $array = ['C31', 'C32', 'C51', 'C52'];
                for ($j = 0; $j < 4; $j++) :
                ?>
                  <td>
                    <select name="<?= $array[$j] ?>[]" class="form-control form-control-sm">
                      <?php
                      for ($k = 5; $k > 0; $k--) :
                        foreach ($queryNilai as $nilai) :
                          if ($nilai['id_guru'] == $data['id_guru']) :
                      ?>
                            <option value="<?= $k ?>" <?= $nilai[$array[$j]] == $k ? 'selected' : '' ?>><?= $k ?></option>
                      <?php endif;
                        endforeach;
                      endfor; ?>
                    </select>
                  </td>
                <?php endfor; ?>
              </tr>
          <?php $i++;
            endforeach;
          endif; ?>
        </tbody>
      </table>
      <div class="form-group">
        <button type="submit" name="kinerja_guru" class="btn btn-info">Simpan Penilaian</button>
      </div>
    </form>

  </div>
</div>

<script>
  function changeGuru(e) {
    if (e.target.value == -1) {
      window.location = '?p=penilaian_kinerja_guru';
    } else {
      window.location = '?p=penilaian_kinerja_guru&g=' + e.target.value;
    }
  }
</script>
<?php
$joinSiswa = "INNER JOIN users b ON a.id_user=b.id_user";
$username = $_SESSION['username'];
$querySiswa = select($c, 'siswa a', ['join' => $joinSiswa, 'where' => "b.username=$username", 'select' => "a.*"]);
$id_siswa = mysqli_fetch_assoc($querySiswa)['id_siswa'];

$querySemesterActive = select($c, 'semester', ['where' => "status=1"]);
$semesterActive = mysqli_fetch_assoc($querySemesterActive);
$id_semester_active = $semesterActive['id_semester'];

$queryNilai = select($c, 'nilaidarisiswa', ['where' => "id_siswa=$id_siswa AND id_semester=$id_semester_active"]);
// $queryGuru = select($c, 'guru');
$queryGuru = select($c, 'guru a', ['join' => 'INNER JOIN users b ON a.id_user=b.id_user', 'where' => "b.level='guru'"]);

if (isset($_GET['g'])) {
  $checkGuru = select($c, 'guru', ['where' => 'id_guru=' . $_GET['g']]);
  $checkNilaiGuru = select($c, 'nilaidarisiswa', ['where' => "id_siswa=$id_siswa AND id_guru=" . $_GET['g'] . " AND id_semester=$id_semester_active"]);
  if ($checkNilaiGuru->num_rows > 0) {
    $nilaiGuru = mysqli_fetch_assoc($checkNilaiGuru);

    $C31 = $nilaiGuru['C31'];
    $C32 = $nilaiGuru['C32'];
    $C33 = $nilaiGuru['C33'];
    $C34 = $nilaiGuru['C34'];
    $C35 = $nilaiGuru['C35'];
    $C36 = $nilaiGuru['C36'];
    $C37 = $nilaiGuru['C37'];
    $C51 = $nilaiGuru['C51'];
    $C52 = $nilaiGuru['C52'];
    $C53 = $nilaiGuru['C53'];
    $C54 = $nilaiGuru['C54'];
    $C55 = $nilaiGuru['C55'];
    $C56 = $nilaiGuru['C56'];
    $C57 = $nilaiGuru['C57'];
  } else {
    $C31 = '';
    $C32 = '';
    $C33 = '';
    $C34 = '';
    $C35 = '';
    $C36 = '';
    $C37 = '';
    $C51 = '';
    $C52 = '';
    $C53 = '';
    $C54 = '';
    $C55 = '';
    $C56 = '';
    $C57 = '';
  }


  if ($checkGuru->num_rows <= 0) {
    echo "<script>alert('Guru yang dipilih tidak ditemukan');</script>";
    echo "<script>window.location='?p=penilaian_kinerja_guru';</script>";
  }
}
?>

<style>
  div.h1>svg {
    height: 200px;
    width: 200px;
  }
</style>
<div class="card overflow-hidden" style="border-radius: 7px;">
  <div class="card-header bg-secondary px-4 py-3 d-flex justify-content-between align-items-center">
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
            <td>Guru mampu menguasai dan menyampaikan teori pelajaran hingga siswa mudah mengerti</td>
          </tr>
          <tr>
            <td>C3.2</td>
            <td>Guru adil dan tidak memandang perbedaan</td>
          </tr>
          <tr>
            <td>C3.3</td>
            <td>Guru terampil dalam menggunakan alat bantu saat mengajar</td>
          </tr>
          <tr>
            <td>C3.4</td>
            <td>Guru mengajar sesuai dengan materi pelajaran</td>
          </tr>
          <tr>
            <td>C3.5</td>
            <td>Guru akan tetap memberikan tugas ketika berhalangan hadir</td>
          </tr>
          <tr>
            <td>C3.6</td>
            <td>Guru mengajar dengan cara yang bervariasi, sehingga tidak membosankan bagi siswa</td>
          </tr>
          <tr>
            <td>C3.7</td>
            <td>Guru mampu membimbing siswa ketika mengalami kesulitan</td>
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
          <tr>
            <td>C5.3</td>
            <td>Guru mampu memberi arahan kepada siswa</td>
          </tr>
          <tr>
            <td>C5.4</td>
            <td>Guru memperhatikan perkembangan belajar siswa</td>
          </tr>
          <tr>
            <td>C5.5</td>
            <td>Guru mampu menjawab pertanyaan siswa dengan benar</td>
          </tr>
          <tr>
            <td>C5.6</td>
            <td>Guru mampu berkomunikasi dengan orang tua/wali</td>
          </tr>
          <tr>
            <td>C5.7</td>
            <td>Guru mampu membangun suasana kelas menjadi nyaman</td>
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

      <?php if (isset($_GET['g'])) : ?>

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
              <td>Guru mampu menguasai dan menyampaikan teori pelajaran hingga siswa mudah mengerti</td>
              <td>
                <select name="C31" class="form-control form-control-sm">
                  <?php for ($i = 5; $i > 0; $i--) : ?>
                    <option value="<?= $i ?>" <?= $C31 == $i ? 'selected' : '' ?>><?= $i ?></option>
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
                    <option value="<?= $i ?>" <?= $C32 == $i ? 'selected' : '' ?>><?= $i ?></option>
                  <?php endfor; ?>
                </select>
              </td>
            </tr>
            <tr>
              <td>C3.3</td>
              <td>Guru terampil dalam menggunakan alat bantu saat mengajar</td>
              <td>
                <select name="C33" class="form-control form-control-sm">
                  <?php for ($i = 5; $i > 0; $i--) : ?>
                    <option value="<?= $i ?>" <?= $C33 == $i ? 'selected' : '' ?>><?= $i ?></option>
                  <?php endfor; ?>
                </select>
              </td>
            </tr>
            <tr>
              <td>C3.4</td>
              <td>Guru mengajar sesuai dengan materi pelajaran</td>
              <td>
                <select name="C34" class="form-control form-control-sm">
                  <?php for ($i = 5; $i > 0; $i--) : ?>
                    <option value="<?= $i ?>" <?= $C34 == $i ? 'selected' : '' ?>><?= $i ?></option>
                  <?php endfor; ?>
                </select>
              </td>
            </tr>
            <tr>
              <td>C3.5</td>
              <td>Guru akan tetap memberikan tugas ketika berhalangan hadir</td>
              <td>
                <select name="C35" class="form-control form-control-sm">
                  <?php for ($i = 5; $i > 0; $i--) : ?>
                    <option value="<?= $i ?>" <?= $C35 == $i ? 'selected' : '' ?>><?= $i ?></option>
                  <?php endfor; ?>
                </select>
              </td>
            </tr>
            <tr>
              <td>C3.6</td>
              <td>Guru mengajar dengan cara yang bervariasi, sehingga tidak membosankan bagi siswa</td>
              <td>
                <select name="C36" class="form-control form-control-sm">
                  <?php for ($i = 5; $i > 0; $i--) : ?>
                    <option value="<?= $i ?>" <?= $C36 == $i ? 'selected' : '' ?>><?= $i ?></option>
                  <?php endfor; ?>
                </select>
              </td>
            </tr>
            <tr>
              <td>C3.7</td>
              <td>Guru mampu membimbing siswa ketika mengalami kesulitan</td>
              <td>
                <select name="C37" class="form-control form-control-sm">
                  <?php for ($i = 5; $i > 0; $i--) : ?>
                    <option value="<?= $i ?>" <?= $C37 == $i ? 'selected' : '' ?>><?= $i ?></option>
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
                    <option value="<?= $i ?>" <?= $C51 == $i ? 'selected' : '' ?>><?= $i ?></option>
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
                    <option value="<?= $i ?>" <?= $C52 == $i ? 'selected' : '' ?>><?= $i ?></option>
                  <?php endfor; ?>
                </select>
              </td>
            </tr>
            <tr>
              <td>C5.3</td>
              <td>Guru mampu memberi arahan kepada siswa</td>
              <td>
                <select name="C53" class="form-control form-control-sm">
                  <?php for ($i = 5; $i > 0; $i--) : ?>
                    <option value="<?= $i ?>" <?= $C53 == $i ? 'selected' : '' ?>><?= $i ?></option>
                  <?php endfor; ?>
                </select>
              </td>
            </tr>
            <tr>
              <td>C5.4</td>
              <td>Guru memperhatikan perkembangan belajar siswa</td>
              <td>
                <select name="C54" class="form-control form-control-sm">
                  <?php for ($i = 5; $i > 0; $i--) : ?>
                    <option value="<?= $i ?>" <?= $C54 == $i ? 'selected' : '' ?>><?= $i ?></option>
                  <?php endfor; ?>
                </select>
              </td>
            </tr>
            <tr>
              <td>C5.5</td>
              <td>Guru mampu menjawab pertanyaan siswa dengan benar</td>
              <td>
                <select name="C55" class="form-control form-control-sm">
                  <?php for ($i = 5; $i > 0; $i--) : ?>
                    <option value="<?= $i ?>" <?= $C55 == $i ? 'selected' : '' ?>><?= $i ?></option>
                  <?php endfor; ?>
                </select>
              </td>
            </tr>
            <tr>
              <td>C5.6</td>
              <td>Guru mampu berkomunikasi dengan orang tua/wali</td>
              <td>
                <select name="C56" class="form-control form-control-sm">
                  <?php for ($i = 5; $i > 0; $i--) : ?>
                    <option value="<?= $i ?>" <?= $C56 == $i ? 'selected' : '' ?>><?= $i ?></option>
                  <?php endfor; ?>
                </select>
              </td>
            </tr>
            <tr>
              <td>C5.7</td>
              <td>Guru mampu membangun suasana kelas menjadi nyaman</td>
              <td>
                <select name="C57" class="form-control form-control-sm">
                  <?php for ($i = 5; $i > 0; $i--) : ?>
                    <option value="<?= $i ?>" <?= $C57 == $i ? 'selected' : '' ?>><?= $i ?></option>
                  <?php endfor; ?>
                </select>
              </td>
            </tr>
          </tbody>
        </table>

        <div class="form-group">
          <button type="submit" name="kinerja_guru" class="btn btn-info">Simpan Penilaian</button>
        </div>

      <?php endif; ?>
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
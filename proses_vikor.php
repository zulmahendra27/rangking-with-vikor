<?php
if ($_SESSION['level'] != 'admin') {
  echo "<script>alert('Anda tidak diizinkan mengakses halaman ini');</script>";
  echo "<script>window.location='?p=dashboard';</script>";
}
?>

<div class="card overflow-hidden" style="border-radius: 7px;">
  <div class="card-header bg-secondary px-4 py-3 d-flex justify-content-between align-items-center">
    <h4 class="card-title m-0 text-white"><?= $title ?? '' ?></h4>
  </div>
  <div class="card-body">
    <?php
    $querySemesterActive = select($c, 'semester', ['where' => "status=1"]);
    $semesterActive = mysqli_fetch_assoc($querySemesterActive);
    $id_semester_active = $semesterActive['id_semester'];

    $whereNilai = "id_semester=$id_semester_active";
    $queryNilaiGuru = select($c, 'nilaiguru', ['where' => $whereNilai]);

    if ($queryNilaiGuru->num_rows > 0) :

      $queryLog = select($c, 'log', ['limit' => 1, 'order' => 'time DESC']);
      $log = mysqli_fetch_assoc($queryLog)['time'];
    ?>

      <h3 class="text-dark font-weight-500 mb-4">Data berikut merupakan Update Penilaian Tanggal
        <span class="text-danger"><?= date('d-m-Y', strtotime($log)) ?></span>, Jam <span class="text-danger"><?= date('H:i', strtotime($log)) ?></span>.<br>Untuk mengupdate penilaian, silahkan lakukan
        penilaian ulang pada Menu <a href="?p=penilaian_guru" class="text-info">Penilaian Guru</a>.
      </h3>

      <h3 class="text-dark mb-4">Data Penilaian Guru</h3>

      <?php
      include_once "./control/vikor.php";

      // $queryGuru = select($c, 'guru');
      $queryGuru = select($c, 'guru a', ['join' => 'INNER JOIN users b ON a.id_user=b.id_user', 'where' => "b.level='guru'"]);
      $queryKriteria = select($c, 'kriteria');

      $arrayPerAlternatif = array(array());
      $arrayPerKriteria = array(array());

      $dataNilai = array();
      if ($queryNilaiGuru->num_rows > 0) {
        while ($row = mysqli_fetch_assoc($queryNilaiGuru)) {
          $dataNilai[] = $row['id_guru'] . '-' . $row['id_kriteria'] . '-' . $row['nilai_guru'];
        }
      }
      ?>

      <div class="table-responsive">
        <table width="100%" class="small table table-striped table-bordered nowrap text-dark">
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
                  <th class="text-wrap"><?= $kriteria['kode_kriteria'] ?></th>
              <?php endforeach;
              endif; ?>
            </tr>
          </thead>
          <tbody>
            <?php $i = 1;
            if ($queryGuru->num_rows > 0) :
              foreach ($queryGuru as $keyG => $data) : ?>
                <tr>
                  <td><?= $i ?></td>
                  <td><?= $data['nama_guru'] ?></td>
                  <?php
                  if ($queryKriteria->num_rows > 0) :
                    foreach ($queryKriteria as $keyK => $kriteria) :
                      echo "<td>";
                      if ($kriteria['kode_kriteria'] == 'C1') {
                        if ($queryNilaiGuru->num_rows > 0) {
                          foreach ($queryNilaiGuru as $row) {
                            if ($row['id_guru'] == $data['id_guru'] && $row['id_kriteria'] == $kriteria['id_kriteria']) {
                              echo $row['nilai_guru'];
                              $arrayPerAlternatif[$keyG][$keyK] = $row['nilai_guru'];
                              $arrayPerKriteria[$keyK][$keyG] = $row['nilai_guru'];
                            }
                          }
                        }
                      } else {
                        $id_kriteria = $kriteria['id_kriteria'];
                        $whereSub = "id_kriteria=$id_kriteria";
                        $querySub = select($c, 'subkriteria', ['where' => $whereSub]);
                        if ($querySub->num_rows > 0) {
                          foreach ($querySub as $sub) {
                            $check = $data['id_guru'] . '-' . $kriteria['id_kriteria'] . '-' . $sub['bobot_sub'];
                            // print_r($check);
                            $search = array_search($check, $dataNilai);
                            if ($search !== false) {
                              echo $sub['bobot_sub'];
                              $arrayPerAlternatif[$keyG][$keyK] = $sub['bobot_sub'];
                              $arrayPerKriteria[$keyK][$keyG] = $sub['bobot_sub'];
                            }
                          }
                        }
                      }
                  ?>
                    <?php echo "</td>";
                    endforeach; ?>
                  <?php endif; ?>
                </tr>
            <?php $i++;
              endforeach;
            endif; ?>
          </tbody>
        </table>

      </div>

      <!-- ######################################################################################## -->
      <hr class="my-5 border-dark">

      <h3 class="text-dark mb-4">Bobot dan Perbaikan Bobot Kriteria</h3>
      <div class="table-responsive">
        <table width="100%" class="small table table-striped table-bordered nowrap text-dark">

          <thead class="text-center align-items-center">
            <tr>
              <th>#</th>
              <?php foreach ($queryKriteria as $kriteria) : ?>
                <th><?= $kriteria['kode_kriteria'] ?></th>
              <?php endforeach; ?>
              <th>Total</th>
            </tr>
          </thead>
          <tbody>
            <tr class="bg-secondary text-white">
              <th>BOBOT KRITERIA</th>
              <?php $totalBobot = 0;
              foreach ($queryKriteria as $kriteria) : ?>
                <th><?= $kriteria['bobot'] ?></th>
              <?php $totalBobot += $kriteria['bobot'];
              endforeach; ?>
              <th><?= $totalBobot ?></th>
            </tr>
            <tr class="bg-primary text-white">
              <th>PERBAIKAN BOBOT</th>
              <?php
              $arrayPerbaikanBobot = array();
              foreach ($queryKriteria as $kriteria) : ?>
                <th>
                  <?php
                  $perbaikanBobot = round(($kriteria['bobot'] / $totalBobot), 5);
                  echo $perbaikanBobot;
                  $arrayPerbaikanBobot[] = $perbaikanBobot;
                  ?>
                </th>
              <?php endforeach; ?>
              <th><?= round(array_sum($arrayPerbaikanBobot), 0) ?></th>
            </tr>
          </tbody>

        </table>
      </div>


      <!-- ######################################################################################## -->

      <hr class="my-5 border-dark">

      <h3 class="text-dark mb-4">Matriks Normalisasi</h3>
      <div class="table-responsive">
        <table width="100%" class="small table table-striped table-bordered nowrap text-dark">
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
                  <th class="text-wrap"><?= $kriteria['kode_kriteria'] ?></th>
              <?php endforeach;
              endif; ?>
            </tr>
          </thead>
          <tbody>
            <?php $i = 1;
            if ($queryGuru->num_rows > 0) :
              foreach ($queryGuru as $keyG => $data) : ?>
                <tr>
                  <td><?= $i ?></td>
                  <td><?= $data['nama_guru'] ?></td>
                  <?php
                  if ($queryKriteria->num_rows > 0) :
                    foreach ($queryKriteria as $keyK => $kriteria) :
                  ?>
                      <td>
                        <?php
                        if ($kriteria['kode_kriteria'] == 'C1') {
                          if ($queryNilaiGuru->num_rows > 0) {
                            foreach ($queryNilaiGuru as $row) {
                              if ($row['id_guru'] == $data['id_guru'] && $row['id_kriteria'] == $kriteria['id_kriteria']) {
                                echo $arrayNormalisasi[$keyG][$keyK] = normalisasi($arrayPerKriteria[$keyK], $row['nilai_guru']);
                              }
                            }
                          }
                        } else {
                          $id_kriteria = $kriteria['id_kriteria'];
                          $whereSub = "id_kriteria=$id_kriteria";
                          $querySub = select($c, 'subkriteria', ['where' => $whereSub]);
                          if ($querySub->num_rows > 0) {
                            foreach ($querySub as $sub) {
                              $check = $data['id_guru'] . '-' . $kriteria['id_kriteria'] . '-' . $sub['bobot_sub'];
                              $search = array_search($check, $dataNilai);
                              if ($search !== false) {
                                echo $arrayNormalisasi[$keyG][$keyK] = normalisasi($arrayPerKriteria[$keyK], $sub['bobot_sub']);
                              }
                            }
                          }
                        }
                        ?>
                      </td>
                  <?php endforeach;
                  endif; ?>
                </tr>
            <?php $i++;
              endforeach;
            endif; ?>
          </tbody>
        </table>

      </div>



      <!-- ######################################################################################## -->

      <hr class="my-5 border-dark">

      <h3 class="text-dark mb-4">Perbaikan Bobot Dikali Kriteria</h3>
      <div class="table-responsive">

        <table width="100%" class="small table table-striped table-bordered nowrap text-dark">
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
                  <th class="text-wrap"><?= $kriteria['kode_kriteria'] ?></th>
              <?php endforeach;
              endif; ?>
            </tr>
          </thead>
          <tbody>
            <?php $i = 1;
            if ($queryGuru->num_rows > 0) :
              foreach ($queryGuru as $keyG => $data) : ?>
                <tr>
                  <td><?= $i ?></td>
                  <td><?= $data['nama_guru'] ?></td>
                  <?php
                  if ($queryKriteria->num_rows > 0) :
                    foreach ($queryKriteria as $keyK => $kriteria) :
                  ?>
                      <td>
                        <?php
                        if ($kriteria['kode_kriteria'] == 'C1') {
                          if ($queryNilaiGuru->num_rows > 0) {
                            foreach ($queryNilaiGuru as $row) {
                              if ($row['id_guru'] == $data['id_guru'] && $row['id_kriteria'] == $kriteria['id_kriteria']) {
                                echo $nilaiSR[$keyG][$keyK] = normalisasiXBobot($arrayNormalisasi[$keyG][$keyK], $arrayPerbaikanBobot[$keyK]);
                              }
                            }
                          }
                        } else {
                          $id_kriteria = $kriteria['id_kriteria'];
                          $whereSub = "id_kriteria=$id_kriteria";
                          $querySub = select($c, 'subkriteria', ['where' => $whereSub]);
                          if ($querySub->num_rows > 0) {
                            foreach ($querySub as $sub) {
                              $check = $data['id_guru'] . '-' . $kriteria['id_kriteria'] . '-' . $sub['bobot_sub'];
                              $search = array_search($check, $dataNilai);
                              if ($search !== false) {
                                echo $nilaiSR[$keyG][$keyK] = normalisasiXBobot($arrayNormalisasi[$keyG][$keyK], $arrayPerbaikanBobot[$keyK]);
                              }
                            }
                          }
                        }
                        ?>
                      </td>
                  <?php endforeach;
                  endif; ?>
                </tr>
            <?php
                $i++;
              endforeach;
            endif;
            ?>
          </tbody>
        </table>

      </div>


      <!-- ######################################################################################## -->

      <hr class="my-5 border-dark">

      <h3 class="text-dark mb-4">Nilai S dan R</h3>
      <div class="table-responsive">

        <table width="100%" class="small table table-striped table-bordered nowrap text-dark">
          <thead class="text-center align-items-center">
            <tr>
              <th rowspan="2">No</th>
              <th rowspan="2">Nama Guru</th>
              <th rowspan="2">Nilai S</th>
              <th rowspan="2">Nilai R</th>
            </tr>
          </thead>
          <tbody>
            <?php $i = 1;
            if ($queryGuru->num_rows > 0) :
              foreach ($queryGuru as $keyG => $data) :
                $maxMinS[$keyG] = array_sum($nilaiSR[$keyG]);
                $maxMinR[$keyG] = max($nilaiSR[$keyG]);
            ?>
                <tr>
                  <td><?= $i ?></td>
                  <td><?= $data['nama_guru'] ?></td>
                  <td><?= array_sum($nilaiSR[$keyG]) ?></td>
                  <td><?= max($nilaiSR[$keyG]) ?></td>
                </tr>
            <?php
                $i++;
              endforeach;
            endif;
            ?>
            <tr class="bg-secondary text-white">
              <th colspan="2">MIN</th>
              <th><?= min($maxMinS) ?></th>
              <th><?= min($maxMinR) ?></th>
            </tr>
            <tr class="bg-info text-white">
              <th colspan="2">MAX</th>
              <th><?= max($maxMinS) ?></th>
              <th><?= max($maxMinR) ?></th>
            </tr>
          </tbody>
        </table>

      </div>


      <!-- ######################################################################################## -->

      <hr class="my-5 border-dark">

      <h3 class="text-dark mb-4">Nilai Q</h3>
      <div class="table-responsive">

        <table width="100%" class="small table table-striped table-bordered nowrap text-dark">
          <thead class="text-center align-items-center">
            <tr>
              <th rowspan="2">No</th>
              <th rowspan="2">Nama Guru</th>
              <th rowspan="2">Nilai Q</th>
            </tr>
          </thead>
          <tbody>
            <?php $i = 1;
            if ($queryGuru->num_rows > 0) :
              foreach ($queryGuru as $keyG => $data) :
                $nilaiQ[$keyG][0] = $data['nama_guru'];
                $nilaiQ[$keyG][1] = nilaiQ(array_sum($nilaiSR[$keyG]), max($nilaiSR[$keyG]), $maxMinS, $maxMinR);
                $nilaiQ[$keyG][2] = $data['id_guru'];
            ?>
                <tr>
                  <td><?= $i ?></td>
                  <td><?= $data['nama_guru'] ?></td>
                  <td><?= nilaiQ(array_sum($nilaiSR[$keyG]), max($nilaiSR[$keyG]), $maxMinS, $maxMinR); ?></td>
                </tr>
            <?php
                $i++;
              endforeach;
            endif;
            ?>
          </tbody>
        </table>

      </div>


      <!-- ######################################################################################## -->

      <hr class="my-5 border-dark">

      <h3 class="text-dark mb-4">Perangkingan</h3>
      <div class="table-responsive">

        <table width="100%" class="small table table-striped table-bordered nowrap text-dark">
          <thead class="text-center align-items-center">
            <tr>
              <th rowspan="2">Rangking</th>
              <th rowspan="2">Nama Guru</th>
              <th rowspan="2">Nilai Q</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $i = 1;
            if (count($nilaiQ) > 0) :
              // $c->query("TRUNCATE TABLE hasilnilai");
              // var_dump($id_semester_active);
              delete($c, 'hasilnilai', "id_semester=$id_semester_active");

              $dataInput['key'] = array('id_guru', 'id_semester', 'nilai');
              $dataInput['values'] = [];
              $nilaiQ = sortMultiArray($nilaiQ);
              foreach ($nilaiQ as $nilai) :
                $dataInput['values'][] = "(" . $nilai[2] . "," . $id_semester_active . "," . $nilai[1] . ")";
                $color = ($i == 1 ? 'bg-secondary text-white' : ($i == 2 ? 'bg-info text-white' : ($i == 3 ? 'bg-warning text-white' : '')));
                $tag = in_array($i, [1, 2, 3]) ? 'th' : 'td';
            ?>
                <tr class="<?= $color ?>">
                  <<?= $tag ?>><?= $i ?></<?= $tag ?>>
                  <<?= $tag ?>><?= $nilai[0] ?></<?= $tag ?>>
                  <<?= $tag ?>><?= $nilai[1]; ?></<?= $tag ?>>
                </tr>
            <?php
                $i++;
              endforeach;
              insert_batch($c, $dataInput, 'hasilnilai');
            endif;
            ?>
          </tbody>
        </table>

      </div>
      <div class="h4 my-5 text-dark">
        <p>Dalam menggunakan <span class="font-weight-bold">Metode Vikor</span>, maka perangkingan diurutkan berdasarkan
          <span class="font-weight-bold">Nilai Q</span> terkecil. Menurut hasil <span class="font-weight-bold">Nilai
            Q</span>, maka didapatkan Guru Terbaik adalah <span class="font-weight-bold text-danger"><u><?= $nilaiQ[0][0] ?></u></span>
          dengan Nilai <span class="font-weight-bold text-danger"><u><?= $nilaiQ[0][1] ?></u></span>.
        </p>
      </div>

    <?php else : ?>
      <h3 class="text-dark font-weight-bold mb-4">Proses VIKOR belum dapat dilakukan karena belum ada penilaian terhadap
        guru. Silahkan lengkapi penilaian pada menu <a href="?p=penilaian_guru">Penilaian Guru</a>.
      </h3>
    <?php endif; ?>
  </div>

</div>
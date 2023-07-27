<div class="text-center">
  <h1 class="font-weight-bold mb-0"><?= $WEB_NAME ?></h1>
  <p class="mb-4"><?= $ALAMAT_SEKOLAH ?></p>
</div>
<div class="d-flex justify-content-center mb-4">
  <h2 class="font-weight-bold">Laporan Data <?= ucwords($_GET['p']) ?></h2>
</div>

<div class="pt-3">
  <table class="table table-striped table-bordered mx-4">
    <thead>
      <tr class="text-center">
        <th>No.</th>
        <th>Mata Pelajaran</th>
        <th>Rombel</th>
        <th>Guru Pengampu</th>
      </tr>
    </thead>
    <tbody>
      <?php
      if ($_SESSION['level'] == 'admin') {
        $join = "INNER JOIN mapel b ON a.id_mapel=b.id_mapel INNER JOIN rombel c ON a.id_rombel=c.id_rombel INNER JOIN guru d ON a.id_guru=d.id_guru";
        $query = select($c, 'pengampu a', ['join' => $join]);
      } elseif ($_SESSION['level'] == 'guru') {
        $queryGuru = select($c, 'users a', ['join' => 'INNER JOIN guru b ON a.id_user=b.id_user', 'where' => "a.username='" . $_SESSION['username'] . "'"]);
        $dataGuru = mysqli_fetch_assoc($queryGuru);

        $join = "INNER JOIN mapel b ON a.id_mapel=b.id_mapel INNER JOIN rombel c ON a.id_rombel=c.id_rombel INNER JOIN guru d ON a.id_guru=d.id_guru";
        $query = select($c, 'pengampu a', ['join' => $join, 'where' => "a.id_guru=" . $dataGuru['id_guru']]);
      }
      if ($query->num_rows > 0) :
        $i = 1;
        foreach ($query as $data) :
      ?>
      <tr>
        <td class="text-center"><?= $i ?></td>
        <td><?= $data['nama_mapel'] ?></td>
        <td><?= $data['nama_rombel'] ?></td>
        <td><?= $data['nama_guru'] ?></td>
      </tr>
      <?php $i++;
        endforeach;
      else : ?>
      <tr>
        <td colspan="4" class="text-center">-- Tidak ada data</td>
      </tr>
      <?php endif; ?>
    </tbody>
  </table>

  <table style="width: 100%;" class="mt-4">
    <tr>
      <th style="width: 60%;"></th>
      <th>Tapanuli Tengah, <?= bulanIndo(date('Y-m-d')) ?></th>
    </tr>
    <tr>
      <th></th>
      <th>Mengetahui,</th>
    </tr>
    <tr>
      <th style="padding-bottom: 4rem;"></th>
      <th style="padding-bottom: 4rem;">Kepala Sekolah</th>
    </tr>
    <?php
    $joinGuru = "INNER JOIN users b ON a.id_user=b.id_user";
    $queryGuru = select($c, 'guru a', ['join' => $joinGuru, 'where' => "b.level='kepsek'"]);
    $dataKepsek = mysqli_fetch_assoc($queryGuru);
    ?>
    <tr>
      <th></th>
      <th><?= $dataKepsek['nama_guru'] ?></th>
    </tr>
    <tr>
      <th></th>
      <th>NIP. <?= $dataKepsek['nip'] ?></th>
    </tr>
  </table>
</div>
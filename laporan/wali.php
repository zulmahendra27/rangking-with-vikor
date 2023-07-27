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
        <th>Username</th>
        <th>Nama</th>
        <th>Email</th>
        <th>No. HP</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $query = select($c, 'wali', ['join' => 'INNER JOIN users ON wali.id_user=users.id_user']);
      if ($query->num_rows > 0) :
        $i = 1;
        foreach ($query as $data) :
      ?>
      <tr>
        <td class="text-center"><?= $i ?></td>
        <td><?= $data['username'] ?></td>
        <td><?= $data['nama_wali'] ?></td>
        <td><?= $data['email_wali'] ?></td>
        <td><?= $data['nohp_wali'] ?></td>
      </tr>
      <?php $i++;
        endforeach;
      else : ?>
      <tr>
        <td colspan="5" class="text-center">-- Tidak ada data</td>
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
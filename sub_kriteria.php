<?php
/* echo "<script>window.location='?p=dashboard';</script>"; */

$id_kriteria = $_GET['k'] ?? null;
$whereKriteria = 'id_kriteria=' . $id_kriteria;
$queryKriteria = select($c, 'kriteria', ['where' => $whereKriteria]);
if ($queryKriteria->num_rows <= 0) {
  echo "<script>alert('Kriteria tidak ditemukan');</script>";
  echo "<script>window.location='?p=kriteria';</script>";
}
?>
<div class="card overflow-hidden" style="border-radius: 7px;">
  <div class="card-header bg-success px-4 py-3 d-flex justify-content-between align-items-center">
    <h4 class="card-title m-0 text-white"><?= $title ?? '' ?></h4>
    <button type="button" id="addButton" class="btn btn-sm btn-info" data-toggle="modal"
      data-target="#subkriteriaModal"><i class="ik ik-plus-circle"></i>Tambah Data</button>
  </div>
  <div class="card-body">
    <div class="h2 mb-4">
      <div class="badge badge-info">Kriteria : <?= mysqli_fetch_assoc($queryKriteria)['nama_kriteria'] ?></div>
    </div>
    <div class="table-responsive">
      <table id="tabelData" class="small table table-striped table-bordered nowrap">
        <thead>
          <tr>
            <th>No</th>
            <th>Sub Kriteria</th>
            <th>Bobot</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php $i = 1;
          $whereSub = "id_kriteria=$id_kriteria";
          $querySub = select($c, 'subkriteria', ['where' => $whereKriteria]);
          foreach ($querySub as $sub) : ?>
          <tr>
            <td><?= $i ?></td>
            <td><?= $sub['nama_sub'] ?></td>
            <td><?= $sub['bobot_sub'] ?></td>
            <td class="p-2">
              <div class="table-actions text-center">
                <button type="button" class="btn btn-sm btn-icon btn-info" id="editButton" data-toggle="modal"
                  data-target="#subkriteriaModal" onclick="editData(<?= $sub['id_subkriteria'] ?>)"><i
                    class="icon-note"></i></button>
                <button type="button" class="btn btn-sm btn-icon btn-danger" data-toggle="modal"
                  data-target="#deleteModal"
                  onclick="deleteData('<?= $sub['id_subkriteria'] ?>', '<?= htmlspecialchars($_GET['k']) ?>')">
                  <i class="icon-trash"></i>
                </button>
              </div>
            </td>
          </tr>
          <?php $i++;
          endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<div class="modal fade" id="subkriteriaModal" tabindex="-1" role="dialog" aria-labelledby="subkriteriaModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content p-2">
      <form id="formData" action="./control/aksi_insert.php" method="post" data-type="add">
        <input type="hidden" name="id_subkriteria" id="id_subkriteria">
        <input type="hidden" name="id_kriteria" value="<?= htmlspecialchars($_GET['k']) ?>">
        <div class="modal-header">
          <h5 class="modal-title" id="subkriteriaModalLabel">Data Sub Kriteria</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
              aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">

          <div class="form-group row">
            <label for="nama_sub" class="col-sm-3 col-form-label">Sub Kriteria</label>
            <div class="col-sm-9">
              <input type="text" name="nama_sub" class="form-control" id="nama_sub" placeholder="Sub Kriteria"
                autofocus>
            </div>
          </div>
          <div class="form-group row">
            <label for="bobot_sub" class="col-sm-3 col-form-label">Bobot</label>
            <div class="col-sm-9">
              <input type="number" name="bobot_sub" step="0.01" class="form-control" id="bobot_sub" placeholder="Bobot">
            </div>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" id="buttonForm" name="subkriteria" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
window.addEventListener("DOMContentLoaded", () => {
  let addButton = document.getElementById('addButton');
  let formData = document.getElementById('formData');

  addButton.addEventListener('click', function() {
    formData.reset();
    formData.setAttribute('action', './control/aksi_insert.php');
  });
});

function editData(id) {
  document.getElementById('formData').setAttribute('action', './control/aksi_update.php')

  fetch('./control/aksi_select.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: new URLSearchParams({
        'detail': 'subkriteria',
        'id_subkriteria': id
      })
    })
    .then(response => response.json())
    .then(data => {
      document.getElementById('id_subkriteria').value = data.id_subkriteria;
      document.getElementById('nama_sub').value = data.nama_sub;
      document.getElementById('bobot_sub').value = data.bobot_sub;
    })
    .catch(err => console.log(err));
}

function deleteData(id, k) {
  document.getElementById('delButton').setAttribute('href', './control/aksi_delete.php?del=subkriteria&id=' + id +
    '&k=' + k);
}
</script>
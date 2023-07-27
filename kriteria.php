<?php /* echo "<script>window.location='?p=dashboard';</script>"; */ ?>
<div class="card overflow-hidden" style="border-radius: 7px;">
  <div class="card-header bg-success px-4 py-3 d-flex justify-content-between align-items-center">
    <h4 class="card-title m-0 text-white"><?= $title ?? '' ?></h4>
    <button type="button" id="addButton" class="btn btn-sm btn-info" data-toggle="modal" data-target="#kriteriaModal"><i class="ik ik-plus-circle"></i>Tambah Data</button>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table id="tabelData" class="small table table-striped table-bordered nowrap">
        <thead>
          <tr>
            <th>No</th>
            <th>Kode Kriteria</th>
            <th>Nama Kriteria</th>
            <th>Bobot</th>
            <th>Sub Kriteria</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php $i = 1;
          $query = select($c, 'kriteria');
          while ($data = mysqli_fetch_assoc($query)) : ?>
            <tr>
              <td><?= $i ?></td>
              <td><?= $data['kode_kriteria'] ?></td>
              <td><?= $data['nama_kriteria'] ?></td>
              <td><?= $data['bobot'] ?></td>
              <td>
                <a href="?p=sub_kriteria&k=<?= $data['id_kriteria'] ?>" class="h5">
                  <div class="badge badge-success">Data Sub Kriteria</div>
                </a>
              </td>
              <td class="p-2">
                <div class="table-actions text-center">
                  <button type="button" class="btn btn-sm btn-icon btn-info" id="editButton" data-toggle="modal" data-target="#kriteriaModal" onclick="editData(<?= $data['id_kriteria'] ?>)"><i class="icon-note"></i></button>
                  <button type="button" class="btn btn-sm btn-icon btn-danger" data-toggle="modal" data-target="#deleteModal" onclick="deleteData('<?= $data['id_kriteria'] ?>')">
                    <i class="icon-trash"></i>
                  </button>
                </div>
              </td>
            </tr>
          <?php $i++;
          endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<div class="modal fade" id="kriteriaModal" tabindex="-1" role="dialog" aria-labelledby="kriteriaModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content p-2">
      <form id="formData" action="./control/aksi_insert.php" method="post" data-type="add">
        <input type="hidden" name="id_kriteria" id="id_kriteria">
        <div class="modal-header">
          <h5 class="modal-title" id="kriteriaModalLabel">Data Kriteria</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">

          <div class="form-group row">
            <label for="kode_kriteria" class="col-sm-3 col-form-label">Kode Kriteria</label>
            <div class="col-sm-9">
              <input type="text" name="kode_kriteria" class="form-control" id="kode_kriteria" placeholder="Kode Kriteria">
            </div>
          </div>
          <div class="form-group row">
            <label for="nama_kriteria" class="col-sm-3 col-form-label">Nama Kriteria</label>
            <div class="col-sm-9">
              <input type="text" name="nama_kriteria" class="form-control" id="nama_kriteria" placeholder="Nama Kriteria">
            </div>
          </div>
          <div class="form-group row">
            <label for="bobot" class="col-sm-3 col-form-label">Bobot</label>
            <div class="col-sm-9">
              <input type="number" min="1" value="1" name="bobot" class="form-control" id="bobot" placeholder="Bobot">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" id="buttonForm" name="kriteria" class="btn btn-primary">Simpan</button>
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
          'detail': 'kriteria',
          'id_kriteria': id
        })
      })
      .then(response => response.json())
      .then(data => {
        document.getElementById('id_kriteria').value = data.id_kriteria;
        document.getElementById('kode_kriteria').value = data.kode_kriteria;
        document.getElementById('nama_kriteria').value = data.nama_kriteria;
        document.getElementById('bobot').value = data.bobot;
      })
      .catch(err => console.log(err));
  }

  function deleteData(id) {
    document.getElementById('delButton').setAttribute('href', './control/aksi_delete.php?del=kriteria&id=' + id);
  }
</script>
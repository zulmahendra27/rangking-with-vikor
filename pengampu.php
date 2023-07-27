<div class="card overflow-hidden" style="border-radius: 7px;">
  <div class="card-header bg-success px-4 py-3 d-flex justify-content-between align-items-center">
    <h4 class="card-title m-0 text-white"><?= $title ?? '' ?></h4>
    <button type="button" id="addButton" class="btn btn-sm btn-info" data-toggle="modal" data-target="#pengampuModal"><i
        class="ik ik-plus-circle"></i>Tambah Data</button>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table id="tabelData" class="small table table-striped table-bordered nowrap">
        <thead>
          <tr>
            <th>No</th>
            <th>Mata Pelajaran</th>
            <th>Rombel</th>
            <th>Nama Guru</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php $i = 1;
          $join = "INNER JOIN mapel b ON a.id_mapel=b.id_mapel INNER JOIN rombel c ON a.id_rombel=c.id_rombel INNER JOIN guru d ON a.id_guru=d.id_guru";
          $query = select($c, 'pengampu a', ['join' => $join]);
          while ($data = mysqli_fetch_assoc($query)) : ?>
          <tr>
            <td><?= $i ?></td>
            <td><?= $data['nama_mapel'] ?></td>
            <td><?= $data['nama_rombel'] ?></td>
            <td><?= $data['nama_guru'] ?></td>
            <td class="p-2">
              <div class="table-actions text-center">
                <button type="button" class="btn btn-sm btn-icon btn-info" id="editButton" data-toggle="modal"
                  data-target="#pengampuModal" onclick="editData(<?= $data['id_pengampu'] ?>)"><i
                    class="icon-note"></i></button>
                <button type="button" class="btn btn-sm btn-icon btn-danger" data-toggle="modal"
                  data-target="#deleteModal" onclick="deleteData('<?= $data['id_pengampu'] ?>')">
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

<div class="modal fade" id="pengampuModal" tabindex="-1" role="dialog" aria-labelledby="pengampuModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content p-2">
      <form id="formData" action="./control/aksi_insert.php" method="post" data-type="add">
        <input type="hidden" name="id_pengampu" id="id_pengampu">
        <div class="modal-header">
          <h5 class="modal-title" id="pengampuModalLabel">Data Pengampu Mata Pelajaran</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
              aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">

          <div class="form-group row">
            <label for="mapel" class="col-sm-3 col-form-label">Nama Mapel</label>
            <div class="col-sm-9">
              <select name="id_mapel" id="mapel" class="form-control">
                <?php
                $queryMapel = select($c, 'mapel');
                while ($data = mysqli_fetch_assoc($queryMapel)) :
                ?>
                <option value="<?= $data['id_mapel'] ?>"><?= $data['nama_mapel'] ?></option>
                <?php endwhile; ?>
              </select>
            </div>
          </div>
          <div class="form-group row">
            <label for="rombel" class="col-sm-3 col-form-label">Nama Rombel</label>
            <div class="col-sm-9">
              <select name="id_rombel" id="rombel" class="form-control">
                <?php
                $queryMapel = select($c, 'rombel');
                while ($data = mysqli_fetch_assoc($queryMapel)) :
                ?>
                <option value="<?= $data['id_rombel'] ?>"><?= $data['nama_rombel'] ?></option>
                <?php endwhile; ?>
              </select>
            </div>
          </div>
          <div class="form-group row">
            <label for="guru" class="col-sm-3 col-form-label">Nama Guru</label>
            <div class="col-sm-9">
              <select name="id_guru" id="guru" class="form-control">
                <?php
                // $queryMapel = select($c, 'guru');
                $queryMapel = select($c, 'guru a', ['join' => 'INNER JOIN users b ON a.id_user=b.id_user', 'where' => "b.level='guru'"]);
                while ($data = mysqli_fetch_assoc($queryMapel)) :
                ?>
                <option value="<?= $data['id_guru'] ?>"><?= $data['nama_guru'] ?></option>
                <?php endwhile; ?>
              </select>
            </div>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" id="buttonForm" name="pengampu" class="btn btn-primary">Simpan</button>
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
        'detail': 'pengampu',
        'id_pengampu': id
      })
    })
    .then(response => response.json())
    .then(data => {
      document.getElementById('id_pengampu').value = data.id_pengampu;
      document.getElementById('nama').value = data.nama_pengampu;
    })
    .catch(err => console.log(err));
}

function deleteData(id) {
  document.getElementById('delButton').setAttribute('href', './control/aksi_delete.php?del=pengampu&id=' + id);
}
</script>
<div class="card overflow-hidden" style="border-radius: 7px;">
  <div class="card-header bg-success px-4 py-3 d-flex justify-content-between align-items-center">
    <h4 class="card-title m-0 text-white"><?= $title ?? '' ?></h4>
    <button type="button" id="addButton" class="btn btn-sm btn-info" data-toggle="modal" data-target="#rombelModal"><i
        class="ik ik-plus-circle"></i>Tambah Data</button>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table id="tabelData" class="small table table-striped table-bordered nowrap">
        <thead>
          <tr>
            <th>No</th>
            <th>Nama Rombel</th>
            <th>Wali Kelas</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php $i = 1;
          $query = select($c, 'rombel', ['join' => 'INNER JOIN guru ON rombel.walas=guru.id_guru']);
          while ($data = mysqli_fetch_assoc($query)) : ?>
          <tr>
            <td><?= $i ?></td>
            <td><?= $data['nama_rombel'] ?></td>
            <td><?= $data['nama_guru'] ?></td>
            <td class="p-2">
              <div class="table-actions text-center">
                <button type="button" class="btn btn-sm btn-icon btn-info" id="editButton" data-toggle="modal"
                  data-target="#rombelModal" onclick="editData(<?= $data['id_rombel'] ?>)"><i
                    class="icon-note"></i></button>
                <button type="button" class="btn btn-sm btn-icon btn-danger" data-toggle="modal"
                  data-target="#deleteModal" onclick="deleteData('<?= $data['id_rombel'] ?>')">
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

<div class="modal fade" id="rombelModal" tabindex="-1" role="dialog" aria-labelledby="rombelModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content p-2">
      <form id="formData" action="./control/aksi_insert.php" method="post" data-type="add">
        <input type="hidden" name="id_rombel" id="id_rombel">
        <div class="modal-header">
          <h5 class="modal-title" id="rombelModalLabel">Data Rombel</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
              aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">

          <div class="form-group row">
            <label for="nama" class="col-sm-3 col-form-label">Nama Rombel</label>
            <div class="col-sm-9">
              <input type="text" name="nama" class="form-control" id="nama" placeholder="Nama Rombel">
            </div>
          </div>
          <div class="form-group row">
            <label for="walas" id="walasLabel" class="col-sm-3 col-form-label">Wali Kelas</label>
            <div class="col-sm-9">
              <select name="walas" id="walas" class="form-control">
                <?php
                // $queryGuru = select($c, 'guru');
                $queryGuru = select($c, 'guru a', ['join' => 'INNER JOIN users b ON a.id_user=b.id_user', 'where' => "b.level='guru'"]);
                while ($data = mysqli_fetch_assoc($queryGuru)) :
                ?>
                <option value="<?= $data['id_guru'] ?>"><?= $data['nama_guru'] ?></option>
                <?php endwhile; ?>
              </select>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" id="buttonForm" name="rombel" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
window.addEventListener("DOMContentLoaded", () => {
  let addButton = document.getElementById('addButton');
  let formData = document.getElementById('formData');
  // let buttonForm = document.getElementById('buttonForm');

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
        'detail': 'rombel',
        'id_rombel': id
      })
    })
    .then(response => response.json())
    .then(data => {
      document.getElementById('id_rombel').value = data.id_rombel;
      document.getElementById('nama').value = data.nama_rombel;
      let walas = document.getElementById("walas");

      for (let i = 0; i < walas.options.length; i++) {
        if (walas.options[i].value == data.walas) {
          walas.options[i].selected = true;
          break;
        }
      }
    })
    .catch(err => console.log(err));
}

function deleteData(id) {
  document.getElementById('delButton').setAttribute('href', './control/aksi_delete.php?del=rombel&id=' + id);
}
</script>
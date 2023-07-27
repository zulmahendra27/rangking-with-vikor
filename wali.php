<div class="card overflow-hidden" style="border-radius: 7px;">
  <div class="card-header bg-success px-4 py-3 d-flex justify-content-between align-items-center">
    <h4 class="card-title m-0 text-white"><?= $title ?? '' ?></h4>
    <button type="button" id="addButton" class="btn btn-sm btn-info" data-toggle="modal" data-target="#waliModal"><i
        class="ik ik-plus-circle"></i>Tambah Data</button>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table id="tabelData" class="small table table-striped table-bordered nowrap">
        <thead>
          <tr>
            <th>No</th>
            <th>Username</th>
            <th>Nama</th>
            <th>Email</th>
            <th>No. HP</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php $i = 1;
          $query = select($c, 'wali', ['join' => 'INNER JOIN users ON wali.id_user=users.id_user']);
          while ($data = mysqli_fetch_assoc($query)) : ?>
          <tr>
            <td><?= $i ?></td>
            <td><?= $data['username'] ?></td>
            <td><?= $data['nama_wali'] ?></td>
            <td><?= $data['email_wali'] ?></td>
            <td><?= $data['nohp_wali'] ?></td>
            <td class="p-2">
              <div class="table-actions text-center">
                <button type="button" class="btn btn-sm btn-icon btn-info" id="editButton" data-toggle="modal"
                  data-target="#waliModal" onclick="editData(<?= $data['id_user'] ?>)"><i
                    class="icon-note"></i></button>
                <button type="button" class="btn btn-sm btn-icon btn-danger" data-toggle="modal"
                  data-target="#deleteModal" onclick="deleteData('<?= $data['id_user'] ?>')">
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

<div class="modal fade" id="waliModal" tabindex="-1" role="dialog" aria-labelledby="waliModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content p-2">
      <form id="formData" action="./control/aksi_insert.php" method="post" data-type="add">
        <input type="hidden" name="id_user" id="id_user">
        <div class="modal-header">
          <h5 class="modal-title" id="waliModalLabel">Data Wali</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
              aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">

          <div class="form-group row">
            <label for="nama" class="col-sm-3 col-form-label">Nama</label>
            <div class="col-sm-9">
              <input type="text" name="nama" class="form-control" id="nama" placeholder="Nama">
            </div>
          </div>
          <div class="form-group row">
            <label for="username" id="usernameLabel" class="col-sm-3 col-form-label">Username</label>
            <div class="col-sm-9">
              <input type="text" name="username" class="form-control" id="username" placeholder="Username">
            </div>
          </div>
          <div class="form-group row">
            <label for="email" class="col-sm-3 col-form-label">Email</label>
            <div class="col-sm-9">
              <input type="email" class="form-control" name="email" id="email" placeholder="Email">
            </div>
          </div>
          <div class="form-group row">
            <label for="nohp" class="col-sm-3 col-form-label">No. HP</label>
            <div class="col-sm-9">
              <input type="text" name="nohp" class="form-control" id="nohp" placeholder="No. HP">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" id="buttonForm" name="wali" class="btn btn-primary">Simpan</button>
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
  document.getElementById('formData').setAttribute('action', './control/aksi_update.php');

  fetch('./control/aksi_select.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: new URLSearchParams({
        'detail': 'wali',
        'id_user': id
      })
    })
    .then(response => response.json())
    .then(data => {
      document.getElementById('id_user').value = data.id_user;
      document.getElementById('nama').value = data.nama_wali;
      document.getElementById('username').value = data.username;
      document.getElementById('email').value = data.email_wali;
      document.getElementById('nohp').value = data.nohp_wali;
    })
    .catch(err => console.log(err));
}

function deleteData(id) {
  document.getElementById('delButton').setAttribute('href', './control/aksi_delete.php?del=wali&id=' + id);
}
</script>
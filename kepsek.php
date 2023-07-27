<?php
$selectKepsek = "a.*, b.username";
$joinKepsek = "INNER JOIN users b ON a.id_user=b.id_user";
$whereKepsek = "b.level='kepsek'";
$queryKepsek = select($c, 'guru a', ['join' => $joinKepsek, 'select' => $selectKepsek, 'where' => $whereKepsek]);
$data = mysqli_fetch_assoc($queryKepsek);
?>
<div class="card overflow-hidden" style="border-radius: 7px;">
  <div class="card-header bg-secondary px-4 py-3 d-flex justify-content-between align-items-center">
    <h4 class="card-title m-0 text-white"><?= $title ?? '' ?></h4>
  </div>
  <div class="card-body">
    <div class="row">
      <div class="col-lg-6">
        <h4 class="font-weight-bold text-dark mb-3">Update Profil</h4>
        <form action="./control/aksi_update.php" method="post">
          <input type="hidden" name="id_user" value="<?= $data['id_user'] ?>">
          <div class="form-group">
            <label for="nama_guru" class="col-form-label">Nama</label>
            <input type="text" name="nama_guru" class="form-control" id="nama_guru" placeholder="Nama" value="<?= $data['nama_guru'] ?>">
          </div>
          <div class="form-group">
            <label for="username" class="col-form-label">Username</label>
            <input type="text" name="username" class="form-control" id="username" placeholder="Username" value="<?= $data['username'] ?>">
          </div>
          <div class="form-group">
            <label for="nip" class="col-form-label">NIP</label>
            <input type="text" name="nip" class="form-control" id="nip" placeholder="NIP" value="<?= $data['nip'] ?>">
          </div>
          <div class="form-group">
            <label for="pendidikan" class="col-form-label">Pendidikan</label>
            <select name="pendidikan" id="pendidikan" class="form-control">
              <option value="S1" <?= $data['pendidikan'] == 'S1' ? 'selected' : '' ?>>S1</option>
              <option value="S2" <?= $data['pendidikan'] == 'S2' ? 'selected' : '' ?>>S2</option>
              <option value="S3" <?= $data['pendidikan'] == 'S3' ? 'selected' : '' ?>>S3</option>
            </select>
          </div>
          <div class="form-group">
            <label for="email" class="col-form-label">Email</label>
            <input type="email" name="email" class="form-control" id="email" placeholder="Email" value="<?= $data['email'] ?>">
          </div>
          <div class="form-group">
            <label for="nohp" class="col-form-label">No. HP</label>
            <input type="text" name="nohp" class="form-control" id="nohp" placeholder="No. HP" value="<?= $data['nohp'] ?>">
          </div>
          <div class="form-group">
            <button type="submit" name="update_kepsek" class="btn btn-primary">Update Profil</button>
          </div>
        </form>
      </div>
      <div class="col-lg-6">
        <h4 class="font-weight-bold text-dark mb-3">Change Password</h4>
        <form action="./control/aksi_update.php" method="post">
          <input type="hidden" name="id_user_for_password" value="<?= $data['id_user'] ?>">
          <input type="hidden" name="page" value="<?= $_GET['p'] ?>">
          <div class="form-group">
            <label for="password" class="col-form-label">Password</label>
            <input type="password" name="password" class="form-control" id="password" placeholder="Password" required>
          </div>
          <div class="form-group">
            <label for="conf_password" class="col-form-label">Konfirmasi Password</label>
            <input type="password" name="conf_password" class="form-control" id="conf_password" placeholder="Konfirmasi Password" required>
          </div>
          <div class="form-group">
            <button type="submit" name="update_password" class="btn btn-primary">Update Password</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="guruModal" tabindex="-1" role="dialog" aria-labelledby="guruModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content p-2">
      <form id="formData" action="./control/aksi_insert.php" method="post" data-type="add">
        <input type="hidden" name="id_user" id="id_user">
        <div class="modal-header">
          <h5 class="modal-title" id="guruModalLabel">Data Guru</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">

          <div class="form-group row">
            <label for="nama" class="col-sm-3 col-form-label">Nama</label>
            <div class="col-sm-9">
              <input type="text" name="nama" class="form-control" id="nama" placeholder="Nama" autofocus>
            </div>
          </div>
          <div class="form-group row">
            <label for="status" class="col-sm-3 col-form-label">Status</label>
            <div class="col-sm-9">
              <select name="status" id="status" class="form-control">
                <option value="asn">ASN</option>
                <option value="honorer">Honorer</option>
              </select>
            </div>
          </div>
          <div class="form-group row">
            <label for="username" id="usernameLabel" class="col-sm-3 col-form-label">NIP</label>
            <div class="col-sm-9">
              <input type="text" name="username" class="form-control" id="username" placeholder="NIP">
            </div>
          </div>
          <div class="form-group row">
            <label for="pendidikan" id="pendidikanLabel" class="col-sm-3 col-form-label">Pendidikan</label>
            <div class="col-sm-9">
              <select name="pendidikan" id="pendidikan" class="form-control">
                <option value="S2">S2</option>
                <option value="S1">S1</option>
                <option value="DIV">DIV</option>
                <option value="DIII">DIII</option>
                <option value="DII">DII</option>
              </select>
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
          <button type="submit" id="buttonForm" name="guru" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  window.addEventListener("DOMContentLoaded", () => {
    let status = document.getElementById('status');
    let username = document.getElementById('username');
    let usernameLabel = document.getElementById('usernameLabel');

    status.addEventListener('change', function() {
      if (this.value == 'asn') {
        username.setAttribute('placeholder', 'NIP');
        usernameLabel.innerText = 'NIP';
      } else {
        username.setAttribute('placeholder', 'Username');
        usernameLabel.innerText = 'Username';
      }
    });

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
          'detail': 'guru',
          'id_user': id
        })
      })
      .then(response => response.json())
      .then(data => {
        let username = '';

        if (data.nip == '') {
          document.getElementById('usernameLabel').innerText = 'Username';
          document.getElementById('status').options[1].selected = true;
          username = data.username;
        } else {
          document.getElementById('usernameLabel').innerText = 'NIP';
          document.getElementById('status').options[0].selected = true;
          username = data.nip
        }

        document.getElementById('id_user').value = data.id_user;
        document.getElementById('nama').value = data.nama_guru;
        document.getElementById('username').value = username;
        document.getElementById('email').value = data.email;
        document.getElementById('nohp').value = data.nohp;
        let pendidikan = document.getElementById("pendidikan");

        for (let i = 0; i < pendidikan.options.length; i++) {
          if (pendidikan.options[i].value == data.pendidikan) {
            pendidikan.options[i].selected = true;
            break;
          }
        }
      })
      .catch(err => console.log(err));
  }

  function deleteData(id) {
    document.getElementById('delButton').setAttribute('href', './control/aksi_delete.php?del=guru&id=' + id);
  }
</script>
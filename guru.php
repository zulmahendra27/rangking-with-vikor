<div class="card overflow-hidden" style="border-radius: 7px;">
  <div class="card-header bg-secondary px-4 py-3 d-flex justify-content-between align-items-center">
    <h4 class="card-title m-0 text-white"><?= $title ?? '' ?></h4>
    <button type="button" id="addButton" class="btn btn-sm btn-info" data-toggle="modal" data-target="#guruModal"><i class="ik ik-plus-circle"></i>Tambah Data</button>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table id="tabelData" class="small table table-striped table-bordered nowrap">
        <thead>
          <tr>
            <th>No</th>
            <th>NIP</th>
            <th>Nama</th>
            <th>Pendidikan</th>
            <th>Email</th>
            <th>No. HP</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php $i = 1;
          // $query = select($c, 'guru', ['join' => 'INNER JOIN users ON guru.id_user=users.id_user']);
          $query = select($c, 'guru a', ['join' => 'INNER JOIN users b ON a.id_user=b.id_user', 'where' => "b.level='guru'"]);
          while ($data = mysqli_fetch_assoc($query)) : ?>
            <tr>
              <td><?= $i ?></td>
              <td><?= $data['nip'] == '' ? '-' : $data['nip'] ?></td>
              <td><?= $data['nama_guru'] ?></td>
              <td><?= $data['pendidikan'] ?></td>
              <td><?= $data['email'] ?></td>
              <td><?= $data['nohp'] ?></td>
              <td class="p-2">
                <div class="table-actions text-center">
                  <button type="button" class="btn btn-sm btn-primary" title="Change Password" data-toggle="modal" data-target="#passwordModal" onclick="changePassword(<?= $data['id_user'] ?>)"><i class="icon-key"></i></button>
                  <button type="button" class="btn btn-sm btn-info" id="editButton" data-toggle="modal" data-target="#guruModal" onclick="editData(<?= $data['id_user'] ?>)"><i class="icon-note"></i></button>
                  <button type="button" class="btn btn-sm btn-secondary" data-toggle="modal" data-target="#deleteModal" onclick="deleteData('<?= $data['id_user'] ?>')"><i class="icon-trash"></i>
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
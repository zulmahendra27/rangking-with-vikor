<div class="card overflow-hidden" style="border-radius: 7px;">
  <div class="card-header bg-secondary px-4 py-3 d-flex justify-content-between align-items-center">
    <h4 class="card-title m-0 text-white"><?= $title ?? '' ?></h4>
    <button type="button" id="addButton" class="btn btn-sm btn-info" data-toggle="modal" data-target="#siswaModal"><i class="ik ik-plus-circle"></i>Tambah Data</button>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table id="tabelData" class="small table table-striped table-bordered nowrap">
        <thead>
          <tr>
            <th>No</th>
            <th>NISN</th>
            <th>Nama</th>
            <th>Kelas</th>
            <th>Nama Wali</th>
            <th>Email</th>
            <th>No. HP</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php $i = 1;
          $join = 'INNER JOIN users ON siswa.id_user=users.id_user INNER JOIN rombel ON siswa.id_rombel=rombel.id_rombel INNER JOIN wali ON siswa.id_wali=wali.id_wali';
          $select = "users.username, users.level, siswa.*, rombel.*, wali.id_wali, wali.nama_wali";
          $query = select($c, 'siswa', ['join' => $join, 'select' => $select]);
          while ($data = mysqli_fetch_assoc($query)) : ?>
            <tr>
              <td><?= $i ?></td>
              <td><?= $data['nisn'] ?></td>
              <td><?= $data['nama_siswa'] ?></td>
              <td><?= $data['nama_rombel'] ?></td>
              <td><?= $data['nama_wali'] ?></td>
              <td><?= $data['email_siswa'] ?></td>
              <td><?= $data['nohp_siswa'] ?></td>
              <td class="p-2">
                <div class="table-actions text-center">
                  <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" title="Change Password" data-target="#passwordModal" onclick="changePassword(<?= $data['id_user'] ?>)"><i class="icon-key"></i></button>
                  <button type="button" class="btn btn-sm btn-icon btn-info" id="editButton" data-toggle="modal" data-target="#siswaModal" onclick="editData(<?= $data['id_user'] ?>)"><i class="icon-note"></i></button>
                  <button type="button" class="btn btn-sm btn-icon btn-secondary" data-toggle="modal" data-target="#deleteModal" onclick="deleteData('<?= $data['id_user'] ?>')">
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

<div class="modal fade" id="siswaModal" tabindex="-1" role="dialog" aria-labelledby="siswaModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content p-2">
      <form id="formData" action="./control/aksi_insert.php" method="post" data-type="add">
        <input type="hidden" name="id_user" id="id_user">
        <div class="modal-header">
          <h5 class="modal-title" id="siswaModalLabel">Data Siswa</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">

          <div class="form-group row">
            <label for="nama" class="col-sm-3 col-form-label">Nama</label>
            <div class="col-sm-9">
              <input type="text" name="nama" class="form-control" id="nama" placeholder="Nama">
            </div>
          </div>
          <div class="form-group row">
            <label for="nisn" id="nisnLabel" class="col-sm-3 col-form-label">NISN</label>
            <div class="col-sm-9">
              <input type="text" name="nisn" class="form-control" id="nisn" placeholder="NISN">
            </div>
          </div>
          <div class="form-group row">
            <label for="rombel" id="rombelLabel" class="col-sm-3 col-form-label">Rombel</label>
            <div class="col-sm-9">
              <select name="id_rombel" id="rombel" class="form-control">
                <?php
                $queryRombel = select($c, 'rombel');
                while ($data = mysqli_fetch_assoc($queryRombel)) :
                ?>
                  <option value="<?= $data['id_rombel'] ?>"><?= $data['nama_rombel'] ?></option>
                <?php endwhile; ?>
              </select>
            </div>
          </div>
          <div class="form-group row">
            <label for="wali" id="waliLabel" class="col-sm-3 col-form-label">Nama Wali</label>
            <div class="col-sm-9">
              <select name="id_wali" id="wali" class="form-control">
                <?php
                $queryWali = select($c, 'wali');
                while ($data = mysqli_fetch_assoc($queryWali)) :
                ?>
                  <option value="<?= $data['id_wali'] ?>"><?= $data['nama_wali'] ?></option>
                <?php endwhile; ?>
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
          <button type="submit" id="buttonForm" name="siswa" class="btn btn-primary">Simpan</button>
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
          'detail': 'siswa',
          'id_user': id
        })
      })
      .then(response => response.json())
      .then(data => {
        document.getElementById('id_user').value = data.id_user;
        document.getElementById('nama').value = data.nama_siswa;
        document.getElementById('nisn').value = data.nisn;
        document.getElementById('email').value = data.email_siswa;
        document.getElementById('nohp').value = data.nohp_siswa;

        let rombel = document.getElementById("rombel");

        for (let i = 0; i < rombel.options.length; i++) {
          if (rombel.options[i].value == data.id_rombel) {
            rombel.options[i].selected = true;
            break;
          }
        }

        let wali = document.getElementById("wali");

        for (let i = 0; i < wali.options.length; i++) {
          if (wali.options[i].value == data.id_wali) {
            wali.options[i].selected = true;
            break;
          }
        }
      })
      .catch(err => console.log(err));
  }

  function deleteData(id) {
    document.getElementById('delButton').setAttribute('href', './control/aksi_delete.php?del=siswa&id=' + id);
  }
</script>
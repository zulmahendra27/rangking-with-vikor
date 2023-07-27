<div class="card overflow-hidden" style="border-radius: 7px;">
  <div class="card-header bg-success px-4 py-3 d-flex justify-content-between align-items-center">
    <h4 class="card-title m-0 text-white"><?= $title ?? '' ?></h4>
    <button type="button" id="addButton" class="btn btn-sm btn-info" data-toggle="modal" data-target="#semesterModal"><i class="ik ik-plus-circle"></i>Tambah Data</button>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table id="tabelData" class="small table table-striped table-bordered nowrap">
        <thead>
          <tr>
            <th>No</th>
            <th>Semester</th>
            <th>Tahun Pelajaran</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php $i = 1;
          $query = select($c, 'semester');
          while ($data = mysqli_fetch_assoc($query)) : ?>
            <tr>
              <td><?= $i ?></td>
              <td><?= "Semester " . $data['nama_semester'] ?></td>
              <td><?= $data['tahun'] . "/" . ($data['tahun'] + 1); ?></td>
              <td>
                <div class="text-white badge bg-<?= $data['status'] == 0 ? 'warning' : 'success' ?>">
                  <?= $data['status'] == 0 ? 'Tidak aktif' : 'Aktif' ?>
                </div>
              </td>
              <td class="p-2">
                <div class="table-actions">
                  <button type="button" class="btn btn-sm btn-icon btn-info" id="editButton" data-toggle="modal" data-target="#semesterModal" onclick="editData(<?= $data['id_semester'] ?>)"><i class="icon-note"></i></button>
                  <button type="button" class="btn btn-sm btn-icon btn-danger" data-toggle="modal" data-target="#deleteModal" onclick="deleteData('<?= $data['id_semester'] ?>')">
                    <i class="icon-trash"></i>
                  </button>
                  <?php if ($data['status'] == 0) : ?>
                    <button type="button" class="btn btn-sm btn-icon btn-success" data-toggle="modal" data-target="#activeSemesterModal" onclick="activeSemesterData('<?= $data['id_semester'] ?>')">
                      <i class="icon-check"></i>
                    </button>
                  <?php endif; ?>
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

<div class="modal fade" id="semesterModal" tabindex="-1" role="dialog" aria-labelledby="semesterModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content p-2">
      <form id="formData" action="./control/aksi_insert.php" method="post" data-type="add">
        <input type="hidden" name="id_semester" id="id_semester">
        <div class="modal-header">
          <h5 class="modal-title" id="semesterModalLabel">Data Semester</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">

          <div class="form-group row">
            <label for="nama_semester" class="col-sm-3 col-form-label">Semester</label>
            <div class="col-sm-9">
              <select name="nama_semester" id="nama_semester" class="form-control">
                <option value="1">Semester 1</option>
                <option value="2">Semester 2</option>
              </select>
            </div>
          </div>
          <div class="form-group row">
            <label for="tahun" class="col-sm-3 col-form-label">Tahun Pelajaran</label>
            <div class="col-sm-9">
              <select name="tahun" id="tahun" class="form-control">
                <?php
                $awal = date('Y') - 1;
                $akhir = date('Y');

                for ($i = $awal; $i <= $akhir; $i++) :
                ?>
                  <option value="<?= $i ?>"><?= $i . "/" . ($i + 1) ?></option>
                <?php endfor; ?>
              </select>
            </div>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" id="buttonForm" name="semester" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="activeSemesterModal" tabindex="-1" role="dialog" aria-labelledby="activeSemesterModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content p-2">
      <form id="formData" action="./control/aksi_insert.php" method="post" data-type="add">
        <input type="hidden" name="id_semester" id="id_semester">
        <div class="modal-header">
          <h5 class="modal-title" id="activeSemesterModalLabel">Update Semester</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
          <p>Apakah anda ingin menjadikan semester ini sebagai semester aktif?</p>
          <!-- <p>Data yang sudah dihapus tidak dapat dikembalikan.</p> -->

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
          <a id="activeSemesterButton" class="btn btn-primary text-white">Yes</a>
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
          'detail': 'semester',
          'id_semester': id
        })
      })
      .then(response => response.json())
      .then(data => {
        document.getElementById('id_semester').value = data.id_semester;
        let nama_semester = document.getElementById("nama_semester");

        for (let i = 0; i < nama_semester.options.length; i++) {
          if (nama_semester.options[i].value == data.nama_semester) {
            nama_semester.options[i].selected = true;
            break;
          }
        }

        let tahun = document.getElementById("tahun");

        for (let i = 0; i < tahun.options.length; i++) {
          if (tahun.options[i].value == data.tahun) {
            tahun.options[i].selected = true;
            break;
          }
        }
      })
      .catch(err => console.log(err));
  }

  function deleteData(id) {
    document.getElementById('delButton').setAttribute('href', './control/aksi_delete.php?del=semester&id=' + id);
  }

  function activeSemesterData(id) {
    document.getElementById('activeSemesterButton').setAttribute('href', './control/aksi_update.php?update=semester&id=' +
      id);
  }
</script>
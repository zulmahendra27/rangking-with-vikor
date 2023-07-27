<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="logoutModalLabel">Keluar?</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
            aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <p>Apakah anda ingin keluar dari sistem?</p>
        <p>Setelah keluar, anda dapat masuk kembali dengan akun masing-masing.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <a href="logout.php" class="btn btn-warning">Keluar</a>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">Hapus Data?</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
            aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <p>Apakah anda ingin menghapus data ini?</p>
        <p>Data yang sudah dihapus tidak dapat dikembalikan.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <a id="delButton" class="btn btn-primary text-white">Hapus</a>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="passwordModal" tabindex="-1" role="dialog" aria-labelledby="passwordModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content p-2">
      <form action="./control/aksi_update.php" method="post" data-type="add">
        <input type="hidden" name="id_user_for_password" id="id_user_for_password">
        <input type="hidden" name="page" id="page">
        <div class="modal-header">
          <h5 class="modal-title" id="passwordModalLabel">Change Password</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
              aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">

          <div class="form-group row">
            <label for="password" class="col-sm-3 col-form-label">Password</label>
            <div class="col-sm-9">
              <input type="password" name="password" class="form-control" id="password" placeholder="Password"
                autofocus>
            </div>
          </div>
          <div class="form-group row">
            <label for="conf_password" class="col-sm-3 col-form-label">Konfirmasi Password</label>
            <div class="col-sm-9">
              <input type="password" name="conf_password" class="form-control" id="conf_password"
                placeholder="Konfirmasi Password" autofocus>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" name="update_password" class="btn btn-primary">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>
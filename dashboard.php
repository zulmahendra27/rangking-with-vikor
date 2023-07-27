<div class="card overflow-hidden" style="border-radius: 7px;">
  <div class="card-header bg-secondary p-4">
    <h4 class="card-title m-0 text-white"><?= $title ?></h4>
  </div>

  <div class="card-body">
    <div class="card-group">
      <div class="card border-left border-right bg-secondary text-white">
        <div class="card-body">
          <div class="d-flex d-lg-flex d-md-block align-items-center">
            <div>
              <h2 class="mb-1 font-weight-medium">
                <?= select($c, 'guru a', ['join' => 'INNER JOIN users b ON a.id_user=b.id_user', 'where' => "b.level='guru'"])->num_rows ?>
              </h2>
              <h6 class="font-weight-normal mb-0 w-100 text-truncate">Guru</h6>
            </div>
            <div class="ml-auto mt-md-3 mt-lg-0">
              <span class="opacity-7"><i data-feather="user"></i></span>
            </div>
          </div>
        </div>
      </div>
      <div class="card border-left border-right bg-info text-white">
        <div class="card-body">
          <div class="d-flex d-lg-flex d-md-block align-items-center">
            <div>
              <h2 class="mb-1 font-weight-medium"><?= select($c, 'siswa')->num_rows ?></h2>
              <h6 class="font-weight-normal mb-0 w-100 text-truncate">Siswa</h6>
            </div>
            <div class="ml-auto mt-md-3 mt-lg-0">
              <span class="opacity-7"><i data-feather="users"></i></span>
            </div>
          </div>
        </div>
      </div>
      <?php if (in_array($_SESSION['level'], [])) : ?>
        <?php if (in_array($_SESSION['level'], ['admin'])) : ?>
          <div class="card border-left border-right bg-info text-white">
            <div class="card-body">
              <div class="d-flex d-lg-flex d-md-block align-items-center">
                <div>
                  <h2 class="mb-1 font-weight-medium"><?= select($c, 'wali')->num_rows ?></h2>
                  <h6 class="font-weight-normal mb-0 w-100 text-truncate">Wali</h6>
                </div>
                <div class="ml-auto mt-md-3 mt-lg-0">
                  <span class="opacity-7"><i data-feather="user-check"></i></span>
                </div>
              </div>
            </div>
          </div>
        <?php endif; ?>
        <div class="card border-left border-right bg-secondary text-white">
          <div class="card-body">
            <div class="d-flex d-lg-flex d-md-block align-items-center">
              <div>
                <h2 class="mb-1 font-weight-medium"><?= select($c, 'rombel')->num_rows ?></h2>
                <h6 class="font-weight-normal mb-0 w-100 text-truncate">Rombel</h6>
              </div>
              <div class="ml-auto mt-md-3 mt-lg-0">
                <span class="opacity-7"><i data-feather="home"></i></span>
              </div>
            </div>
          </div>
        </div>
        <div class="card border-left border-right bg-info text-white">
          <div class="card-body">
            <div class="d-flex d-lg-flex d-md-block align-items-center">
              <div>
                <h2 class="mb-1 font-weight-medium"><?= select($c, 'mapel')->num_rows ?></h2>
                <h6 class="font-weight-normal mb-0 w-100 text-truncate">Mata Pelajaran</h6>
              </div>
              <div class="ml-auto mt-md-3 mt-lg-0">
                <span class="opacity-7"><i data-feather="book-open"></i></span>
              </div>
            </div>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>
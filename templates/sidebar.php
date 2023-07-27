<aside class="left-sidebar" data-sidebarbg="skin6">
  <!-- Sidebar scroll-->
  <div class="scroll-sidebar" data-sidebarbg="skin6">
    <!-- Sidebar navigation-->
    <nav class="sidebar-nav pt-0">
      <ul id="sidebarnav">
        <li class="sidebar-item <?= !isset($_GET['p']) ? 'selected' : ($_GET['p'] == 'dashboard' ? 'selected' : '') ?>">
          <a class="sidebar-link sidebar-link" href="?p=dashboard" aria-expanded="false">
            <i data-feather="bar-chart-2" class="feather-icon"></i>
            <span class="hide-menu">Dashboard</span>
          </a>
        </li>

        <?php if ($_SESSION['level'] == 'admin') : ?>
        <li class="list-divider"></li>
        <li class="nav-small-cap"><span class="hide-menu">Master Data</span></li>

        <li class="sidebar-item <?= $_GET['p'] == 'guru' ? 'selected' : '' ?>">
          <a class="sidebar-link" href="?p=guru" aria-expanded="false">
            <i data-feather="user" class="feather-icon"></i>
            <span class="hide-menu">Guru</span>
          </a>
        </li>

        <li class="sidebar-item <?= $_GET['p'] == 'siswa' ? 'selected' : '' ?>">
          <a class="sidebar-link" href="?p=siswa" aria-expanded="false">
            <i data-feather="users" class="feather-icon"></i>
            <span class="hide-menu">Siswa</span>
          </a>
        </li>

        <li class="sidebar-item <?= $_GET['p'] == 'kepsek' ? 'selected' : '' ?>">
          <a class="sidebar-link" href="?p=kepsek" aria-expanded="false">
            <i data-feather="user" class="feather-icon"></i>
            <span class="hide-menu">Kepala Sekolah</span>
          </a>
        </li>

        <li class="sidebar-item <?= $_GET['p'] == 'semester' ? 'selected' : '' ?>">
          <a class="sidebar-link" href="?p=semester" aria-expanded="false">
            <i data-feather="book-open" class="feather-icon"></i>
            <span class="hide-menu">Semester Aktif</span>
          </a>
        </li>

        <?php if (in_array($_SESSION['level'], [])) : ?>
        <li class="sidebar-item <?= $_GET['p'] == 'wali' ? 'selected' : '' ?>">
          <a class="sidebar-link" href="?p=wali" aria-expanded="false">
            <i data-feather="user-check" class="feather-icon"></i>
            <span class="hide-menu">Wali</span>
          </a>
        </li>

        <li class="sidebar-item <?= $_GET['p'] == 'rombel' ? 'selected' : '' ?>">
          <a class="sidebar-link" href="?p=rombel" aria-expanded="false">
            <i data-feather="home" class="feather-icon"></i>
            <span class="hide-menu">Rombongan Belajar</span>
          </a>
        </li>

        <li class="sidebar-item <?= $_GET['p'] == 'mapel' ? 'selected' : '' ?>">
          <a class="sidebar-link" href="?p=mapel" aria-expanded="false">
            <i data-feather="book-open" class="feather-icon"></i>
            <span class="hide-menu">Mata Pelajaran</span>
          </a>
        </li>

        <li class="sidebar-item <?= $_GET['p'] == 'pengampu' ? 'selected' : '' ?>">
          <a class="sidebar-link" href="?p=pengampu" aria-expanded="false">
            <i data-feather="book" class="feather-icon"></i>
            <span class="hide-menu">Pengampu Mapel</span>
          </a>
        </li>
        <?php endif;
        endif; ?>

        <?php
        if (in_array($_SESSION['level'], ['admin', 'guru', 'siswa', 'kepsek'])) :
          $href = in_array($_SESSION['level'], ['admin', 'guru', 'kepsek']) ? 'penilaian_guru' : 'penilaian_kinerja_guru';
        ?>
        <li class="list-divider"></li>
        <li class="nav-small-cap"><span class="hide-menu">Penilaian Guru</span></li>

        <li class="sidebar-item <?= $_GET['p'] == $href ? 'selected' : '' ?>">
          <a class="sidebar-link" href="?p=<?= $href ?>" aria-expanded="false">
            <i data-feather="award" class="feather-icon"></i>
            <span class="hide-menu">Penilaian Guru</span>
          </a>
        </li>
        <?php endif; ?>

        <?php if ($_SESSION['level'] == 'admin') : ?>
        <!-- <li class="sidebar-item <?php /* in_array($_GET['p'], ['kriteria', 'sub_kriteria']) ? 'selected' : '' */ ?>">
            <a class="sidebar-link" href="?p=kriteria" aria-expanded="false">
              <i data-feather="grid" class="feather-icon"></i>
              <span class="hide-menu">Kriteria Pemilihan</span>
            </a>
          </li> -->

        <li class="sidebar-item <?= $_GET['p'] == 'proses_vikor' ? 'selected' : '' ?>">
          <a class="sidebar-link" href="?p=proses_vikor" aria-expanded="false">
            <i data-feather="cpu" class="feather-icon"></i>
            <span class="hide-menu">Proses VIKOR</span>
          </a>
        </li>
        <?php endif; ?>

        <?php if (!in_array($_SESSION['level'], ['siswa', 'kepsek'])) : ?>

        <li class="list-divider"></li>
        <li class="nav-small-cap"><span class="hide-menu">Akademik</span></li>

        <?php endif; ?>

        <?php if ($_SESSION['level'] == 'guru') : ?>
        <li class="sidebar-item <?= in_array($_GET['p'], ['absensi', 'absensi_guru']) ? 'selected' : '' ?>"> <a
            class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false"><i
              data-feather="check-square" class="feather-icon"></i><span class="hide-menu">Daftar Hadir</span></a>
          <ul aria-expanded="false" class="collapse first-level base-level-line">
            <li class="sidebar-item <?= $_GET['p'] == 'absensi_guru' ? 'active' : '' ?>">
              <a href="?p=absensi_guru" class="sidebar-link">
                <span class="hide-menu">Guru</span>
              </a>
            </li>
            <li class="sidebar-item <?= $_GET['p'] == 'absensi' ? 'active' : '' ?>">
              <a href="?p=absensi" class="sidebar-link">
                <span class="hide-menu">Siswa</span>
              </a>
            </li>
          </ul>
        </li>
        <?php else : if (!in_array($_SESSION['level'], ['siswa', 'kepsek'])) : ?>

        <li class="sidebar-item <?= $_GET['p'] == 'absensi' ? 'selected' : '' ?>">
          <a class="sidebar-link" href="?p=absensi" aria-expanded="false">
            <i data-feather="check-square" class="feather-icon"></i>
            <span class="hide-menu">Daftar Hadir</span>
          </a>
        </li>
        <?php endif;
        endif; ?>

        <?php if (!in_array($_SESSION['level'], ['siswa'])) :
          if (!in_array($_SESSION['level'], ['kepsek'])) : ?>
        <li class="sidebar-item <?= in_array($_GET['p'], ['penilaian', 'penilaian_add']) ? 'selected' : '' ?>">
          <a class="sidebar-link" href="?p=penilaian" aria-expanded="false">
            <i data-feather="edit-3" class="feather-icon"></i>
            <span class="hide-menu">Penilaian</span>
          </a>
        </li>
        <?php endif; ?>

        <li class="list-divider"></li>
        <li class="nav-small-cap"><span class="hide-menu">Laporan</span></li>

        <li class="sidebar-item <?= $_GET['p'] == 'laporan' ? 'selected' : '' ?>">
          <a class="sidebar-link" href="?p=laporan" aria-expanded="false">
            <i data-feather="file" class="feather-icon"></i>
            <span class="hide-menu">Laporan</span>
          </a>
        </li>

        <?php endif; ?>

        <li class="list-divider mb-5"></li>

      </ul>
    </nav>
    <!-- End Sidebar navigation -->
  </div>
  <!-- End Sidebar scroll-->
</aside>
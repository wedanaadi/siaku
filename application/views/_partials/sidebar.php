<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<div class="main-sidebar sidebar-style-2">
  <aside id="sidebar-wrapper">
    <div class="sidebar-brand">
      <img alt="image" src="<?php echo base_url(); ?>assets/img/logo/logo.png" class="mr-1" style="width: 35px; margin-top:-5px;">
      <a href="<?php echo base_url(); ?>dist/index">PAUD & TK Rare Suci</a>
    </div>
    <div class="sidebar-brand sidebar-brand-sm">
      <a href="<?php echo base_url(); ?>dist/index">SIA</a>
    </div>
    <ul class="sidebar-menu">
      <li class="menu-header">Menu</li>
      <li class="<?php echo $this->uri->segment(1) == 'Dashboard_c' && $this->uri->segment(2) == 'index' ? 'active' : ''; ?>"><a class="<?php echo $this->uri->segment(1) == 'Dashboard_c' && $this->uri->segment(2) == 'index' ? 'nav-link beep beep-sidebar' : 'nav-link'; ?>" href="<?php echo base_url(); ?>Dashboard_c/index"><i class="fas fa-home"></i> <span>Dashboard</span></a></li>
      <?php if ($this->session->userdata('jabatan') === 'siswa') : ?>
        <li class="<?php echo $this->uri->segment(2) == 'profil' ? 'active' : ''; ?>"><a class="<?php echo $this->uri->segment(2) == 'profil' ? 'nav-link beep beep-sidebar' : 'nav-link'; ?>" href="<?php echo base_url(); ?>Siswa_c/profil"><i class="fas fa-user-tag"></i> <span>Profil</span></a></li>
      <?php endif; ?>
      <?php if ($this->session->userdata('jabatan') === 'admin') : ?>
        <li class="<?= $this->uri->segment(1) == 'Kelompok_c' || $this->uri->segment(1) == 'TahunAjaran_c' || $this->uri->segment(1) == 'Siswa_c' && $this->uri->segment(2) == 'index' || $this->uri->segment(1) == 'Pengeluaran_c' || $this->uri->segment(1) == 'User_c' || $this->uri->segment(1) == 'Pemasukan_c' ? 'active' : ''; ?>">
          <a href="#" class="nav-link has-dropdown"><i class="fas fa-th"></i><span>Master Data</span></a>
          <ul class="dropdown-menu">
            <li class="<?= $this->uri->segment(1) == 'Kelompok_c' && $this->uri->segment(2) == 'index' ? 'active' : ''; ?>"><a class="<?= $this->uri->segment(1) == 'Kelompok_c' && $this->uri->segment(2) == 'index' ? 'nav-link beep beep-sidebar' : 'nav-link'; ?>" href="<?php echo base_url(); ?>Kelompok_c/index">Kelompok</a></li>
            <li class="<?= $this->uri->segment(1) == 'TahunAjaran_c' && $this->uri->segment(2) == 'index' ? 'active' : ''; ?>"><a class="<?= $this->uri->segment(1) == 'TahunAjaran_c' && $this->uri->segment(2) == 'index' ? 'nav-link beep beep-sidebar' : 'nav-link'; ?>" href="<?php echo base_url(); ?>TahunAjaran_c/index">Tahun Ajaran</a></li>
            <li class="<?= $this->uri->segment(1) == 'Siswa_c' && $this->uri->segment(2) == 'index' ? 'active' : ''; ?>"><a class="<?= $this->uri->segment(1) == 'Siswa_c' && $this->uri->segment(2) == 'index' ? 'nav-link beep beep-sidebar' : 'nav-link'; ?>" href="<?php echo base_url(); ?>Siswa_c/index">Siswa</a></li>
            <li class="<?= $this->uri->segment(1) == 'User_c' && $this->uri->segment(2) == 'index' ? 'active' : ''; ?>"><a class="<?= $this->uri->segment(1) == 'User_c' && $this->uri->segment(2) == 'index' ? 'nav-link beep beep-sidebar' : 'nav-link'; ?>" href="<?php echo base_url(); ?>User_c/index">User</a></li>
            <li class="<?= $this->uri->segment(1) == 'Pengeluaran_c' && $this->uri->segment(2) == 'index' ? 'active' : ''; ?>"><a class="<?= $this->uri->segment(1) == 'Pengeluaran_c' && $this->uri->segment(2) == 'index' ? 'nav-link beep beep-sidebar' : 'nav-link'; ?>" href="<?php echo base_url(); ?>Pengeluaran_c/index">Pengeluaran</a></li>
            <li class="<?= $this->uri->segment(1) == 'Pemasukan_c' && $this->uri->segment(2) == 'index' ? 'active' : ''; ?>"><a class="<?= $this->uri->segment(1) == 'Pemasukan_c' && $this->uri->segment(2) == 'index' ? 'nav-link beep beep-sidebar' : 'nav-link'; ?>" href="<?php echo base_url(); ?>Pemasukan_c/index">Pemasukan</a></li>
          </ul>
        </li>
      <?php endif; ?>
      <?php if ($this->session->userdata('jabatan') !== 'kepsek') : ?>
        <li class="<?= $this->uri->segment(1) == 'Tabungan_c' && $this->uri->segment(2) == 'index' || $this->uri->segment(1) == 'Tabungan_c' && $this->uri->segment(2) == 'detilWali' ? 'active' : ''; ?>">
          <a href="#" class="nav-link has-dropdown"><i class="fas fa-money-check-alt"></i><span>Tabungan</span></a>
          <ul class="dropdown-menu">
            <?php if ($this->session->userdata('jabatan') === 'admin') : ?>
              <li class="<?= $this->uri->segment(1) == 'Tabungan_c' && $this->uri->segment(2) == 'index' ? 'active' : ''; ?>"><a class="<?= $this->uri->segment(1) == 'Tabungan_c' && $this->uri->segment(2) == 'index' ? 'nav-link beep beep-sidebar' : 'nav-link'; ?>" href="<?php echo base_url(); ?>Tabungan_c/index">Tabungan</a></li>
            <?php endif; ?>
            <?php if ($this->session->userdata('jabatan') === 'siswa') : ?>
              <li class="<?= $this->uri->segment(1) == 'Tabungan_c' && $this->uri->segment(2) == 'detilWali' ? 'active' : ''; ?>"><a class="<?= $this->uri->segment(1) == 'Tabungan_c' && $this->uri->segment(2) == 'detilWali' ? 'nav-link beep beep-sidebar' : 'nav-link'; ?>" href="<?php echo base_url(); ?>Tabungan_c/detilWali">Detil Tabungan</a></li>
            <?php endif; ?>
          </ul>
        </li>
        <li class="<?= $this->uri->segment(1) == 'Spp_c' && $this->uri->segment(2) == 'index' || $this->uri->segment(1) == 'Spp_c' && $this->uri->segment(2) == 'sppTunggakan' || $this->uri->segment(1) == 'Spp_c' && $this->uri->segment(2) == 'sppLunas' || $this->uri->segment(1) == 'Spp_c' && $this->uri->segment(2) == 'verifikasi' || $this->uri->segment(1) == 'Spp_c' && $this->uri->segment(2) == 'detail' ? 'active' : ''; ?>">
          <a href="#" class="nav-link has-dropdown"><i class="fas fa-file-invoice-dollar"></i><span>SPP</span></a>
          <ul class="dropdown-menu">
            <?php if ($this->session->userdata('jabatan') === 'admin') : ?>
              <li class="<?= $this->uri->segment(1) == 'Spp_c' && $this->uri->segment(2) == 'index' ? 'active' : ''; ?>"><a class="<?= $this->uri->segment(1) == 'Spp_c' && $this->uri->segment(2) == 'index' ? 'nav-link beep beep-sidebar' : 'nav-link'; ?>" href="<?php echo base_url(); ?>Spp_c/index">Tagihan SPP</a></li>
              <li class="<?= $this->uri->segment(1) == 'Spp_c' && $this->uri->segment(2) == 'verifikasi' ? 'active' : ''; ?>"><a class="<?= $this->uri->segment(1) == 'Spp_c' && $this->uri->segment(2) == 'verifikasi' ? 'nav-link beep beep-sidebar' : 'nav-link'; ?>" href="<?php echo base_url(); ?>Spp_c/verifikasi">Verifikasi Tagihan SPP</a></li>
            <?php endif; ?>
            <?php if ($this->session->userdata('jabatan') === 'siswa') : ?>
              <li class="<?= $this->uri->segment(1) == 'Spp_c' && $this->uri->segment(2) == 'detail' ? 'active' : ''; ?>"><a class="<?= $this->uri->segment(1) == 'Spp_c' && $this->uri->segment(2) == 'detail' ? 'nav-link beep beep-sidebar' : 'nav-link'; ?>" href="<?php echo base_url('Spp_c/detail/' . $this->session->userdata('kodeuser')); ?>">Tagihan SPP</a></li>
            <?php endif; ?>
            <li class="<?= $this->uri->segment(1) == 'Spp_c' && $this->uri->segment(2) == 'sppTunggakan' ? 'active' : ''; ?>"><a class="<?= $this->uri->segment(1) == 'Spp_c' && $this->uri->segment(2) == 'sppTunggakan' ? 'nav-link beep beep-sidebar' : 'nav-link'; ?>" href="<?php echo base_url(); ?>Spp_c/sppTunggakan">Tunggakan SPP Lainnya</a></li>
            <li class="<?= $this->uri->segment(1) == 'Spp_c' && $this->uri->segment(2) == 'sppLunas' ? 'active' : ''; ?>"><a class="<?= $this->uri->segment(1) == 'Spp_c' && $this->uri->segment(2) == 'sppLunas' ? 'nav-link beep beep-sidebar' : 'nav-link'; ?>" href="<?php echo base_url(); ?>Spp_c/sppLunas">SPP Lunas</a></li>
          </ul>
        </li>
      <?php endif; ?>
      <?php if ($this->session->userdata('jabatan') !== 'siswa') : ?>
        <li class="<?= $this->uri->segment(1) == 'Tabungan_c' && $this->uri->segment(2) == 'getLaporan' || $this->uri->segment(1) == 'Pengeluaran_c' && $this->uri->segment(2) == 'getLaporan' || $this->uri->segment(2) == 'getLaporanTagihan' ||  $this->uri->segment(2) == 'getLaporanTunggakan' || $this->uri->segment(2) == 'getLaporanLunas' || $this->uri->segment(2) == 'rekap' ? 'active' : '' ?>">
          <a href="#" class="nav-link has-dropdown"><i class="fas fa-file-alt"></i><span>Laporan</span></a>
          <ul class="dropdown-menu">
            <!-- <li class="<?= $this->uri->segment(2) == 'getLaporanTagihan' ? 'active' : '' ?>"><a class="<?= $this->uri->segment(2) == 'getLaporanTagihan' ? 'nav-link beep beep-sidebar' : 'nav-link' ?>" href="<?php echo base_url(); ?>Spp_c/getLaporanTagihan">Tagihan SPP</a></li> -->
            <li class="<?= $this->uri->segment(2) == 'getLaporanTunggakan' ? 'active' : '' ?>"><a class="<?= $this->uri->segment(2) == 'getLaporanTunggakan' ? 'nav-link beep beep-sidebar' : 'nav-link' ?>" href="<?php echo base_url(); ?>Spp_c/getLaporanTunggakan">Tunggakan</a></li>
            <li class="<?= $this->uri->segment(2) == 'getLaporanLunas' ? 'active' : '' ?>"><a class="<?= $this->uri->segment(2) == 'getLaporanLunas' ? 'nav-link beep beep-sidebar' : 'nav-link' ?>" href="<?php echo base_url(); ?>Spp_c/getLaporanLunas">SPP Lunas</a></li>
            <li class="<?= $this->uri->segment(1) == 'Tabungan_c' && $this->uri->segment(2) == 'getLaporan' ? 'active' : '' ?>"><a class="<?= $this->uri->segment(1) == 'Tabungan_c' && $this->uri->segment(2) == 'getLaporan' ? 'nav-link beep beep-sidebar' : 'nav-link' ?>" href="<?php echo base_url(); ?>Tabungan_c/getLaporan">Tabungan</a></li>
            <li class="<?= $this->uri->segment(2) == 'rekap' ? 'active' : '' ?>"><a class="<?= $this->uri->segment(2) == 'rekap' ? 'nav-link beep beep-sidebar' : 'nav-link' ?>" href="<?php echo base_url(); ?>Tabungan_c/rekap">Rekap Tabungan</a></li>
            <li class="<?= $this->uri->segment(1) == 'Pengeluaran_c' && $this->uri->segment(2) == 'getLaporan' ? 'active' : '' ?>"><a class="<?= $this->uri->segment(1) == 'Pengeluaran_c' && $this->uri->segment(2) == 'getLaporan' ? 'nav-link beep beep-sidebar' : 'nav-link' ?>" href="<?php echo base_url(); ?>Pengeluaran_c/getLaporan">Laporan Rekap Bulanan</a></li>
          </ul>
        </li>
      <?php endif; ?>
      <?php if ($this->session->userdata('jabatan') === 'admin') : ?>
        <li class="<?php echo $this->uri->segment(1) == 'Dashboard_c' && $this->uri->segment(2) == 'setting' ? 'active' : ''; ?>"><a class="<?php echo $this->uri->segment(1) == 'Dashboard_c' && $this->uri->segment(2) == 'setting' ? 'nav-link beep beep-sidebar' : 'nav-link'; ?>" href="<?php echo base_url(); ?>Dashboard_c/setting"><i class="fas fa-cog"></i> <span>Setting Sistem</span></a></li>
      <?php endif; ?>
    </ul>

    <!-- <div class="mt-4 mb-4 p-3 hide-sidebar-mini">
            <a href="https://getstisla.com/docs" class="btn btn-primary btn-lg btn-block btn-icon-split">
              <i class="fas fa-rocket"></i> Documentation
            </a>
          </div> -->
  </aside>
</div>
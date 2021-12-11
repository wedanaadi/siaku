<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<body>
  <div id="app">
    <div class="main-wrapper main-wrapper-1">
      <div class="navbar-bg"></div>
      <nav class="navbar navbar-expand-lg main-navbar">
        <form class="form-inline mr-auto">
          <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
            <li><a href="#" data-toggle="search" class="nav-link nav-link-lg d-sm-none"><i class="fas fa-search"></i></a></li>
          </ul>
        </form>
        <ul class="navbar-nav navbar-right">
          <?php if ($this->session->userdata('jabatan') == 'siswa') :
            $cek = $this->session->userdata('cek');
            if ($cek['tagihan'] > 0 or $cek['tunggakan'] > 0) {
              $c = ' beep';
            } else {
              $c = '';
            }
          ?>
            <li class="dropdown dropdown-list-toggle"><a href="#" data-toggle="dropdown" class="nav-link nav-link-lg message-toggle <?= $c; ?>"><i class="far fa-envelope"></i></a>
              <div class="dropdown-menu dropdown-list dropdown-menu-right">
                <div class="dropdown-header">Pesan
                  <div class="float-right">
                    <!-- <a href="#">Mark All As Read</a> -->
                  </div>
                </div>
                <div class="dropdown-list-content dropdown-list-message">
                  <?php if ($cek['tagihan'] > 0) : ?>
                    <a href="#" class="dropdown-item dropdown-item-unread">
                      <div class="dropdown-item-desc">
                        <b>Tagihan SPP</b>
                        <p>Hello, Tagihan SPP tahun Ajaran ini sudah dibuat,mohon melakukan pembayaran!</p>
                      </div>
                    </a>
                  <?php endif; ?>
                  <?php if ($cek['tunggakan'] > 0) : ?>
                    <a href="#" class="dropdown-item dropdown-item-unread">
                      <div class="dropdown-item-desc">
                        <b>Tunggakan SPP</b>
                        <p>Hello, Anda memiliki tunggakan SPP, mohon melakukan pelunasan!</p>
                      </div>
                    </a>
                  <?php endif; ?>
                  <div class="dropdown-footer text-center">
                    <p>&nbsp;</p>
                  </div>
                </div>
              </div>
            </li>
          <?php endif; ?>
          <li class="dropdown"><a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
              <figure class="avatar mr-2 avatar-sm bg-success text-white" data-initial="SIA"></figure>
              <!-- <img alt="image" src="<?php echo base_url(); ?>assets/img/avatar/avatar-1.png" class="rounded-circle mr-1"> -->
              <div class="d-sm-none d-lg-inline-block">Hi, <?= $this->session->userdata('namauser') ?></div>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
              <div class="dropdown-title">
                <?php
                function time_elapsed_string($datetime, $full = false)
                {
                  $now = new DateTime;
                  $ago = new DateTime($datetime);
                  $diff = $now->diff($ago);

                  $diff->w = floor($diff->d / 7);
                  $diff->d -= $diff->w * 7;

                  $string = array(
                    'y' => 'year',
                    'm' => 'month',
                    'w' => 'week',
                    'd' => 'day',
                    'h' => 'hour',
                    'i' => 'minute',
                    's' => 'second',
                  );
                  foreach ($string as $k => &$v) {
                    if ($diff->$k) {
                      $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
                    } else {
                      unset($string[$k]);
                    }
                  }

                  if (!$full) $string = array_slice($string, 0, 1);
                  return $string ? implode(', ', $string) . ' ago' : 'just now';
                }
                echo time_elapsed_string($this->session->userdata('timeago')); ?>
              </div>
              <!-- <a href="<?php echo base_url(); ?>dist/features_profile" class="dropdown-item has-icon">
                <i class="far fa-user"></i> Profile
              </a> -->
              <!-- <a href="<?php echo base_url(); ?>dist/features_activities" class="dropdown-item has-icon">
                <i class="fas fa-bolt"></i> Activities
              </a>
              <a href="<?php echo base_url(); ?>dist/features_settings" class="dropdown-item has-icon">
                <i class="fas fa-cog"></i> Settings
              </a> -->
              <div class="dropdown-divider"></div>
              <a href="<?= base_url('Auth_c/logout') ?>" class="dropdown-item has-icon text-danger">
                <i class="fas fa-sign-out-alt"></i> Logout
              </a>
            </div>
          </li>
        </ul>
      </nav>
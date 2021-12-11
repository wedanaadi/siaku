<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title><?php echo $title; ?> &mdash; SIAKU</title>

  <style>
    .table:not(.table-sm) thead th {
      background-color: white !important;
    }

    .section .section-header:before {
      content: ' ';
      border-radius: 5px;
      height: 8px;
      width: 30px;
      background-color: #6777ef;
      display: inline-block;
      /* float: left; */
      margin-top: 6px;
      margin-right: 15px;
    }

    .table:not(.table-sm) thead th {
      background-color: rgba(0, 0, 0, 0.04) !important;
      /* border: 1px solid #DDDDDD; */
    }

    td.details-control {
      background: url('../assets/img/details_open.png') no-repeat center center;
      cursor: pointer;
    }

    tr.shown td.details-control {
      background: url('../assets/img/details_close.png') no-repeat center center;
    }
  </style>

  <!-- General CSS Files -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/fontawesome/css/all.min.css">

  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/datatables/datatables.min.css">
  <!-- <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/datatables/DataTables-1.11.3/css/dataTables.bootstrap.css"> -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/datatables/FixedColumns-4.0.0/css/fixedColumns.bootstrap.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/datatables/Select-1.2.4/css/select.bootstrap4.min.css">

  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/izitoast/css/iziToast.min.css">

  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/dtp/css/bootstrap-datetimepicker.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/select2/dist/css/select2.min.css">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.min.css" crossorigin="anonymous">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/inputfile/css/fileinput.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/inputfile/themes/explorer-fas/theme.css">
  <!-- CSS Libraries -->

  <!-- Template CSS -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/components.css">
  <!-- Start GA -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=UA-94034622-3"></script>
  <script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
      dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', 'UA-94034622-3');
  </script>
  <!-- /END GA -->
</head>

<div id="app">
  <section class="section">
    <div class="container mt-5">
      <div class="row">
        <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
          <div class="login-brand">
            <img src="<?php echo base_url(); ?>assets/img/logo/logo.png" alt="logo" width="100" class="shadow-light">
          </div>

          <div class="card card-primary">
            <div class="card-header">
              <h4>Login Sebagai</h4>
            </div>

            <div class="card-body">
              <form method="POST" action="#" id="auth_f" class="needs-validation" novalidate="" autocomplete="off">
                <div class="form-group">
                  <div class="selectgroup w-100">
                    <label class="selectgroup-item">
                      <input type="radio" name="value" value="auth1" class="selectgroup-input" checked="">
                      <span class="selectgroup-button">Pegawai</span>
                    </label>
                    <label class="selectgroup-item">
                      <input type="radio" name="value" value="auth2" class="selectgroup-input">
                      <span class="selectgroup-button">Murid</span>
                    </label>
                  </div>
                </div>
                <div id="auth"></div>
                <div class="form-group">
                  <label for="username">Username</label>
                  <input id="username" type="username" class="form-control" name="username" tabindex="1" required autofocus>
                  <div class="invalid-feedback">
                    Masukan Username
                  </div>
                </div>

                <div class="form-group">
                  <div class="d-block">
                    <label for="password" class="control-label">Password</label>
                  </div>
                  <input id="password" type="password" class="form-control" name="password" tabindex="2" required>
                  <div class="invalid-feedback">
                    Masukan Password
                  </div>
                </div>

                <div class="form-group">
                  <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                    Login
                  </button>
                </div>
              </form>

            </div>
          </div>
          <div class="simple-footer">
            Copyright &copy; SIAKU 2021
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<?php $this->load->view('_partials/js'); ?>

<script>
  $('#auth_f').on('submit', function(e) {
    e.preventDefault();
    $.ajax({
      method: "POST",
      dataType: "JSON",
      url: "<?= base_url() ?>Auth_c/loginProses",
      contentType: false,
      processData: false,
      data: new FormData($("#auth_f")[0]),
      success: function(respon) {
        if (respon.action === '1') {
          notifsukses2(respon.msg);
          setTimeout(function() {
            window.location.href = "<?= base_url('Dashboard_c') ?>";
          }, 1000);
        } else {
          notifgagal2(respon.msg);
        }
      }
    });
    return false;
  });

  $('input[type=radio]').change(function() {
    $('[name=username]').val('');
    $('[name=password]').val('');
  });
</script>
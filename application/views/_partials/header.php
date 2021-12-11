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

    .nicescroll-cursors {
      background-color: #ffffff !important;
    }

    .dropdown-list .dropdown-list-content {
      height: auto !important;
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

  <!-- <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/dtp/css/bootstrap-datetimepicker.min.css"> -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/bootstrap-daterangepicker/daterangepicker.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/select2/dist/css/select2.min.css">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.min.css" crossorigin="anonymous">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/inputfile/css/fileinput.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/inputfile/themes/explorer-fas/theme.css">
  <!-- CSS Libraries -->
  <?php
  if ($this->uri->segment(2) == "" || $this->uri->segment(2) == "index") { ?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/jqvmap/dist/jqvmap.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/summernote/summernote-bs4.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/owlcarousel2/dist/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/owlcarousel2/dist/assets/owl.theme.default.min.css">
  <?php
  } elseif ($this->uri->segment(2) == "index_0") { ?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/jqvmap/dist/jqvmap.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/weather-icon/css/weather-icons.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/weather-icon/css/weather-icons-wind.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/summernote/summernote-bs4.css">
  <?php
  } elseif ($this->uri->segment(2) == "bootstrap_card") { ?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/chocolat/dist/css/chocolat.css">
  <?php
  } elseif ($this->uri->segment(2) == "bootstrap_modal") { ?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/prism/prism.css">
  <?php
  } elseif ($this->uri->segment(2) == "components_gallery") { ?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/chocolat/dist/css/chocolat.css">
  <?php
  } elseif ($this->uri->segment(2) == "components_multiple_upload") { ?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/dropzonejs/dropzone.css">
  <?php
  } elseif ($this->uri->segment(2) == "components_statistic") { ?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/jqvmap/dist/jqvmap.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/flag-icon-css/css/flag-icon.min.css">
  <?php
  } elseif ($this->uri->segment(2) == "components_user") { ?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/bootstrap-social/bootstrap-social.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/owlcarousel2/dist/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/owlcarousel2/dist/assets/owl.theme.default.min.css">
  <?php
  } elseif ($this->uri->segment(2) == "forms_advanced_form") { ?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/bootstrap-daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/select2/dist/css/select2.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/jquery-selectric/selectric.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/bootstrap-timepicker/css/bootstrap-timepicker.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.css">
  <?php
  } elseif ($this->uri->segment(2) == "forms_editor") { ?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/summernote/summernote-bs4.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/codemirror/lib/codemirror.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/codemirror/theme/duotone-dark.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/jquery-selectric/selectric.css">
  <?php
  } elseif ($this->uri->segment(2) == "modules_calendar") { ?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/fullcalendar/fullcalendar.min.css">
  <?php
  } elseif ($this->uri->segment(2) == "modules_datatables") { ?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/datatables/datatables.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/datatables/Select-1.2.4/css/select.bootstrap4.min.css">
  <?php
  } elseif ($this->uri->segment(2) == "modules_ion_icons") { ?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/ionicons/css/ionicons.min.css">
  <?php
  } elseif ($this->uri->segment(2) == "modules_owl_carousel") { ?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/owlcarousel2/dist/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/owlcarousel2/dist/assets/owl.theme.default.min.css">
  <?php
  } elseif ($this->uri->segment(2) == "modules_toastr") { ?>

  <?php
  } elseif ($this->uri->segment(2) == "modules_vector_map") { ?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/izitoast/css/iziToast.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/jqvmap/dist/jqvmap.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/flag-icon-css/css/flag-icon.min.css">
  <?php
  } elseif ($this->uri->segment(2) == "modules_weather_icon") { ?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/weather-icon/css/weather-icons.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/weather-icon/css/weather-icons-wind.min.css">
  <?php
  } elseif ($this->uri->segment(2) == "auth_login") { ?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/bootstrap-social/bootstrap-social.css">
  <?php
  } elseif ($this->uri->segment(2) == "auth_register") { ?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/jquery-selectric/selectric.css">
  <?php
  } elseif ($this->uri->segment(2) == "features_post_create") { ?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/summernote/summernote-bs4.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/jquery-selectric/selectric.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.css">
  <?php
  } elseif ($this->uri->segment(2) == "features_posts") { ?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/jquery-selectric/selectric.css">
  <?php
  } elseif ($this->uri->segment(2) == "features_profile") { ?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/bootstrap-social/bootstrap-social.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/summernote/summernote-bs4.css">
  <?php
  } elseif ($this->uri->segment(2) == "features_setting_detail") { ?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/codemirror/lib/codemirror.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/codemirror/theme/duotone-dark.css">
  <?php
  } elseif ($this->uri->segment(2) == "features_tickets") { ?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/summernote/summernote-bs4.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/modules/chocolat/dist/css/chocolat.css">
  <?php
  } ?>

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

<?php
$this->load->view('_partials/layout');
$this->load->view('_partials/sidebar');
?>
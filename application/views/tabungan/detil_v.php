<?php
defined('BASEPATH') or exit('No direct script access allowed');
$this->load->view('_partials/header');
?>
<style>
  .table:not(.table-sm) thead th {
    /* background-color: rgba(0, 0, 0, 0.04) !important; */
    border: 1px solid #DDDDDD;
  }
</style>
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1>Detil Tabungan</h1>
    </div>

    <div class="section-body">
      <div class="row">
        <div class="col-12 col-md-12 col-lg-12">
          <div class="card">
            <div class="card-header">
              <!-- <h4>Card Header</h4> -->
              <?php if ($this->session->userdata('jabatan') === 'admin') : ?>
                <a href="<?= base_url() ?>Tabungan_c/index" class="btn btn-icon icon-left btn-info">
                  <i class="fas fa-chevron-left"></i>

                </a> &nbsp;
                <button class="btn btn-icon icon-left btn-danger" id-pk="<?= $datatabungan->id_tabungan; ?>" id="detail_print">
                  <i class="fas fa-print"></i>
                  Cetak Detail Tabungan
                </button>
              <?php endif; ?>
            </div>
            <div class="card-body">
              <?php if ($count > 0) : ?>
                <table width="80%" style="margin-top: 10px; margin-bottom: 10px;">
                  <tr>
                    <td width="15%"> <strong>No Tabungan </strong> </td>
                    <td width="30%"> <?= $datatabungan->id_tabungan; ?> </td>
                    <td width="15%"><strong> Saldo Rendah </strong> </td>
                    <td width="30%"> <?php echo ": " . number_format($min, 0, '.', '.') ?> </td>
                  </tr>
                  <tr>
                    <td width="15%"><strong> Nama </strong> </td>
                    <td width="30%"> <?= $datatabungan->nama_siswa ?> </td>
                    <td width="15%"><strong> Saldo Tinggi </strong> </td>
                    <td width="30%"> <?php echo ": " . number_format($max, 0, '.', '.') ?> </td>
                  </tr>
                  <tr>
                    <td width="15%"><strong> Tanggal Buka </strong> </td>
                    <td width="30%"> <?= $datatabungan->tgl_buka; ?></td>
                    <td width="15%"><strong> Saldo Akhir </strong> </td>
                    <td width="30%"> <?php echo ": " . number_format($datatabungan->jumlah_tabungan, 0, '.', '.') ?> </td>
                  </tr>
                </table>
                <hr>
                <div class="table-responsive">
                  <table class="table" id="detail_tabel" style="width:100%">
                    <thead>
                      <tr>
                        <th rowspan="2" class="align-middle">Tanggal</th>
                        <th colspan="2" class="text-center">Tabungan</th>
                        <th rowspan="2" class="align-middle">Saldo</th>
                        <th rowspan="2" class="align-middle" style="width: 30%">Keterangan</th>
                      </tr>
                      <tr>
                        <th>Masuk</th>
                        <th>Keluar</th>
                      </tr>
                    </thead>
                  </table>
                </div>
              <?php else : ?>
                <h4>Tabungan tidak ditemukan</h4>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
<?php $this->load->view('_partials/footer'); ?>

<script>
  $.fn.dataTableExt.oApi.fnPagingInfo = function(oSettings) {
    return {
      "iStart": oSettings._iDisplayStart,
      "iEnd": oSettings.fnDisplayEnd(),
      "iLength": oSettings._iDisplayLength,
      "iTotal": oSettings.fnRecordsTotal(),
      "iFilteredTotal": oSettings.fnRecordsDisplay(),
      "iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
      "iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
    };
  };

  var tabel = $("#detail_tabel").dataTable({
    initComplete: function() {
      var api = this.api();
      $('#detail_tabel_filter input').off('.DT').on('input.DT', function() {
        api.search(this.value).draw();
      });
    },
    oLanguage: {
      sProcessing: "loading..."
    },
    processing: false,
    ordering: false,
    searching: false,
    info: false,
    serverSide: true,
    paging: false,
    ajax: {
      "url": "<?php echo base_url() . 'Tabungan_c/ignited_detail/' . $datatabungan->id_tabungan ?>",
      "type": "POST"
    },
    columns: [{
        "data": "tgl_baru",
        class: 'text-center'
      },
      {
        "data": "masuk",
        class: 'text-center'
      },
      {
        "data": "keluar",
        class: 'text-center'
      },
      {
        "data": "saldo",
        class: 'text-center'
      },
      {
        "data": "keterangan",
        class: 'text-center'
      }
    ],
    // order: [
    //   [1, 'DESC']
    // ],
    columnDefs: [{
      targets: [1, 2, 3],
      render: $.fn.dataTable.render.number('.', ',', '', '')
    }],
    rowCallback: function(row, data, iDisplayIndex) {
      var info = this.fnPagingInfo();
      var page = info.iPage;
      var length = info.iLength;
    }
  });

  $(document).on('click', '#detail_print', function() {
    var id = $(this).attr('id-pk');
    window.open("<?php echo base_url() . 'Tabungan_c/cetak_detil_tabungan/' ?>" + id);
  });
</script>
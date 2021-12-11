<?php
defined('BASEPATH') or exit('No direct script access allowed');
$this->load->view('_partials/header');
?>

<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1>Data Tagihan SPP <?= $spp; ?></h1>
    </div>

    <div class="section-body">
      <div class="row">
        <div class="col-12 col-md-12 col-lg-12">
          <div class="card">
            <div class="card-header">
              <a href="<?= base_url() ?>Spp_c/tagihanSPP" class="btn btn-icon icon-left btn-primary" id="spp_t">
                <i class="fas fa-plus"></i>
                Buat Tagihan
              </a>
              &nbsp;
              <a href="<?= base_url() ?>Spp_c/editTagihanSPP" class="btn btn-icon icon-left btn-warning">
                <i class="fas fa-pencil"></i>
                Ubah Jumlah Tagihan SPP
              </a>
            </div>
            <div class="card-body">
              <div class="form-group col-3" id="ta-group">
                <!-- <label>Kelompok</label> -->
                <select name="kelompok" class="form-control js-select2" style="width:100%;" required="">
                </select>
                <div class="invalid-feedback">
                  Tahun Ajaran ?
                </div>
                <div class="valid-feedback">
                  Terisi!
                </div>
              </div>
              <div class="table-responsive">
                <table class="table table-striped" id="spp_tabel" style="width:100%">
                  <thead>
                    <tr>
                      <th>
                        #
                      </th>
                      <th>NIS</th>
                      <th>Nama Siswa</th>
                      <th>Kelompok</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- Modal -->
  <div class="modal fade" data-backdrop="static" role="dialog" id="modal">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Modal title</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="spp_f" class="needs-validation" novalidate="" autocomplete="off">
          <div class="modal-body">
            <div id="tag_html"></div>
          </div>
          <div class="modal-footer bg-whitesmoke br">
            <button type="button" class="btn btn-icon icon-left btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i>Tutup</button>
            <button type="submit" id="submit" class="btn btn-icon icon-left btn-primary">
              <i class="fas fa-save"></i>
              Simpan
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<?php $this->load->view('_partials/footer'); ?>

<script>
  let isSave = 1;
  let idspp = "";
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

  var tabel = $("#spp_tabel").dataTable({
    initComplete: function() {
      var api = this.api();
      $('#mytable_filter input').off('.DT').on('input.DT', function() {
        api.search(this.value).draw();
      });
    },
    oLanguage: {
      sProcessing: "loading..."
    },
    processing: false,
    serverSide: true,
    ajax: {
      "url": "<?php echo base_url() . 'Spp_c/ignited_data_siswa' ?>",
      "type": "POST"
    },
    columns: [{
        "data": null,
        width: "10px",
        orderable: false,
        searchable: false
      },
      {
        "data": "NIS"
      },
      {
        "data": "nama_siswa"
      },
      {
        "data": "nama_kelompok"
      },
      {
        "data": "view",
        width: "170px",
        orderable: false,
        searchable: false,
        class: 'text-center'
      }
    ],
    order: [
      [1, 'asc']
    ],
    rowCallback: function(row, data, iDisplayIndex) {
      var info = this.fnPagingInfo();
      var page = info.iPage;
      var length = info.iLength;
      $('td:eq(0)', row).html(iDisplayIndex + 1 + info.iStart);
    }
  });

  $(document).on('click', '#spp_t', function() {
    $.ajax({
      type: "GET",
      dataType: "JSON",
      url: "<?= base_url(); ?>Spp_c/form/",
      success: function(respon) {
        $('#spp_f')[0].reset();
        $('#spp_f').removeClass('was-validated');
        $('#submit').removeClass('disabled btn-progress');
        $("#tag_html").html(respon.view);
        $('.modal-title').text('Tambah Tagihan');
        $("#modal-body").scrollTop(0);
        $('#modal').modal('show');
      }
    });

    $('#spp_f').on('submit', function(e) {
      e.preventDefault();
      var $select2ta = $('[name=ta]', $(this));
      var $select2bulan = $('[name=bulan]', $(this));
      if ($select2ta.val() === null) {
        $('#ta-group .select2-selection').css('border-color', '#dc3545');
      } else {
        $('#ta-group .select2-selection').css('border-color', '#28a745');
      }
      if ($select2bulan.val() === null) {
        $('#bulan-group .select2-selection').css('border-color', '#dc3545');
      } else {
        $('#bulan-group .select2-selection').css('border-color', '#28a745');
      }
      $('#submit').addClass('disabled btn-progress');
      if (parseInt($('[name=jumlah]').inputmask('unmaskedvalue')) < 1000) {
        e.stopPropagation();
        $('[name=jumlah]').css("border-color", "red");
        $('.grupuang .invalid-feedback').css("display", "block");
        $('.grupuang .valid-feedback').css("display", "none");
        $('[name=jumlah]').css("border-color", "#dc3545")
        $('#submit').removeClass('disabled btn-progress');
        return false;
      }
      var form = $(this);
      if (form[0].checkValidity() === false) {
        event.stopPropagation();
        $('#submit').removeClass('disabled btn-progress');
      } else {
        $.ajax({
          method: "POST",
          contentType: false,
          processData: false,
          data: new FormData($("#spp_f")[0]),
          dataType: "JSON",
          url: '<?= base_url() ?>Spp_c/createTagihan',
          success: function(respon) {
            tabel.api().ajax.reload();
            setTimeout(function() {
              $('#modal').modal('hide');
              if (respon.status === 'sukses') {
                notifsukses(respon.msg, 'disimpan');
              } else {
                notifgagal2(respon.msg);
              }
            }, 200);
          }
        });
      }
      return false;
    });

  });

  $(function() {
    $('select[name=kelompok]').select2({
      placeholder: "Semua...",
      ajax: {
        url: "<?= base_url(); ?>Kelompok_c/select2/",
        dataType: 'json',
        data: function(params) {
          return {
            q: $.trim(params.term)
          };
        },
        processResults: function(data) {
          return {
            results: data
          };
        },
        cache: true
      }
    });

    select2isi("select[name=kelompok]", 'semua', 'Semua Kelompok');

    $('select[name=kelompok]').on('select2:select', function(e) {
      tabel.api().clear().destroy();
      $("#spp_tabel").dataTable({
        initComplete: function() {
          var api = this.api();
          $('#mytable_filter input').off('.DT').on('input.DT', function() {
            api.search(this.value).draw();
          });
        },
        oLanguage: {
          sProcessing: "loading..."
        },
        processing: false,
        serverSide: true,
        ajax: {
          "url": "<?php echo base_url() . 'Spp_c/ignited_data_siswa/' ?>" + $(this).val(),
          "type": "POST"
        },
        columns: [{
            "data": null,
            width: "10px",
            orderable: false,
            searchable: false
          },
          {
            "data": "NIS"
          },
          {
            "data": "nama_siswa"
          },
          {
            "data": "nama_kelompok"
          },
          {
            "data": "view",
            width: "170px",
            orderable: false,
            searchable: false,
            class: 'text-center'
          }
        ],
        order: [
          [1, 'asc']
        ],
        rowCallback: function(row, data, iDisplayIndex) {
          var info = this.fnPagingInfo();
          var page = info.iPage;
          var length = info.iLength;
          $('td:eq(0)', row).html(iDisplayIndex + 1 + info.iStart);
        }
      });
    });
  });
</script>
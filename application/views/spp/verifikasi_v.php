<?php
defined('BASEPATH') or exit('No direct script access allowed');
$this->load->view('_partials/header');
?>

<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1>Data Verifikasi Pembayaran SPP</h1>
    </div>

    <div class="section-body">
      <div class="row">
        <div class="col-12 col-md-12 col-lg-12">
          <div class="card">
            <div class="card-body">
              <div class="form-group col-3" id="ta-group">
                <!-- <label for="">Kelompok</label> -->
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
                <table class="table table-striped nowrap" id="veri_tabel" style="width:100%">
                  <thead>
                    <tr>
                      <th>
                        #
                      </th>
                      <!-- <th>NO SPP</th> -->
                      <th>Nama Siswa</th>
                      <th>Kelompok</th>
                      <th>Tahun Ajaran</th>
                      <th>Bulan</th>
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
    <div class="modal-dialog modal-lg" role="document">
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

  var tabel = $("#veri_tabel").dataTable({
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
      "url": "<?php echo base_url() . 'Spp_c/ignited_data_verifikasi' ?>",
      "type": "POST"
    },
    columns: [{
        "data": null,
        width: "10px",
        orderable: false,
        searchable: false
      },
      // {
      //   "data": "id_spp"
      // },
      {
        "data": "nama_siswa"
      },
      {
        "data": "nama_kelompok"
      },
      {
        "data": "tahun_ajaran"
      },
      {
        "data": "bulan",
        render: function(e) {
          return month[e];
        }
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

  $(document).on('click', '#foto_view', function() {
    const id = $(this).attr('id-pk');
    $.ajax({
      type: "GET",
      dataType: "JSON",
      data: {
        id: id
      },
      url: "<?= base_url(); ?>Spp_c/getBukti/",
      success: function(respon) {
        $("#tag_html").html('<img src="' + "<?= base_url('assets/img/buktispp/') ?>" + respon + '" alt="Girl in a jacket" style="width:auto !important; height: auto !important;  max-width: 100%;">');
        $('.modal-title').text('Bukti Pembayaran');
        $("#modal-body").scrollTop(0);
        $('#modal').modal('show');
      }
    });
  });

  $(document).on('click', '#veri_proses', function() {
    isSave = 0;
    const id = $(this).attr('id-pk');
    const idspp = $(this).attr('id-spp');
    swal({
        title: 'Verifikasi data ?',
        text: 'Anda akan menverifikasi data pembayaran ini!',
        icon: 'warning',
        buttons: ["Batal", "Verifikasi"],
      })
      .then((willDelete) => {
        if (willDelete) {
          $.ajax({
            type: "POST",
            url: "<?= base_url() ?>Spp_c/veriProses",
            data: {
              id: id,
              spp: idspp
            },
            success: function(respon) {
              tabel.api().ajax.reload();
              notifsukses('Pembayaran', 'diverifikasi');
            }
          });
        } else {
          notifgagal('Verifikasi');
        }
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
      $("#veri_tabel").dataTable({
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
          "url": "<?php echo base_url() . 'Spp_c/ignited_data_verifikasi/' ?>" + $(this).val(),
          "type": "POST"
        },
        columns: [{
            "data": null,
            width: "10px",
            orderable: false,
            searchable: false
          },
          // {
          //   "data": "id_spp"
          // },
          {
            "data": "nama_siswa"
          },
          {
            "data": "nama_kelompok"
          },
          {
            "data": "tahun_ajaran"
          },
          {
            "data": "bulan",
            render: function(e) {
              return month[e];
            }
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
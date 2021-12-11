<?php
defined('BASEPATH') or exit('No direct script access allowed');
$this->load->view('_partials/header');
?>

<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1>Data Tabungan</h1>
    </div>

    <div class="section-body">
      <div class="row">
        <div class="col-12 col-md-12 col-lg-12">
          <div class="card">
            <div class="card-header">
              <button class="btn btn-icon icon-left btn-primary" id="tabungan_t">
                <i class="fas fa-plus"></i>
                Tambah Setoran Awal
              </button>
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
                <table class="table table-striped" id="siswa_tabel" style="width:100%">
                  <thead>
                    <tr>
                      <th>Nomor Tabungan</th>
                      <th>Nama</th>
                      <th>Kelompok</th>
                      <th>Tanggal Buka</th>
                      <th>Jumlah Tabungan</th>
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

  <div class="modal fade" data-backdrop="static" role="dialog" id="modal">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Modal title</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="tabungan_f" class="needs-validation" novalidate="" autocomplete="off">
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
  var methodts = '1';
  var isSave = '1';
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

  var tabel = $("#siswa_tabel").dataTable({
    initComplete: function() {
      var api = this.api();
      $('#siswa_tabel_filter input').off('.DT').on('input.DT', function() {
        api.search(this.value).draw();
      });
    },
    oLanguage: {
      sProcessing: "loading..."
    },
    processing: false,
    serverSide: true,
    paging: true,
    ajax: {
      "url": "<?php echo base_url() . 'Tabungan_c/ignited_data_tabungan' ?>",
      "type": "POST"
    },
    columns: [{
        "data": "id_tabungan"
      },
      {
        "data": "nama_siswa"
      },
      {
        "data": "nama_kelompok"
      },
      {
        "data": "tgl_buka"
      },
      {
        "data": "jumlah_tabungan",
        class: 'text-right'
      },
      {
        "data": "view",
        width: "10%",
        orderable: false,
        searchable: false,
        class: 'text-center'
      }
    ],
    columnDefs: [{
      targets: [4],
      render: $.fn.dataTable.render.number('.', ',', '', '')
    }],
    order: [
      [0, 'asc']
    ],
    rowCallback: function(row, data, iDisplayIndex) {
      var info = this.fnPagingInfo();
      var page = info.iPage;
      var length = info.iLength;
    }
  });

  $(document).on('click', '#tabungan_t', function() {
    isSave = '1';
    $.ajax({
      type: "GET",
      dataType: "JSON",
      url: "<?= base_url(); ?>Tabungan_c/form/",
      success: function(respon) {
        $('#tabungan_f')[0].reset();
        $('#tabungan_f').removeClass('was-validated');
        $('#submit').removeClass('disabled btn-progress');
        $("#tag_html").html(respon.view);
        $('.modal-title').text('Tambah Data');
        $("#modal-body").scrollTop(0);
        $('#modal').modal('show');
      }
    });
  });

  $('#tabungan_f').on('submit', function(e) {
    e.preventDefault();
    var $select2 = $('.js-select2', $(this));
    if ($select2.val() === null) {
      $('.select2-selection').css('border-color', '#dc3545');
    } else {
      $('.select2-selection').css('border-color', '#28a745');
    }
    $('#submit').addClass('disabled btn-progress');
    angka = isSave === '1' ? parseInt($('[name=setoranawal]').inputmask('unmaskedvalue')) : parseInt($('[name=saldo]').inputmask('unmaskedvalue'));
    if (angka < 1000) {
      console.log('kurang');
      e.stopPropagation();
      $('[name=setoranawal]').css("border-color", "red");
      $('.grupuang .invalid-feedback').css("display", "block");
      $('.grupuang .valid-feedback').css("display", "none");
      isSave === '1' ? $('[name=setoranawal]').css("border-color", "#dc3545") : $('[name=saldo]').css("border-color", "#dc3545");
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
        data: new FormData($("#tabungan_f")[0]),
        dataType: "JSON",
        url: isSave === '1' ? '<?= base_url() ?>Tabungan_c/setorawal' : '<?= base_url() ?>Tabungan_c/insertST',
        success: function(respon) {
          tabel.api().clear().destroy();
          select2isi("select[name=kelompok]", 'semua', 'Semua Kelompok');
          $("#siswa_tabel").dataTable({
            initComplete: function() {
              var api = this.api();
              $('#siswa_tabel_filter input').off('.DT').on('input.DT', function() {
                api.search(this.value).draw();
              });
            },
            oLanguage: {
              sProcessing: "loading..."
            },
            processing: false,
            serverSide: true,
            paging: true,
            ajax: {
              "url": "<?php echo base_url() . 'Tabungan_c/ignited_data_tabungan' ?>",
              "type": "POST"
            },
            columns: [{
                "data": "id_tabungan"
              },
              {
                "data": "nama_siswa"
              },
              {
                "data": "nama_kelompok"
              },
              {
                "data": "tgl_buka"
              },
              {
                "data": "jumlah_tabungan",
                class: 'text-right'
              },
              {
                "data": "view",
                width: "10%",
                orderable: false,
                searchable: false,
                class: 'text-center'
              }
            ],
            columnDefs: [{
              targets: [4],
              render: $.fn.dataTable.render.number('.', ',', '', '')
            }],
            order: [
              [0, 'asc']
            ],
            rowCallback: function(row, data, iDisplayIndex) {
              var info = this.fnPagingInfo();
              var page = info.iPage;
              var length = info.iLength;
              $('td:eq(1)', row).css('white-space', 'nowrap');
            }
          });
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
    form.addClass('was-validated');
    return false;
  });

  $(document).on('click', '#tarik', function(e) {
    methodts = '0';
    isSave = '2';
    e.preventDefault();
    $.ajax({
      type: "GET",
      dataType: "JSON",
      url: "<?= base_url(); ?>Tabungan_c/form_st/",
      data: {
        tipe: methodts,
        id: $(this).attr('id-pk'),
        saldoakhir: $(this).attr('id-saldo')
      },
      success: function(respon) {
        console.log(respon.data.tipe);
        $('#tabungan_f')[0].reset();
        $('#tabungan_f').removeClass('was-validated');
        $('#submit').removeClass('disabled btn-progress');
        $("#tag_html").html(respon.view);
        $('[name=tipeaksi]').val(respon.data.tipe);
        $('[name=idtabungan]').val(respon.data.id);
        $('[name=saldoakhir]').val(respon.data.saldoakhir);
        $('.modal-title').text('Penarikan');
        $("#modal-body").scrollTop(0);
        $('#modal').modal('show');
      }
    });
    return false;
  });

  $(document).on('click', '#tabung', function(e) {
    methodts = '1';
    isSave = '2';
    e.preventDefault();
    $.ajax({
      type: "GET",
      dataType: "JSON",
      url: "<?= base_url(); ?>Tabungan_c/form_st/",
      data: {
        tipe: methodts,
        id: $(this).attr('id-pk'),
        saldoakhir: $(this).attr('id-saldo')
      },
      success: function(respon) {
        console.log(respon.data.tipe);
        $('#tabungan_f')[0].reset();
        $('#tabungan_f').removeClass('was-validated');
        $('#submit').removeClass('disabled btn-progress');
        $("#tag_html").html(respon.view);
        $('[name=tipeaksi]').val(respon.data.tipe);
        $('[name=idtabungan]').val(respon.data.id);
        $('[name=saldoakhir]').val(respon.data.saldoakhir);
        $('[name=keterangan]').removeAttr('required');
        $('.grupket').css('display', 'none');
        $('.modal-title').text('Setoran');
        $("#modal-body").scrollTop(0);
        $('#modal').modal('show');
      }
    });
    return false;
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
      $("#siswa_tabel").dataTable({
        initComplete: function() {
          var api = this.api();
          $('#siswa_tabel_filter input').off('.DT').on('input.DT', function() {
            api.search(this.value).draw();
          });
        },
        oLanguage: {
          sProcessing: "loading..."
        },
        processing: false,
        serverSide: true,
        paging: true,
        ajax: {
          "url": "<?php echo base_url() . 'Tabungan_c/ignited_data_tabungan/' ?>" + $(this).val(),
          "type": "POST"
        },
        columns: [{
            "data": "id_tabungan"
          },
          {
            "data": "nama_siswa"
          },
          {
            "data": "nama_kelompok"
          },
          {
            "data": "tgl_buka"
          },
          {
            "data": "jumlah_tabungan",
            class: 'text-right'
          },
          {
            "data": "view",
            width: "10%",
            orderable: false,
            searchable: false,
            class: 'text-center'
          }
        ],
        columnDefs: [{
          targets: [4],
          render: $.fn.dataTable.render.number('.', ',', '', '')
        }],
        order: [
          [2, 'asc']
        ],
        rowCallback: function(row, data, iDisplayIndex) {
          var info = this.fnPagingInfo();
          var page = info.iPage;
          var length = info.iLength;
        }
      });
    });
  });
</script>
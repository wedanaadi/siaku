<?php
defined('BASEPATH') or exit('No direct script access allowed');
$this->load->view('_partials/header');
?>
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1>Tunggakan SPP Lainnya</h1>
    </div>

    <div class="section-body">
      <div class="row">
        <div class="col-12 col-md-12 col-lg-12">
          <div class="card">
            <div class="card-body">
              <?php if ($this->session->userdata('jabatan') === 'admin') : ?>
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
              <?php endif; ?>
              <div class="table-responsive">
                <table class="table table-striped" id="lunas_tabel" style="width:100%">
                  <thead>
                    <tr>
                      <th>
                      </th>
                      <th>NIS</th>
                      <th>Nama Siswa</th>
                      <th>Kelompok</th>
                      <!-- <th>Action</th> -->
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
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Modal title</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="pay_f" class="needs-validation" novalidate="" autocomplete="off">
          <div class="modal-body">
            <div class="form-group">
              <label class="form-label">Metode</label>
              <div class="selectgroup w-100">
                <label class="selectgroup-item">
                  <input type="radio" name="value" value="pay1" class="selectgroup-input" checked="">
                  <span class="selectgroup-button">Bayar</span>
                </label>
                <label class="selectgroup-item">
                  <input type="radio" name="value" value="pay2" class="selectgroup-input">
                  <span class="selectgroup-button">Bayar dengan Tabungan</span>
                </label>
                <?php if ($this->session->userdata('jabatan') === 'admin') : ?>
                  <label class="selectgroup-item">
                    <input type="radio" name="value" value="pay3" class="selectgroup-input">
                    <span class="selectgroup-button">Tunai</span>
                  </label>
                <?php endif; ?>
              </div>
            </div>
            <div class="form-group">
              <label>No Referensi</label>
              <input type="text" name="noref" class="form-control" required="">
              <div class="invalid-feedback">
                No Reverensi ?
              </div>
              <div class="valid-feedback">
                Terisi!
              </div>
            </div>
            <div class="form-group fotog">
              <label>Foto</label>
              <input type="file" name="foto" class="form-control">
              <div class="invalid-feedback">
                Foto ?
              </div>
              <div class="valid-feedback">
                Terisi!
              </div>
            </div>
            <!-- <div id="tag_html"></div> -->
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
  var idpk = '';
  var NIS = '';
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

  var tabel = $("#lunas_tabel").dataTable({
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
        "className": 'details-control',
        "orderable": false,
        "data": null,
        "defaultContent": '',
        'searchable': false
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
    ],
    order: [
      [1, 'asc']
    ],
    rowCallback: function(row, data, iDisplayIndex) {
      var info = this.fnPagingInfo();
      var page = info.iPage;
      var length = info.iLength;
      // $('td:eq(0)', row).html(iDisplayIndex + 1 + info.iStart);
    }
  });

  function format(d, vv) {
    // `d` is the original data object for the row
    $.ajax({
      method: "GET",
      dataType: "JSON",
      url: "<?= base_url() ?>Spp_c/detailTunggakan",
      data: {
        id: vv,
      },
      success: function(respon) {
        let j = "<?= $this->session->userdata('jabatan') ?>";
        let tabeldetil = '<table class="table table-striped">' +
          '<thead>' +
          '<tr>' +
          '<th style="width: 5%">NO</th>' +
          '<th>Bulan</th>' +
          '<th>Tahun Ajaran</th>';
        tabeldetil += '<th style="width: 200px" class="text-center">Opsi</th>';
        // if (j === 'admin') {
        // }
        tabeldetil += '</tr>' +
          '</thead><tbody>';
        var pgno = 1;
        var tbody = '';
        if (respon.length > 0) {
          $.each(respon, function(index, value) {
            var tanggal = value.tgl.split('-');
            tbody += "<tr><td>" + pgno + "</td><td>" + month[value.bulan] + ' ' + tanggal[0] + "</td><td>" + value.tahun_ajaran + "</td>";
            if (value.status === '2') {
              tbody += "<td><span class='badge badge-warning'>Menunggu Verifikasi</span></td>";
            } else {
              tbody += "<td> <button id='bayar' id-pk='" + value.id_spp + "' id-NIS='" + value.NIS + "' class='btn btn-icon icon-left btn-danger'><i class='fas fa-file-invoice-dollar'></i> Bayar Tagihan</button> </td></tr>";
            }
            pgno++;
          });
        } else {
          tbody += "<tr><td colspan='4' class='text-center'>Tidak ada data tunggakan.</td></tr>";
        }
        tfoot = '</tbody></table>';
        // console.log(tbody);
        d($(tabeldetil + tbody + tfoot)).show();
      }
    });
    // return tabeldetil;
  }
  $('#lunas_tabel tbody').on('click', 'td.details-control', function() {
    var tr = $(this).closest('tr');
    console.log(tr);
    var row = tabel.api().row(tr);
    if (row.child.isShown()) {
      // This row is already open - close it
      row.child.hide();
      tr.removeClass('shown');
    } else {
      format(row.child, row.data().NIS); // create new if not exist
      tr.addClass('shown');
    }
  });

  $('.formuang').inputmask("numeric", {
    groupSeparator: ".",
    digits: 0,
    autoGroup: true,
    rightAlign: false,
    removeMaskOnSubmit: true,
    allowMinus: false
  });

  $(document).on('click', '#bayar', function() {
    $('#pay_f')[0].reset();
    $('#pay_f').removeClass('was-validated');
    let id = $(this).attr('id-pk');
    let nis = $(this).attr('id-NIS');
    idpk = id;
    NIS = nis;
    $('.modal-title').text('Pilih Metode');
    $('#modal').modal('show');
  });

  $pay = 1;
  $('input[type=radio]').change(function() {
    if (this.value == 'pay1') {
      $('[name=noref]').val('');
      $('[name=noref]').attr('disabled', false);
      $('.fotog').css("display", "block");
    } else if (this.value == 'pay2') {
      $('[name=noref]').val('Bayar dari tabungan');
      $('[name=noref]').attr('disabled', true);
      $('.fotog').css("display", "none");
    } else {
      $('[name=noref]').val('Tunai');
      $('[name=noref]').attr('disabled', true);
      $('.fotog').css("display", "none");
    }
  });

  $('#pay_f').on('submit', function(e) {
    e.preventDefault();
    $('#submit').addClass('disabled btn-progress');
    var form = $(this);
    if (form[0].checkValidity() === false) {
      event.stopPropagation();
      $('#submit').removeClass('disabled btn-progress');
    } else {
      const buktifoto = $('[name=foto]').prop('files')[0];
      const dataFoto = typeof buktifoto != 'undefined' ? true : false;
      if (!dataFoto && $('[name=value]:checked').val() === 'pay1') {
        alert('Bukti pembayaran tolong diisi!');
        $('#submit').removeClass('disabled btn-progress');
        return false;
      }
      let formdata = new FormData();
      formdata.append('foto', buktifoto);
      formdata.append('pilih', $('[name=value]:checked').val());
      formdata.append('noref', $('[name=noref]').val());
      formdata.append('nis', NIS);
      $.ajax({
        method: "POST",
        contentType: false,
        processData: false,
        dataType: "JSON",
        data: formdata,
        url: "<?= base_url() ?>Spp_c/pay/" + idpk,
        success: function(respon) {
          if (respon.status === 'sukses') {
            notifsukses(respon.msg, 'disimpan');
          } else {
            notifgagal2(respon.msg);
          }
          setTimeout(function() {
            window.location = "<?= base_url('Spp_c/sppTunggakan') ?>";
          }, 2000);
        }
      });
    }
    form.addClass('was-validated');
    return false;
  });

  $(function() {
    $(document).ready(function() {
      $("[name=foto]").fileinput({
        showCaption: false,
        dropZoneEnabled: false,
        showUpload: false,
        allowedFileTypes: ['image'],
        maxFileSize: 1024,
      });
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
      $("#lunas_tabel").dataTable({
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
            "className": 'details-control',
            "orderable": false,
            "data": null,
            "defaultContent": '',
            'searchable': false
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
        ],
        order: [
          [1, 'asc']
        ],
        rowCallback: function(row, data, iDisplayIndex) {
          var info = this.fnPagingInfo();
          var page = info.iPage;
          var length = info.iLength;
          // $('td:eq(0)', row).html(iDisplayIndex + 1 + info.iStart);
        }
      });
    });
  });
</script>
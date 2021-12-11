<?php
defined('BASEPATH') or exit('No direct script access allowed');
$this->load->view('_partials/header');
?>
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1>SPP Lunas</h1>
    </div>

    <div class="section-body">
      <div class="row">
        <div class="col-xs-12 col-md-12 col-lg-12">
          <div class="card">
            <div class="card-header">
              <!-- <div class="row"> -->
              <div class="col-xs-12 col-md-3">
                <div class="form-group" style="margin-bottom: 0px" id="ta-group">
                  <!-- <label>Tahun Ajaran</label> -->
                  <select name="ta" class="form-control js-select2" style="width:100%;" required="">
                  </select>
                  <div class="invalid-feedback">
                    Tahun Ajaran ?
                  </div>
                  <div class="valid-feedback">
                    Terisi!
                  </div>
                </div>
              </div>
              <?php if ($this->session->userdata('jabatan') === 'admin') : ?>
                <div class="col-xs-12 col-md-3">
                  <div class="form-group" style="margin-bottom: 0px" id="ta-group2">
                    <select name="kelompok" class="form-control js-select2" style="width:100%;" required="">
                    </select>
                    <div class="invalid-feedback">
                      Kelompok ?
                    </div>
                    <div class="valid-feedback">
                      Terisi!
                    </div>
                  </div>
                </div>
              <?php endif; ?>
            </div>
            <div class="card-body">
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

  function format(d, vv, idta) {
    // `d` is the original data object for the row
    $.ajax({
      method: "GET",
      dataType: "JSON",
      url: "<?= base_url() ?>Spp_c/detailLunas",
      data: {
        id: vv,
        ta: idta
      },
      success: function(respon) {
        let j = "<?= $this->session->userdata('jabatan') ?>";
        let tabeldetil = '<table class="table table-striped">' +
          '<thead>' +
          '<tr>' +
          '<th style="width: 5%">NO</th>' +
          '<th>Bulan</th>' +
          '<th>Tahun Ajaran</th>';
        // tabeldetil += '<th style="width: 200px" class="text-center">Opsi</th>';
        if (j === 'admin') {
          tabeldetil += '<th>Jumlah</th>';
        } else {
          tabeldetil += '<th>Status</th>'
        }
        tabeldetil += '</tr>' +
          '</thead><tbody>';
        var pgno = 1;
        var tbody = '';
        if (respon.length > 0) {
          $.each(respon, function(index, value) {
            var tanggal = value.tgl.split('-');
            tbody += "<tr><td>" + pgno + "</td><td>" + month[value.bulan] + ' ' + tanggal[0] + "</td><td>" + value.ta + "</td>";
            if (j === 'admin') {
              tbody += '<td>' + value.jumlah.replace(/\B(?=(\d{3})+(?!\d))/g, "."); + '</td>';
            } else {
              tbody += '<td><span class="badge badge-success">Terverifikasi</span></td>';
            }
            tbody += '</tr>';
            pgno++;
          });
        } else {
          tbody += "<tr><td colspan='4' class='text-center'>Tidak ada data SPP lunas.</td></tr>";
        }
        tfoot = '</tbody></table>';
        // console.log(tbody);
        d($(tabeldetil + tbody + tfoot)).show();
      }
    });
    // return tabeldetil;
  }
  $('#lunas_tabel tbody').on('click', 'td.details-control', function() {
    if ($('[name=ta]').val() != null) {
      var tr = $(this).closest('tr');
      console.log(tr);
      var row = tabel.api().row(tr);
      if (row.child.isShown()) {
        // This row is already open - close it
        row.child.hide();
        tr.removeClass('shown');
      } else {
        // Open this row
        // row.child(format(row.data())).show();
        // tr.addClass('shown');
        format(row.child, row.data().NIS, $('[name=ta]').val()); // create new if not exist
        tr.addClass('shown');
      }
    } else {
      alert('Pilih Tahun Ajaran');
    }
  });

  $(function() {
    $('select[name=ta]').select2({
      placeholder: "Tahun Ajaran...",
      ajax: {
        url: "<?= base_url(); ?>TahunAjaran_c/select2/",
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

    $('select[name=ta]').on('select2:select', function(e) {
      for (let i = 0; i < tabel.rows().count(); i++) {
        tabel.row(i).child.hide();
      }
      $('tr').removeClass('shown');
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
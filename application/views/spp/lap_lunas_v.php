<?php
defined('BASEPATH') or exit('No direct script access allowed');
$this->load->view('_partials/header');
?>
<style>
  @media only screen and (min-width: 720px) {
    #tabelJenis {
      width: 20% !important;
    }
  }
</style>
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1>Laporan SPP Lunas</h1>
    </div>

    <div class="section-body">
      <div class="row">
        <div class="col-12 col-md-12 col-lg-12">
          <div class="card">
            <div class="card-header">
              <button class="btn btn-icon icon-left btn-danger" id="print">
                <i class="fas fa-print"></i>
                Cetak Laporan
              </button>
            </div>
            <div class="card-body">
              <div class="card-header">
                <div class="card-title">
                  <h4 id="labelperiode">Periode : <?= $lap['awal'] . ' s/d ' . $lap['akhir'] ?></h4>
                </div>
              </div>
              <div class="row">
                <div class="form-group col-xs-12 col-md-3" id="ta-group" style="margin-top: 10px">
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

                <div class="form-group col-xs-12 col-md-3" id="ta-group" style="margin-top: 10px">
                  <input type="text" name="periode" class="form-control">
                </div>

                <div class="col-xs-12 col-md-3" style="margin-top:12px">
                  <button id="pilih" class="btn btn-primary"> <i class="fas fa-search"></i> Pilih</button>
                </div>
              </div>
              <hr>
              <table class="mb-3" id="tabelJenis" style="width: 100%;">
                <tr class="font-weight-bold">
                  <td colspan="3">
                    <h5>Jenis Pembayaran</h5>
                  </td>
                </tr>
                <tr>
                  <td class="font-weight-bold">BANK</td>
                  <td>:</td>
                </tr>
                <tr>
                  <td class="pl-3">BANK BPD</td>
                  <td>:</td>
                  <td class="text-right"><?= number_format($lap['bpd'], '0', ',', '.') ?></td>
                </tr>
                <tr>
                  <td class="pl-3">BANK BRI</td>
                  <td>:</td>
                  <td class="text-right"><?= number_format($lap['bri'], '0', ',', '.') ?></td>
                </tr>
                <tr>
                  <td class="font-weight-bold">Tunai</td>
                  <td>:</td>
                  <td class="text-right"><?= number_format($lap['tunai'], '0', ',', '.') ?></td>
                </tr>
                <tr>
                  <td class="font-weight-bold">dari Tabungan</td>
                  <td>:</td>
                  <td class="text-right"><?= number_format($lap['tabungan'], '0', ',', '.') ?></td>
                </tr>
                <tr>
                  <td colspan="2" class="font-weight-bold">
                    <h6>Jumlah</h6>
                  </td>
                  <td class="text-right"><?= number_format($lap['jumlah'], '0', ',', '.') ?></td>
                </tr>
              </table>
              <div class="table-responsive">
                <table class="table table-striped" id="laporan_tabel" style="width:100%">
                  <thead>
                    <tr>
                      <th>Nama</th>
                      <th>Kelompok</th>
                      <th>Bulan Pembayaran</th>
                      <th>Tahun Ajaran</th>
                      <th>Jumlah</th>
                      <th>Keterangan</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $total = 0;
                    $masuk = 0;
                    $keluar = 0;
                    $saldo = 0;
                    $no = 1;
                    foreach ($lap['laporan'] as $row) :
                    ?>
                      <tr>
                        <td><?= $row->nama_siswa ?></td>
                        <td><?= $row->nama_kelompok; ?></td>
                        <td><?= bln_th($row->tgl) ?></td>
                        <td><?= $row->tahun_ajaran; ?></td>
                        <td><?= $row->jumlah; ?></td>
                        <td><?= $row->noref; ?></td>
                      </tr>
                    <?php
                      $no++;
                      // $masuk += $row->masuk;
                      // $keluar += $row->keluar;
                      $saldo += $row->jumlah;
                    endforeach;
                    ?>
                  </tbody>
                  <tfoot>
                    <tr>
                      <td colspan="3"><strong>TOTAL</strong></td>
                      <td></td>
                      <td></td>
                    </tr>
                  </tfoot>
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
  $(function() {
    var date = new Date();
    var firstDay = new Date(date.getFullYear(), date.getMonth(), 1);
    var lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0);

    $('[name=periode]').daterangepicker({
      locale: {
        format: 'YYYY/MM/DD'
      },
      showDropdowns: true,
      autoApply: true,
      startDate: firstDay,
      endDate: lastDay
    });

    $('.calendar.right').hide();
    $('.calendar.left').addClass('single');

    $('.calendar-table').on('DOMSubtreeModified', function() {
      var el = $(".prev.available").parent().children().last();
      if (el.hasClass('next available')) {
        return;
      }
      el.addClass('next available');
      el.append('<i class="fa fa-chevron-right glyphicon glyphicon-chevron-right"></i>');
      // el.append('<span></span>');
    });

    $(document).on('click', '#print', function() {
      var getTgl = $('[name=periode]').val().split('-');
      var awalsplit = getTgl[0].split('/');
      var akhirsplit = getTgl[1].split('/');
      var awal = awalsplit[0] + '-' + awalsplit[1] + '-' + awalsplit[2];
      var akhir = akhirsplit[0] + '-' + akhirsplit[1] + '-' + akhirsplit[2];
      var k = $('[name=kelompok]').val();
      window.open("<?php echo base_url() . 'Spp_c/cetak_lap_lunas?kelompok=' ?>" + k + '&awal=' + awal + "&akhir=" + akhir);
    });

    var tabel = $('#laporan_tabel').dataTable({
      "columnDefs": [{
        targets: [4],
        render: $.fn.dataTable.render.number('.', ',', '', ''),
        "orderable": false,
      }, ],
      "footerCallback": function(row, data, start, end, display) {
        var api = this.api(),
          data;

        // Remove the formatting to get integer data for summation
        var intVal = function(i) {
          return typeof i === 'string' ?
            i.replace(/[\$,]/g, '') * 1 :
            typeof i === 'number' ?
            i : 0;
        };

        masuk = api
          .column(3, {
            page: 'current'
          })
          .data()
          .reduce(function(a, b) {
            return intVal(a) + intVal(b);
          }, 0);

        keluar = api
          .column(4, {
            page: 'current'
          })
          .data()
          .reduce(function(a, b) {
            return intVal(a) + intVal(b);
          }, 0);

        var numFormat = $.fn.dataTable.render.number('.', '.', 0, '').display;
        $(api.column(3).footer()).html(
          numFormat(masuk)
        );
        $(api.column(4).footer()).html(
          numFormat(keluar)
        );
      }
    });

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
      var getTgl = $('[name=periode]').val().split('-');
      var awalsplit = getTgl[0].split('/');
      var akhirsplit = getTgl[1].split('/');
      var awal = awalsplit[0] + '-' + awalsplit[1] + '-' + awalsplit[2];
      var akhir = akhirsplit[0] + '-' + akhirsplit[1] + '-' + akhirsplit[2];
      tabel.api().clear().draw();
      $.ajax({
        method: "GET",
        dataType: "JSON",
        url: "<?= base_url() ?>Spp_c/loadajaxLun",
        data: {
          kelompok: $(this).val(),
          awal,
          akhir
        },
        success: function(respon) {
          $('#labelperiode').text("Periode : " + respon.awal + " s/d " + respon.akhir);
          $.each(respon.laporan, function(k, v) {
            var datedb = v.tgl.split('-');
            tabel.api().row.add([
              v.nama_siswa,
              v.nama_kelompok,
              month[datedb[1]] + ' ' + datedb[0],
              v.tahun_ajaran,
              v.jumlah,
            ]).draw();
          });
        }
      });
    });

    $(document).on('click', '#pilih', function(e) {
      e.preventDefault();
      $('#pilih').addClass('disabled btn-progress');
      var getTgl = $('[name=periode]').val().split('-');
      var awalsplit = getTgl[0].split('/');
      var akhirsplit = getTgl[1].split('/');
      var awal = awalsplit[0] + '-' + awalsplit[1] + '-' + awalsplit[2];
      var akhir = akhirsplit[0] + '-' + akhirsplit[1] + '-' + akhirsplit[2];
      var kelompok = $('[name=kelompok]').val();
      $.ajax({
        method: "GET",
        dataType: "JSON",
        url: "<?= base_url() ?>Spp_c/loadajaxLun",
        data: {
          awal,
          akhir,
          kelompok
        },
        success: function(respon) {
          console.log(respon);
          $('#labelperiode').text("Periode : " + respon.awal + " s/d " + respon.akhir);
          if (respon.laporan.length === 0) {
            notifnotime("Laporan Periode : " + respon.awal + " s/d " + respon.akhir + ' Tidak Ditemukan');
          }
          tabel.api().clear().draw();
          $('#tabelJenis tr').remove();
          $('#tabelJenis').html(`<tr class="font-weight-bold">
                  <td colspan="3">
                    <h5>Jenis Pembayaran</h5>
                  </td>
                </tr>
                <tr>
                  <td class="font-weight-bold">BANK</td>
                  <td>:</td>
                </tr>
                <tr>
                  <td class="pl-3">BANK BPD</td>
                  <td>:</td>
                  <td class="text-right">${respon.bpd.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")}</td>
                </tr>
                <tr>
                  <td class="pl-3">BANK BRI</td>
                  <td>:</td>
                  <td class="text-right">${respon.bri.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")}</td>
                </tr>
                <tr>
                  <td class="font-weight-bold">Tunai</td>
                  <td>:</td>
                  <td class="text-right">${respon.tunai.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")}</td>
                </tr>
                <tr>
                  <td class="font-weight-bold">dari Tabungan</td>
                  <td>:</td>
                  <td class="text-right">${respon.tabungan.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")}</td>
                </tr>
                <tr>
                  <td colspan="2" class="font-weight-bold">
                    <h6>Jumlah</h6>
                  </td>
                  <td class="text-right">${respon.jumlah.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")}</td>
                </tr>`);
          $.each(respon.laporan, function(k, v) {
            var datedb = v.tgl.split('-');
            tabel.api().row.add([
              v.nama_siswa,
              v.nama_kelompok,
              month[datedb[1]] + ' ' + datedb[0],
              v.tahun_ajaran,
              v.jumlah,
              v.noref,
            ]).draw();
          });
          setTimeout(function() {
            $('#pilih').removeClass('disabled btn-progress');
          }, 500);
        }
      });
      return false;
    });

  });
</script>
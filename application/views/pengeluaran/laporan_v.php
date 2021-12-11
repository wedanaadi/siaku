<?php
defined('BASEPATH') or exit('No direct script access allowed');
$this->load->view('_partials/header');
?>
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1 id="label">Laporan Rekap Bulan <?= $lap['bulanth'] ?></h1>
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
              <div class="row">
                <div class="form-group col-xs-12 col-md-3" id="ta-group" style="margin-top: 10px">
                  <input autocomplete="off" type="text" name="periode" class="form-control">
                </div>

                <div class="col-xs-12 col-md-3" style="margin-top:12px">
                  <button id="pilih" class="btn btn-primary"> <i class="fas fa-search"></i> Pilih</button>
                </div>
              </div>
              <div class="table-responsive">
                <table class="table table-striped" id="laporan_tabel" style="width:100%">
                  <thead>
                    <tr>
                      <th>Tanggal</th>
                      <th>Uraian</th>
                      <th>Uang Masuk</th>
                      <th>Uang Keluar</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>&nbsp;</td>
                      <td>Saldo Sampai Bulan <?= bln_th($lap['blnsebelum']) ?></td>
                      <td><?= $lap['saldo']->total ?></td>
                      <td>0</td>
                    </tr>
                    <?php
                    $total = 0;
                    $masuk = 0;
                    $keluar = 0;
                    $saldo = 0;
                    $no = 1;
                    foreach ($lap['laporan'] as $row) :
                    ?>
                      <tr>
                        <td><?= tgl_only($row->tgl) ?></td>
                        <td><?= $row->keterangan; ?></td>
                        <td><?= $row->masuk; ?></td>
                        <td><?= $row->keluar; ?></td>
                        <!-- <td><?= $row->bulan; ?></td> -->
                      </tr>
                    <?php
                      $no++;
                      $masuk += $row->masuk;
                      $keluar += $row->keluar;
                    // $saldo += $row->jumlah;
                    endforeach;
                    ?>
                  </tbody>
                  <tfoot>
                    <tr>
                      <td colspan="2"><strong>Subtotal</strong></td>
                      <td></td>
                      <td></td>
                    </tr>
                    <tr>
                      <td colspan="2"><strong>&nbsp;</strong></td>
                      <td><strong>Saldo</strong></td>
                      <td id="grandtotal"><strong><?= number_format(($masuk + $lap['saldo']->total) - $keluar, 0, ",", ".") ?></strong></td>
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
    $('[name=periode]').daterangepicker({
      locale: {
        format: 'YYYY/MM'
      },
      startDate: JSON.parse('<?= $lap["pic"] ?>'),
      showDropdowns: true,
      singleDatePicker: true,
      autoApply: true,
    });

    $(document).on('click', '#print', function() {
      var getTgl = $('[name=periode]').val().split('/');
      var periode = getTgl[0] + '-' + getTgl[1];
      window.open("<?php echo base_url() . 'Pengeluaran_c/cetak_lap_jurnal?periode=' ?>" + periode);
    });

    var tabel = $('#laporan_tabel').DataTable({
      searching: false,
      "columnDefs": [{
        targets: [2, 3],
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
          .column(2, {
            page: 'current'
          })
          .data()
          .reduce(function(a, b) {
            return intVal(a) + intVal(b);
          }, 0);

        keluar = api
          .column(3, {
            page: 'current'
          })
          .data()
          .reduce(function(a, b) {
            return intVal(a) + intVal(b);
          }, 0);

        var numFormat = $.fn.dataTable.render.number('.', '.', 0, '').display;
        $(api.column(2).footer()).html(
          numFormat(masuk)
        );
        $(api.column(3).footer()).html(
          numFormat(keluar)
        );
      }
    });

    $(document).on('click', '#pilih', function(e) {
      e.preventDefault();
      $('#pilih').addClass('disabled btn-progress');
      var getTgl = $('[name=periode]').val().split('/');
      var periode = getTgl[0] + '-' + getTgl[1];
      var kelompok = $('[name=kelompok]').val();
      $.ajax({
        method: "GET",
        dataType: "JSON",
        url: "<?= base_url() ?>Pengeluaran_c/loadjaxjurnal",
        data: {
          kelompok,
          periode
        },
        success: function(respon) {
          $('#label').text("Laporan Buku Besar " + respon.bulanth);
          tabel.clear().draw();
          $('#grandtotal').text(0);
          var masuk = 0;
          var keluar = 0;
          var no = 1;
          tabel.row.add([
            '',
            'Saldo Bulan ' + respon.blnsebelum,
            respon.saldo.total,
            0
          ]).draw();
          console.log(respon);
          $.each(respon.laporan, function(key, value) {
            tabel.row.add([
              value.tgl.split(" ")[0],
              value.keterangan,
              value.masuk,
              value.keluar,
            ]).draw();
            no++;
            masuk += parseInt(value.masuk);
            keluar += parseInt(value.keluar);
          });
          $('#grandtotal').html("<strong>" + ((masuk + parseInt(respon.saldo.total)) - keluar).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + "</strong>");
          setTimeout(function() {
            $('#pilih').removeClass('disabled btn-progress');
          }, 500);
        }
      });
      return false;
    });
  });
</script>
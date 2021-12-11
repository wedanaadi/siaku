<?php
defined('BASEPATH') or exit('No direct script access allowed');
$this->load->view('_partials/header');
?>
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1>Laporan Tagihan Pembayaran SPP <?= $lap['bulanth'] ?></h1>
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
                <table class="table table-striped" id="laporan_tabel" style="width:100%">
                  <thead>
                    <tr>
                      <th>Nama</th>
                      <th>Kelompok</th>
                      <th>Tagihan Bulan</th>
                      <th>Tahun Ajaran</th>
                      <th>Jumlah</th>
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
                        <!-- <td><?= $row->bulan; ?></td> -->
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
    $(document).on('click', '#print', function() {
      var k = $('[name=kelompok]').val();
      window.open("<?php echo base_url() . 'Spp_c/cetak_lap_spp?kelompok=' ?>" + k);
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
      tabel.api().clear().draw();
      $.ajax({
        method: "GET",
        dataType: "JSON",
        url: "<?= base_url() ?>Spp_c/loadajaxLap",
        data: {
          kelompok: $(this).val()
        },
        success: function(respon) {
          console.log(respon);
          $.each(respon, function(k, v) {
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
  });
</script>
<?php
defined('BASEPATH') or exit('No direct script access allowed');
$this->load->view('_partials/header');
?>
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1 id="label">Rekap Tabungan <?= $label ?></h1>
    </div>

    <div class="section-body">
      <div class="row">
        <div class="col-12 col-md-12 col-lg-12">
          <div class="card">
            <div class="card-header">
              <button class="btn btn-icon icon-left btn-danger" id="print">
                <i class="fas fa-print"></i>
                Cetak Detail Rekap
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
                <table class="table" id="rekap_tabel" style="width:100%">
                  <?php if ($rekap_count > 0) : ?>

                    <?php
                    $header_row1 = $header_row2 = '';
                    $index = 0;
                    foreach ($rekap[0] as $key => $vrk) {
                      if (strpos($key, 'tgl_') !== false) {
                        $tgl = explode('_', $key);
                        $index++;
                        $header_row2 .= "<th class='text-center'>" . $tgl[1] . "</th>";
                      }
                    }
                    $header_row1 = "<th colspan=" . $index . " class='text-center'>T &nbsp;&nbsp; A &nbsp;&nbsp; N &nbsp;&nbsp; G &nbsp;&nbsp; G &nbsp;&nbsp; A &nbsp;&nbsp; L</th>";
                    ?>

                    <thead>
                      <tr>
                        <th rowspan="2" class="align-middle text-center">No</th>
                        <th rowspan="2" class="align-middle text-center">Nama</th>
                        <th rowspan="2" class="align-middle text-center">Jumlah Bulan Sebelumnya</th>
                        <?= $header_row1; ?>
                        <th rowspan="2" class="align-middle text-center">Jumlah Bulan Ini</th>
                        <th rowspan="2" class="align-middle text-center">Jumlah s/d Bulan Ini</th>
                      </tr>
                      <tr>
                        <?= $header_row2; ?>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $no = 1;
                      foreach ($rekap as $v) {
                        echo "  <tr>
                                <td>" . $no . "</td>
                                <td>" . $v->nama_siswa . "</td>
                               ";
                        $bulan_lalu = 0;
                        foreach ($bln_lalu as $vbl) {
                          if ($v->NIS === $vbl->NIS) {
                            echo "<td>" . number_format($vbl->bln_lalu, '0', ',', '.') . "</td>";
                            $bulan_lalu = $vbl->bln_lalu;
                          }
                        }
                        $total_sekarang = 0;
                        foreach ($v as $key => $v2) {
                          if ($key !== 'NIS' && $key !== 'nama_siswa' && $key !== 'total_bln') {
                            echo "<td>" . number_format($v2, '0', ',', '.') . "</td>";
                            $total_sekarang += $v2;
                          }
                        }
                        echo "<td>" . number_format($total_sekarang, '0', ',', '.') . "</td>
                                <td>" . number_format($bulan_lalu + $total_sekarang, '0', ',', '.') . "</td></tr>";
                        $no++;
                      }
                      ?>
                    </tbody>

                  <?php else : ?>
                    <thead>
                      <tr>
                        <th rowspan="2" class="align-middle text-center">No</th>
                        <th rowspan="2" class="align-middle text-center">Nama</th>
                        <th rowspan="2" class="align-middle text-center">Jumlah Bulan Lalu</th>
                        <th colspan="<?= date('t') ?>" class='text-center'>T &nbsp;&nbsp; A &nbsp;&nbsp; N &nbsp;&nbsp; G &nbsp;&nbsp; G &nbsp;&nbsp; A &nbsp;&nbsp; L</th>
                        <th rowspan="2" class="align-middle text-center">Jumlah Bulan Ini</th>
                        <th rowspan="2" class="align-middle text-center">Jumlah s/d Bulan Ini</th>
                      </tr>
                      <tr>
                        <?php for ($i = 1; $i <= date('t'); $i++) : ?>
                          <td class="text-center"><?= $i; ?></td>
                        <?php endfor; ?>
                      </tr>
                    </thead>
                    <tbody></tbody>

                  <?php endif; ?>
                </table>
              </div>
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
      showDropdowns: true,
      singleDatePicker: true,
      autoApply: true,
    });
  });
  var tabel = $("#rekap_tabel").dataTable({
    processing: false,
    ordering: false,
    searching: false,
    info: false,
    serverSide: false,
    paging: false,
    rowCallback: function(row, data, iDisplayIndex) {
      $('td:eq(1)', row).css("white-space", "nowrap");
    }
  });

  $(document).on('click', '#print', function() {
    var getTgl = $('[name=periode]').val().split('/');
    var periode = getTgl[0] + '-' + getTgl[1];
    window.open("<?php echo base_url() . 'Tabungan_c/cetak_rekap_tabungan?periode=' ?>" + periode);
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
      url: "<?= base_url() ?>Tabungan_c/loadjaxrekap",
      data: {
        kelompok,
        periode
      },
      success: function(respon) {
        $('#label').text("Rekap Tabungan : " + respon.label);
        tabel.api().clear().destroy();
        $('#rekap_tabel thead').remove();
        var header_row1, header_row2 = '';
        var index = 0;
        $.each(respon.rekap[0], function(key, vrh) {
          // console.log(key.indexOf('tgl_'));
          if (key.indexOf('tgl_') !== -1) {
            var tgl = key.split("_");
            index++;
            header_row2 += "<th class='text-center'>" + tgl[1] + "</th>";
          }
        });
        header_row1 = "<th colspan='" + index + "' class='text-center'>T &nbsp;&nbsp; A &nbsp;&nbsp; N &nbsp;&nbsp; G &nbsp;&nbsp; G &nbsp;&nbsp; A &nbsp;&nbsp; L</th>";

        var headertabel = '<thead>' +
          '<tr>' +
          '<th rowspan="2" class="align-middle text-center">No</th>' +
          '<th rowspan="2" class="align-middle text-center">Nama</th>' +
          '<th rowspan="2" class="align-middle text-center">Jumlah Bulan Sebelumnya</th>' +
          header_row1 + '<th rowspan="2" class="align-middle text-center">Jumlah Bulan Ini</th>' +
          '<th rowspan="2" class="align-middle text-center">Jumlah s/d Bulan Ini</th>' +
          '</tr>' +
          '<tr>' +
          header_row2 +
          '</tr>' +
          '</thead>' +
          '<tbody><tbody>';
        $('#rekap_tabel').html(headertabel);
        $("#rekap_tabel").dataTable().api().clear().destroy();
        var tabelre = $("#rekap_tabel").dataTable({
          processing: false,
          ordering: false,
          searching: false,
          info: false,
          serverSide: false,
          paging: false,
          rowCallback: function(row, data, iDisplayIndex) {
            $('td:eq(1)', row).css("white-space", "nowrap");
          }
        });
        tabelre.api().clear().draw();
        var no = 1;
        $.each(respon.rekap, function(k, v) {
          var dataarray = [];
          dataarray.push(no);
          dataarray.push(v.nama_siswa);
          var bulan_lalu = 0;
          $.each(respon.bln_lalu, function(kb, bl) {
            if (v.NIS === bl.NIS) {
              dataarray.push(bl.bln_lalu.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."));
              bulan_lalu = parseInt(bl.bln_lalu);
            }
          });
          var total_sekarang = 0;
          $.each(v, function(key, value) {
            if (key !== 'NIS' && key !== 'nama_siswa' && key !== 'total_bln') {
              dataarray.push(value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."));
              total_sekarang += parseInt(value);
            }
          });
          dataarray.push(total_sekarang.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."));
          dataarray.push((bulan_lalu + total_sekarang).toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."));
          no++;
          tabel.api().row.add(dataarray).draw();
        });
        setTimeout(function() {
          $('#pilih').removeClass('disabled btn-progress');
        }, 500);
      }
    });
    return false;
  });
</script>
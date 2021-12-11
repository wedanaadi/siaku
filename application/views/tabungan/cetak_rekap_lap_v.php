<?php echo $header ?>

<style>
  .text-center {
    text-align: center;
  }

  .text-right {
    text-align: right;
  }

  #TabelKonten tr td {
    padding-right: 7px;
    padding-left: 7px;
    font-size: 15px;
  }

  tr.noBorder td {
    border: 0;
  }
</style>
<h4 style="text-align:center">Laporan Rekap Tabungan <?= $lap['label'] ?></h4>
<table id="TabelKonten" border="1" class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
  <?php if ($lap['rekap_count'] > 0) : ?>

    <?php
    $header_row1 = $header_row2 = '';
    $index = 0;
    foreach ($lap['rekap'][0] as $key => $vrk) {
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
      foreach ($lap['rekap'] as $v) {
        echo "  <tr>
                                <td>" . $no . "</td>
                                <td style='white-space:nowrap'>" . $v->nama_siswa . "</td>
                               ";
        $bulan_lalu = 0;
        foreach ($lap['bln_lalu'] as $vbl) {
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

<table width="100%" border="0" style="font-size:11px; page-break-inside:avoid">
  <tr>
    <td>&nbsp; </td>
    <td>&nbsp; </td>
    <td>&nbsp; </td>
  </tr>
  <tr>
    <td>&nbsp; </td>
    <td>&nbsp; </td>
    <td align="center"> Tabanan, <?= tgl_only(date('Y-m-d')) ?> </td>
  </tr>

  <tr>
    <td align="center"><strong>Kepala Sekolah</strong></td>
    <td align="center"><strong>&nbsp;</strong></td>
    <td align="center"><strong>Bendahara</strong></td>
  </tr>
  <tr>
    <td>&nbsp; </td>
    <td>&nbsp; </td>
    <td>&nbsp; </td>
  </tr>
  <tr>
    <td>&nbsp; </td>
    <td>&nbsp; </td>
    <td>&nbsp; </td>
  </tr>
  <tr>
    <td>&nbsp; </td>
    <td>&nbsp; </td>
    <td>&nbsp; </td>
  </tr>
  <tr>
    <td>&nbsp; </td>
    <td>&nbsp; </td>
    <td>&nbsp; </td>
  </tr>
  <tr>
    <td>&nbsp; </td>
    <td>&nbsp; </td>
    <td>&nbsp; </td>
  </tr>
  <tr>
    <td align="center" style="width: 35%"> I Gusti Ayu Malini Kahesma Dewi, S.Pd </td>
    <td align="center" style="width: 35%"> &nbsp;</td>
    <td align="center" style="width: 30%"> Ni Made Arini</td>
  </tr>
</table>
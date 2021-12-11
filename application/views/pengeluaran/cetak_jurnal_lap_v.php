<?php echo $header;
$this->load->helper('Tanggal'); ?>

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
<h4 style="text-align:center">Laporan Rekap Bulan <?= $lap['bulanth'] ?></h4>
<table id="TabelKonten" border="1" class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
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
      <td>Saldo Bulan <?= $lap['blnsebelum'] ?></td>
      <td><?= number_format($lap['saldo']->total, '0', ',', '.') ?></td>
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
        <td><?= number_format($row->masuk, '0', ',', '.'); ?></td>
        <td><?= number_format($row->keluar, '0', ',', '.'); ?></td>
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
      <td colspan="2" style="text-align:center"><strong>Subtotal</strong></td>
      <td><?= number_format($masuk + $lap['saldo']->total, '0', ',', '.') ?></td>
      <td><?= number_format($keluar, '0', ',', '.') ?></td>
    </tr>
    <tr>
      <td colspan="2"><strong>&nbsp;</strong></td>
      <td style="text-align:center"><strong>Saldo</strong></td>
      <td><strong><?= number_format(($masuk + $lap['saldo']->total) - $keluar, 0, ",", ".") ?></strong></td>
    </tr>
  </tfoot>
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
    <td align="center"><strong>Ketua Yayasan</strong></td>
    <td align="center"><strong>&nbsp;</strong></td>
    <td align="center"><strong>Kepala Sekolah</strong></td>
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
    <td align="center" style="width: 35%"> Ida Bagus Komang Trisika, SE </td>
    <td align="center" style="width: 35%"> &nbsp;</td>
    <td align="center" style="width: 30%"> I Gusti Ayu Malini Kahesma Dewi, S.Pd</td>
  </tr>
</table>
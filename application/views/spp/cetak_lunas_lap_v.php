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

  .pl-3 {
    padding-left: 15px;
  }

  .font-bold {
    font-weight: bold !important;
  }

  .text-right {
    text-align: right;
  }
</style>
<h4 style="text-align:center; margin-bottom:0">Laporan SPP Lunas</h4>
<h5 style="text-align:center; margin-top:5px"><?= 'Periode : ' . $lap['awal'] . ' - ' . $lap['akhir'] ?></h5>

<table id="tabelJenis" style="width: 30%; margin-bottom: 15px; font-size: 13px">
  <tr>
    <td colspan="3">
      <h3>Jenis Pembayaran</h3>
    </td>
  </tr>
  <tr>
    <td class="font-bold">BANK</td>
    <td>:</td>
  </tr>
  <tr>
    <td class="pl-3">BANK BPD</td>
    <td>:</td>
    <td class="text-right"><?= $lap['bpd'] ?></td>
  </tr>
  <tr>
    <td class="pl-3">BANK BRI</td>
    <td>:</td>
    <td class="text-right"><?= $lap['bri'] ?></td>
  </tr>
  <tr>
    <td class="font-bold">Tunai</td>
    <td>:</td>
    <td class="text-right"><?= $lap['cTunai'] ?></td>
  </tr>
  <tr>
    <td class="font-bold">dari Tabungan</td>
    <td>:</td>
    <td class="text-right"><?= $lap['cTabungan'] ?></td>
  </tr>
  <tr>
    <td colspan="2" class="font-bold">
      <h4>Jumlah</h4>
    </td>
    <td class="text-right"><?= $lap['cJumlah'] ?></td>
  </tr>
</table>

<table id="TabelKonten" border="1" class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
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
    // $total = 0;
    // $masuk = 0;
    // $keluar = 0;
    $saldo = 0;
    $no = 1;
    foreach ($lap['laporan'] as $row) :
    ?>
      <tr>
        <td><?= $row->nama_siswa ?></td>
        <td><?= $row->nama_kelompok; ?></td>
        <td><?= bln_th($row->tgl) ?></td>
        <td><?= $row->tahun_ajaran; ?></td>
        <td><?= number_format($row->jumlah, '0', ',', '.'); ?></td>
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
      <td colspan="4"><strong>TOTAL</strong></td>
      <td><?= number_format($saldo, '0', ',', '.') ?></td>
      <td>&nbsp;</td>
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
    <td align="center"><strong>&nbsp;</strong> </td>
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
    <td align="center" style="width: 35%"> &nbsp; </td>
    <td align="center" style="width: 35%"> &nbsp;</td>
    <td align="center" style="width: 30%">I Gusti Ayu Malini Kahesma Dewi, S.Pd</td>
  </tr>
</table>
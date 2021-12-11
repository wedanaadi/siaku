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
<h4 style="text-align:center">Laporan Detail Tabungan</h4>
<table width="80%" style="margin-top: 10px; margin-bottom: 10px;">
  <tr>
    <td width="15%"> <strong>No Tabungan </strong> </td>
    <td width="30%"> <?= $datatabungan->id_tabungan; ?> </td>
    <td width="15%"><strong> Saldo Rendah </strong> </td>
    <td width="30%"> <?php echo ": " . number_format($min, 0, '.', '.') ?> </td>
  </tr>
  <tr>
    <td width="15%"><strong> Nama </strong> </td>
    <td width="30%"> <?= $datatabungan->nama_siswa ?> </td>
    <td width="15%"><strong> Saldo Tinggi </strong> </td>
    <td width="30%"> <?php echo ": " . number_format($max, 0, '.', '.') ?> </td>
  </tr>
  <tr>
    <td width="15%"><strong> Tanggal Buka </strong> </td>
    <td width="30%"> <?= $datatabungan->tgl_buka; ?></td>
    <td width="15%"><strong> Saldo Akhir </strong> </td>
    <td width="30%"> <?php echo ": " . number_format($datatabungan->jumlah_tabungan, 0, '.', '.') ?> </td>
  </tr>
</table>
<hr>
<table id="TabelKonten" border="1" class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
  <thead>
    <tr>
      <th rowspan="2" class="align-middle;" style="width:15%">Tanggal</th>
      <th colspan="2" class="text-center">Tabungan</th>
      <th rowspan="2" class="align-middle">Saldo</th>
      <th rowspan="2" class="align-middle" style="width:40%">Keterangan</th>
    </tr>
    <tr>
      <th>Masuk</th>
      <th>Keluar</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($loop as $l) : ?>
      <tr>
        <td><?= tgl_only($l->tgl_baru) ?></td>
        <td><?= number_format($l->masuk, '0', ',', '.') ?></td>
        <td><?= number_format($l->keluar, '0', ',', '.') ?></td>
        <td><?= number_format($l->saldo, '0', ',', '.') ?></td>
        <td><?= $l->keterangan ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
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
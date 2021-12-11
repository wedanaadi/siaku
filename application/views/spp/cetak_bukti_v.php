<?php
$this->load->helper('Tanggal'); ?>
<?php
$this->load->helper('Tanggal'); ?>
<style>
  .text-center {
    text-align: center;
  }

  .text-right {
    text-align: right;
  }

  #TabelKonten tr td {
    /* padding-right: 7px;
    padding-left: 7px; */
    font-size: 10px !important;
  }

  tr.noBorder td {
    border: 0;
  }
</style>

<body>
  <table border="0">
    <tr>
      <td><img height="25px" src="<?php echo base_url('assets/img/logo/kop.jpeg') ?>"></td>
      <td>
        <h4 style="text-align:center">Bukti Pembayaran SPP</h4>
      </td>
    </tr>
  </table>
  <hr>
  <table id="TabelKonten" border="0" width="100%" cellspacing="0">
    <thead>
      <tr>
        <td>Nama</td>
        <td>: </td>
        <td><?= $body->nama_siswa ?></td>
      </tr>
      <tr>
        <td>Bulan</td>
        <td>: </td>
        <td><?= bln_th($body->tgl_trx) ?></td>
      </tr>
      <tr>
        <td>Tahun Ajaran</td>
        <td>: </td>
        <td><?= $body->ta ?></td>
      </tr>
      <tr>
        <td>No Bukti</td>
        <td>: </td>
        <td><?= $body->noref ?></td>
      </tr>
      <tr>
        <td colspan="3">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="3" style="font-size: 7px;">dicetak by SIAKU (<?= date('Y-m-d') ?>)</td>
      </tr>
    </thead>
  </table>
</body>

<!-- <table width="100%" border="0" style="font-size:11px; page-break-inside:avoid">
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
    <td align="center"><strong>Mengetahui</strong></td>
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
    <td align="center" style="width: 30%"> _________________________</td>
  </tr>
</table> -->
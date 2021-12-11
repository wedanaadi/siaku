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

<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header border-bottom">

      </div>
      <div class="card-body">
        <h4 style="text-align:center">Laporan Tabungan</h4>
        <h4 class="card-title mb-4" style="text-align:center">Periode : <?= $lap['awal'] . ' s/d ' . $lap['akhir'] ?></h4>
        <div class="table-responsive">
          <table id="TabelKonten" border="1" class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th style="width:18%">Tanggal</th>
                <th>Nama</th>
                <th>Masuk</th>
                <th>Keluar</th>
                <th>Saldo</th>
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
                  <td><?= tgl_only($row->tgl); ?></td>
                  <td><?= $row->nama_siswa ?></td>
                  <td style="text-align: right;"><?= number_format($row->masuk, '0', ',', '.'); ?></td>
                  <td style="text-align: right;"><?= number_format($row->keluar, '0', ',', '.'); ?></td>
                  <td style="text-align: right;"><?= number_format($row->saldo, '0', ',', '.'); ?></td>
                </tr>
              <?php
                $no++;
                $masuk += $row->masuk;
                $keluar += $row->keluar;
                $saldo += $row->saldo;
              endforeach;
              ?>
            </tbody>
            <tfoot>
              <tr>
                <td colspan=" 2" style="text-align: center;"><strong>TOTAL</strong></td>
                <td style="text-align: right;"><?= number_format($masuk, '0', ',', '.'); ?></td>
                <td style="text-align: right;"><?= number_format($keluar, '0', ',', '.'); ?></td>
                <td style="text-align: right;"><?= number_format($masuk - $keluar, '0', ',', '.'); ?></td>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>
  </div>

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
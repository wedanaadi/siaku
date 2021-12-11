<?php
defined('BASEPATH') or exit('No direct script access allowed');
$this->load->view('_partials/header');
?>
<style>
  .table:not(.table-sm) thead th {
    /* background-color: rgba(0, 0, 0, 0.04) !important; */
    border: 1px solid #DDDDDD;
  }
</style>
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1>Form Ubah Tabungan</h1>
    </div>

    <div class="section-body">
      <div class="card">
        <div class="card-header">
          <a href="<?= base_url() ?>Tabungan_c/index" class="btn btn-icon icon-left btn-info">
            <i class="fas fa-chevron-left"></i>
          </a>
        </div>
        <div class="card-body">
          <form id="formEdit" action="#" method="POST" enctype="multipart/form-data">
            <div class="form-group">
              <label>Nomor Tabungan</label>
              <input type="text" name="id" class="form-control" value="<?= $edit->id_tabungan ?>" readonly>
            </div>
            <div class="form-group">
              <label>Jumlah</label>
              <input type="text" name="ubahInput" class="form-control formuang" value="<?= number_format($edit->saldo, 0, ',', '.') ?>">
              <input type="hidden" name="jumlah" class="form-control" value="<?= number_format($edit->jumlah_tabungan, 0, ',', '.') ?>">
              <input type="hidden" name="saldoold" class="form-control" value="<?= number_format($edit->saldo, 0, ',', '.') ?>">
              <input type="hidden" name="saldoakhir" class="form-control" value="<?= number_format($edit->saldo_akhir, 0, ',', '.') ?>">
            </div>
            <div class="form-group col-lg-12">
              <a href="<?php echo base_url('Tabungan_c/index') ?>" class="btn btn-secondary"><i class="fa fa-ban"></i> Batal</a>
              <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
</div>
<?php $this->load->view('_partials/footer'); ?>

<script>
  $('.formuang').inputmask("numeric", {
    groupSeparator: ".",
    digits: 0,
    autoGroup: true,
    rightAlign: false,
    removeMaskOnSubmit: true,
    allowMinus: false
  });

  var idtab = JSON.parse('<?= $kodetabungan ?>');
  var typeaksi = JSON.parse('<?= $typeaksi ?>');

  $('#formEdit').on('submit', function(e) {
    e.preventDefault();
    $.ajax({
      method: "POST",
      dataType: "JSON",
      contentType: false,
      processData: false,
      data: new FormData($("#formEdit")[0]),
      url: '<?= base_url() ?>Tabungan_c/saveEditTabungan/' + idtab + '/' + typeaksi,
      success: function(respon) {
        if (respon.status === 'sukses') {
          notifsukses('Tabungan', 'diubah');
          setTimeout(function() {
            window.location = "<?= base_url() ?>Tabungan_c/edit/" + respon.id;
          }, 1000);
        } else {
          notifgagal2('Edit Tabungan Gagal');
        }
      }
    });
    return false;
  });
</script>
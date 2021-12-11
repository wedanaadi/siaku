<?php
defined('BASEPATH') or exit('No direct script access allowed');
$this->load->view('_partials/header');
?>

<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1>Setting Sistem</h1>
    </div>

    <div class="section-body">
      <div class="row">
        <div class="col-12 col-md-12 col-lg-12">
          <div class="card">
            <form id="formSetting" action="#" method="post" autocomplete="off">
              <div class="card-body">
                <div class="row">
                  <div class="col-xs-6 col-md-6 col-lg-6">
                    <div class="form-group">
                      <label>Tahun Ajaran</label>
                      <select name="ta" class="form-control js-select2" style="width:100%;" required="">
                      </select>
                      <div class="invalid-feedback">
                        Kelompok ?
                      </div>
                      <div class="valid-feedback">
                        Terisi!
                      </div>
                    </div>
                  </div>
                  <div class="col-xs-6 col-md-6 col-lg-6">
                    <div class="form-group grupuang">
                      <label>Biaya SPP Bulanan</label>
                      <input type="text" name="biaya" class="form-control formuang" required="">
                      <div class="invalid-feedback">
                        Jumlah SPP Bulanan ?
                      </div>
                      <div class="valid-feedback">
                        Terisi!
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-footer">
                <button type="submit" id="submit" class="btn btn-icon icon-left btn-primary">
                  <i class="fas fa-save"></i>
                  Simpan
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<?php $this->load->view('_partials/footer'); ?>

<script>
  $(function() {

    var data = JSON.parse('<?= isset($set) ? $set : "{}"; ?>');
    if (data != null) {
      $('[name=biaya]').val(data.jumlah_spp);
      select2isi("select[name=ta]", data.tahun_ajaran_aktif, data.tahun_ajaran);
    }

    $('select[name=ta]').select2({
      placeholder: "Tahun Ajaran...",
      ajax: {
        url: "<?= base_url(); ?>TahunAjaran_c/select2/",
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

    $('.formuang').inputmask("numeric", {
      groupSeparator: ".",
      digits: 0,
      autoGroup: true,
      rightAlign: false,
      removeMaskOnSubmit: true,
      allowMinus: false
    });
  });

  $('#formSetting').on('submit', function(e) {
    e.preventDefault();
    $.ajax({
      method: "POST",
      dataType: "JSON",
      contentType: false,
      processData: false,
      data: new FormData($("#formSetting")[0]),
      url: '<?= base_url() ?>Dashboard_c/updatesetting',
      success: function(respon) {
        if (respon.status === 'sukses') {
          notifsukses('Setting', 'disimpan');
          setTimeout(function() {
            window.location = "<?= base_url() ?>Dashboard_c/setting";
          }, 1000);
        } else {
          notifgagal2('Setting Gagal');
        }
      }
    });
    return false;
  });
</script>
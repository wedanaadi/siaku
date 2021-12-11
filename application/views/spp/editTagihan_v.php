<?php
defined('BASEPATH') or exit('No direct script access allowed');
$this->load->view('_partials/header');
?>
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1>Ubah Tagihan SPP</h1>
    </div>
    <div class="section-body">
      <div class="card">
        <div class="card-header">
          <?php if ($this->session->userdata('jabatan') === 'admin') : ?>
            <a href="<?= base_url() ?>Spp_c/index" class="btn btn-icon icon-left btn-info">
              <i class="fas fa-chevron-left"></i>
            </a> &nbsp;
          <?php endif; ?>
        </div>
      </div>
      <form id="spp_f" class="needs-validation" novalidate="" autocomplete="off">
        <div class="row">
          <div class="col-xs-12 col-md-6 col-lg-6">
            <div class="card">
              <div class="card-body">
                <div class="form-group" id="ta-group">
                  <label>Tahun Ajaran</label>
                  <select name="ta" class="form-control js-select2" style="width:100%;" required="">
                  </select>
                  <div class="invalid-feedback">
                    Tahun Ajaran ?
                  </div>
                  <div class="valid-feedback">
                    Terisi!
                  </div>
                </div>
                <div class="form-group grupuang">
                  <label>Jumlah</label>
                  <input type="text" name="jumlah" class="form-control formuang" required="" value="<?= $jumlah_setting ?>">
                  <div class="invalid-feedback">
                    Jumlah Tagihan ?
                  </div>
                  <div class="valid-feedback">
                    Terisi!
                  </div>
                </div>
              </div>
              <div class="card-footer">
                <!-- <button type="button" class="btn btn-icon icon-left btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i>Tutup</button> -->
                <button type="submit" id="submit" class="btn btn-icon icon-left btn-primary">
                  <i class="fas fa-save"></i>
                  Simpan
                </button>
                <p></p>
              </div>
            </div>
          </div>
          <div class="col-xs-12 col-md-6 col-lg-6">
            <div class="card">
              <div class="card-body">
                <div class="col-xs-12 col-md-4 col-lg-4">
                  <div class="form-group">
                    <label class="d-block">Bulan</label>
                    <div class="form-check">
                      <input class="form-check-input" name="bulan[]" value="1" type="checkbox" id="ta_1" disabled>
                      <label class="form-check-label">
                        Januari
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" name="bulan[]" value="2" type="checkbox" id="ta_2" disabled>
                      <label class="form-check-label">
                        Februari
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" name="bulan[]" value="3" type="checkbox" id="ta_3" disabled>
                      <label class="form-check-label">
                        Maret
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" name="bulan[]" value="4" type="checkbox" id="ta_4" disabled>
                      <label class="form-check-label">
                        April
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" name="bulan[]" value="5" type="checkbox" id="ta_5" disabled>
                      <label class="form-check-label">
                        Mei
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" name="bulan[]" value="6" type="checkbox" id="ta_6" disabled>
                      <label class="form-check-label">
                        Juni
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" name="bulan[]" value="7" type="checkbox" id="ta_7" disabled>
                      <label class="form-check-label">
                        Juli
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" name="bulan[]" value="8" type="checkbox" id="ta_8" disabled>
                      <label class="form-check-label">
                        Agustus
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" name="bulan[]" value="9" type="checkbox" id="ta_9" disabled>
                      <label class="form-check-label">
                        September
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" name="bulan[]" value="10" type="checkbox" id="ta_10" disabled>
                      <label class="form-check-label">
                        Oktober
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" name="bulan[]" value="11" type="checkbox" id="ta_11" disabled>
                      <label class="form-check-label">
                        November
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" name="bulan[]" value="12" type="checkbox" id="ta_12" disabled>
                      <label class="form-check-label">
                        Desember
                      </label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-xs-12 col-md-12 col-lg-12">
            <div class="card">
              <div class="card-header">
                <strong>Data Siwa</strong>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-striped" id="spp_tabel" style="width:100%">
                    <thead>
                      <tr>
                        <th style="width:50px">
                          #
                        </th>
                        <th>NIS</th>
                        <th>Nama Siswa</th>
                      </tr>
                    </thead>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
    </form>
</div>
</section>
</div>
<?php $this->load->view('_partials/footer'); ?>

<script>
  $(function() {

    var tabel = $('#spp_tabel').DataTable({
      processing: true,
      // serverSide: true,
      paging: false,
      searching: false,
      language: {
        emptyTable: "No data available in table"
      },
      rowCallback: function(row, data, iDisplayIndex, iDisplayIndexFull) {}
    });


    // $('input:checkbox').prop('checked', true);

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

    $('select[name=ta]').on('select2:select', function(e) {
      $('#ta-group .select2-selection').css('border-color', '#28a745');
      $('#ta-group .invalid-feedback').css("display", "none");
      $('#ta-group .valid-feedback').css("display", "block");

      $.ajax({
        type: "GET",
        dataType: "JSON",
        url: '<?= base_url() ?>Spp_c/getSiswaTagihanUbah',
        data: {
          ta: $('[name=ta]').val(),
        },
        success: function(respon) {
          if (respon.siswa.length == 0) {
            $('input:checkbox').prop('checked', false);
            $('#submit').addClass('disabled btn-progress');
            $('.card-footer p').html("");
            $('.card-footer p').html("<strong>Data SPP tidak ditemukan !</strong>");
          } else {
            for (let index = respon.bulan; index <= 12; index++) {
              const name = "#ta_" + index;
              $(name).prop('checked', true);
            }
            notifnotime('Perubahan Jumlah Tagihan SPP hanya dapat dilakukan dari bulan ' + month[respon.bulan]);
            $('.card-footer p').html("");
            $('#submit').removeClass('disabled btn-progress');
          }
          let index = 1;
          tabel.clear().draw();
          $.each(respon.siswa, function(key, value) {
            rowadd = tabel.row.add([
              index,
              value.NIS,
              value.nama_siswa,
            ]).draw();

            index++;
          });
        }
      });
    });

    $('#spp_f').on('submit', function(e) {
      e.preventDefault();
      var $select2ta = $('[name=ta]', $(this));
      if ($select2ta.val() === null) {
        $('#ta-group .select2-selection').css('border-color', '#dc3545');
      } else {
        $('#ta-group .select2-selection').css('border-color', '#28a745');
      }
      $('#submit').addClass('disabled btn-progress');
      if (parseInt($('[name=jumlah]').inputmask('unmaskedvalue')) < 1000) {
        e.stopPropagation();
        $('[name=jumlah]').css("border-color", "#dc3545");
        $('.grupuang .invalid-feedback').css("display", "block");
        $('.grupuang .valid-feedback').css("display", "none");
        $('[name=jumlah]').css("border-color", "#dc3545")
        $('#submit').removeClass('disabled btn-progress');
        return false;
      }
      if ($('input:checkbox:checked').length === 0) {
        $('.form-check-label').css('color', '#dc3545');
        $('#submit').removeClass('disabled btn-progress');
        return false;
      }
      var form = $(this);
      if (form[0].checkValidity() === false) {
        event.stopPropagation();
        $('#submit').removeClass('disabled btn-progress');
      } else {
        var checked = []
        $("input[name='bulan[]']:checked").each(function() {
          checked.push(parseInt($(this).val()));
        });
        console.log(checked);
        $.ajax({
          method: "POST",
          data: {
            bulan: checked,
            jumlah: $('[name=jumlah]').val(),
            ta: $('[name=ta]').val(),
            siswa: tabel.rows().data().toArray()
          },
          dataType: "JSON",
          url: '<?= base_url() ?>Spp_c/updateTagihan',
          success: function(respon) {
            if (respon.status === 'sukses') {
              notifsukses(respon.msg, 'diubah');
            } else {
              notifgagal2(respon.msg);
            }
            setTimeout(function() {
              window.location = "<?= base_url() ?>Spp_c";
            }, 2000);
          }
        });
      }
      return false;
    });
  });
</script>
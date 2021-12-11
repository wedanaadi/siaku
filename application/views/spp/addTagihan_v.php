<?php
defined('BASEPATH') or exit('No direct script access allowed');
$this->load->view('_partials/header');
?>
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1>Tambah Tagihan SPP</h1>
    </div>
    <div class="section-body">
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
                      <input class="form-check-input" name="bulan[]" value="1" type="checkbox" id="defaultCheck1" disabled>
                      <label class="form-check-label" for="defaultCheck1">
                        Januari
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" name="bulan[]" value="2" type="checkbox" id="defaultCheck1" disabled>
                      <label class="form-check-label" for="defaultCheck1">
                        Februari
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" name="bulan[]" value="3" type="checkbox" id="defaultCheck1" disabled>
                      <label class="form-check-label" for="defaultCheck1">
                        Maret
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" name="bulan[]" value="4" type="checkbox" id="defaultCheck1" disabled>
                      <label class="form-check-label" for="defaultCheck1">
                        April
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" name="bulan[]" value="5" type="checkbox" id="defaultCheck1" disabled>
                      <label class="form-check-label" for="defaultCheck1">
                        Mei
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" name="bulan[]" value="6" type="checkbox" id="defaultCheck1" disabled>
                      <label class="form-check-label" for="defaultCheck1">
                        Juni
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" name="bulan[]" value="7" type="checkbox" id="defaultCheck1" disabled>
                      <label class="form-check-label" for="defaultCheck1">
                        Juli
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" name="bulan[]" value="8" type="checkbox" id="defaultCheck1" disabled>
                      <label class="form-check-label" for="defaultCheck1">
                        Agustus
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" name="bulan[]" value="9" type="checkbox" id="defaultCheck1" disabled>
                      <label class="form-check-label" for="defaultCheck1">
                        September
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" name="bulan[]" value="10" type="checkbox" id="defaultCheck1" disabled>
                      <label class="form-check-label" for="defaultCheck1">
                        Oktober
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" name="bulan[]" value="11" type="checkbox" id="defaultCheck1" disabled>
                      <label class="form-check-label" for="defaultCheck1">
                        November
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" name="bulan[]" value="12" type="checkbox" id="defaultCheck1" disabled>
                      <label class="form-check-label" for="defaultCheck1">
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


    $('input:checkbox').prop('checked', true);
    // $("#semuacheck").click(function() { ini script check semua
    //   $('input:checkbox').not(this).prop('checked', this.checked);
    // });

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
        url: '<?= base_url() ?>Spp_c/getSiswaTagihan',
        data: {
          ta: $('[name=ta]').val(),
        },
        success: function(respon) {
          if (respon.length == 0) {
            $('#submit').addClass('disabled btn-progress');
            $('.card-footer p').html("<strong>Semua siswa sudah dibuatkan tagihan untuk tahun ajaran " + $('[name=ta] option:selected').text() + "</strong>");
          } else {
            $('.card-footer p').html("");
            $('#submit').removeClass('disabled btn-progress');
          }
          // let html = "";
          // html += "<div class='table-responsive'><table class='table table-striped' id='siswa_tabel' style='width: 100 % '>";
          // html += "<thead><tr><th>No</th><th>NIS</th><th>Nama Siswa</th></tr></thead><tbody>";
          let index = 1;
          tabel.clear().draw();
          $.each(respon, function(key, value) {
            //   html += "<tr>" +
            //     "<td>" + index + "</td>" +
            //     "<td>" + value.NIS + "</td>" +
            //     "<td>" + value.nama_siswa + "</td>" +
            //     "</tr>";
            rowadd = tabel.row.add([
              index,
              value.NIS,
              value.nama_siswa,
            ]).draw();

            index++;
          });
          // html += "</tbody></table></div>";
          // $('#tabel').html(html);
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
          url: '<?= base_url() ?>Spp_c/createTagihan',
          success: function(respon) {
            if (respon.status === 'sukses') {
              notifsukses(respon.msg, 'disimpan');
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
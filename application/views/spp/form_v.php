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

<div class="form-group" id="bulan-group">
  <label>Bulan</label>
  <select name="bulan" class="form-control js-select2" style="width:100%;" required="">
  </select>
  <div class="invalid-feedback">
    Bulan ?
  </div>
  <div class="valid-feedback">
    Terisi!
  </div>
</div>

<div class="form-group grupuang">
  <label>Jumlah</label>
  <input type="text" name="jumlah" class="form-control formuang" required="">
  <div class="invalid-feedback">
    Jumlah Tagihan ?
  </div>
  <div class="valid-feedback">
    Terisi!
  </div>
</div>

<script>
  $(function() {
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

    $('select[name=bulan]').select2({
      placeholder: "Bulan...",
      ajax: {
        url: "<?= base_url(); ?>Spp_c/select2bulan/",
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

    var d = new Date();
    var n = d.getMonth();
    select2isi("select[name=bulan]", n + 1, month[n + 1]);

    $('select[name=ta]').on('select2:select', function(e) {
      $('#ta-group .select2-selection').css('border-color', '#28a745');
      $('#ta-group .invalid-feedback').css("display", "none");
      $('#ta-group .valid-feedback').css("display", "block");
      ajax_tabel();
    });
    $('select[name=bulan]').on('select2:select', function(e) {
      $('#bulan-group .select2-selection').css('border-color', '#28a745');
      $('#bulan-group .invalid-feedback').css("display", "none");
      $('#bulan-group .valid-feedback').css("display", "block");
      ajax_tabel();
    });

    function ajax_tabel() {
      $.ajax({
        type: "GET",
        dataType: "JSON",
        url: '<?= base_url() ?>/Spp_c/getSiswaTagihan',
        data: {
          ta: $('[name=ta]').val(),
          bulan: $('[name=bulan]').val()
        },
        success: function(respon) {
          console.log('aaa');
        }
      });
    }
  });
</script>
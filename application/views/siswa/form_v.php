<div class="row">
  <div class="col-xs-12 col-md-3 col-lg-3">
    <div class="form-group">
      <label>NIS</label>
      <input type="text" name="nis" class="form-control" required="">
      <div class="invalid-feedback">
        NIS ?
      </div>
      <div class="valid-feedback">
        Terisi!
      </div>
    </div>
  </div>
  <div class="col-xs-12 col-md-6 col-lg-6">
    <div class="form-group">
      <label>Nama</label>
      <input type="text" name="nama" class="form-control" required="">
      <div class="invalid-feedback">
        Nama ?
      </div>
      <div class="valid-feedback">
        Terisi!
      </div>
    </div>
  </div>
  <div class="col-xs-12 col-md-3 col-lg-3">
    <div class="form-group">
      <label>Jenis Kelamin</label>
      <select name="jeniskelamin" class="form-control">
        <option value="L">Laki-Laki</option>
        <option value="P">Perempuan</option>
      </select>
      <div class="valid-feedback">
        Terisi!
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-xs-12 col-md-3 col-lg-3">
    <div class="form-group">
      <label>Tempat Lahir</label>
      <input type="text" name="tempatlahir" class="form-control" required="">
      <div class="invalid-feedback">
        Tempat Lahir?
      </div>
      <div class="valid-feedback">
        Terisi!
      </div>
    </div>
  </div>
  <div class="col-xs-12 col-md-3 col-lg-3">
    <div class="form-group">
      <label>Tanggal Lahir</label>
      <input type="text" name="tanggallahir" class="form-control" id="waktu" required="">
      <div class="invalid-feedback">
        Tanggal Lahir?
      </div>
      <div class="valid-feedback">
        Terisi!
      </div>
    </div>
  </div>
  <div class="col-xs-12 col-md-3 col-lg-3">
    <div class="form-group">
      <label>Telepon</label>
      <input type="text" name="telp" class="form-control" required="">
      <div class="invalid-feedback">
        Telepon ?
      </div>
      <div class="valid-feedback">
        Terisi!
      </div>
    </div>
  </div>
  <div class="col-xs-12 col-md-3 col-lg-3">
    <div class="form-group">
      <label>Kelompok</label>
      <select name="kelompok" class="form-control js-select2" style="width:100%;" required="">
      </select>
      <div class="invalid-feedback">
        Kelompok ?
      </div>
      <div class="valid-feedback">
        Terisi!
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-xs-12 col-md-6 col-lg-6">
    <div class="form-group">
      <label>Nama Ayah</label>
      <input type="text" name="ayah" class="form-control" required="">
      <div class="invalid-feedback">
        Nama Ayah ?
      </div>
      <div class="valid-feedback">
        Terisi!
      </div>
    </div>
  </div>
  <div class="col-xs-12 col-md-6 col-lg-6">
    <div class="form-group">
      <label>Nama Ibu</label>
      <input type="text" name="ibu" class="form-control" required="">
      <div class="invalid-feedback">
        Nama Ibu ?
      </div>
      <div class="valid-feedback">
        Terisi!
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-xs-12 col-md-12 col-lg-12">
    <div class="form-group">
      <label>Alamat</label>
      <textarea name="alamat" class="form-control" required=""></textarea>
      <div class="invalid-feedback">
        Alamat ?
      </div>
      <div class="valid-feedback">
        Terisi!
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-xs-12 col-md-6 col-lg-6">
    <div class="form-group">
      <label>Agama</label>
      <select name="agama" class="form-control">
        <option value="Islam">Islam</option>
        <option value="Hindu">Hindu</option>
        <option value="Budha">Budha</option>
        <option value="Katolik">Katolik</option>
        <option value="Protestan">Protestan</option>
        <option value="Konghuchu">Konghuchu</option>
      </select>
      <div class="valid-feedback">
        Terisi!
      </div>
    </div>
  </div>
  <div class="col-xs-12 col-md-6 col-lg-6">
    <div class="form-group fotog">
      <label>Foto</label>
      <input type="file" name="foto" class="file form-control" data-show-preview="true" required="">
      <div class="invalid-feedback">
        Foto ?
      </div>
      <div class="valid-feedback">
        Terisi!
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  $(function() {
    // $('#waktu').datetimepicker({
    //   // useCurrent: false,
    //   format: 'YYYY-MM-DD',
    //   // debug: true
    // });
    $('#waktu').daterangepicker({
      locale: {
        format: 'YYYY-MM-DD'
      },
      singleDatePicker: true,
      showDropdowns: true,
    });

    $('select[name=kelompok]').select2({
      placeholder: "Pilih Kelompok",
      ajax: {
        url: "<?= base_url(); ?>Siswa_c/select2/",
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

    $('select[name=kelompok]').on('select2:select', function(e) {
      $('.select2-selection').css('border-color', '#28a745');
    });


  });
</script>
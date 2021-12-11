<div class="form-group">
  <label>Tanggal</label>
  <input type="text" name="tgl" id="waktu" class="form-control" required="" value="<?= date('Y-m-d') ?>">
  <div class="invalid-feedback">
    Tanggal ?
  </div>
  <div class="valid-feedback">
    Terisi!
  </div>
</div>

<div class="form-group grupuang">
  <label>Biaya</label>
  <input type="text" name="biaya" class="form-control formuang" required="">
  <div class="invalid-feedback">
    Biaya ?
  </div>
  <div class="valid-feedback">
    Terisi!
  </div>
</div>

<div class="form-group">
  <label>Keterangan</label>
  <textarea name="keterangan" class="form-control" required=""></textarea>
  <div class="invalid-feedback">
    Keterangan ?
  </div>
  <div class="valid-feedback">
    Terisi!
  </div>
</div>

<script type="text/javascript">
  $(function() {
    $('.formuang').inputmask("numeric", {
      groupSeparator: ".",
      digits: 0,
      autoGroup: true,
      rightAlign: false,
      removeMaskOnSubmit: true,
      allowMinus: false
    });

    $('#waktu').daterangepicker({
      locale: {
        format: 'YYYY-MM-DD'
      },
      singleDatePicker: true,
      showDropdowns: true,
    });
  });
</script>
<div class="form-group grupuang">
  <label>Jumlah</label>
  <input type="text" name="saldo" class="form-control formuang" required="">
  <input type="hidden" name="tipeaksi" class="form-control">
  <input type="hidden" name="idtabungan" class="form-control">
  <input type="hidden" name="saldoakhir" class="form-control">
  <div class="invalid-feedback">
    Jumlah Uang ?
  </div>
  <div class="valid-feedback">
    Terisi!
  </div>
</div>

<div class="form-group grupket">
  <label>Keterangan</label>
  <input type="text" name="keterangan" class="form-control" required="">
  <div class="invalid-feedback">
    Keterangan ?
  </div>
  <div class="valid-feedback">
    Terisi!
  </div>
</div>

<script>
  $('.formuang').inputmask("numeric", {
    groupSeparator: ".",
    digits: 0,
    autoGroup: true,
    rightAlign: false,
    removeMaskOnSubmit: true,
    allowMinus: false
  });
</script>
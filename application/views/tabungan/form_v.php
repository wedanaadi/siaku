<div class="form-group">
    <label>Siswa</label>
    <select name="siswa" class="form-control js-select2" style="width:100%;" required="">
    </select>
    <div class="invalid-feedback">
        Nama Siswa ?
    </div>
    <div class="valid-feedback">
        Terisi!
    </div>
</div>

<div class="form-group grupuang">
    <label>Jumlah</label>
    <input type="text" name="setoranawal" class="form-control formuang" required="">
    <div class="invalid-feedback">
        Jumlah Setoran Awal ?
    </div>
    <div class="valid-feedback">
        Terisi!
    </div>
</div>

<script type="text/javascript">
$(function () {
    $('select[name=siswa]').select2({
        placeholder: "Pilih Siswa",
        ajax: {
            url: "<?= base_url(); ?>Tabungan_c/select2siswa/",
            dataType: 'json',
            data: function (params) {
                return {
                    q: $.trim(params.term)
                };
            },
            processResults: function (data) {
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

    $('[name=setoranawal]').filter(function() {
        return parseInt($(this).val(), 10) > 0;
    });

    $('select[name=siswa]').on('select2:select',function(e){
        $('.select2-selection').css('border-color','#28a745');
    });
});
</script>
<?php
defined('BASEPATH') or exit('No direct script access allowed');
$this->load->view('_partials/header');
?>
<style>
  .table:not(.table-sm) thead th {
    /* background-color: rgba(0, 0, 0, 0.04) !important; */
    border: none !important;
    text-align: center;
  }
</style>
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1>Pembayaran SPP</h1>
    </div>
    <div class="section-body">
      <div class="row">
        <div class="col-xs-12 col-md-12 col-lg-12">

          <div class="card">
            <div class="card-header">
              <?php if ($this->session->userdata('jabatan') === 'admin') : ?>
                <a href="<?= base_url() ?>Spp_c/index" class="btn btn-icon icon-left btn-info">
                  <i class="fas fa-chevron-left"></i>
                </a>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-xs-12 col-md-4 col-lg-4">
          <div class="card">
            <div class="card-header">
              <h4>Informasi tagihan</h4>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-striped" style="width: 100%;">
                  <tr>
                    <td>Tahun Ajaran</td>
                    <td>:</td>
                    <td><?= $info['ta'] ?></td>
                  </tr>
                  <tr>
                    <td>NIS</td>
                    <td>:</td>
                    <td><?= $info['NIS'] ?></td>
                  </tr>
                  <tr>
                    <td>Nama Siswa</td>
                    <td>:</td>
                    <td><?= $info['nama'] ?></td>
                  </tr>
                  <tr>
                    <td>Total Tagihan</td>
                    <td>:</td>
                    <td><?= number_format($info['utang'], 0, '.', '.') ?></td>
                  </tr>
                </table>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xs-12 col-md-8 col-lg-8">
          <h4>Pembayaran tagihan</h4>
          <div class="card">
            <div class="card-header">
              <h4>Semester Ganjil</h4>
              <div class="card-header-action">
                <a data-collapse="#ganjil" class="btn btn-icon btn-info" href="#"><i class="fas fa-minus"></i></a>
              </div>
            </div>
            <div class="collapse show" id="ganjil">
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-striped" id="info_tabel1" style="width: 100%;">
                    <thead>
                      <tr>
                        <th style="width: 20px;">NO</th>
                        <th>Bulan</th>
                        <th>Tagihan</th>
                        <th>Status</th>
                        <th>Bayar</th>
                        <th>Cetak</th>
                      </tr>
                    </thead>
                    <?php
                    if ($count > 0) :
                      $month = [
                        'list', 'Januari',
                        'Februari', 'Maret',
                        'April', 'Mei',
                        'Juni', 'Juli',
                        'Agustus', 'September',
                        'Oktober', 'November', 'Desember'
                      ];
                      $no = 1;
                      foreach ($loop as $l) :
                        if ((int)$l->bulan > 6) :
                    ?>
                          <tr>
                            <td><?= $no ?></td>
                            <td><?= $month[$l->bulan] . ' ' . $l->thn ?></td>
                            <td><?= number_format($l->jumlah, 0, '.', '.') ?></td>
                            <td>
                              <?php if ($l->status === '0') : ?>
                                <div class="badge badge-danger">Belum dibayar</div>
                              <?php elseif ($l->status === '1') : ?>
                                <div class="badge badge-success">dibayar</div>
                              <?php else : ?>
                                <div class="badge badge-warning">menungu Verifikasi</div>
                              <?php endif; ?>
                            </td>
                            <td>
                              <?php if ($l->status === '0') : ?>
                                <?php
                                $date = date_create($l->tgl_trx);
                                $year = date_format($date, "Ym");
                                $ta_aktif_select = $this->db->query("SELECT sistem.*,ta.`tahun_ajaran` FROM `sistem` INNER JOIN tahun_ajaran ta ON ta.`id` = sistem.`tahun_ajaran_aktif`");
                                $thnajaran_pisah = explode('/', $ta_aktif_select->row()->tahun_ajaran);
                                if ($year > $thnajaran_pisah[0] . date('m')) {
                                  $is_disabled = 'disabled';
                                } else {
                                  $is_disabled = '';
                                }

                                ?>
                                <button id="pay" id-pk="<?= $l->id_spp ?>" jm-tagihan="<?= $l->jumlah ?>" class="btn btn-icon btn-sm btn-danger">
                                  <i class="fas fa-times"></i></button>
                              <?php elseif ($l->status === '1') : ?>
                                <button id="pay" id-pk="<?= $l->id_spp ?>" jm-tagihan="<?= $l->jumlah ?>" class="btn btn-icon btn-sm btn-success" disabled>
                                  <i class="fas fa-check"></i></button>
                              <?php else : ?>
                                <button id="pay" id-pk="<?= $l->id_spp ?>" jm-tagihan="<?= $l->jumlah ?>" class="btn btn-icon btn-sm btn-warning" disabled>
                                  <i class="fas fa-clock"></i></button>
                              <?php endif; ?>
                            </td>
                            <td>
                              <?php
                              if ($l->status === '1') {
                                $class = '';
                              } else {
                                $class = 'disabled';
                              }

                              ?>
                              <button id="cetakbukti" id-pk="<?= $l->id_spp ?>" class="btn btn-icon btn-sm btn-danger" <?= $class; ?>>
                                <i class="fas fa-print"></i></button>
                            </td>
                          </tr>
                      <?php $no++;
                        endif;
                      endforeach;
                    else :
                      ?>
                      <tr>
                        <td colspan="6" class="text-center">Tagihan Belum dibuat</td>
                      </tr>
                    <?php
                    endif;
                    ?>
                  </table>
                </div>
              </div>
            </div>
          </div>

          <div class="card">
            <div class="card-header">
              <h4>Semester Genap</h4>
              <div class="card-header-action">
                <a data-collapse="#genap" class="btn btn-icon btn-info" href="#"><i class="fas fa-minus"></i></a>
              </div>
            </div>
            <div class="collapse show" id="genap">
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-striped" id="info_tabel2" style="width: 100%;">
                    <thead>
                      <tr>
                        <th style="width: 20px;">NO</th>
                        <th>Bulan</th>
                        <th>Tagihan</th>
                        <th>Status</th>
                        <th>Bayar</th>
                        <th>Cetak</th>
                      </tr>
                    </thead>
                    <?php
                    if ($count > 0) :
                      $month = [
                        'list', 'Januari',
                        'Februari', 'Maret',
                        'April', 'Mei',
                        'Juni', 'Juli',
                        'Agustus', 'September',
                        'Oktober', 'November', 'Desember'
                      ];
                      $no = 1;
                      foreach ($loop as $l) :
                        if ((int)$l->bulan < 7) :
                    ?>
                          <tr>
                            <td><?= $no ?></td>
                            <td><?= $month[$l->bulan] . ' ' . $l->thn ?></td>
                            <td><?= number_format($l->jumlah, 0, '.', '.') ?></td>
                            <td>
                              <?php if ($l->status === '0') : ?>
                                <div class="badge badge-danger">Belum dibayar</div>
                              <?php elseif ($l->status === '1') : ?>
                                <div class="badge badge-success">dibayar</div>
                              <?php else : ?>
                                <div class="badge badge-warning">menungu Verifikasi</div>
                              <?php endif; ?>
                            </td>
                            <td>
                              <?php if ($l->status === '0') : ?>
                                <?php
                                $date = date_create($l->tgl_trx);
                                $year = date_format($date, "Ym");
                                $ta_aktif_select = $this->db->query("SELECT sistem.*,ta.`tahun_ajaran` FROM `sistem` INNER JOIN tahun_ajaran ta ON ta.`id` = sistem.`tahun_ajaran_aktif`");
                                $thnajaran_pisah = explode('/', $ta_aktif_select->row()->tahun_ajaran);
                                if ($year > $thnajaran_pisah[0] . date('m')) {
                                  $is_disabled = 'disabled';
                                } else {
                                  $is_disabled = '';
                                }

                                ?>
                                <button id="pay" id-pk="<?= $l->id_spp ?>" jm-tagihan="<?= $l->jumlah ?>" class="btn btn-icon btn-sm btn-danger">
                                  <i class="fas fa-times"></i></button>
                              <?php elseif ($l->status === '1') : ?>
                                <button id="pay" id-pk="<?= $l->id_spp ?>" jm-tagihan="<?= $l->jumlah ?>" class="btn btn-icon btn-sm btn-success" disabled>
                                  <i class="fas fa-check"></i></button>
                              <?php else : ?>
                                <button id="pay" id-pk="<?= $l->id_spp ?>" jm-tagihan="<?= $l->jumlah ?>" class="btn btn-icon btn-sm btn-warning" disabled>
                                  <i class="fas fa-clock"></i></button>
                              <?php endif; ?>
                            </td>
                            <td>
                              <?php
                              if ($l->status === '1') {
                                $class = '';
                              } else {
                                $class = 'disabled';
                              }

                              ?>
                              <button id="cetakbukti" id-pk="<?= $l->id_spp ?>" class="btn btn-icon btn-sm btn-danger" <?= $class; ?>>
                                <i class="fas fa-print"></i></button>
                            </td>
                          </tr>
                      <?php $no++;
                        endif;
                      endforeach;
                    else :
                      ?>
                      <tr>
                        <td colspan="6" class="text-center">Tagihan Belum dibuat</td>
                      </tr>
                    <?php
                    endif;
                    ?>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <div class="modal fade" data-backdrop="static" role="dialog" id="modal">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Modal title</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="pay_f" class="needs-validation" novalidate="" autocomplete="off">
          <div class="modal-body">
            <div class="form-group">
              <label class="form-label">Size</label>
              <div class="selectgroup w-100">
                <label class="selectgroup-item">
                  <input type="radio" name="value" value="pay1" class="selectgroup-input" checked="">
                  <span class="selectgroup-button">Bayar</span>
                </label>
                <label class="selectgroup-item">
                  <input type="radio" name="value" value="pay2" class="selectgroup-input">
                  <span class="selectgroup-button">Bayar dengan Tabungan</span>
                </label>
                <?php if ($this->session->userdata('jabatan') === 'admin') : ?>
                  <label class="selectgroup-item">
                    <input type="radio" name="value" value="pay3" class="selectgroup-input">
                    <span class="selectgroup-button">Tunai</span>
                  </label>
                <?php endif; ?>
              </div>
            </div>
            <div class="form-group">
              <label>No Referensi</label>
              <input type="text" name="noref" class="form-control" required="">
              <div class="invalid-feedback">
                No Reverensi ?
              </div>
              <div class="valid-feedback">
                Terisi!
              </div>
            </div>
            <div class="form-group bankg">
              <label>Bank</label>
              <select name="bank" class="form-control" required="">
                <option value="BPD">BPD</option>
                <option value="BRI">BRI</option>
              </select>
              <div class="invalid-feedback">
                Bank ?
              </div>
              <div class="valid-feedback">
                Terisi!
              </div>
            </div>
            <div class="form-group">
              <label>Jumlah Tagihan</label>
              <input type="text" name="jmtagihan" class="form-control formuang" required="">
              <div class="invalid-feedback">
                Jumlah Tagihan ?
              </div>
              <div class="valid-feedback">
                Terisi!
              </div>
            </div>
            <div class="form-group fotog">
              <label>Foto</label>
              <input type="file" name="foto" class="form-control">
              <div class="invalid-feedback">
                Foto ?
              </div>
              <div class="valid-feedback">
                Terisi!
              </div>
            </div>
            <!-- <div id="tag_html"></div> -->
          </div>
          <div class="modal-footer bg-whitesmoke br">
            <button type="button" class="btn btn-icon icon-left btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i>Tutup</button>
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
<?php $this->load->view('_partials/footer'); ?>

<script>
  var idpk = '';
  $.fn.dataTableExt.oApi.fnPagingInfo = function(oSettings) {
    return {
      "iStart": oSettings._iDisplayStart,
      "iEnd": oSettings.fnDisplayEnd(),
      "iLength": oSettings._iDisplayLength,
      "iTotal": oSettings.fnRecordsTotal(),
      "iFilteredTotal": oSettings.fnRecordsDisplay(),
      "iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
      "iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
    };
  };

  var tabel = $("#info_tabel1,#info_tabel2").dataTable({
    initComplete: function() {
      var api = this.api();
      $('#siswa_tabel_filter input').off('.DT').on('input.DT', function() {
        api.search(this.value).draw();
      });
    },
    oLanguage: {
      sProcessing: "loading..."
    },
    fixedHeader: true,
    processing: false,
    serverSide: false,
    scrollY: "300px",
    scrollX: true,
    scrollCollapse: true,
    paging: false,
    searching: false,
    // fixedColumns: {
    //   // left: 0,
    //   // right: 1
    //   // leftColumns: 0,
    //   // rightColumns: 1
    // },
    order: [
      [0, 'asc']
    ],
    rowCallback: function(row, data, iDisplayIndex) {
      var info = this.fnPagingInfo();
      var page = info.iPage;
      var length = info.iLength;
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

  $(document).on('click', '#pay', function() {
    $('#pay_f')[0].reset();
    $('#pay_f').removeClass('was-validated');
    let id = $(this).attr('id-pk');
    let jm = $(this).attr('jm-tagihan');
    $('[name=jmtagihan]').val(jm);
    $('[name=jmtagihan]').attr('disabled', true);
    idpk = id;
    $('.modal-title').text('Pilih Metode');
    $('#modal').modal('show');
  });

  $(document).on('click', '#cetakbukti', function() {
    let id = $(this).attr('id-pk');
    idpk = id;
    window.open("<?php echo base_url() . 'Spp_c/cetak_bukti/' ?>" + id);
  });

  $pay = 1;
  $('input[type=radio]').change(function() {
    if (this.value == 'pay1') {
      $('[name=noref]').val('');
      $('[name=noref]').attr('disabled', false);
      $('.fotog').css("display", "block");
      $('.bankg').css("display", "block");
    } else if (this.value == 'pay2') {
      $('[name=noref]').val('Bayar dari tabungan');
      $('[name=noref]').attr('disabled', true);
      $('.fotog').css("display", "none");
      $('.bankg').css("display", "none");
    } else {
      $('[name=noref]').val('Tunai');
      $('[name=noref]').attr('disabled', true);
      $('.fotog').css("display", "none");
      $('.bankg').css("display", "none");
    }
  });

  $('#pay_f').on('submit', function(e) {
    e.preventDefault();
    $('#submit').addClass('disabled btn-progress');
    var form = $(this);
    if (form[0].checkValidity() === false) {
      event.stopPropagation();
      $('#submit').removeClass('disabled btn-progress');
    } else {
      const buktifoto = $('[name=foto]').prop('files')[0];
      const dataFoto = typeof buktifoto != 'undefined' ? true : false;
      if (!dataFoto && $('[name=value]:checked').val() === 'pay1') {
        alert('Bukti pembayaran tolong diisi!');
        $('#submit').removeClass('disabled btn-progress');
        return false;
      }
      let formdata = new FormData();
      formdata.append('foto', buktifoto);
      formdata.append('pilih', $('[name=value]:checked').val());
      formdata.append('noref', $('[name=noref]').val());
      formdata.append('bank', $('[name=bank]').val());
      formdata.append('bayar', $('[name=jmtagihan]').val());
      formdata.append('nis', "<?= $info['NIS'] ?>");
      $.ajax({
        method: "POST",
        contentType: false,
        processData: false,
        dataType: "JSON",
        data: formdata,
        url: "<?= base_url() ?>Spp_c/pay/" + idpk,
        success: function(respon) {
          if (respon.status === 'sukses') {
            notifsukses(respon.msg, 'disimpan');
            if (respon.jabat === 'siswa' && respon.aksi === 'pay1') {
              notifnotime('Pembayaran Anda menunggu verifikasi');
            }
          } else {
            notifgagal2(respon.msg);
          }
          setTimeout(function() {
            window.location = "<?= base_url('Spp_c/detail/') . $info['NIS'] ?>";
          }, 2000);
        }
      });
    }
    form.addClass('was-validated');
    return false;
  });

  $(function() {
    $(document).ready(function() {
      $("[name=foto]").fileinput({
        showCaption: false,
        dropZoneEnabled: false,
        showUpload: false,
        allowedFileTypes: ['image'],
        maxFileSize: 1024,
      });
    });
  });
</script>
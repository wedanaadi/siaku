<?php
defined('BASEPATH') or exit('No direct script access allowed');
$this->load->view('_partials/header');
?>
<style>
  .modal-body {
    max-height: calc(90vh - 140px);
    overflow-y: auto;
  }
</style>
<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1>Data Siswa</h1>
    </div>

    <div class="section-body">
      <div class="row">
        <div class="col-12 col-md-12 col-lg-12">
          <div class="card">
            <div class="card-header">
              <button class="btn btn-icon icon-left btn-primary" id="siswa_t">
                <i class="fas fa-plus"></i>
                Tambah
              </button>
            </div>
            <div class="card-body">
              <div class="form-group col-3" id="ta-group">
                <!-- <label>Kelompok</label> -->
                <select name="kelompok" class="form-control js-select2" style="width:100%;" required="">
                </select>
                <div class="invalid-feedback">
                  Tahun Ajaran ?
                </div>
                <div class="valid-feedback">
                  Terisi!
                </div>
              </div>
              <div class="table-responsive">
                <table class="table table-striped nowrap" id="siswa_tabel" style="width:100%">
                  <thead>
                    <tr>
                      <th>
                        #
                      </th>
                      <th>NIS</th>
                      <th>Nama</th>
                      <th>Alamat</th>
                      <th>Telepon</th>
                      <th>TTL</th>
                      <th>Jenis Kelamin</th>
                      <th>Agama</th>
                      <th>Ayah</th>
                      <th>Ibu</th>
                      <th>Kelompok</th>
                      <th>Foto</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- Modal -->
  <div class="modal fade" data-backdrop="static" role="dialog" id="modal">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Modal title</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="siswa_f" class="needs-validation" novalidate="" autocomplete="off">
          <div class="modal-body">
            <div id="tag_html"></div>
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
  let isSave = 1;
  let idsiswa = "";
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

  var tabel = $("#siswa_tabel").dataTable({
    initComplete: function() {
      var api = this.api();
      $('#siswa_tabel_filter input').off('.DT').on('input.DT', function() {
        api.search(this.value).draw();
      });
    },
    oLanguage: {
      sProcessing: "loading..."
    },
    processing: false,
    serverSide: true,
    scrollY: false,
    scrollX: true,
    scrollCollapse: true,
    paging: true,
    // fixedColumns: {
    //   // left: 0,
    //   // right: 1
    //   leftColumns: 1,
    //   rightColumns: 1
    // },
    ajax: {
      "url": "<?php echo base_url() . 'Siswa_c/ignited_data' ?>",
      "type": "POST"
    },
    columns: [{
        "data": null,
        width: "10px",
        orderable: false,
        searchable: false
      },
      {
        "data": "NIS"
      },
      {
        "data": "nama_siswa"
      },
      {
        "data": "alamat_siswa"
      },
      {
        "data": "telp"
      },
      {
        "data": "TTL",
        searchable: false
      },
      {
        "data": "jenis_kelamin",
        render: function(data) {
          if (data === 'L') {
            return 'Laki-Laki';
          } else {
            return 'Perempuan';
          }
        }
      },
      {
        "data": "agama"
      },
      {
        "data": "nama_ayah"
      },
      {
        "data": "nama_ibu"
      },
      {
        "data": "nama_kelompok"
      },
      {
        "data": "foto",
        orderable: false,
        searchable: false,
        class: 'text-center'
      },
      {
        "data": "view",
        width: "10%",
        orderable: false,
        searchable: false,
        class: 'text-center'
      }
    ],
    order: [
      [1, 'asc']
    ],
    rowCallback: function(row, data, iDisplayIndex) {
      var info = this.fnPagingInfo();
      var page = info.iPage;
      var length = info.iLength;
      $('td:eq(0)', row).html(iDisplayIndex + 1 + info.iStart);
    }
  });

  $(document).on('click', '#siswa_t', function() {
    isSave = 1
    $.ajax({
      type: "GET",
      dataType: "JSON",
      url: "<?= base_url(); ?>Siswa_c/form/",
      success: function(respon) {
        $('#siswa_f')[0].reset();
        $('#siswa_f').removeClass('was-validated');
        $('#submit').removeClass('disabled btn-progress');
        $("#tag_html").html(respon.view);
        // $("[name=nis]").val(respon.data.nis);
        $("[name=nis]").addClass('disabled');
        $('[name=foto]').fileinput({
          allowedFileTypes: ['image'],
          allowedFileExtensions: ['jpg', 'gif', 'png'],
          maxFileSize: 1024,
        });
        $('.modal-title').text('Tambah Data');
        // $('#Modal').show().scrollTop(0);
        $('#modal').modal('show');
      }
    });
  });


  $('#siswa_f').on('submit', function(e) {
    e.preventDefault();
    var $select2 = $('.js-select2', $(this));
    if ($select2.val() === null) {
      $('.select2-selection').css('border-color', '#dc3545');
    } else {
      $('.select2-selection').css('border-color', '#28a745');
    }
    if ($('[name=foto]').val() == '' && isSave == 1) {
      $('.file-caption-name').addClass('is-invalid');
      $('.fotog .invalid-feedback').css("display", "block");
      $('.fotog .valid-feedback').css("display", "none");
      return false;
    }

    $('#submit').addClass('disabled btn-progress');
    var form = $(this);
    if (form[0].checkValidity() === false) {
      event.stopPropagation();
      $('#submit').removeClass('disabled btn-progress');
    } else {
      $.ajax({
        method: "POST",
        dataType: "JSON",
        contentType: false,
        processData: false,
        data: new FormData($("#siswa_f")[0]),
        url: isSave ? '<?= base_url() ?>Siswa_c/create' : '<?= base_url() ?>Siswa_c/update/' + idsiswa,
        success: function(respon) {
          // tabel.api().ajax.reload();
          $('#modal').modal('hide');
          if (respon.status === 'sukses') {
            notifsukses('Siswa', isSave ? 'disimpan' : 'diubah');
            setTimeout(function() {
              window.location = "<?= base_url() ?>Siswa_c/index";
            }, 1000);
          } else {
            notifgagal2('NIS sudah ada');
          }
        }
      });
    }
    form.addClass('was-validated');
    return false;
  });

  $(document).on('click', '#siswa_u', function() {
    isSave = 0;
    const id = $(this).attr('id-pk');
    $.ajax({
      type: "GET",
      dataType: "JSON",
      url: "<?= base_url(); ?>Siswa_c/form/" + id,
      success: function(respon) {
        $('#siswa_f')[0].reset();
        $('#siswa_f').removeClass('was-validated');
        $('#submit').removeClass('disabled btn-progress');
        $("#tag_html").html(respon.view);
        $('[name=nis]').val(respon.data.NIS);
        $('[name=nis]').prop('readonly', true);
        $('[name=nama]').val(respon.data.nama_siswa);
        $('[name=telp]').val(respon.data.telp);
        $('[name=alamat]').val(respon.data.alamat);
        $('[name=jeniskelamin]').val(respon.data.jenis_kelamin);
        $('[name=agama]').val(respon.data.agama);
        $('[name=tempatlahir]').val(respon.data.tempat_lahir);
        $('[name=tanggallahir]').val(respon.data.tanggal_lahir);
        $('[name=ayah]').val(respon.data.nama_ayah);
        $('[name=ibu]').val(respon.data.nama_ibu);
        $('[name=alamat]').val(respon.data.alamat_siswa);
        $('[name=foto]').attr('required', false);
        const urlf = "<?= base_url() ?>assets/img/foto/" + respon.data.foto;
        $('[name=foto]').fileinput({
          allowedFileTypes: ['image'],
          allowedFileExtensions: ['jpg', 'gif', 'png'],
          maxFileSize: 1024,
          initialPreview: [urlf],
          initialPreviewAsData: true,
          initialPreviewConfig: [{
            caption: "Foto Siswa",
            downloadUrl: urlf,
            url: urlf,
            filename: respon.data.foto,
            description: "",
            size: 500,
            width: "120px",
            type: "image",
            filetype: "image/jpg",
            key: 1
          }],
        });
        $('#tag_html').append('<div class="form-group"> <input type ="hidden" value="' + respon.data.foto + '" name="oldfoto" class ="form-control"></div>');
        select2isi("#siswa_f select[name=kelompok]", respon.kelompok.id, respon.kelompok.nama_kelompok);
        idsiswa = respon.data.NIS;
        $('.modal-title').text('Ubah Data');
        $('#Modal').show().scrollTop(0);
        $('#modal').modal('show');
      }
    });
  });

  $(document).on('click', '#siswa_d', function() {
    isSave = 0;
    const id = $(this).attr('id-pk');
    swal({
        title: 'Hapus data ?',
        text: 'Setelah dihapus, Anda tidak akan dapat memulihkan data ini!',
        icon: 'warning',
        buttons: ["Batal", "Hapus"],
      })
      .then((willDelete) => {
        if (willDelete) {
          $.ajax({
            type: "POST",
            url: "<?= base_url() ?>Siswa_c/hapus/" + id,
            success: function(respon) {
              tabel.api().ajax.reload();
              notifsukses('Siswa', 'dihapus');
            }
          });
        } else {
          notifgagal('Siswa');
        }
      });
  });

  $(function() {
    $('select[name=kelompok]').select2({
      placeholder: "Semua...",
      ajax: {
        url: "<?= base_url(); ?>Kelompok_c/select2/",
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

    select2isi("select[name=kelompok]", 'semua', 'Semua Kelompok');

    $('select[name=kelompok]').on('select2:select', function(e) {
      tabel.api().clear().destroy();
      $("#siswa_tabel").dataTable({
        initComplete: function() {
          var api = this.api();
          $('#siswa_tabel_filter input').off('.DT').on('input.DT', function() {
            api.search(this.value).draw();
          });
        },
        oLanguage: {
          sProcessing: "loading..."
        },
        processing: false,
        serverSide: true,
        scrollY: false,
        scrollX: true,
        scrollCollapse: true,
        paging: true,
        // fixedColumns: {
        //   // left: 0,
        //   // right: 1
        //   leftColumns: 1,
        //   rightColumns: 1
        // },
        ajax: {
          "url": "<?php echo base_url() . 'Siswa_c/ignited_data/' ?>" + $(this).val(),
          "type": "POST"
        },
        columns: [{
            "data": null,
            width: "10px",
            orderable: false,
            searchable: false
          },
          {
            "data": "NIS"
          },
          {
            "data": "nama_siswa"
          },
          {
            "data": "alamat_siswa"
          },
          {
            "data": "telp"
          },
          {
            "data": "TTL",
            searchable: false
          },
          {
            "data": "jenis_kelamin",
            render: function(data) {
              if (data === 'L') {
                return 'Laki-Laki';
              } else {
                return 'Perempuan';
              }
            }
          },
          {
            "data": "agama"
          },
          {
            "data": "nama_ayah"
          },
          {
            "data": "nama_ibu"
          },
          {
            "data": "nama_kelompok"
          },
          {
            "data": "foto",
            orderable: false,
            searchable: false,
            class: 'text-center'
          },
          {
            "data": "view",
            width: "10%",
            orderable: false,
            searchable: false,
            class: 'text-center'
          }
        ],
        order: [
          [1, 'asc']
        ],
        rowCallback: function(row, data, iDisplayIndex) {
          var info = this.fnPagingInfo();
          var page = info.iPage;
          var length = info.iLength;
          $('td:eq(0)', row).html(iDisplayIndex + 1 + info.iStart);
        }
      });
    });
  });
</script>
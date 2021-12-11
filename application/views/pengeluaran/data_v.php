<?php
defined('BASEPATH') or exit('No direct script access allowed');
$this->load->view('_partials/header');
?>

<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1>Data Pengeluaran</h1>
    </div>

    <div class="section-body">
      <div class="row">
        <div class="col-12 col-md-12 col-lg-12">
          <div class="card">
            <div class="card-header">
              <button class="btn btn-icon icon-left btn-primary" id="pengeluaran_t">
                <i class="fas fa-plus"></i>
                Tambah Pengeluaran
              </button>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-striped nowrap text-center" id="pengeluaran_tabel" style="width:100%">
                  <thead>
                    <tr>
                      <th>Id</th>
                      <th>Tanggal</th>
                      <th>Biaya</th>
                      <th>Keterangan</th>
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

  <div class="modal fade" data-backdrop="static" role="dialog" id="modal">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Modal title</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="pengeluaran_f" class="needs-validation" novalidate="" autocomplete="off">
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
  let idpengeluaran = "";
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

  var tabel = $("#pengeluaran_tabel").dataTable({
    initComplete: function() {
      var api = this.api();
      $('#pengeluaran_tabel_filter input').off('.DT').on('input.DT', function() {
        api.search(this.value).draw();
      });
    },
    oLanguage: {
      sProcessing: "loading..."
    },
    processing: false,
    serverSide: true,
    paging: true,
    ajax: {
      "url": "<?php echo base_url() . 'Pengeluaran_c/ignited_data' ?>",
      "type": "POST"
    },
    columns: [{
        "data": "id"
      },
      {
        "data": "tgl"
      },
      {
        "data": "biaya",
        class: 'text-right'
      },
      {
        "data": "keterangan"
      },
      {
        "data": "view",
        width: "200px",
        orderable: false,
        searchable: false,
        class: 'text-center'
      }
    ],
    columnDefs: [{
      targets: [2],
      render: $.fn.dataTable.render.number('.', ',', '', '')
    }],
    order: [
      [1, 'desc']
    ],
    rowCallback: function(row, data, iDisplayIndex) {
      var info = this.fnPagingInfo();
      var page = info.iPage;
      var length = info.iLength;
    }
  });

  $(document).on('click', '#pengeluaran_t', function() {
    isSave = '1';
    $.ajax({
      type: "GET",
      dataType: "JSON",
      url: "<?= base_url(); ?>Pengeluaran_c/form/",
      success: function(respon) {
        $('#pengeluaran_f')[0].reset();
        $('#pengeluaran_f').removeClass('was-validated');
        $('#submit').removeClass('disabled btn-progress');
        $("#tag_html").html(respon.view);
        $('.modal-title').text('Tambah Data');
        $("#modal-body").scrollTop(0);
        $('#modal').modal('show');
      }
    });
  });

  $('#pengeluaran_f').on('submit', function(e) {
    e.preventDefault();
    $('#submit').addClass('disabled btn-progress');
    console.log(parseInt($('[name=biaya]').inputmask('unmaskedvalue')));
    if (parseInt($('[name=biaya]').inputmask('unmaskedvalue')) < 1000) {
      console.log('kurang');
      e.stopPropagation();
      $('[name=biaya]').css("border-color", "red");
      $('.grupuang .invalid-feedback').css("display", "block");
      $('.grupuang .valid-feedback').css("display", "none");
      isSave === '1' ? $('[name=setoranawal]').css("border-color", "#dc3545") : $('[name=saldo]').css("border-color", "#dc3545");
      $('#submit').removeClass('disabled btn-progress');
      return false;
    }
    var form = $(this);
    if (form[0].checkValidity() === false) {
      event.stopPropagation();
      $('#submit').removeClass('disabled btn-progress');
    } else {
      $.ajax({
        method: "POST",
        contentType: false,
        processData: false,
        dataType: "JSON",
        data: new FormData($("#pengeluaran_f")[0]),
        url: isSave ? '<?= base_url() ?>Pengeluaran_c/create' : '<?= base_url() ?>Pengeluaran_c/update/' + idpengeluaran,
        success: function(respon) {
          tabel.api().ajax.reload();
          setTimeout(function() {
            $('#modal').modal('hide');
            if (respon.status === 'sukses') {
              notifsukses(respon.msg, isSave ? 'disimpan' : 'diubah');
            } else {
              notifgagal2(respon.msg);
            }
          }, 200);
        }
      });
    }
    form.addClass('was-validated');
    return false;
  });

  $(document).on('click', '#pengeluaran_u', function() {
    isSave = 0;
    const id = $(this).attr('id-pk');
    $.ajax({
      type: "GET",
      dataType: "JSON",
      url: "<?= base_url(); ?>Pengeluaran_c/form/" + id,
      success: function(respon) {
        $('#pengeluaran_f')[0].reset();
        $('#pengeluaran_f').removeClass('was-validated');
        $('#submit').removeClass('disabled btn-progress');
        $("#tag_html").html(respon.view);
        $('[name=tgl]').val(respon.data.tgl);
        $('[name=biaya]').val(respon.data.biaya);
        $('[name=keterangan]').val(respon.data.keterangan);
        idpengeluaran = respon.data.id;
        $('.modal-title').text('Ubah Data');
        $('#modal').modal('show');
      }
    });
  });

  $(document).on('click', '#pengeluaran_d', function() {
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
            url: "<?= base_url() ?>Pengeluaran_c/hapus/" + id,
            success: function(respon) {
              tabel.api().ajax.reload();
              notifsukses('Pengeluaran', 'dihapus');
            }
          });
        } else {
          notifgagal('Pengeluaran');
        }
      });
  });
</script>
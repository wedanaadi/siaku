<?php
defined('BASEPATH') or exit('No direct script access allowed');
$this->load->view('_partials/header');
?>
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
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-xs-12 col-md-3 col-md-3">
                  <img src="<?= base_url('assets/img/foto/' . $data->foto) ?>" alt="Foto" width="100%">
                </div>
                <div class="col-xs-12 col-md-4 col-md-4">
                  <table class="table" width="100%">
                    <tr>
                      <td>Nama</td>
                      <td>:</td>
                      <td><?= $data->nama_siswa ?></td>
                    </tr>
                    <tr>
                      <td>Alamat</td>
                      <td>:</td>
                      <td><?= $data->alamat_siswa ?></td>
                    </tr>
                    <tr>
                      <td>Telepon</td>
                      <td>:</td>
                      <td><?= $data->telp ?></td>
                    </tr>
                    <tr>
                      <td>Kelompok</td>
                      <td>:</td>
                      <td><?= $data->nama_kelompok ?></td>
                    </tr>
                    <tr>
                      <td>Agama</td>
                      <td>:</td>
                      <td><?= $data->agama ?></td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
<?php $this->load->view('_partials/footer'); ?>
<?php
defined('BASEPATH') or exit('No direct script access allowed');
$this->load->view('_partials/header');
?>
<style>
  .main-content {
    min-height: 530px !important;
  }
</style>
<!-- Main Content -->
<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1>Dashboard - Tahun Ajaran Aktif : <?= $ta_aktif ?> | Tanggal : <?= date('Y-m-d') ?></h1>
    </div>

    <div class="section-body">
      <?php if ($this->session->userdata('jabatan') !== 'siswa') : ?>
        <div class="row">
          <div class="col-12">
            <div class="card card-primary">
              <div class="card-header">
                <h4>Periode Tahun : <?= date('Y') ?></h4>
                <div class="card-header-action">
                  <a data-collapse="#mycard-collapse1" class="btn btn-icon btn-primary" href="#"><i class="fas fa-plus"></i></a>
                </div>
              </div>
              <div class="collapse" id="mycard-collapse1">
                <div class="card-body">

                  <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                      <div class="card card-statistic-1 bg-danger">
                        <div class="card-icon">
                          <i class="fas fa-shopping-basket"></i>
                        </div>
                        <div class="card-wrap">
                          <div class="card-header">
                            <h4 class="text-white">Pengeluaran Bulan <?= bln_th(date('Y-m')) ?></h4>
                          </div>
                          <div class="card-body text-white">
                            <?= number_format($cPengeluaran, '0', ',', '.') ?>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                      <div class="card card-statistic-1 bg-warning">
                        <div class="card-icon">
                          <i class="fas fa-money-check-alt"></i>
                        </div>
                        <div class="card-wrap">
                          <div class="card-header">
                            <h4 class="text-white">Saldo s/d bulan <?= bln_th(date('Y-m')) ?></h4>
                          </div>
                          <div class="card-body text-white">
                            <?= number_format($cSaldo, '0', ',', '.') ?>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                      <div class="card card-statistic-1 bg-success">
                        <div class="card-icon">
                          <i class="fas fa-exchange-alt"></i>
                        </div>
                        <div class="card-wrap">
                          <div class="card-header">
                            <h4 class="text-white">Penerimaan SPP Bulan <?= bln_th(date('Y-m')) ?></h4>
                          </div>
                          <div class="card-body text-white">
                            <?= number_format($cSpp, '0', ',', '.') ?>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- chart -->
                  <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                      <div class="card">
                        <div class="card-header">
                          <h4>Pemasukan & pengeluaran Tahun <?= date('Y') ?></h4>
                        </div>
                        <div class="card-body">
                          <canvas id="myChart" height="158"></canvas>
                        </div>
                      </div>
                    </div>
                  </div>

                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-12">
            <div class="card card-danger">
              <div class="card-header">
                <h4>Periode Tahun Ajaran : <?= $ta_aktif ?></h4>
                <div class="card-header-action">
                  <a data-collapse="#mycard-collapse2" class="btn btn-icon btn-danger" href="#"><i class="fas fa-minus"></i></a>
                </div>
              </div>
              <div class="collapse show" id="mycard-collapse2">
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                      <div class="card card-statistic-1 bg-primary">
                        <div class="card-icon">
                          <i class="far fa-user"></i>
                        </div>
                        <div class="card-wrap">
                          <div class="card-header">
                            <h4 class="text-white">Jumlah Siswa Aktif</h4>
                          </div>
                          <div class="card-body text-white">
                            <?= $cSiswa; ?>
                          </div>
                        </div>
                      </div>
                    </div>
                    <?php
                    $select = $this->db->query("SELECT * FROM `sistem`
                              INNER JOIN `tahun_ajaran` ta ON ta.`id` = sistem.`tahun_ajaran_aktif`");
                    if ($select->num_rows() > 0) {
                      $tasplit = explode('/', $select->row()->tahun_ajaran);
                    } else {
                      $tasplit = [date('Y'), date('Y')];
                    }
                    $i = date('m');
                    if ($i < 7) {
                      $date = $tasplit[1] . '-' . $i;
                    } else {
                      $date = $tasplit[0] . '-' . $i;
                    }
                    ?>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                      <div class="card card-statistic-1 bg-danger">
                        <div class="card-icon">
                          <i class="fas fa-shopping-basket"></i>
                        </div>
                        <div class="card-wrap">
                          <div class="card-header">
                            <h4 class="text-white">Pengeluaran Bulan <?= bln_th($date) ?></h4>
                          </div>
                          <div class="card-body text-white">
                            <?= number_format($cPengeluaran2, '0', ',', '.') ?>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                      <div class="card card-statistic-1 bg-warning">
                        <div class="card-icon">
                          <i class="fas fa-money-check-alt"></i>
                        </div>
                        <div class="card-wrap">
                          <div class="card-header">
                            <h4 class="text-white">Saldo s/d bulan <?= bln_th($date) ?></h4>
                          </div>
                          <div class="card-body text-white">
                            <?= number_format($cSaldo2, '0', ',', '.') ?>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                      <div class="card card-statistic-1 bg-success">
                        <div class="card-icon">
                          <i class="fas fa-exchange-alt"></i>
                        </div>
                        <div class="card-wrap">
                          <div class="card-header">
                            <h4 class="text-white">Penerimaan SPP Bulan <?= bln_th($date) ?></h4>
                          </div>
                          <div class="card-body text-white">
                            <?= number_format($cSpp2, '0', ',', '.') ?>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                      <div class="card">
                        <div class="card-header">
                          <h4>Pemasukan & pengeluaran Tahun Ajaran <?= $ta_aktif ?></h4>
                        </div>
                        <div class="card-body">
                          <canvas id="myChart2" height="158"></canvas>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      <?php endif; ?>


      <?php if ($this->session->userdata('jabatan') === 'siswa') : ?>
        <div class="row">
          <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1 bg-danger">
              <div class="card-wrap">
                <div class="card-header">
                  <h4 class="text-white font-weight-bold">Rekening BRI</h4>
                </div>
                <div class="card-body text-white">
                  <p>32620101205739</p>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1 bg-danger">
              <div class="card-wrap">
                <div class="card-header">
                  <h4 class="text-white font-weight-bold">Rekening BNI</h4>
                </div>
                <div class="card-body text-white">
                  <p>0223074696</p>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1 bg-danger">
              <div class="card-wrap">
                <div class="card-header">
                  <h4 class="text-white font-weight-bold">Rekening Mandiri</h4>
                </div>
                <div class="card-body text-white">
                  <p>1560009861578</p>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1 bg-danger">
              <div class="card-wrap">
                <div class="card-header">
                  <h4 class="text-white font-weight-bold">Rekening BCA</h4>
                </div>
                <div class="card-body text-white">
                  <p>5220304312</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row d-flex justify-content-center">
          <div class="col-xs-12 col-md-4 col-lg-4">
            <div class="card card-statistic-1 bg-warning">
              <div class="card-icon">
                <i class="fas fa-landmark"></i>
              </div>
              <div class="card-wrap">
                <div class="card-header">
                  <h4 class="text-white">Jumlah Tabungan</h4>
                </div>
                <div class="card-body text-white">
                  <?= number_format($saldotabungan, '0', ',', '.') ?>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xs-12 col-md-6 col-lg-6">
            <div class="card card-statistic-1 bg-info">
              <div class="card-icon">
                <i class="fas fa-money-bill-alt"></i>
              </div>
              <div class="card-wrap">
                <div class="card-header">
                  <h4 class="text-white">Pembayaran SPP Bulan <?= date('m') . ' ' . $ta_aktif ?></h4>
                </div>
                <div class="card-body text-white">
                  <?= $checkSPP ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </section>
</div>
<?php $this->load->view('_partials/footer'); ?>

<script>
  var chartp = JSON.parse('<?php echo $chart ?>');
  var chartp2 = JSON.parse('<?php echo $chart2 ?>');

  var ctx = document.getElementById("myChart").getContext('2d');
  var ctx2 = document.getElementById("myChart2").getContext('2d');
  var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"],
      datasets: [{
          label: 'Pemasukan',
          data: chartp.pemasukan,
          // data: [3000, 4000, 4500, 2000, 5000, 440, 2098, 1345],
          borderWidth: 2,
          backgroundColor: 'rgba(63,82,227,.8)',
          borderWidth: 0,
          borderColor: 'transparent',
          pointBorderWidth: 0,
          pointRadius: 3.5,
          pointBackgroundColor: 'transparent',
          pointHoverBackgroundColor: 'rgba(63,82,227,.8)',
        },
        {
          label: 'Pengeluaran',
          data: chartp.pengeluaran,
          // data: [2207, 3403, 2200, 5025, 2302, 4208, 3880, 4880],
          borderWidth: 2,
          backgroundColor: 'rgba(254,86,83,.7)',
          borderWidth: 0,
          borderColor: 'transparent',
          pointBorderWidth: 0,
          pointRadius: 3.5,
          pointBackgroundColor: 'transparent',
          pointHoverBackgroundColor: 'rgba(254,86,83,.8)',
        }
      ]
    },
    options: {
      legend: {
        display: true
      },
      tooltips: {
        callbacks: {
          label: function(tooltipItem) {
            return 'Rp ' + Number(tooltipItem.yLabel).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
          }
        }
      },
      scales: {
        yAxes: [{
          gridLines: {
            // display: true,
            drawBorder: false,
            color: '#f2f2f2',
          },
          ticks: {
            beginAtZero: true,
            callback: function(value, index, values) {
              if (Math.floor(value) === value) {
                return 'Rp ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
              }
            }
          }
        }],
        xAxes: [{
          gridLines: {
            display: true,
            tickMarkLength: 15,
          }
        }]
      },
    }
  });
  var myChart2 = new Chart(ctx2, {
    type: 'bar',
    data: {
      // labels: ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"],
      labels: chartp2.label,
      datasets: [{
          label: 'Pemasukan',
          data: chartp2.pemasukan,
          // data: [3000, 4000, 4500, 2000, 5000, 440, 2098, 1345],
          borderWidth: 2,
          backgroundColor: 'rgba(63,82,227,.8)',
          borderWidth: 0,
          borderColor: 'transparent',
          pointBorderWidth: 0,
          pointRadius: 3.5,
          pointBackgroundColor: 'transparent',
          pointHoverBackgroundColor: 'rgba(63,82,227,.8)',
        },
        {
          label: 'Pengeluaran',
          data: chartp2.pengeluaran,
          // data: [2207, 3403, 2200, 5025, 2302, 4208, 3880, 4880],
          borderWidth: 2,
          backgroundColor: 'rgba(254,86,83,.7)',
          borderWidth: 0,
          borderColor: 'transparent',
          pointBorderWidth: 0,
          pointRadius: 3.5,
          pointBackgroundColor: 'transparent',
          pointHoverBackgroundColor: 'rgba(254,86,83,.8)',
        }
      ]
    },
    options: {
      legend: {
        display: true
      },
      tooltips: {
        callbacks: {
          label: function(tooltipItem) {
            return 'Rp ' + Number(tooltipItem.yLabel).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
          }
        }
      },
      scales: {
        yAxes: [{
          gridLines: {
            display: true,
            drawBorder: false,
            color: '#f2f2f2',
          },
          ticks: {
            beginAtZero: true,
            callback: function(value, index, values) {
              if (Math.floor(value) === value) {
                return 'Rp ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
              }
            }
          }
        }],
        xAxes: [{
          gridLines: {
            display: true,
            tickMarkLength: 15,
          }
        }]
      },
    }
  });
</script>
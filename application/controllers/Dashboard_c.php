<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard_c extends CI_Controller
{
  function __construct()
  {
    $this->CI = &get_instance();
    parent::__construct();
    if ($this->session->userdata('kodeuser') == null) {
      redirect('Auth_c/login');
    }
    $this->load->library(['bcrypt']);
    $this->load->model(['Auth_m', 'Pengeluaran_m']);
  }

  public function index()
  {
    $data['title'] = "Dashboard";
    $data['ta_aktif'] = $this->ta_setting();
    $data['cSiswa'] = $this->countSiswa();
    $data['cUser'] = $this->countUser();
    $data['cPengeluaran'] = $this->countPengeluaran();
    $data['cPengeluaran2'] = $this->countPengeluaran2();
    $data['cSaldo'] = $this->countSaldo();
    $data['cSaldo2'] = $this->countSaldo2();
    $data['cSpp'] = $this->countSpp();
    $data['cSpp2'] = $this->countSpp2();
    $data['chart'] = $this->chart();
    $data['chart2'] = $this->chart2();
    if ($this->session->userdata('jabatan') === 'siswa') {
      $data['saldotabungan'] = $this->tabungansiswa();
      $data['checkSPP'] = $this->checkSPP();
      // $data['cek'] = $this->cekSPP();
      $this->session->set_userdata('cek', $this->cekSPP());
    };
    $this->load->view('dashboard_v', $data);
  }

  public function cekSPP()
  {
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
    $cek_tagihan = $this->db->query("SELECT * FROM spp WHERE `status` = '0' AND NIS = '" . $this->session->userdata('kodeuser') . "' AND DATE_FORMAT(`tgl_trx`,'%Y-%m') = '$date'")->num_rows();
    $cek_tunggakan = $this->db->query("SELECT * FROM spp WHERE `status` = '0' AND NIS = '" . $this->session->userdata('kodeuser') . "' AND DATE_FORMAT(`tgl_trx`,'%Y-%m') < '$date'")->num_rows();
    $data['tagihan'] = $cek_tagihan;
    $data['tunggakan'] = $cek_tunggakan;
    return $data;
  }

  public function ta_setting()
  {
    $this->db->select('sistem.*,ta.tahun_ajaran');
    $this->db->from('sistem');
    $this->db->join('tahun_ajaran ta', 'ta.id=sistem.tahun_ajaran_aktif');
    $data = $this->db->get()->row();
    if (is_null($data)) {
      return 'Belum diatur';
    } else {
      return $data->tahun_ajaran;
    }
  }

  public function countSiswa()
  {
    $this->db->where('is_aktif', 1);
    return $this->db->get('siswa')->num_rows();
  }

  public function countUser()
  {
    $this->db->where('is_aktif', 1);
    return $this->db->get('user')->num_rows();
  }

  public function countPengeluaran()
  {
    $sql = "SELECT sum(biaya) as saldo FROM pengeluaran WHERE is_aktif=1 AND DATE_FORMAT(tgl,'%Y-%m')=DATE_FORMAT(NOW(),'%Y-%m')";
    $dateStart = date('Y') . '01';
    $dateEnd = date('Y') . '12';
    $sql1Thn = "SELECT sum(biaya) as saldo FROM pengeluaran WHERE is_aktif=1 AND DATE_FORMAT(tgl,'%Y%m') BETWEEN '$dateStart' AND '$dateEnd'";
    $data['bulanini'] = $this->db->query($sql)->row()->saldo;
    $data['thn'] = $this->db->query($sql1Thn)->row()->saldo;
    return $data;
  }

  public function countPengeluaran2()
  {
    $select = $this->db->query("SELECT * FROM `sistem`
                              INNER JOIN `tahun_ajaran` ta ON ta.`id` = sistem.`tahun_ajaran_aktif`");
    if ($select->num_rows() > 0) {
      $tasplit = explode('/', $select->row()->tahun_ajaran);
    } else {
      $tasplit = [date('Y'), date('Y')];
    }

    $i = date('m');
    if ($i < 7) {
      $date = $tasplit[1] . '-' . $i . '-1';
    } else {
      $date = $tasplit[0] . '-' . $i . '-1';
    }
    $sql = "SELECT sum(biaya) as saldo FROM pengeluaran WHERE is_aktif=1 AND DATE_FORMAT(tgl,'%Y-%m')=DATE_FORMAT('$date','%Y-%m')";
    $dateStart = $tasplit[0] . '07';
    $dateEnd = $tasplit[1] . '06';
    $sqlThnAjaran = "SELECT SUM(biaya) AS saldo FROM pengeluaran WHERE DATE_FORMAT(`tgl`,'%Y%m') BETWEEN '$dateStart' AND '$dateEnd'";
    $data['bulanini'] = $this->db->query($sql)->row()->saldo;
    $data['thn'] = $this->db->query($sqlThnAjaran)->row()->saldo;
    return $data;
  }

  public function countSpp()
  {
    $sql = "SELECT IFNULL(SUM(jumlah),0) AS total 
            FROM spp
            INNER JOIN siswa s ON s.`NIS` = spp.`NIS`
            WHERE STATUS = '1' AND DATE_FORMAT(`tgl_trx`,'%Y%m') = DATE_FORMAT(NOW(),'%Y%m') AND s.`is_aktif` = '1'";
    $dateStart = date('Y') . '01';
    $dateEnd = date('Y') . '12';
    $sqlThn = "SELECT IFNULL(SUM(jumlah),0) AS total 
              FROM spp
              INNER JOIN siswa s ON s.`NIS` = spp.`NIS`
              WHERE STATUS = '1' AND DATE_FORMAT(`tgl_trx`,'%Y%m') BETWEEN '$dateStart' AND '$dateEnd' AND s.`is_aktif` = '1'";
    $data['bulanini'] = $this->db->query($sql)->row()->total;
    $data['thn'] = $this->db->query($sqlThn)->row()->total;
    return $data;
  }

  public function countSpp2()
  {
    $select = $this->db->query("SELECT * FROM `sistem`
                              INNER JOIN `tahun_ajaran` ta ON ta.`id` = sistem.`tahun_ajaran_aktif`");
    if ($select->num_rows() > 0) {
      $tasplit = explode('/', $select->row()->tahun_ajaran);
    } else {
      $tasplit = [date('Y'), date('Y')];
    }
    $i = date('m');
    if ($i < 7) {
      $date = $tasplit[1] . '-' . $i . '-1';
    } else {
      $date = $tasplit[0] . '-' . $i . '-1';
    }
    $sql = "SELECT IFNULL(SUM(jumlah),0) AS total 
            FROM spp 
            INNER JOIN siswa s ON s.`NIS` = spp.`NIS`
            WHERE STATUS = '1' AND DATE_FORMAT(`tgl_trx`,'%Y%m') = DATE_FORMAT('$date','%Y%m') AND s.`is_aktif` = '1'";
    $dateStart = $tasplit[0] . '07';
    $dateEnd = $tasplit[1] . '06';
    $sqlTA = "SELECT IFNULL(SUM(jumlah),0) AS total 
            FROM spp 
            INNER JOIN siswa s ON s.`NIS` = spp.`NIS`
            WHERE STATUS = '1' AND DATE_FORMAT(`tgl_trx`,'%Y%m') BETWEEN '$dateStart' AND '$dateEnd' AND s.`is_aktif` = '1'";
    $data['bulanini'] = $this->db->query($sql)->row()->total;
    $data['ta'] = $this->db->query($sqlTA)->row()->total;
    return $data;
  }

  public function countSaldo()
  {
    $sql = "SELECT SUM(masuk) AS masuk, SUM(keluar) AS keluar, SUM(masuk) - SUM(keluar) AS 'total' 
            FROM (
              SELECT *, 0 AS total FROM(
                  SELECT tgl,0 AS masuk, biaya AS keluar, keterangan, 2 AS tipe FROM pengeluaran
                  WHERE DATE_FORMAT(`tgl`,'%Y-%m') = DATE_FORMAT(NOW(),'%Y-%m') AND `is_aktif`='1'
                  UNION
                  SELECT tgl, biaya AS masuk, 0 AS keluar, keterangan, 3 AS tipe FROM `pemasukan`
                  WHERE DATE_FORMAT(`tgl`,'%Y-%m') = DATE_FORMAT(NOW(),'%Y-%m') AND `is_aktif`='1'
                  UNION
                  SELECT tgl_trx AS tgl, SUM(jumlah) AS masuk, 0 AS keluar, 'Pembayaran SPP' AS keterangan, 1 AS tipe FROM spp
                  INNER JOIN siswa s ON s.`NIS` = spp.`NIS`
                  WHERE DATE_FORMAT(`tgl_trx`,'%Y-%m') = DATE_FORMAT(NOW(),'%Y-%m') AND STATUS='1' AND s.`is_aktif` = '1'
              ) AS tabel
                UNION
                SELECT tgl, SUM(masuk) AS 'masuk', SUM(keluar) AS 'keluar','saldo awal' AS keterangan,
                0 AS tipe, SUM(masuk) - SUM(keluar) AS 'total'
                FROM (
                  SELECT tgl_trx AS tgl, SUM(jumlah) AS masuk, 0 AS keluar, 'Jumlah SPP bln Lalu' AS keterangan, 2 AS tipe FROM spp
                  INNER JOIN siswa s ON s.`NIS` = spp.`NIS`
                  WHERE DATE_FORMAT(`tgl_trx`,'%Y-%m') < DATE_FORMAT(NOW(),'%Y-%m') AND STATUS='1' AND s.`is_aktif` = '1'
                  UNION
                  SELECT tgl, biaya AS masuk, 0 AS keluar, keterangan, 3 AS tipe FROM `pemasukan`
                  WHERE DATE_FORMAT(`tgl`,'%Y-%m') < DATE_FORMAT(NOW(),'%Y-%m') AND `is_aktif`='1'
                  UNION
                  SELECT tgl,0 AS masuk, biaya AS keluar, keterangan, 1 AS tipe FROM pengeluaran
                  WHERE DATE_FORMAT(`tgl`,'%Y-%m') < DATE_FORMAT(NOW(),'%Y-%m') AND `is_aktif`='1'
              ) AS tabel2
                ORDER BY tipe ASC
            ) AS pivot";
    $sqlBlnIni = "SELECT SUM(masuk) AS 'masuk', SUM(keluar) AS 'keluar', keterangan,tgl, SUM(masuk) - SUM(keluar) AS total FROM( 
                  SELECT tgl,0 AS masuk, biaya AS keluar, keterangan, 2 AS tipe 
                  FROM pengeluaran 
                  WHERE DATE_FORMAT(`tgl`,'%Y-%m') = DATE_FORMAT(NOW(),'%Y-%m') AND `is_aktif`='1' 
                  UNION 
                  SELECT tgl, biaya AS masuk, 0 AS keluar, keterangan, 3 AS tipe 
                  FROM `pemasukan` 
                  WHERE DATE_FORMAT(`tgl`,'%Y-%m') = DATE_FORMAT(NOW(),'%Y-%m') AND `is_aktif`='1' 
                  UNION 
                  SELECT tgl_trx AS tgl, SUM(jumlah) AS masuk, 0 AS keluar, 'Pembayaran SPP' AS keterangan, 1 AS tipe 
                  FROM spp 
                  INNER JOIN siswa s ON s.`NIS` = spp.`NIS` 
                  WHERE DATE_FORMAT(`tgl_trx`,'%Y-%m') = DATE_FORMAT(NOW(),'%Y-%m') AND STATUS='1' AND s.`is_aktif` = '1' 
                ) AS tabel";
    $data['bulanini'] = $this->db->query($sqlBlnIni)->row()->total;
    $data['periode'] = $this->db->query($sql)->row()->total;
    return $data;
  }

  public function countSaldo2()
  {
    $select = $this->db->query("SELECT * FROM `sistem`
                              INNER JOIN `tahun_ajaran` ta ON ta.`id` = sistem.`tahun_ajaran_aktif`");
    if ($select->num_rows() > 0) {
      $tasplit = explode('/', $select->row()->tahun_ajaran);
    } else {
      $tasplit = [date('Y'), date('Y')];
    }
    $i = date('m');
    if ($i < 7) {
      $date = $tasplit[1] . '-' . $i . '-1';
    } else {
      $date = $tasplit[0] . '-' . $i . '-1';
    }
    $sql = "SELECT SUM(masuk) AS masuk, SUM(keluar) AS keluar, SUM(masuk) - SUM(keluar) AS 'total' 
            FROM (
              SELECT *, 0 AS total FROM(
                  SELECT tgl,0 AS masuk, biaya AS keluar, keterangan, 2 AS tipe FROM pengeluaran
                  WHERE DATE_FORMAT(`tgl`,'%Y-%m') = DATE_FORMAT('$date','%Y-%m') AND `is_aktif`='1'
                  UNION
                  SELECT tgl, biaya AS masuk, 0 AS keluar, keterangan, 3 AS tipe FROM `pemasukan`
                  WHERE DATE_FORMAT(`tgl`,'%Y-%m') = DATE_FORMAT('$date','%Y-%m') AND `is_aktif`='1'
                  UNION
                  SELECT tgl_trx AS tgl, SUM(jumlah) AS masuk, 0 AS keluar, 'Pembayaran SPP' AS keterangan, 1 AS tipe FROM spp
                  INNER JOIN siswa s ON s.`NIS` = spp.`NIS`
                  WHERE DATE_FORMAT(`tgl_trx`,'%Y-%m') = DATE_FORMAT('$date','%Y-%m') AND STATUS='1' AND s.`is_aktif` = '1'
              ) AS tabel
                UNION
                SELECT tgl, SUM(masuk) AS 'masuk', SUM(keluar) AS 'keluar','saldo awal' AS keterangan,
                0 AS tipe, SUM(masuk) - SUM(keluar) AS 'total'
                FROM (
                  SELECT tgl_trx AS tgl, SUM(jumlah) AS masuk, 0 AS keluar, 'Jumlah SPP bln Lalu' AS keterangan, 2 AS tipe FROM spp
                  INNER JOIN siswa s ON s.`NIS` = spp.`NIS`
                  WHERE DATE_FORMAT(`tgl_trx`,'%Y-%m') < DATE_FORMAT('$date','%Y-%m') AND STATUS='1' AND s.`is_aktif` = '1'
                  UNION
                  SELECT tgl, biaya AS masuk, 0 AS keluar, keterangan, 3 AS tipe FROM `pemasukan`
                  WHERE DATE_FORMAT(`tgl`,'%Y-%m') < DATE_FORMAT('$date','%Y-%m') AND `is_aktif`='1'
                  UNION
                  SELECT tgl,0 AS masuk, biaya AS keluar, keterangan, 1 AS tipe FROM pengeluaran
                  WHERE DATE_FORMAT(`tgl`,'%Y-%m') < DATE_FORMAT('$date','%Y-%m') AND `is_aktif`='1'
              ) AS tabel2
                ORDER BY tipe ASC
            ) AS pivot";
    $sqlBlnIni = "SELECT SUM(masuk) AS 'masuk', SUM(keluar) AS 'keluar', keterangan,tgl, SUM(masuk) - SUM(keluar) AS total FROM( 
                  SELECT tgl,0 AS masuk, biaya AS keluar, keterangan, 2 AS tipe 
                  FROM pengeluaran 
                  WHERE DATE_FORMAT(`tgl`,'%Y-%m') = DATE_FORMAT('$date','%Y-%m') AND `is_aktif`='1' 
                  UNION 
                  SELECT tgl, biaya AS masuk, 0 AS keluar, keterangan, 3 AS tipe 
                  FROM `pemasukan` 
                  WHERE DATE_FORMAT(`tgl`,'%Y-%m') = DATE_FORMAT('$date','%Y-%m') AND `is_aktif`='1' 
                  UNION 
                  SELECT tgl_trx AS tgl, SUM(jumlah) AS masuk, 0 AS keluar, 'Pembayaran SPP' AS keterangan, 1 AS tipe 
                  FROM spp 
                  INNER JOIN siswa s ON s.`NIS` = spp.`NIS` 
                  WHERE DATE_FORMAT(`tgl_trx`,'%Y-%m') = DATE_FORMAT('$date','%Y-%m') AND STATUS='1' AND s.`is_aktif` = '1' 
                ) AS tabel";
    $data['bulanini'] = $this->db->query($sqlBlnIni)->row()->total;
    $data['sampaibulan'] = $this->db->query($sql)->row()->total;
    return $data;
  }

  public function tabungansiswa()
  {;
    $this->db->where('NIS', $this->session->userdata('kodeuser'));
    $data = $this->db->get('tabungan');
    if ($data->num_rows() > 0) {
      return $data->row()->jumlah_tabungan;
    } else {
      return 0;
    }
  }

  public function checkSPP()
  {
    $sql = "SELECT * FROM `spp` WHERE `NIS` = '" . $this->session->userdata('kodeuser') . "' AND `tahun_ajaran` = (SELECT `tahun_ajaran_aktif` FROM sistem) AND bulan = MONTH(NOW())";
    $ex = $this->db->query($sql);
    if ($ex->num_rows() > 0) {
      $data = $ex->row();
      return $data->status == 0 ? number_format($data->jumlah, '0', ',', '.') . ' (Belum dilunasi)' : number_format($data->jumlah, '0', ',', '.') . ' (Sudah dilunasi)';
    } else {
      return "Tagihan SPP Belum dibuat";
    }
  }

  public function chart()
  {
    $datadbpengeluaran = $this->Pengeluaran_m->getPengeluaranChart();
    $datadbpemasukan = $this->getPemasukanHitung();
    $pengeluaran = [];
    $pemasukan = [];
    for ($i = 1; $i <= 12; $i++) {
      $nilaidb = 'nilai_' . $i;
      array_push($pengeluaran, $datadbpengeluaran->$nilaidb);
      if ($datadbpemasukan) {
        array_push($pemasukan, $datadbpemasukan->$nilaidb);
      } else {
        array_push($pemasukan, 0);
      }
    }
    $data['pengeluaran'] = $pengeluaran;
    $data['pemasukan'] = $pemasukan;
    return json_encode($data);
  }

  public function chart2()
  {
    $datadbpengeluaran = $this->Pengeluaran_m->getPengeluaranChart2();
    $datadbpemasukan = $this->getPemasukanHitung2();
    $pemasukanGanjil = [];
    $pemasukanGenap = [];
    $pengeluaranGanjil = [];
    $pengeluaranGenap = [];
    for ($i = 1; $i <= 12; $i++) {
      $nilaidb = 'nilai_' . $i;
      if ($i < 7) {
        array_push($pengeluaranGenap, $datadbpengeluaran->$nilaidb);
      } else {
        array_push($pengeluaranGanjil, $datadbpengeluaran->$nilaidb);
      }
      if ($datadbpemasukan) {
        if ($i < 7) {
          array_push($pemasukanGenap, $datadbpemasukan->$nilaidb);
        } else {
          array_push($pemasukanGanjil, $datadbpemasukan->$nilaidb);
        }
      } else {
        array_push($pemasukanGenap, 0);
        array_push($pemasukanGanjil, 0);
      }
    }
    $pemasukan = array_merge($pemasukanGanjil, $pemasukanGenap);
    $pengeluaran = array_merge($pengeluaranGanjil, $pengeluaranGenap);
    $select = $this->db->query("SELECT * FROM `sistem`
                              INNER JOIN `tahun_ajaran` ta ON ta.`id` = sistem.`tahun_ajaran_aktif`");
    if ($select->num_rows() > 0) {
      $tasplit = explode('/', $select->row()->tahun_ajaran);
    } else {
      $tasplit = [date('Y'), date('Y')];
    }
    $data['label'] = [
      'Juli ' . $tasplit[0],
      'Agustus ' . $tasplit[0],
      'September ' . $tasplit[0],
      'Oktober ' . $tasplit[0],
      'November ' . $tasplit[0],
      'Desember ' . $tasplit[0],
      'Januari ' . $tasplit[1],
      'Februai ' . $tasplit[1],
      'Maret ' . $tasplit[1],
      'April ' . $tasplit[1],
      'Mei ' . $tasplit[1],
      'Juni ' . $tasplit[1],
    ];
    $data['pengeluaran'] = $pengeluaran;
    $data['pemasukan'] = $pemasukan;
    return json_encode($data);
  }

  public function getPemasukanHitung()
  {
    $pemasukandinamis = '';
    $sppdinamis = '';
    $selectinselect = '';
    for ($i = 1; $i <= 12; $i++) {
      $date = date_create(date('Y-' . $i));
      $ftgl = date_format($date, "Ym");
      $pemasukandinamis .= "SUM(IF(DATE_FORMAT(tgl,'%Y%m') = '" . $ftgl . "',biaya,0)) AS nilai_" . $i . ",";
      $sppdinamis .= "SUM(IF(DATE_FORMAT(tgl_trx,'%Y%m') = '" . $ftgl . "',jumlah,0)) AS nilai_" . $i . ",";
      $selectinselect .= "SUM(nilai_" . $i . ") as nilai_" . $i . ",";
    }

    $querypemasukan = "SELECT " . $pemasukandinamis . " 'pemasukan' as 'keterangan' FROM pemasukan WHERE `is_aktif` = '1'";
    $queryspp = "SELECT " . $sppdinamis . " 'spp' as 'keterangan' FROM spp INNER JOIN siswa s ON s.`NIS` = spp.`NIS` WHERE STATUS = '1' AND s.`is_aktif` = '1'";
    $queryunion = "SELECT " . $selectinselect . "'gabungan' as 'keterangan' FROM (" . $querypemasukan . " UNION " . $queryspp . ") AS tabel";
    return $this->db->query($queryunion)->row();
  }

  public function getPemasukanHitung2()
  {
    $pemasukandinamis = '';
    $sppdinamis = '';
    $selectinselect = '';
    if ($this->db->query("SELECT sistem.*, tahun_ajaran FROM sistem INNER JOIN tahun_ajaran ta on ta.`id` = sistem.`tahun_ajaran_aktif`")->num_rows() > 0) {
      $tgl_aktif = $this->db->query("SELECT sistem.*, tahun_ajaran FROM sistem INNER JOIN tahun_ajaran ta on ta.`id` = sistem.`tahun_ajaran_aktif`")->row()->tahun_ajaran;
      $a = explode('/', $tgl_aktif);
      for ($i = 1; $i <= 12; $i++) {
        $date = date_create(date('Y-' . $i));
        $ftgl = date_format($date, "Ym");
        if ($i < 7) {
          $y = $a[1];
        } else {
          $y = $a[0];
        }
        $date2 = date_create(date($y . '-' . $i));
        $ftgl2 = date_format($date2, "Ym");
        $pemasukandinamis .= "SUM(IF(DATE_FORMAT(tgl,'%Y%m') = '" . $ftgl2 . "',biaya,0)) AS nilai_" . $i . ",";
        $sppdinamis .= "SUM(IF(DATE_FORMAT(tgl_trx,'%Y%m') = '" . $ftgl2 . "',jumlah,0)) AS nilai_" . $i . ",";
        $selectinselect .= "SUM(nilai_" . $i . ") as nilai_" . $i . ",";
      }

      $querypemasukan = "SELECT " . $pemasukandinamis . " 'pemasukan' as 'keterangan' FROM pemasukan WHERE `is_aktif` = '1'";
      $queryspp = "SELECT " . $sppdinamis . " 'spp' as 'keterangan' FROM spp INNER JOIN siswa s ON s.`NIS` = spp.`NIS` WHERE STATUS = '1' AND s.`is_aktif` = '1'";
      $queryunion = "SELECT " . $selectinselect . "'gabungan' as 'keterangan' FROM (" . $querypemasukan . " UNION " . $queryspp . ") AS tabel";
      return $this->db->query($queryunion)->row();
    } else {
      $tgl_aktif = date('Y') . '/' . date('Y');
      $a = explode('/', $tgl_aktif);
      for ($i = 1; $i <= 12; $i++) {
        $date = date_create(date('Y-' . $i));
        $ftgl = date_format($date, "Ym");
        if ($i < 7) {
          $y = $a[1];
        } else {
          $y = $a[0];
        }
        $date2 = date_create(date($y . '-' . $i));
        $ftgl2 = date_format($date2, "Ym");
        $pemasukandinamis .= "SUM(IF(DATE_FORMAT(tgl,'%Y%m') = '" . $ftgl2 . "',biaya,0)) AS nilai_" . $i . ",";
        $sppdinamis .= "SUM(IF(DATE_FORMAT(tgl_trx,'%Y%m') = '" . $ftgl2 . "',jumlah,0)) AS nilai_" . $i . ",";
        $selectinselect .= "SUM(nilai_" . $i . ") as nilai_" . $i . ",";
      }

      $querypemasukan = "SELECT " . $pemasukandinamis . " 'pemasukan' as 'keterangan' FROM pemasukan";
      $queryspp = "SELECT " . $sppdinamis . " 'spp' as 'keterangan' FROM spp WHERE status = '1'";
      $queryunion = "SELECT " . $selectinselect . "'gabungan' as 'keterangan' FROM (" . $querypemasukan . " UNION " . $queryspp . ") AS tabel";
      return $this->db->query($queryunion)->row();
    }
  }

  public function setting()
  {
    $data['title'] = "Setting";
    $data['set'] = json_encode($this->db->query("SELECT sistem.*,ta.`tahun_ajaran` FROM sistem INNER JOIN tahun_ajaran ta ON ta.`id`=sistem.`tahun_ajaran_aktif`")->row());
    $this->load->view('setting_v', $data);
  }

  public function updatesetting()
  {
    $data = [
      'tahun_ajaran_aktif' => $this->input->post('ta'),
      'jumlah_spp' => str_replace('.', '', $this->input->post('biaya')),
    ];
    $check = $this->db->query("SELECT * FROM sistem")->num_rows();
    if ($check > 0) {
      $this->db->where('id', 1);
      $this->db->update('sistem', $data);
    } else {
      $data['id'] = 1;
      $this->db->insert('sistem', $data);
    }

    echo json_encode(['status' => 'sukses']);
    // redirect('Dashboard_c/setting');
  }
}

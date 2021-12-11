<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pengeluaran_c extends CI_Controller
{

  function __construct()
  {
    $this->CI = &get_instance();
    parent::__construct();
    if ($this->session->userdata('kodeuser') == null) {
      redirect('Auth_c/login');
    }
    $this->load->library(['datatables', 'create_kode', 'create_pdf']);
    $this->load->model(['Pengeluaran_m']);
  }

  public function index()
  {
    $data['title'] = 'Pengeluaran';
    $this->load->view('pengeluaran/data_v', $data);
  }

  function ignited_data()
  { //data data produk by JSON object
    header('Content-Type: application/json');
    echo $this->Pengeluaran_m->ignited_data();
  }

  public function form($aksi = false)
  {
    $data = [];
    if ($aksi != false) {
      $data = $this->Pengeluaran_m->getBy('id', $aksi);
    }
    echo json_encode(['data' => $data, 'view' => $this->load->view('pengeluaran/form_v', null, true)]);
  }

  public function create()
  {
    $data = [
      'id' => $this->create_kode->KodeGenerate($this->Pengeluaran_m->createKode()->kode, 5, 6, 'KR', date('y') . date('m')),
      'tgl' => $this->input->post('tgl'),
      'biaya' => str_replace('.', '', $this->input->post('biaya')),
      'keterangan' => $this->input->post('keterangan'),
    ];
    $this->Pengeluaran_m->insertdb($data);
    echo json_encode(['status' => 'sukses', 'msg' => 'Pengeluaran']);
  }

  public function update($id)
  {
    $data = [
      'tgl' => $this->input->post('tgl'),
      'biaya' => str_replace('.', '', $this->input->post('biaya')),
      'keterangan' => $this->input->post('keterangan'),
    ];
    $this->Pengeluaran_m->updatedb($data, $id);
    echo json_encode(['status' => 'sukses', 'msg' => 'Pengeluaran']);
  }

  public function hapus($id)
  {
    $this->Pengeluaran_m->updatedb(['is_aktif' => 0], $id);
  }

  public function get_laporan_mk()
  {
    $select = $this->db->query("SELECT * FROM spp WHERE `tahun_ajaran` = (SELECT `tahun_ajaran_aktif` FROM `sistem`) AND bulan = MONTH(NOW())");
    if ($select->num_rows() > 0) {
      $datas = $this->db->query("SELECT * FROM spp WHERE `tahun_ajaran` = (SELECT `tahun_ajaran_aktif` FROM `sistem`) AND bulan = MONTH(NOW())")->row();
      $data['bulanth'] = bln_th($datas->tgl_trx);
      $data['pic'] = json_encode($datas->tgl_trx);
      $thn = $select->row()->tgl_trx;
    } else {
      $ta_aktif_select = $this->db->query("SELECT sistem.*,ta.`tahun_ajaran` FROM `sistem` INNER JOIN tahun_ajaran ta ON ta.`id` = sistem.`tahun_ajaran_aktif`");
      if ($ta_aktif_select->num_rows() > 0) {
        $data['bulanth'] = " - Tahun Ajaran " . $ta_aktif_select->row()->tahun_ajaran . " Belum dibuat";
      } else {
        $data['bulanth'] = '';
      }
      $thn = date('Y-m-d');
      $data['pic'] = json_encode(date('Y-m-d'));
    }
    $newdate = date("Y-m-d", strtotime('-1 month', strtotime($thn)));
    $data['blnsebelum'] = $newdate;
    $ta_aktif_select = $this->db->query("SELECT sistem.*,ta.`tahun_ajaran` FROM `sistem` INNER JOIN tahun_ajaran ta ON ta.`id` = sistem.`tahun_ajaran_aktif`");
    if ($ta_aktif_select->num_rows() > 0) {
      $thnajaran_pisah = explode('/', $ta_aktif_select->row()->tahun_ajaran);
    } else {
      $thnajaran_pisah = [date('Y'), date('Y')];
    }

    $data['laporan'] = $this->Pengeluaran_m->laporan_jurnal($thn);
    $data['saldo'] = $this->Pengeluaran_m->getSaldoSisa($thnajaran_pisah, $thn);

    return $data;
  }

  public function loadjaxjurnal()
  {
    $p = @$this->input->get('periode') ? date($this->input->get('periode') . '-1') : date('Y-m-d');
    $periodethn = date_format(date_create($p), "Y");
    $newdate = date("Y-m-d", strtotime('-1 month', strtotime($p)));
    $data['blnsebelum'] = bln_th($newdate);
    $data['bulanth'] = bln_th($p);
    $ta_aktif_select = $this->db->query("SELECT sistem.*,ta.`tahun_ajaran` FROM `sistem` INNER JOIN tahun_ajaran ta ON ta.`id` = sistem.`tahun_ajaran_aktif`");
    $thnaktif = explode('/', $ta_aktif_select->row()->tahun_ajaran);
    $i = date('m');
    if ($i < 7) {
      $y = $thnaktif[1];
    } else {
      $y = $thnaktif[0];
    }
    if ($y === $periodethn) {
      $thnajaran_pisah = $thnaktif;
    } else {
      $thnajaran_pisah = [$periodethn, $periodethn];
    }
    $data['laporan'] = $this->Pengeluaran_m->laporan_jurnal($p);
    $data['saldo'] = $this->Pengeluaran_m->getSaldoSisa($thnajaran_pisah, $p);
    echo json_encode($data);
  }

  public function getLaporanMKPeriode()
  {
    $select = $this->db->query("SELECT * FROM spp WHERE `tahun_ajaran` = (SELECT `tahun_ajaran_aktif` FROM `sistem`) AND bulan = MONTH(NOW())");
    $p = @$this->input->get('periode') ? date($this->input->get('periode') . '-1') : $select->row()->tgl_trx;
    $periodethn = date_format(date_create($p), "Y");
    $newdate = date("Y-m-d", strtotime('-1 month', strtotime($p)));
    $data['blnsebelum'] = bln_th($newdate);
    $data['bulanth'] = bln_th($p);
    $ta_aktif_select = $this->db->query("SELECT sistem.*,ta.`tahun_ajaran` FROM `sistem` INNER JOIN tahun_ajaran ta ON ta.`id` = sistem.`tahun_ajaran_aktif`");
    $thnaktif = explode('/', $ta_aktif_select->row()->tahun_ajaran);
    $i = date('m');
    if ($i < 7) {
      $y = $thnaktif[1];
    } else {
      $y = $thnaktif[0];
    }
    if ($y === $periodethn) {
      $thnajaran_pisah = $thnaktif;
    } else {
      $thnajaran_pisah = [$periodethn - 1, $periodethn];
    }
    $data['laporan'] = $this->Pengeluaran_m->laporan_jurnal($p);
    $data['saldo'] = $this->Pengeluaran_m->getSaldoSisa($thnajaran_pisah, $p);
    return $data;
  }

  public function getLaporan()
  {
    $data['title'] = "Laporan Tabungan";
    $data['lap'] = $this->get_laporan_mk();
    $this->load->view('pengeluaran/laporan_v', $data);
  }

  public function cetakHeader()
  {
    // $d['profil'] = ['nama' => 'adi', 'alamat' => 'alamat'];
    $data = $this->load->view('_partials/header_laporan', false, true);
    return $data;
  }

  public function cetak_lap_jurnal()
  {
    $data['header'] = $this->cetakHeader();
    $data['lap'] = $this->getLaporanMKPeriode();
    $html = $this->load->view('pengeluaran/cetak_jurnal_lap_v', $data, TRUE);
    $this->create_pdf->load($html, 'Laporan Jurnal', 'A4-P');
  }
}

<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pemasukan_c extends CI_Controller
{

  function __construct()
  {
    $this->CI = &get_instance();
    parent::__construct();
    if ($this->session->userdata('kodeuser') == null) {
      redirect('Auth_c/login');
    }
    $this->load->library(['datatables', 'create_kode', 'create_pdf']);
    $this->load->model(['Pemasukan_m']);
  }

  public function index()
  {
    $data['title'] = 'Pemasukan';
    $this->load->view('pemasukan/data_v', $data);
  }

  function ignited_data()
  { //data data produk by JSON object
    header('Content-Type: application/json');
    echo $this->Pemasukan_m->ignited_data();
  }

  public function form($aksi = false)
  {
    $data = [];
    if ($aksi != false) {
      $data = $this->Pemasukan_m->getBy('id', $aksi);
    }
    echo json_encode(['data' => $data, 'view' => $this->load->view('pemasukan/form_v', null, true)]);
  }

  public function create()
  {
    $data = [
      'id' => $this->create_kode->KodeGenerate($this->Pemasukan_m->createKode()->kode, 5, 6, 'MK', date('y') . date('m')),
      'tgl' => $this->input->post('tgl'),
      'biaya' => str_replace('.', '', $this->input->post('biaya')),
      'keterangan' => $this->input->post('keterangan'),
    ];
    $this->Pemasukan_m->insertdb($data);
    echo json_encode(['status' => 'sukses', 'msg' => 'Pemasukan']);
  }

  public function update($id)
  {
    $data = [
      'tgl' => $this->input->post('tgl'),
      'biaya' => str_replace('.', '', $this->input->post('biaya')),
      'keterangan' => $this->input->post('keterangan'),
    ];
    $this->Pemasukan_m->updatedb($data, $id);
    echo json_encode(['status' => 'sukses', 'msg' => 'Pemasukan']);
  }

  public function hapus($id)
  {
    $this->Pemasukan_m->updatedb(['is_aktif' => 0], $id);
  }
}

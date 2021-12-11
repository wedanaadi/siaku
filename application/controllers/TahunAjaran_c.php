<?php
defined('BASEPATH') or exit('No direct script access allowed');

class TahunAjaran_c extends CI_Controller
{

    function __construct()
    {
        $this->CI = &get_instance();
        parent::__construct();
        if ($this->session->userdata('kodeuser') == null) {
            redirect('Auth_c/login');
        }
        $this->load->library('datatables');
        $this->load->model('TahunAjaran_m');
    }

    public function index()
    {
        $data['title'] = 'Tahun Ajaran';
        $data['ta_db'] = $this->TahunAjaran_m->getAll();
        $this->load->view('ta/data_v', $data);
    }

    function ignited_data()
    { //data data produk by JSON object
        header('Content-Type: application/json');
        echo $this->TahunAjaran_m->getAll_ignited();
    }

    public function form($aksi = false)
    {
        $data = [];
        if ($aksi != false) {
            $data = $this->TahunAjaran_m->getBy('id', $aksi);
        }
        echo json_encode(['data' => $data, 'view' => $this->load->view('ta/form_v', null, true)]);
    }

    public function create()
    {
        $data = [
            'id' => uniqid(),
            'tahun_ajaran' => $this->input->post('tahunajaran'),
            // 'semester' => $this->input->post('semester'),
            'is_aktif' => 1
        ];
        $this->TahunAjaran_m->insertdb($data);
    }

    public function update($id)
    {
        $data = [
            'tahun_ajaran' => $this->input->post('tahunajaran'),
            // 'semester' => $this->input->post('semester'),
        ];
        $this->TahunAjaran_m->updatedb($data, $id);
    }

    public function hapus($id)
    {
        $this->TahunAjaran_m->updatedb(['is_aktif' => 0], $id);
    }

    public function select2()
    {
        $datas = $this->TahunAjaran_m->getAll();
        $ta[] = [];
        foreach ($datas as $data) {
            $ta[] = ['id' => $data->id, 'text' => $data->tahun_ajaran];
        }
        echo json_encode($ta);
    }
}

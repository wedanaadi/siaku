<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kelompok_c extends CI_Controller
{

    function __construct()
    {
        $this->CI = &get_instance();
        parent::__construct();
        if ($this->session->userdata('kodeuser') == null) {
            redirect('Auth_c/login');
        }
        $this->load->library('datatables');
        $this->load->model('Kelompok_m');
    }

    public function index()
    {
        $data['title'] = 'Kelompok';
        $data['ta_db'] = $this->Kelompok_m->getAll();
        $this->load->view('kelompok/data_v', $data);
    }

    function ignited_data()
    { //data data produk by JSON object
        header('Content-Type: application/json');
        echo $this->Kelompok_m->getAll_ignited();
    }

    public function form($aksi = false)
    {
        $data = [];
        if ($aksi != false) {
            $data = $this->Kelompok_m->getBy('id', $aksi);
        }
        echo json_encode(['data' => $data, 'view' => $this->load->view('kelompok/form_v', null, true)]);
    }

    public function create()
    {
        $data = [
            'id' => uniqid(),
            'nama_kelompok' => $this->input->post('kelompok'),
            'is_aktif' => 1
        ];
        $this->Kelompok_m->insertdb($data);
    }

    public function update($id)
    {
        $data = [
            'nama_kelompok' => $this->input->post('kelompok'),
        ];
        $this->Kelompok_m->updatedb($data, $id);
    }

    public function hapus($id)
    {
        $this->Kelompok_m->updatedb(['is_aktif' => 0], $id);
        $this->db->query("UPDATE siswa SET is_aktif='0' WHERE id_kelompok ='$id'");
    }

    public function select2()
    {
        $datas = $this->Kelompok_m->getAll();
        $ta[] = ['id' => 'semua', 'text' => 'Semua'];
        foreach ($datas as $data) {
            $ta[] = ['id' => $data->id, 'text' => $data->nama_kelompok];
        }
        echo json_encode($ta);
    }
}

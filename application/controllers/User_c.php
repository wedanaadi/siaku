<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User_c extends CI_Controller
{

    function __construct()
    {
        $this->CI = &get_instance();
        parent::__construct();
        if ($this->session->userdata('kodeuser') == null) {
            redirect('Auth_c/login');
        }

        $this->load->library(['datatables', 'bcrypt']);
        $this->load->model('User_m');
    }

    public function index()
    {
        $data['title'] = 'User';
        $data['ta_db'] = $this->User_m->getAll();
        $this->load->view('user/data_v', $data);
    }

    function ignited_data()
    { //data data produk by JSON object
        header('Content-Type: application/json');
        echo $this->User_m->getAll_ignited();
    }

    public function form($aksi = false)
    {
        $data = [];
        if ($aksi != false) {
            $data = $this->User_m->getBy('id', $aksi);
        }
        echo json_encode(['data' => $data, 'view' => $this->load->view('user/form_v', null, true)]);
    }

    public function create()
    {
        $data = [
            'id' => uniqid(),
            'username' => $this->input->post('username'),
            'password' => $this->bcrypt->hash_password($this->input->post('password')),
            'nama_pegawai' => $this->input->post('nama'),
            'alamat' => $this->input->post('alamat'),
            'telp' => $this->input->post('telp'),
            'jabatan' => $this->input->post('jabatan'),
            'is_aktif' => 1
        ];
        $this->User_m->insertdb($data);
    }

    public function update($id)
    {
        $data = [
            'username' => $this->input->post('username'),
            'nama_pegawai' => $this->input->post('nama'),
            'alamat' => $this->input->post('alamat'),
            'telp' => $this->input->post('telp'),
            'jabatan' => $this->input->post('jabatan'),
        ];
        $this->input->post('password') == null ? '' : $data['password'] = $this->bcrypt->hash_password($this->input->post('password'));
        $this->User_m->updatedb($data, $id);
    }

    public function hapus($id)
    {
        $this->User_m->updatedb(['is_aktif' => 0], $id);
    }
}

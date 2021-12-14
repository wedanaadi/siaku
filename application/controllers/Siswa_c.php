<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Siswa_c extends CI_Controller
{

    function __construct()
    {
        $this->CI = &get_instance();
        parent::__construct();
        if ($this->session->userdata('kodeuser') == null) {
            redirect('Auth_c/login');
        }
        $this->load->library(['datatables', 'create_kode']);
        $this->load->model(['Siswa_m', 'Kelompok_m']);
    }

    public function index()
    {
        $data['title'] = 'Siswa';
        $data['ta_db'] = $this->Siswa_m->getAll();
        $this->load->view('siswa/data_v', $data);
    }

    function ignited_data($kelompok = 'semua')
    { //data data produk by JSON object
        header('Content-Type: application/json');
        echo $this->Siswa_m->getAll_ignited($kelompok);
    }

    public function form($aksi = false)
    {
        $data = [];
        $kelompok = [];
        if ($aksi != false) {
            $data = $this->Siswa_m->getBy('NIS', $aksi);
            $kelompok = $this->Kelompok_m->getBy('id', $data->id_kelompok);
        } else {
            $data = ['nis' => $this->create_kode->KodeGenerate($this->Siswa_m->createKode()->kode, 5, 6, '', date('y') . date('m'))];
        }
        echo json_encode(['data' => $data, 'view' => $this->load->view('siswa/form_v', null, true), 'kelompok' => $kelompok]);
    }

    public function select2()
    {
        $datas = $this->Kelompok_m->getAll();
        $kelompok[] = [];
        foreach ($datas as $data) {
            $kelompok[] = ['id' => $data->id, 'text' => $data->nama_kelompok];
        }
        echo json_encode($kelompok);
    }

    public function create()
    {
        $nisinput = $this->input->post('nis');
        $check = $this->db->query("SELECT * FROM siswa WHERE NIS='$nisinput' AND is_aktif='1'")->num_rows();
        if ($check === 0) {
            $config['upload_path']          = "assets/img/foto/";
            $config['allowed_types']        = 'gif|jpg|png|jpeg';
            $config['overwrite'] = true;
            $config['max_size']             = 1024;
            $config['file_name']             = 'siswa_' . $this->input->post('nis') . '_file';

            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('foto')) {
                $data['error'] = $this->upload->display_errors();
            } else {
                $uploaded_data = $this->upload->data();
                $data = [
                    'nama_siswa' => ucwords($this->input->post('nama')),
                    'tempat_lahir' => ucwords($this->input->post('tempatlahir')),
                    'alamat_siswa' => ucwords($this->input->post('alamat')),
                    'tanggal_lahir' => $this->input->post('tanggallahir'),
                    'telp' => $this->input->post('telp'),
                    'jenis_kelamin' => $this->input->post('jeniskelamin'),
                    'nama_ayah' => ucwords($this->input->post('ayah')),
                    'nama_ibu' => ucwords($this->input->post('ibu')),
                    'id_kelompok' => $this->input->post('kelompok'),
                    'agama' => $this->input->post('agama'),
                    'is_aktif' => 1,
                    'foto' => $uploaded_data['file_name'],
                ];
                $check2 = $this->db->query("SELECT * FROM siswa WHERE NIS='$nisinput' AND is_aktif='0'")->num_rows();
                if ($check2 === 0) {
                    $data['NIS'] = $this->input->post('nis');
                    $this->Siswa_m->insertdb($data);
                } else {
                    $this->Siswa_m->updatedb($data, $this->input->post('nis'));
                }
            }
            echo json_encode(['status' => 'sukses']);
        } else {
            echo json_encode(['status' => 'gagal']);
        }
    }

    public function cekDuplicateNIS()
    {
        $inputNIS = $this->input->get('NIS');
        $check = $this->db->query("SELECT * FROM siswa WHERE NIS='$inputNIS' AND is_aktif='1'")->num_rows();
        if ($check > 0) {
            echo json_encode(['status' => false]);
        } else {
            echo json_encode(['status' => true]);
        }
    }

    public function update($id)
    {
        $config['upload_path']          = "assets/img/foto/";
        $config['allowed_types']        = 'gif|jpg|png|jpeg';
        $config['overwrite'] = true;
        $config['max_size']             = 1024;
        $config['file_name']             = 'siswa_' . $id . '_file';

        $c = 0;
        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('foto')) {
            $data['error'] = $this->upload->display_errors();
            $c = 1;
        } else {
            $uploaded_data = $this->upload->data();
        }

        $data = [
            'nama_siswa' => ucwords($this->input->post('nama')),
            'tempat_lahir' => ucwords($this->input->post('tempatlahir')),
            'alamat_siswa' => ucwords($this->input->post('alamat')),
            'tanggal_lahir' => $this->input->post('tanggallahir'),
            'telp' => $this->input->post('telp'),
            'jenis_kelamin' => $this->input->post('jeniskelamin'),
            'nama_ayah' => ucwords($this->input->post('ayah')),
            'nama_ibu' => ucwords($this->input->post('ibu')),
            'id_kelompok' => $this->input->post('kelompok'),
            'agama' => $this->input->post('agama'),
            'is_aktif' => 1
        ];
        if ($c === 0) {
            $data['foto'] = $uploaded_data['file_name'];
        }
        $this->Siswa_m->updatedb($data, $id);
        echo json_encode(['status' => 'sukses']);
    }

    public function hapus($id)
    {
        $this->Siswa_m->updatedb(['is_aktif' => 0], $id);
    }

    public function profil()
    {
        $nis = $this->session->userdata('kodeuser');
        $data['title'] = 'Siswa';
        $data['data'] = $this->Siswa_m->profil($nis);
        $this->load->view('siswa/profil_v', $data);
    }
}

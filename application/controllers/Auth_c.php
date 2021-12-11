<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth_c extends CI_Controller
{

  function __construct()
  {
    $this->CI = &get_instance();
    parent::__construct();
    $this->load->library(['bcrypt']);
    $this->load->model('Auth_m');
  }

  public function index()
  {
    $this->login();
  }

  public function login()
  {
    $data['title'] = 'Auth';
    $this->load->view('auth/login_v', $data);
  }

  public function loginProses()
  {
    if ($this->input->post('value') === 'auth1') {
      $userdata = $this->Auth_m->getUser($this->input->post('username'));
      if ($userdata->num_rows() > 0) {
        if ($this->bcrypt->check_password($this->input->post('password'), $userdata->row()->password)) {
          $user_session = [
            'session_id' => $this->session->userdata('session_id'),
            'kodeuser' => $userdata->row()->id,
            'namauser' => $userdata->row()->nama_pegawai,
            'jabatan' => $userdata->row()->jabatan,
            'timeago' => date('Y-m-d H:i:s'),
          ];
          $this->session->set_userdata($user_session);
          echo json_encode(['action' => '1', 'msg' => 'Login Sukses.']);
        } else {
          echo json_encode(['action' => '0', 'msg' => 'Username dan Password tidak sama.']);
        }
      } else {
        echo json_encode(['action' => '0', 'msg' => 'Data Tidak ditemukan.']);
      }
    } else {
      if ($this->input->post('username') == $this->input->post('password')) {
        $userCount = $this->Auth_m->getSiswa($this->input->post('username'));
        $userdata = $this->Auth_m->getSiswaData($this->input->post('username'));
        if ($userCount > 0) {
          $user_session = [
            'session_id' => $this->session->userdata('session_id'),
            'kodeuser' => $userdata->NIS,
            'namauser' => $userdata->nama_siswa,
            'jabatan' => 'siswa',
            'timeago' => date('Y-m-d H:i:s'),
          ];
          $this->session->set_userdata($user_session);
          echo json_encode(['action' => '1', 'msg' => 'Login Sukses.']);
        } else {
          echo json_encode(['action' => '0', 'msg' => 'Data Tidak ditemukan.']);
        }
      } else {
        echo json_encode(['action' => '0', 'msg' => 'Username dan Password tidak sama.']);
      }
    }
  }

  public function logout()
  {
    $this->session->sess_destroy();
    redirect('Auth_c/login');
  }
}

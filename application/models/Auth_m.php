<?php
class Auth_m extends CI_Model
{
  public function getUser($username)
  {
    $this->db->where('username', $username);
    $this->db->where('is_aktif', 1);
    return $this->db->get('user');
  }
  public function getSiswa($username)
  {
    $this->db->where('NIS', $username);
    $this->db->where('is_aktif', 1);
    return $this->db->get('siswa')->num_rows();
  }
  public function getSiswaData($username)
  {
    $this->db->where('NIS', $username);
    $this->db->where('is_aktif', 1);
    return $this->db->get('siswa')->row();
  }
}

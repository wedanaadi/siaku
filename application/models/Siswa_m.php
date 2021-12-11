<?php
class Siswa_m extends CI_Model
{

  private $tabel = 'siswa';

  function getAll()
  {
    $this->db->where('is_aktif', 1);
    $this->db->order_by('nama_siswa', 'ASC');
    return $this->db->get($this->tabel)->result();
  }

  function getAll_ignited($kelompok)
  {
    $this->datatables->select("NIS,nama_siswa,tempat_lahir,tanggal_lahir,jenis_kelamin,nama_ayah,nama_ibu,CONCAT(tempat_lahir,', ',`tanggal_lahir`) AS 'TTL',alamat_siswa,telp,nama_kelompok,foto,agama");
    $this->datatables->join("kelompok", 'kelompok.id=' . $this->tabel . ".id_kelompok");
    $this->datatables->where($this->tabel . '.is_aktif', 1);
    $this->datatables->where('kelompok.is_aktif', 1);
    if ($kelompok !== 'semua') {
      $this->datatables->where($this->tabel . '.id_kelompok', $kelompok);
    }
    $this->datatables->from($this->tabel);
    $this->datatables->add_column('foto', '<img alt="image" src="' . base_url('assets/img/foto/') . '$1" width="35" data-toggle="tooltip" title="" data-original-title="$2">', 'foto,nama_siswa');
    $this->datatables->add_column(
      'view',
      '<button class="btn btn-icon icon-left btn-warning" id-pk="$1" id="siswa_u">
                                                    <i class="fas fa-edit"></i>
                                                    Ubah
                                                </button>
                                                <button class="btn btn-icon icon-left btn-danger" id-pk="$1" id="siswa_d">
                                                    <i class="fas fa-trash"></i>
                                                    Hapus
                                                </button>',
      'NIS,foto,nama_siswa'
    );
    return $this->datatables->generate();
  }

  function createKode()
  {
    $date = date('y') . date('m');
    return $this->db->query("SELECT MAX(`NIS`) AS 'kode' FROM siswa WHERE SUBSTR(`NIS`,1,4) = '$date' AND `is_aktif` = '1'")->row();
  }

  function getBy($indicator, $id)
  {
    $this->db->where($indicator, $id);
    return $this->db->get($this->tabel)->row();
  }

  function profil($nis)
  {
    $this->db->where('NIS', $nis);
    $this->db->select('siswa.*,kelompok.nama_kelompok');
    $this->db->join('kelompok', 'kelompok.id=siswa.id_kelompok');
    $this->db->from($this->tabel);
    return $this->db->get()->row();
  }

  function insertdb($data)
  {
    $this->db->insert($this->tabel, $data);
  }

  function updatedb($data, $id)
  {
    $this->db->Where('NIS', $id);
    $this->db->update($this->tabel, $data);
  }
}

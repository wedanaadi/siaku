<?php
class Pemasukan_m extends CI_Model
{
  private $tabel = 'pemasukan';
  function ignited_data()
  {
    $this->datatables->select("id, date(tgl) as tgl, biaya, keterangan");
    $this->datatables->where('is_aktif', 1);
    $this->datatables->from($this->tabel);
    $this->datatables->add_column(
      'view',
      '<button class="btn btn-icon icon-left btn-warning" id-pk="$1" id="pemasukan_u">
                                                    <i class="fas fa-edit"></i>
                                                    Ubah
                                                </button>
                                                <button class="btn btn-icon icon-left btn-danger" id-pk="$1" id="pemasukan_d">
                                                    <i class="fas fa-trash"></i>
                                                    Hapus
                                                </button>',
      'id'
    );
    return $this->datatables->generate();
  }

  function getBy($indicator, $id)
  {
    $this->db->where($indicator, $id);
    return $this->db->get($this->tabel)->row();
  }

  function insertdb($data)
  {
    $this->db->insert($this->tabel, $data);
  }

  function updatedb($data, $id)
  {
    $this->db->Where('id', $id);
    $this->db->update($this->tabel, $data);
  }

  function createKode()
  {
    $date = date('y') . date('m');
    return $this->db->query("SELECT MAX(`id`) AS 'kode' FROM pemasukan WHERE SUBSTR(`id`,3,4) = '$date'")->row();
  }
}

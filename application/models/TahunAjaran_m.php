<?php
class TahunAjaran_m extends CI_Model
{

    private $tabel = 'tahun_ajaran';

    function getAll()
    {
        $this->db->order_by('tahun_ajaran', 'ASC');
        $this->db->where('is_aktif', 1);
        return $this->db->get($this->tabel)->result();
    }

    function getAll_ignited()
    {
        $this->datatables->select("id,tahun_ajaran");
        $this->datatables->where('is_aktif', 1);
        $this->datatables->from($this->tabel);
        $this->datatables->add_column(
            'view',
            '<button class="btn btn-icon icon-left btn-warning" id-pk="$1" id="ta_u">
                                                    <i class="fas fa-edit"></i>
                                                    Ubah
                                                </button>
                                                <button class="btn btn-icon icon-left btn-danger" id-pk="$1" id="ta_d">
                                                    <i class="fas fa-trash"></i>
                                                    Hapus
                                                </button>',
            'id,tahun_ajaran'
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
}

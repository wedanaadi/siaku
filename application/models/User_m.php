<?php
class User_m extends CI_Model{
    
    private $tabel = 'user';

    function getAll()
    {
        return $this->db->get($this->tabel)->result();
    }

    function getAll_ignited()
    {
        $this->datatables->select("id,username,nama_pegawai,jabatan,alamat,telp");
        $this->datatables->where('is_aktif',1);
        $this->datatables->from($this->tabel);
        $this->datatables->add_column('view', '<button class="btn btn-icon icon-left btn-warning" id-pk="$1" id="user_u">
                                                    <i class="fas fa-edit"></i>
                                                    Ubah
                                                </button>
                                                <button class="btn btn-icon icon-left btn-danger" id-pk="$1" id="user_d">
                                                    <i class="fas fa-trash"></i>
                                                    Hapus
                                                </button>',
                                     'id,username,nama_pegawai,jabatan,alamat,telp');
        return $this->datatables->generate(); 
    }

    function getBy($indicator,$id)
    {
        $this->db->where($indicator,$id);
        return $this->db->get($this->tabel)->row();
    }

    function insertdb($data)
    {
        $this->db->insert($this->tabel,$data);
    }

    function updatedb($data,$id)
    {
        $this->db->Where('id',$id);
        $this->db->update($this->tabel,$data);
    }
}
<?php
class Spp_m extends CI_Model
{
  private $tabel = 'spp';
  private $siswa = 'siswa';
  private $veri = 'spp_bukti_bayar';

  function ignited_data_siswa($kelompok)
  {
    $this->datatables->select($this->siswa . ".NIS,nama_siswa,tempat_lahir,tanggal_lahir,jenis_kelamin,nama_ayah,nama_ibu,CONCAT(tempat_lahir,', ',`tanggal_lahir`) AS 'TTL',alamat_siswa,telp,nama_kelompok");
    $this->datatables->join("kelompok", 'kelompok.id=' . $this->siswa . ".id_kelompok");
    $this->datatables->join($this->tabel, $this->tabel . '.NIS=' . $this->siswa . ".NIS");
    $this->datatables->where($this->siswa . '.is_aktif', 1);
    $this->datatables->where('kelompok.is_aktif', 1);
    if ($kelompok != 'semua') {
      $this->datatables->where('kelompok.id', $kelompok);
    }
    if ($this->session->userdata('jabatan') === 'siswa') {
      $this->datatables->where($this->siswa . '.NIS', $this->session->userdata('kodeuser'));
    }
    $this->datatables->from($this->siswa);
    $this->datatables->group_by($this->siswa . ".NIS");
    $this->datatables->add_column(
      'view',
      '<a href="' . base_url('Spp_c/detail/') . '$1" class="btn btn-icon icon-left btn-info">
                                                    <i class="fas fa-file-invoice-dollar"></i>
                                                    Detail Tagihan
                                                </a>',
      'NIS,nama_siswa,tempat_lahir,tanggal_lahir,jenis_kelamin,nama_ayah,nama_ibu'
    );
    return $this->datatables->generate();
  }

  function ignited_data_verifikasi($kelompok)
  {
    $this->datatables->select($this->veri . ".id," . $this->veri . ".nis,spp.id_spp,bukti,is_verifikasi,keterangan,siswa.nama_siswa,ta.tahun_ajaran,spp.bulan,nama_kelompok");
    $this->datatables->join('spp', 'spp.id_spp=' . $this->veri . ".id_spp");
    $this->datatables->join('siswa', 'siswa.NIS=' . $this->veri . ".nis");
    $this->datatables->join('tahun_ajaran ta', 'ta.id=spp.tahun_ajaran');
    $this->datatables->join("kelompok", 'kelompok.id=' . $this->siswa . ".id_kelompok");
    $this->datatables->where($this->veri . '.is_verifikasi', 0);
    if ($kelompok != 'semua') {
      $this->datatables->where('kelompok.id', $kelompok);
    }
    $this->datatables->from($this->veri);
    $this->datatables->add_column(
      'view',
      '<button class="btn btn-icon icon-left btn-info" id-pk="$1" id="foto_view">
          <i class="fas fa-search"></i>
          Lihat Bukti
      </button>
      <button class="btn btn-icon icon-left btn-success" id-pk="$1" id-spp="$2" id="veri_proses">
          <i class="fas fa-check-double"></i>
          Verifikasi
      </button>',
      'id,id_spp'
    );
    return $this->datatables->generate();
  }

  function getSiswaSPP($id)
  {
    $sql = "SELECT * FROM (
            SELECT s.`NIS`,s.`nama_siswa`,IFNULL(tahun_ajaran,'kosong') AS 'thn' FROM spp
            RIGHT JOIN siswa s ON s.`NIS` = spp.`NIS`
            WHERE s.`is_aktif` = '1'
            ) AS pivot
            WHERE `thn` != '$id'
            GROUP BY NIS";
    return $this->db->query($sql)->result();
  }

  function getSiswaSPP2($id)
  {
    $sql = "SELECT * FROM (
            SELECT s.`NIS`,s.`nama_siswa`,IFNULL(tahun_ajaran,'kosong') AS 'thn' FROM spp
            RIGHT JOIN siswa s ON s.`NIS` = spp.`NIS`
            WHERE s.`is_aktif` = '1'
            ) AS pivot
            WHERE `thn` = '$id'
            GROUP BY NIS";
    return $this->db->query($sql)->result();
  }

  function createKode()
  {
    $date = date('y') . date('m');
    return $this->db->query("SELECT MAX(`id_spp`) AS 'kode' FROM spp WHERE SUBSTR(`id_spp`,3,4) = '$date'")->row();
  }

  function insertdb($data)
  {
    $this->db->insert_batch($this->tabel, $data);
  }

  function getTagihan($id)
  {
    $sql = "SELECT spp.*,ta.`tahun_ajaran`, s.`nama_siswa`, YEAR(`tgl_trx`) AS thn FROM spp
            INNER JOIN `tahun_ajaran` ta ON ta.`id` = spp.`tahun_ajaran`
            INNER JOIN siswa s ON s.`NIS` = spp.`NIS`
            WHERE spp.NIS = '$id' AND spp.`tahun_ajaran` = (SELECT `tahun_ajaran_aktif` FROM sistem)
            ORDER BY YEAR(`tgl_trx`) ASC, bulan ASC";
    return $execute = $this->db->query($sql);
  }

  function updateDb($data, $id)
  {
    $this->db->where('id_spp', $id);
    $this->db->update($this->tabel, $data);
  }

  function data_tunggakan($thn, $nis)
  {
    $i = date('m');
    if ($i < 7) {
      $y = $thn[1] . $i;
    } else {
      $y = $thn[0] . $i;
    }

    $sql = "SELECT spp.NIS, s.`nama_siswa`, CONCAT(YEAR(`tgl_trx`),'-',bulan) AS 'tgl', bulan, jumlah, ta.`tahun_ajaran`,
            k.`nama_kelompok`, spp.`id_spp`, spp.`status` 
            FROM spp
            INNER JOIN siswa s ON s.`NIS` = spp.`NIS`
            INNER JOIN tahun_ajaran ta ON ta.`id` = spp.`tahun_ajaran` 
            INNER JOIN kelompok k ON k.`id` = s.`id_kelompok`
            WHERE spp.`status` != '1' AND DATE_FORMAT(`tgl_trx`,'%Y%m') < '$y' AND s.`is_aktif`='1' AND spp.NIS = '$nis'
            ORDER BY NIS, DATE_FORMAT(spp.`tgl_trx`,'%Y%m'), tahun_ajaran,nama_kelompok";
    return $this->db->query($sql)->result();
  }

  function laporan_tunggakan($thn, $a, $b, $kelompok)
  {
    if ($kelompok !== 'semua') {
      $lanjut = " AND k.`id`='$kelompok'";
    } else {
      $lanjut = '';
    }

    $i = date('m');
    if ($i < 7) {
      $y = $thn[1] . $i;
    } else {
      $y = $thn[0] . $i;
    }

    $sql = "SELECT spp.NIS, s.`nama_siswa`, CONCAT(YEAR(`tgl_trx`),'-',bulan) AS 'tgl', bulan, jumlah, ta.`tahun_ajaran`, k.`nama_kelompok` FROM spp
            INNER JOIN siswa s ON s.`NIS` = spp.`NIS`
            INNER JOIN tahun_ajaran ta ON ta.`id` = spp.`tahun_ajaran` 
            INNER JOIN kelompok k ON k.`id` = s.`id_kelompok`
            WHERE spp.`status` = '0' AND DATE_FORMAT(`tgl_trx`,'%Y%m') < '$y' $lanjut AND s.`is_aktif`='1'
            AND tgl_trx BETWEEN '$a' AND '$b'
            ORDER BY NIS, DATE_FORMAT(spp.`tgl_trx`,'%Y%m'), tahun_ajaran,nama_kelompok";
    return $this->db->query($sql)->result();
  }

  function laporan_tagihan($kelompok)
  {
    if ($kelompok !== 'semua') {
      $lanjut = " AND k.`id`='$kelompok'";
    } else {
      $lanjut = '';
    }

    $sql = "SELECT spp.NIS, s.`nama_siswa`, CONCAT(YEAR(`tgl_trx`),'-',bulan) AS 'tgl', bulan, jumlah, ta.`tahun_ajaran`, k.`nama_kelompok` FROM spp
            INNER JOIN siswa s ON s.`NIS` = spp.`NIS`
            INNER JOIN tahun_ajaran ta ON ta.`id` = spp.`tahun_ajaran` 
            INNER JOIN kelompok k ON k.`id` = s.`id_kelompok`
            WHERE spp.`status` = '0' AND bulan = MONTH(NOW()) AND spp.`tahun_ajaran` = (SELECT `tahun_ajaran_aktif` FROM `sistem`) $lanjut
            ORDER BY NIS, bulan, nama_kelompok";
    return $this->db->query($sql)->result();
  }

  function laporan_lunas($a, $b, $kelompok)
  {
    if ($kelompok !== 'semua') {
      $lanjut = " AND k.`id`='$kelompok'";
    } else {
      $lanjut = '';
    }

    $sql = "SELECT spp.NIS, s.`nama_siswa`, CONCAT(YEAR(`tgl_trx`),'-',bulan) AS 'tgl', bulan, jumlah, ta.`tahun_ajaran`, k.`nama_kelompok` FROM spp
            INNER JOIN siswa s ON s.`NIS` = spp.`NIS`
            INNER JOIN tahun_ajaran ta ON ta.`id` = spp.`tahun_ajaran` 
            INNER JOIN kelompok k ON k.`id` = s.`id_kelompok`
            WHERE spp.`status` = '1' AND tgl_trx BETWEEN '$a' AND '$b' $lanjut AND s.`is_aktif` = '1'
            ORDER BY NIS, bulan, tahun_ajaran,nama_kelompok";
    return $this->db->query($sql)->result();
  }
}

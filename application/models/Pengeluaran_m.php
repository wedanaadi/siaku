<?php
class Pengeluaran_m extends CI_Model
{
  private $tabel = 'pengeluaran';

  function ignited_data()
  {
    $this->datatables->select("id, date(tgl) as tgl, biaya, keterangan");
    $this->datatables->where('is_aktif', 1);
    $this->datatables->from($this->tabel);
    $this->datatables->add_column(
      'view',
      '<button class="btn btn-icon icon-left btn-warning" id-pk="$1" id="pengeluaran_u">
                                                    <i class="fas fa-edit"></i>
                                                    Ubah
                                                </button>
                                                <button class="btn btn-icon icon-left btn-danger" id-pk="$1" id="pengeluaran_d">
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
    return $this->db->query("SELECT MAX(`id`) AS 'kode' FROM pengeluaran WHERE SUBSTR(`id`,3,4) = '$date'")->row();
  }

  function laporan_jurnal($thn)
  {
    $monthdata = date_format(date_create($thn), 'm');
    $sql = "SELECT *, 0 AS total FROM (
            SELECT tgl,0 AS masuk, biaya AS keluar, keterangan, 2 AS tipe FROM pengeluaran
            WHERE DATE_FORMAT(`tgl`,'%Y-%m') = DATE_FORMAT('$thn','%Y-%m') AND `is_aktif` = '1'
            UNION
            SELECT * FROM (
              SELECT tgl_trx AS tgl, sum(jumlah) AS masuk, 0 AS keluar, CONCAT('Pembayaran SPP bulan ','$monthdata') AS keterangan, 1 AS tipe FROM spp
              INNER JOIN siswa s ON s.`NIS` = spp.`NIS`
              WHERE DATE_FORMAT(`tgl_trx`,'%Y-%m') = DATE_FORMAT('$thn','%Y-%m') AND STATUS='1' AND s.`is_aktif` = '1'
            ) AS pivot
            WHERE masuk IS NOT NULL
            UNION
            SELECT tgl, biaya AS masuk, 0 AS keluar, keterangan, 3 AS tipe FROM pemasukan 
            WHERE DATE_FORMAT(`tgl`,'%Y-%m') = DATE_FORMAT('$thn','%Y-%m') AND `is_aktif` = '1'
            ) AS tabel
            ORDER BY tgl ASC";
    return $this->db->query($sql)->result();
  }

  function getSaldoSisa($thn, $periode)
  {
    $i = date_format(date_create(date($periode)), 'm');
    if ($i < 7) {
      $y = $thn[1] . $i;
    } else {
      $y = $thn[0] . $i;
    }

    $sql = "SELECT tgl, SUM(masuk) AS 'masuk', SUM(keluar) AS 'keluar','saldo awal' AS keterangan,
            0 AS tipe, IFNULL(SUM(masuk) - SUM(keluar),0) AS 'total'FROM (
              SELECT tgl_trx AS tgl, SUM(jumlah) AS masuk, 0 AS keluar, 'Jumlah SPP bln Lalu' AS keterangan, 2 AS tipe FROM spp
              INNER JOIN `siswa` s ON s.`NIS` = spp.`NIS`
              WHERE DATE_FORMAT(`tgl_trx`,'%Y%m') < '$y' AND STATUS='1' AND s.`is_aktif` = '1'
              UNION
              SELECT tgl,0 AS masuk, biaya AS keluar, keterangan, 1 AS tipe FROM pengeluaran
              WHERE DATE_FORMAT(`tgl`,'%Y%m') < '$y' AND `is_aktif` = '1'
              UNION
              SELECT tgl, biaya AS masuk, 0 AS keluar, keterangan, 2 AS tipe FROM pemasukan 
              WHERE DATE_FORMAT(`tgl`,'%Y%m') < '$y' AND `is_aktif` = '1'
            ) AS tabel2";
    return $this->db->query($sql)->row();
  }

  function getPengeluaranChart()
  {
    $query1 = '';
    for ($i = 1; $i <= 12; $i++) {
      $date = date_create(date('Y-' . $i));
      $ftgl = date_format($date, "Ym");
      $query1 .= "SUM(IF(DATE_FORMAT(tgl,'%Y%m') = '" . $ftgl . "',biaya,0)) AS nilai_" . $i . ",";
    }

    $query2 = "SELECT " . $query1 . " 'pengeluaran' as 'status' FROM pengeluaran WHERE is_aktif='1'";
    return $this->db->query($query2)->row();
  }

  function getPengeluaranChart2()
  {
    $query1 = '';
    $ta_select = $this->db->query("SELECT sistem.*, tahun_ajaran FROM sistem INNER JOIN tahun_ajaran ta on ta.`id` = sistem.`tahun_ajaran_aktif`");
    if ($ta_select->num_rows() > 0) {
      $tgl_aktif = $ta_select->row()->tahun_ajaran;
    } else {
      $tgl_aktif = date('Y') . "/" . date('Y');
    }

    $a = explode('/', $tgl_aktif);
    for ($i = 1; $i <= 12; $i++) {
      if ($i < 7) {
        $y = $a[1];
      } else {
        $y = $a[0];
      }
      $date2 = date_create(date($y . '-' . $i));
      $ftgl2 = date_format($date2, "Ym");
      $query1 .= "SUM(IF(DATE_FORMAT(tgl,'%Y%m') = '" . $ftgl2 . "',biaya,0)) AS nilai_" . $i . ",";
    }

    $query2 = "SELECT " . $query1 . " 'pengeluaran' as 'status' FROM pengeluaran WHERE is_aktif='1'";
    return $this->db->query($query2)->row();
  }
}

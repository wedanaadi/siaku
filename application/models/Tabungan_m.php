<?php
class Tabungan_m extends CI_Model
{

	private $tabel = 'tabungan';
	private $tabeldetil = 'tabungan_detil';

	function getAll()
	{
		return $this->db->get($this->tabel)->result();
	}

	function tabungan_ignited($kelompok)
	{
		$this->datatables->select("id_tabungan,tgl_buka,setoran_awal,jumlah_tabungan,siswa.nama_siswa,kelompok.nama_kelompok");
		$this->datatables->join('siswa', "siswa.NIS=" . $this->tabel . ".NIS");
		$this->datatables->join('kelompok', "kelompok.id=siswa.id_kelompok");
		$this->datatables->where('siswa.is_aktif', 1);
		$this->datatables->where('kelompok.is_aktif', 1);
		if ($kelompok != 'semua') {
			$this->datatables->where('kelompok.id', $kelompok);
		}
		$this->datatables->from($this->tabel);
		$this->datatables->add_column(
			'view',
			'<div class="dropdown d-inline">
					<button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							Opsi
					</button>
					<div class="dropdown-menu">
							<a class="dropdown-item has-icon" href="#" id-pk="$1" id-saldo="$2" id="tarik"><i class="fas fa-wallet"></i> Tarik</a>
							<a class="dropdown-item has-icon" href="#" id-pk="$1" id-saldo="$2" id="tabung"><i class="fas fa-donate"></i> Tabung</a>
							<a class="dropdown-item has-icon" href="' . base_url('Tabungan_c/detil/') . '$1"><i class="fas fa-file-invoice-dollar"></i> Detil</a>
							<a class="dropdown-item has-icon" href="' . base_url('Tabungan_c/edit/') . '$1"><i class="fas fa-pen"></i> Edit</a>
					</div>
			</div>',
			'id_tabungan,jumlah_tabungan'
		);
		return $this->datatables->generate();
	}

	function getBy($indicator, $id)
	{
		$this->db->where($indicator, $id);
		return $this->db->get($this->tabel)->row();
	}
	function getByWali($indicator, $id)
	{
		$this->db->where($indicator, $id);
		return $this->db->get($this->tabel);
	}

	function insertdb($data, $tipe = false)
	{
		$this->db->insert($tipe ? $this->tabeldetil : $this->tabel, $data);
	}

	function updatedb($data, $id)
	{
		$this->db->Where('id_tabungan', $id);
		$this->db->update($this->tabel, $data);
	}

	function createKode()
	{
		$date = date('y') . date('m');
		return $this->db->query("SELECT MAX(`id_tabungan`) AS 'kode' FROM tabungan WHERE SUBSTR(`id_tabungan`,3,4) = '$date'")->row();
	}

	function tabunganBy($condition, $id)
	{
		$this->db->where($condition, $id);
		$this->db->select($this->tabel . ".*,siswa.nama_siswa");
		$this->db->join('siswa', 'siswa.NIS=' . $this->tabel . ".NIS");
		$this->db->from($this->tabel);
		return $this->db->get()->row();
	}

	function getMinMaxTabungan($type, $id)
	{
		if ($type === 1) {
			$sqlpart = "MAX(saldo) AS 'saldo'";
		} else {
			$sqlpart = "MIN(saldo) AS 'saldo'";
		}

		$sql =  "SELECT $sqlpart FROM (SELECT setoran_awal AS 'saldo' FROM tabungan WHERE `id_tabungan` = '$id'
						UNION
						SELECT `saldo_akhir` AS 'saldo' FROM `tabungan_detil`WHERE `id_tabungan` = '$id') AS tabel";

		return $this->db->query($sql)->row();
	}

	function getDetail($id)
	{
		$sql = 	"SELECT *, DATE(tgl) as 'tgl_baru' FROM(SELECT tgl_buka AS 'tgl', setoran_awal AS 'masuk', 0 AS 'keluar', setoran_awal AS 'saldo', 'Setoran Awal' AS 'keterangan', 0 AS 'type' FROM tabungan WHERE id_tabungan='$id' 
							UNION 
						SELECT tgl_transaksi AS 'tgl', saldo AS 'masuk', 0 AS 'keluar', saldo_akhir AS 'saldo', keterangan, 1 AS 'type' FROM tabungan_detil WHERE id_tabungan='$id' AND TYPE='1' 
							UNION 
						SELECT tgl_transaksi AS 'tgl', 0 AS 'masuk', saldo AS 'keluar', saldo_akhir AS 'saldo', keterangan, 2 AS 'type' FROM tabungan_detil WHERE id_tabungan='$id' AND TYPE='2'
						ORDER BY tgl ASC) AS tpv";

		return $this->db->query($sql);
	}

	function getEdit($id)
	{
		$sql = 	"SELECT *, DATE(tgl) as 'tgl_baru' FROM(SELECT id_tabungan as 'id', tgl_buka AS 'tgl', setoran_awal AS 'masuk', 0 AS 'keluar', setoran_awal AS 'saldo', 'Setoran Awal' AS 'keterangan', 0 AS 'type' FROM tabungan WHERE id_tabungan='$id' 
							UNION 
						SELECT id, tgl_transaksi AS 'tgl', saldo AS 'masuk', 0 AS 'keluar', saldo_akhir AS 'saldo', keterangan, 1 AS 'type' FROM tabungan_detil WHERE id_tabungan='$id' AND TYPE='1' 
							UNION 
						SELECT id, tgl_transaksi AS 'tgl', 0 AS 'masuk', saldo AS 'keluar', saldo_akhir AS 'saldo', keterangan, 2 AS 'type' FROM tabungan_detil WHERE id_tabungan='$id' AND TYPE='2'
						ORDER BY tgl ASC) AS tpv";

		return $this->db->query($sql);
	}

	function loadedit($id_t)
	{
		$this->db->where('td.id_tabungan', $id_t);
		$this->db->join('tabungan t', 't.id_tabungan=td.id_tabungan');
		return $this->db->get('tabungan_detil td');
	}

	function rekap($periode, $lastday, $count = false)
	{
		$query1 = '';
		for ($i = 1; $i <= $lastday; $i++) {
			$date = date_create(date($periode . $i));
			$ftgl = date_format($date, "Ymd");
			$query1 .= "SUM(IF(DATE_FORMAT(tgl,'%Y%m%d') = '" . $ftgl . "',IF(tipe=1,saldo,saldo*-1),0)) AS tgl_" . $i . ",";
		}

		// $set_dinamis	= $this->db->query($query)->row()->dinamisquery;
		$query_tabel	=	"SELECT t.`NIS`,s.`nama_siswa`, pivot1.* FROM (
											SELECT `id_tabungan`, tgl_buka AS tgl, setoran_awal AS saldo, 1 AS tipe FROM `tabungan` AS t
											UNION
											SELECT `id_tabungan`, `tgl_transaksi` AS `tgl`, saldo, 1 AS `tipe` FROM `tabungan_detil` WHERE TYPE = 1
											UNION
											SELECT `id_tabungan`, `tgl_transaksi` AS `tgl`, saldo, 2 AS `tipe` FROM `tabungan_detil` WHERE TYPE = 2
										) AS pivot1
										INNER JOIN `tabungan` AS t ON t.`id_tabungan` = pivot1.`id_tabungan`
										INNER JOIN siswa AS s ON s.`NIS` = t.`NIS`
										";


		$query_utama = "SELECT primarytabel.`NIS`, s.`nama_siswa`, " . $query1 . "SUM(saldo) as 'total_bln' FROM (" . $query_tabel . ") AS primarytabel
										INNER JOIN siswa s ON s.`NIS` = primarytabel.NIS
										WHERE s.is_aktif = '1'
		   							GROUP BY primarytabel.NIS
										ORDER BY nama_siswa";
		$execute = $this->db->query($query_utama);
		if ($count) {
			return $execute->num_rows();
		} else {
			return $execute->result();
		}
	}

	function getBulanLalu($date)
	{
		$sql = "SELECT NIS, nama_siswa, SUM(bln_sbm) AS 'bln_lalu' FROM (
						SELECT t.`NIS`,s.`nama_siswa`, 0 AS 'bln_sbm' FROM tabungan t
						INNER JOIN siswa s ON s.`NIS`=t.`NIS`
						UNION
						SELECT t.`NIS`,s.`nama_siswa`, SUM(saldo) AS 'bln_sbm' FROM (
							SELECT `id_tabungan`, tgl_buka AS tgl, setoran_awal AS saldo, 1 AS tipe FROM `tabungan` AS t
							UNION
							SELECT `id_tabungan`, `tgl_transaksi` AS `tgl`, saldo, 1 AS `tipe` FROM `tabungan_detil` WHERE TYPE = 1
							UNION
							SELECT `id_tabungan`, `tgl_transaksi` AS `tgl`, saldo*-1 AS saldo, 2 AS `tipe` FROM `tabungan_detil` WHERE TYPE = 2
							) AS pivot1
							INNER JOIN `tabungan` AS t ON t.`id_tabungan` = pivot1.`id_tabungan`
							INNER JOIN siswa AS s ON s.`NIS` = t.`NIS`
							WHERE DATE_FORMAT(tgl,'%Y%m') < DATE_FORMAT('$date','%Y%m')
							GROUP BY NIS
						) AS pivotbaru
						GROUP BY NIS
						ORDER BY nama_siswa";
		return $this->db->query($sql)->result();
	}

	function laporan_simpanan($a, $b, $kelompok)
	{
		if ($kelompok !== 'semua') {
			$lanjut = " AND k.`id`='$kelompok'";
		} else {
			$lanjut = '';
		}
		$sql = "SELECT tabel.*, s.nama_siswa, type, k.`nama_kelompok`, DATE_FORMAT(tgl,'%Y-%m-%d') as tgl FROM (
									SELECT `id_tabungan`, setoran_awal AS 'masuk', 
									0 AS 'keluar', setoran_awal AS 'saldo',tgl_buka AS 'tgl', 
									NIS, 000000000000 AS 'id', 0 as type
									FROM tabungan
									UNION
									SELECT td.id_tabungan, td.saldo AS 'masuk', 0 AS 'keluar', td.saldo_akhir AS 'saldo',
									td.tgl_transaksi AS 'tgl', t.NIS, td.id, type
									FROM `tabungan_detil` td
									INNER JOIN tabungan t ON t.id_tabungan = td.id_tabungan
									WHERE td.type = 1
									UNION
									SELECT td.id_tabungan, 0 AS 'masuk', td.saldo AS 'keluar', td.saldo * -1 AS 'saldo',
									td.tgl_transaksi AS 'tgl', t.NIS, td.id, type
									FROM `tabungan_detil` td
									INNER JOIN tabungan t ON t.id_tabungan = td.id_tabungan
									WHERE td.type = 2
									) AS tabel
									INNER JOIN siswa s ON s.NIS=tabel.NIS
									INNER JOIN kelompok k ON k.`id` = s.`id_kelompok`
									WHERE tgl BETWEEN '$a' AND '$b' AND s.is_aktif='1' $lanjut
									ORDER BY tgl ASC, id_tabungan ASC, id ASC";
		return $this->db->query($sql)->result();
	}
}

<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Spp_c extends CI_Controller
{
  function __construct()
  {
    $this->CI = &get_instance();
    parent::__construct();
    if ($this->session->userdata('kodeuser') == null) {
      redirect('Auth_c/login');
    }
    $this->load->library(['datatables', 'create_kode', 'create_pdf']);
    $this->load->model(['Spp_m', 'Tabungan_m']);
  }

  public function index()
  {
    $data['title'] = 'SPP';
    if ($this->db->query("SELECT ta.`tahun_ajaran` FROM sistem INNER JOIN tahun_ajaran ta on ta.`id`=sistem.`tahun_ajaran_aktif`")->num_rows() > 0) {
      $data['spp'] = $this->db->query("SELECT ta.`tahun_ajaran` FROM sistem INNER JOIN tahun_ajaran ta on ta.`id`=sistem.`tahun_ajaran_aktif`")->row()->tahun_ajaran;
    } else {
      $data['spp'] = '';
    }

    $this->load->view('spp/data_v', $data);
  }

  public function verifikasi()
  {
    $data['title'] = 'Verifikasi';
    $this->load->view('spp/verifikasi_v', $data);
  }

  function ignited_data_siswa($kelompok = 'semua')
  { //data data produk by JSON object
    header('Content-Type: application/json');
    echo $this->Spp_m->ignited_data_siswa($kelompok);
  }

  function ignited_data_verifikasi($kelompok = 'semua')
  { //data data produk by JSON object
    header('Content-Type: application/json');
    echo $this->Spp_m->ignited_data_verifikasi($kelompok);
  }

  function getBukti()
  {
    $this->db->where('id', $this->input->get('id'));
    $data = $this->db->get('spp_bukti_bayar')->row()->bukti;
    echo json_encode($data);
  }

  public function veriProses()
  {
    $idveri = $this->input->post('id');
    $idspp = $this->input->post('spp');
    $this->db->trans_start();
    $this->db->where('id', $idveri);
    $this->db->update('spp_bukti_bayar', ['is_verifikasi' => 1]);
    $this->db->where('id_spp', $idspp);
    $this->db->update('spp', ['status' => 1]);
    $this->db->trans_complete();
  }

  public function form()
  {
    $data = [];
    echo json_encode(['data' => $data, 'view' => $this->load->view('spp/form_v', null, true)]);
  }

  public function getSiswaTagihan()
  {
    $data = $this->Spp_m->getSiswaSPP($this->input->get('ta'));
    echo json_encode($data);
  }

  public function getSiswaTagihanUbah()
  {
    $taSelect = $this->input->get('ta');
    $taSplit = explode("/", $this->input->get('taText'));
    $checkTAaktif = $this->db->query("SELECT * FROM sistem WHERE tahun_ajaran_aktif = '$taSelect' ")->num_rows();
    if ($checkTAaktif > 0) {
      $bln = [];
      if (date('m') > 6) {
        for ($i = date('m'); $i <= 12; $i++) {
          array_push($bln, $i);
        }
        for ($i = 1; $i <= 6; $i++) {
          array_push($bln, $i);
        }
        // $data['message'] = "Perubahan Jumlah Tagihan SPP hanya dapat dilakukan dari bulan " . bln_only(date('m')) . " " . $taSplit[0] . " sampai Juni" . $taSplit[1];
      } else {
        for ($i = date('m'); $i <= 6; $i++) {
          array_push($bln, $i);
        }
      }
      $data['bulan'] = $bln;
      $data['message'] = "Perubahan Jumlah Tagihan SPP hanya dapat dilakukan dari bulan " . bulan_des(date('m')) . " " . $taSplit[0] . " sampai Juni " . $taSplit[1];
    } else {
      $data['bulan'] = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
      $data['message'] = "Perubahan Jumlah Tagihan SPP hanya dapat dilakukan dari bulan Juli " . $taSplit[0] . " sampai Juni " . $taSplit[1];
    }

    $data['siswa'] = $this->Spp_m->getSiswaSPP2($this->input->get('ta'));
    $data['bulanSekarang'] = date('m');
    echo json_encode($data);
  }

  public function createTagihan()
  {
    $ta = $this->db->query("SELECT * FROM tahun_ajaran WHERE id= '" . $this->input->post('ta') . "'")->row()->tahun_ajaran;
    $tasplit = explode("/", $ta);
    foreach ($this->input->post('siswa') as $s) {
      foreach ($this->input->post('bulan') as $b) {
        $data[] = [
          'id_spp' => uniqid(),
          'NIS' => $s[1],
          // 'nama_siswa' => $s[2],
          'bulan' => $b,
          'jumlah' => str_replace('.', '', $this->input->post('jumlah')),
          'tahun_ajaran' => $this->input->post('ta'),
          'tgl_trx' => $b > 6 ? date($tasplit[0] . '-' . $b . '-d') : date($tasplit[1] . '-' . $b . '-d'),
        ];
      }
    }
    $this->db->trans_start();
    $this->Spp_m->insertdb($data, true);
    $this->db->trans_complete();
    echo json_encode(['status' => 'sukses', 'msg' => 'Tagihan Spp']);
  }

  public function updateTagihan()
  {
    $this->db->trans_start();
    foreach ($this->input->post('siswa') as $s) {
      foreach ($this->input->post('bulan') as $b) {
        // $data[] = [
        //   'jumlah' => str_replace('.', '', $this->input->post('jumlah')),
        //   'tahun_ajaran' => $this->input->post('ta'),
        // ];
        $jumlah = str_replace('.', '', $this->input->post('jumlah'));
        $ta = $this->input->post('ta');
        $this->db->query("UPDATE spp SET jumlah='$jumlah' WHERE bulan = '$b' AND tahun_ajaran = '$ta'");
      }
    }
    $this->db->trans_complete();
    echo json_encode(['status' => 'sukses', 'msg' => 'Tagihan Spp']);
  }

  public function tagihanSPP()
  {
    $data['title'] = 'SPP';
    if ($this->db->query("SELECT * FROM sistem")->num_rows() > 0) {
      $data['jumlah_setting'] = $this->db->query("SELECT * FROM sistem")->row()->jumlah_spp;
    } else {
      $data['jumlah_setting'] = '';
    }

    $this->load->view('spp/addTagihan_v', $data);
  }

  public function editTagihanSPP()
  {
    $data['title'] = 'SPP';
    $data['jumlah_setting'] = $this->db->query("SELECT * FROM sistem")->row()->jumlah_spp;
    $this->load->view('spp/editTagihan_v', $data);
  }

  public function detail($id)
  {
    $parsing['title'] = 'SPP';
    $data = $this->Spp_m->getTagihan($id);
    if ($data->num_rows() > 0) {
      $utang = 0;
      $dataresult = $data->result();
      foreach ($dataresult as $v) {
        if ($v->status === '0') {
          $utang += $v->jumlah;
        }
      }
      $parsing['info'] = [
        'ta' => $data->result()[0]->tahun_ajaran,
        'NIS' => $data->result()[0]->NIS,
        'nama' => $data->result()[0]->nama_siswa,
        'utang' => $utang
      ];
      $parsing['loop'] = $data->result();
      $parsing['count'] = $data->num_rows();
    } else {
      if ($this->db->query("SELECT ta.`tahun_ajaran` FROM sistem INNER JOIN tahun_ajaran ta on ta.`id`=sistem.`tahun_ajaran_aktif`")->num_rows() > 0) {
        $taaktif = $this->db->query("SELECT ta.`tahun_ajaran` FROM sistem INNER JOIN tahun_ajaran ta on ta.`id`=sistem.`tahun_ajaran_aktif`")->row()->tahun_ajaran;
      } else {
        $taaktif = 'Belum diatur';
      }

      $parsing['info'] = [
        'ta' => $taaktif,
        'NIS' => $id,
        'nama' => $this->db->query("SELECT * FROM siswa WHERE NIS = '$id'")->row()->nama_siswa,
        'utang' => 0
      ];
      $parsing['count'] = $data->num_rows();
    }
    $this->load->view('spp/detail_v', $parsing);
  }

  public function pay($id)
  {
    $config['upload_path']          = "assets/img/buktispp/";
    $config['allowed_types']        = 'gif|jpg|png';
    $config['overwrite'] = true;
    $config['max_size']             = 1024;
    $config['file_name']             = 'bukti_' . $this->input->post('nis') . '_file_' . $id;

    $this->load->library('upload', $config);
    if (!$this->upload->do_upload('foto')) {
      $data['error'] = $this->upload->display_errors();
    } else {
      if ($this->input->post('pilih') === 'pay1') {
        #bagian pay1
        $uploaded_data = $this->upload->data();
        $verifikasi = [
          'id' => uniqid(),
          'nis' => $this->input->post('nis'),
          'id_spp' => $id,
          'bukti' => $uploaded_data['file_name'],
          'is_verifikasi' => $this->session->userdata('jabatan') === 'admin' ? 1 : 0,
          'keterangan' => $this->session->userdata('jabatan') === 'admin' ? 'Upload By Admin' : 'Upload by siswa',
        ];
        $this->db->insert('spp_bukti_bayar', $verifikasi);
      }
    }
    if ($this->input->post('pilih') === 'pay1') {
      $status_spp = $this->session->userdata('jabatan') === 'siswa' ? 2 : 1;
    } else {
      $status_spp = '1';
    }

    $data = [
      'status' => $status_spp,
      'noref' => $this->input->post('pilih') === 'pay1' ? $this->input->post('noref') . "-" . $this->input->post('bank') : $this->input->post('noref'),
      'bayar' => $this->input->post('bayar')
    ];
    $this->db->trans_start();
    if ($this->input->post('pilih') === 'pay2') {
      $jumlah = $this->db->query("SELECT jumlah FROM spp WHERE `id_spp` = '$id' ")->row()->jumlah;
      $selectTabungan = $this->db->query("SELECT jumlah_tabungan FROM tabungan WHERE `NIS` = '" . $this->input->post('nis') . "' ");
      if ($selectTabungan->num_rows() > 0) {
        $tabungan = $selectTabungan->row()->jumlah_tabungan;
        if ($tabungan - $jumlah < 0) {
          echo json_encode(['status' => 'gagal', 'msg' => 'Saldo tidak cukup']);
          exit();
        }
        $idtabungan = $this->db->query("SELECT id_tabungan FROM tabungan WHERE NIS = '" . $this->input->post('nis') . "'")->row()->id_tabungan;
        $tabungan = [
          'id' => uniqid(),
          'id_tabungan' => $idtabungan,
          'type' => 2,
          'saldo' => $jumlah,
          'saldo_akhir' => $tabungan - $jumlah,
          'tgl_transaksi' => date('Y-m-d H:i:s'),
          'keterangan' => $this->input->post('noref')
        ];
        $dataupdate = [
          'jumlah_tabungan' => $tabungan['saldo_akhir']
        ];
        $this->Tabungan_m->insertdb($tabungan, true);
        $this->Tabungan_m->updatedb($dataupdate, $idtabungan);
      } else {
        echo json_encode(['status' => 'gagal', 'msg' => 'Tabungan Tidak ditemukan']);
        exit();
      }
    } //end pay2
    $this->Spp_m->updateDb($data, $id);
    $this->db->trans_complete();
    echo json_encode(['status' => 'sukses', 'msg' => 'Pembayaran', 'jabat' => $this->session->userdata('jabatan'), 'aksi' => $this->input->post('pilih')]);
  }

  public function sppLunas()
  {
    $data['title'] = 'SPP Lunas';
    $this->load->view('spp/lunas_v', $data);
  }

  public function sppTunggakan()
  {
    $data['title'] = 'Tunggakan SPP';
    $this->load->view('spp/tunggakan_v', $data);
  }

  public function detailLunas()
  {
    $qer = "SELECT spp.*,ta.`tahun_ajaran` as 'ta', DATE_FORMAT(spp.`tgl_trx`,'%Y-%m') AS tgl FROM spp 
            INNER JOIN tahun_ajaran ta ON ta.`id` = spp.`tahun_ajaran`
            WHERE NIS = '" . $this->input->get('id') . "' AND status ='1' AND spp.`tahun_ajaran`='" . $this->input->get('ta') . "'";
    $a = $this->db->query($qer)->result();
    echo json_encode($a);
  }

  public function detailTunggakan()
  {
    $ta_aktif_select = $this->db->query("SELECT sistem.*,ta.`tahun_ajaran` FROM `sistem` INNER JOIN tahun_ajaran ta ON ta.`id` = sistem.`tahun_ajaran_aktif`");
    if ($ta_aktif_select->num_rows() > 0) {
      $thnajaran_pisah = explode('/', $ta_aktif_select->row()->tahun_ajaran);
    } else {
      $thnajaran_pisah = [date('Y'), date('Y')];
    }
    $data  = $this->Spp_m->data_tunggakan($thnajaran_pisah, $this->input->get('id'));
    echo json_encode($data);
  }

  public function get_laporan_tunggakan()
  {
    $a = @$this->input->get('awal') ? $this->input->get('awal') : date('Y-m-01');
    $b = @$this->input->get('akhir') ? $this->input->get('akhir') : date('Y-m-31');
    $c = @$this->input->get('kelompok') ? $this->input->get('kelompok') : 'semua';

    $ta_aktif_select = $this->db->query("SELECT sistem.*,ta.`tahun_ajaran` FROM `sistem` INNER JOIN tahun_ajaran ta ON ta.`id` = sistem.`tahun_ajaran_aktif`");
    if ($ta_aktif_select->num_rows() > 0) {
      $thnajaran_pisah = explode('/', $ta_aktif_select->row()->tahun_ajaran);
    } else {
      $thnajaran_pisah = [date('Y'), date('Y')];
    }

    $data['awal'] = tgl_only($a);
    $data['akhir'] = tgl_only($b);
    $data['laporan'] = $this->Spp_m->laporan_tunggakan($thnajaran_pisah, $a, $b, $c);

    return $data;
  }

  public function loadajaxTung()
  {
    echo json_encode($this->get_laporan_tunggakan());
  }

  public function getLaporanTunggakan()
  {
    $data['title'] = "Laporan Tunggakan";
    $data['lap'] = $this->get_laporan_tunggakan();
    $this->load->view('spp/lap_tunggakan_v', $data);
  }

  public function get_laporan_lunas()
  {
    $a = @$this->input->get('awal') ? $this->input->get('awal') : date('Y-m-01');
    $b = @$this->input->get('akhir') ? $this->input->get('akhir') : date('Y-m-31');
    $c = @$this->input->get('kelompok') ? $this->input->get('kelompok') : 'semua';

    $data['awal'] = tgl_only($a);
    $data['akhir'] = tgl_only($b);
    $data['laporan'] = $this->Spp_m->laporan_lunas($a, $b, $c);
    $tunai = $this->Spp_m->laporan_lunas($a, $b, $c, 'Tunai');
    $bank = $this->Spp_m->laporan_lunas($a, $b, $c, 'bank');
    $tabungan = $this->Spp_m->laporan_lunas($a, $b, $c, 'Bayar dari tabungan');
    $data['cTunai'] = count($tunai);
    $data['cBank'] = count($bank);
    $data['cTabungan'] = count($tabungan);
    $data['cJumlah'] = $data['cTunai'] + $data['cBank'] + $data['cTabungan'];
    $bpd = 0;
    $bri = 0;
    foreach ($bank as $b) {
      if (explode('-', $b->noref)[1] === 'BPD') {
        $bpd += 1;
      } else {
        $bri += 1;
      }
    }
    $data['bpd'] = $bpd;
    $data['bri'] = $bri;

    return $data;
  }

  public function loadajaxLun()
  {
    echo json_encode($this->get_laporan_lunas());
  }

  public function getLaporanLunas()
  {
    $data['title'] = "Laporan SPP Lunas";
    $data['lap'] = $this->get_laporan_lunas();
    $this->load->view('spp/lap_lunas_v', $data);
  }

  public function get_laporan_tagihan()
  {
    if ($this->db->query("SELECT * FROM spp WHERE `tahun_ajaran` = (SELECT `tahun_ajaran_aktif` FROM `sistem`) AND bulan = MONTH(NOW())")->num_rows() > 0) {
      $datas = $this->db->query("SELECT * FROM spp WHERE `tahun_ajaran` = (SELECT `tahun_ajaran_aktif` FROM `sistem`) AND bulan = MONTH(NOW())")->row();
      $data['bulanth'] = bln_th($datas->tgl_trx);
    } else {
      $ta_aktif_select = $this->db->query("SELECT `tahun_ajaran` FROM `sistem` INNER JOIN tahun_ajaran ta ON ta.`id` = sistem.`tahun_ajaran_aktif`");
      if ($ta_aktif_select->num_rows() > 0) {
        $data['bulanth'] = " - Tahun Ajaran " . $ta_aktif_select->row()->tahun_ajaran . " Belum dibuat";
      } else {
        $data['bulanth'] = '';
      }
    }
    $a = @$this->input->get('kelompok') ? $this->input->get('kelompok') : 'semua';
    $data['laporan'] = $this->Spp_m->laporan_tagihan($a);
    return $data;
  }

  public function loadajaxLap()
  {
    echo json_encode($this->Spp_m->laporan_tagihan($this->input->get('kelompok')));
  }

  public function getLaporanTagihan()
  {
    $data['title'] = "Laporan SPP Lunas";
    $data['lap'] = $this->get_laporan_tagihan();
    $this->load->view('spp/lap_tagihan_v', $data);
  }

  public function cetakHeader()
  {
    // $d['profil'] = ['nama' => 'adi', 'alamat' => 'alamat'];
    $data = $this->load->view('_partials/header_laporan', false, true);
    return $data;
  }

  public function get_laporan_spp()
  {
    $datas = $this->db->query("SELECT * FROM spp WHERE `tahun_ajaran` = (SELECT `tahun_ajaran_aktif` FROM `sistem`) AND bulan = MONTH(NOW())")->row();
    $data['bulanth'] = bln_th($datas->tgl_trx);
    $data['laporan'] = $this->Spp_m->laporan_tagihan();
    return $data;
  }

  public function cetak_lap_spp()
  {
    $data['header'] = $this->cetakHeader();
    $data['lap'] = $this->get_laporan_tagihan();
    $html = $this->load->view('spp/cetak_spp_lap_v', $data, TRUE);
    $this->create_pdf->load($html, 'Laporan Pembayaran SPP', 'A4-P');
  }

  public function cetak_lap_tunggakan()
  {
    $data['header'] = $this->cetakHeader();
    $data['lap'] = $this->get_laporan_tunggakan();
    $html = $this->load->view('spp/cetak_tunggakan_lap_v', $data, TRUE);
    $this->create_pdf->load($html, 'Laporan Tunggakan SPP', 'A4-P');
  }

  public function cetak_lap_lunas()
  {
    $data['header'] = $this->cetakHeader();
    $data['lap'] = $this->get_laporan_lunas();
    $html = $this->load->view('spp/cetak_lunas_lap_v', $data, TRUE);
    $this->create_pdf->load($html, 'Laporan SPP Lunas', 'Legal-P');
  }

  public function cetak_bukti($id)
  {
    $data['body'] = $this->db->query("SELECT spp.*, s.nama_siswa, ta.tahun_ajaran as 'ta' FROM spp 
                              inner join tahun_ajaran ta on ta.id = spp.tahun_ajaran
                              INNER JOIN siswa s ON s.NIS=spp.NIS WHERE id_spp = '$id'")->row();
    $html = $this->load->view('spp/cetak_bukti_v', $data, TRUE);
    // $this->create_pdf->load($html, 'Laporan SPP Lunas', 'B8-P');
    $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'B8-P']);
    $mpdf->AddPageByArray([
      'margin-left' => 5,
      'margin-right' => 5,
      'margin-top' => 5,
      'margin-bottom' => 5,
    ]);
    $mpdf->SetTitle('Bukti Pembayaran');
    $mpdf->SetHTMLFooter('
            <table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold; font-style: italic;"><tr>
            <td width="33%">SIAKU</td>
            <td width="33%" align="right" style="font-weight: bold; ">{PAGENO}/{nbpg}</td>
            </tr></table>');
    // $mpdf->autoPageBreak = false;
    $mpdf->WriteHTML($html);
    $mpdf->Output();
  }
}

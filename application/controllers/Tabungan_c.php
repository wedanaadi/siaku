<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tabungan_c extends CI_Controller
{

  function __construct()
  {
    $this->CI = &get_instance();
    parent::__construct();
    if ($this->session->userdata('kodeuser') == null) {
      redirect('Auth_c/login');
    }
    $this->load->library(['datatables', 'create_kode', 'create_pdf']);
    $this->load->model(['Tabungan_m', 'Siswa_m']);
  }

  public function index()
  {
    $data['title'] = 'Tabungan';
    $this->load->view('tabungan/data_v', $data);
  }

  function ignited_data_tabungan($kelompok = 'semua')
  { //data data produk by JSON object
    header('Content-Type: application/json');
    echo $this->Tabungan_m->tabungan_ignited($kelompok);
  }

  public function form($aksi = false)
  {
    $data = [];
    $siswa = $this->Siswa_m->getAll();
    if ($aksi != false) {
      $data = $this->Siswa_m->getBy('NIS', $aksi);
    }
    echo json_encode(['data' => $data, 'view' => $this->load->view('tabungan/form_v', null, true), 'siswa' => $siswa]);
  }

  public function form_st()
  {
    echo json_encode(['data' => $this->input->get(), 'view' => $this->load->view('tabungan/formst_v', null, true)]);
  }

  public function select2siswa()
  {
    $datas = $this->Siswa_m->getAll();
    $siswa[] = [];
    foreach ($datas as $data) {
      $siswa[] = ['id' => $data->NIS, 'text' => $data->nama_siswa];
    }
    echo json_encode($siswa);
  }

  public function setorawal()
  {
    $nis = $this->input->post('siswa');
    if ($this->db->query("SELECT * FROM tabungan WHERE NIS = '$nis'")->num_rows() > 0) {
      echo json_encode(['status' => 'gagal', 'msg' => 'Tabungan Sudah ada!']);
    } else {
      $data = [
        'id_tabungan' => $this->create_kode->KodeGenerate($this->Tabungan_m->createKode()->kode, 5, 6, 'TB', date('y') . date('m')),
        'NIS' => $this->input->post('siswa'),
        'tgl_buka' => date('Y-m-d'),
        'jumlah_tabungan' => str_replace('.', '', $this->input->post('setoranawal')),
        'setoran_awal' => str_replace('.', '', $this->input->post('setoranawal')),
      ];

      $this->Tabungan_m->insertdb($data);
      echo json_encode(['status' => 'sukses', 'msg' => 'Setoran Awal']);
    }
  }

  public function insertST()
  {
    $data = [
      'id' => uniqid(),
      'id_tabungan' => $this->input->post('idtabungan'),
      'type' => $this->input->post('tipeaksi') === '0' ? '2' : '1',
      'saldo_akhir' => $this->input->post('tipeaksi') === '0' ? $this->input->post('saldoakhir') - str_replace('.', '', $this->input->post('saldo')) : $this->input->post('saldoakhir') + str_replace('.', '', $this->input->post('saldo')),
      'saldo' => str_replace('.', '', $this->input->post('saldo')),
      'keterangan' => $this->input->post('tipeaksi') === '0' ? 'Penarikan - ' . $this->input->post('keterangan') : 'Setoran',
      'tgl_transaksi' => date('Y-m-d H:i:s')
    ];
    $dataupdate = [
      'jumlah_tabungan' => $data['saldo_akhir']
    ];
    if ($data['saldo_akhir'] < 0) {
      echo json_encode(['status' => 'gagal', 'msg' => 'Saldo Tidak cukup!']);
    } else {
      $this->db->trans_start();
      $this->Tabungan_m->insertdb($data, true);
      $this->Tabungan_m->updatedb($dataupdate, $this->input->post('idtabungan'));
      $this->db->trans_complete();
      echo json_encode(['status' => 'sukses', 'msg' => $this->input->post('tipeaksi') === '0' ? 'Penarikan' : 'Setoran']);
    }
  }

  public function detil($id)
  {
    $data['title'] = "Detail Tabungan";
    $data['datatabungan'] = $this->Tabungan_m->tabunganBy('id_tabungan', $id);
    $data['max'] = $this->Tabungan_m->getMinMaxTabungan(1, $id)->saldo;
    $data['min'] = $this->Tabungan_m->getMinMaxTabungan(2, $id)->saldo;
    $data['count'] = 1;
    $this->load->view('tabungan/detil_v', $data);
  }

  public function edit($id)
  {
    $data['title'] = "Edit Tabungan";
    $data['datatabungan'] = $this->Tabungan_m->tabunganBy('id_tabungan', $id);
    $data['max'] = $this->Tabungan_m->getMinMaxTabungan(1, $id)->saldo;
    $data['min'] = $this->Tabungan_m->getMinMaxTabungan(2, $id)->saldo;
    $data['count'] = 1;
    $this->load->view('tabungan/edit_v', $data);
  }

  public function detilWali()
  {
    $data['title'] = "Detail Tabungan";
    if ($this->Tabungan_m->getByWali('NIS', $this->session->userdata('kodeuser'))->num_rows() > 0) {
      $id = $this->Tabungan_m->getByWali('NIS', $this->session->userdata('kodeuser'))->row()->id_tabungan;
      $data['datatabungan'] = $this->Tabungan_m->tabunganBy('id_tabungan', $id);
      $data['max'] = $this->Tabungan_m->getMinMaxTabungan(1, $id)->saldo;
      $data['min'] = $this->Tabungan_m->getMinMaxTabungan(2, $id)->saldo;
      $data['count'] = $this->Tabungan_m->getByWali('NIS', $this->session->userdata('kodeuser'))->num_rows();
    } else {
      $data['count'] = $this->Tabungan_m->getByWali('NIS', $this->session->userdata('kodeuser'))->num_rows();
    }
    $this->load->view('tabungan/detil_v', $data);
  }

  public function ignited_detail($id)
  {
    header('Content-Type: application/json');
    $data = [
      'draw' => 1,
      'recordsFiltered' => $this->Tabungan_m->getDetail($id)->num_rows(),
      'recordsTotal' => $this->Tabungan_m->getDetail($id)->num_rows(),
      'data' => $this->Tabungan_m->getDetail($id)->result()
    ];
    echo json_encode($data);
  }

  public function ignited_edit($id)
  {
    header('Content-Type: application/json');
    foreach ($this->Tabungan_m->getEdit($id)->result() as $v) {
      $result[] = [
        'tgl' => $v->tgl,
        'masuk' => $v->masuk,
        'keluar' => $v->keluar,
        'saldo' => $v->saldo,
        'keterangan' => $v->keterangan,
        'type' => $v->type,
        'tgl_baru' => $v->tgl_baru,
        'view' => '<a class="btn btn-warning" href="' . base_url('Tabungan_c/EditForm/') . $v->id . '/' . $v->type . '"><i class="fas fa-pen"></i> Ubah</a>'
      ];
    }
    $data = [
      'draw' => 1,
      'recordsFiltered' => $this->Tabungan_m->getDetail($id)->num_rows(),
      'recordsTotal' => $this->Tabungan_m->getDetail($id)->num_rows(),
      'data' => $result
    ];
    echo json_encode($data);
  }

  public function EditForm($id, $tipe)
  {
    $data['title'] = "Edit Tabungan";
    if ($tipe === '0') {
      $this->db->select('id_tabungan, id_tabungan as ids, 0 as type, setoran_awal as saldo, jumlah_tabungan, setoran_awal as saldo_akhir');
      $this->db->where('id_tabungan', $id);
      $data['edit'] = $this->db->get('tabungan')->row();
    } else {
      $this->db->select('td.id_tabungan,td.id as ids, td.type, td.saldo, t.jumlah_tabungan, td.saldo_akhir');
      $this->db->where('td.id', $id);
      $this->db->join('tabungan t', 't.id_tabungan=td.id_tabungan');
      $data['edit'] = $this->db->get('tabungan_detil td')->row();
    }
    $data['kodetabungan'] = json_encode($data['edit']->ids);
    $data['typeaksi'] = json_encode($data['edit']->type);
    $this->load->view('tabungan/formTabungan_v', $data);
  }

  public function saveEditTabungan($id, $type)
  {
    $datadb = $this->Tabungan_m->loadedit($this->input->post('id'));
    if ($datadb->num_rows() > 0) {
      foreach ($datadb->result() as $key => $value) {
        if ($type === '0') {
          $updateTab = [
            'setoran_awal' => $this->input->post('ubahInput'),
          ];

          $updateDet[] = [
            'id' => $value->id,
            'type' => $value->type,
            'saldo' => $value->saldo,
          ];

          if ($value->type === '1') {
            $i = $key === 0 ? $key : $key - 1;
            if ($key > 0) {
              $updateDet[$key]['saldo_akhir'] = $updateDet[$key - 1]['saldo_akhir'] + $value->saldo;
            } else {
              $updateDet[$key]['saldo_akhir'] = $updateTab['setoran_awal'] + $value->saldo;
            }
          } else {
            $i = $key === 0 ? $key : $key - 1;
            if ($key > 0) {
              $updateDet[$key]['saldo_akhir'] = $updateDet[$key - 1]['saldo_akhir'] - $value->saldo;
            } else {
              $updateDet[$key]['saldo_akhir'] = $updateTab['setoran_awal'] - $value->saldo;
            }
          }
        } else {
          $updateDet[] = [
            'id' => $value->id,
            'type' => $value->type,
            'saldo' => $value->id == $id ? str_replace('.', '', $this->input->post('ubahInput')) : $value->saldo,
          ];

          if ($value->id == $id) {
            if ($type === '1') {
              $updateDet[$key]['saldo_akhir'] = str_replace('.', '', $this->input->post('saldoakhir')) - str_replace('.', '', $this->input->post('saldoold')) + str_replace('.', '', $this->input->post('ubahInput'));
            } else {
              $updateDet[$key]['saldo_akhir'] = str_replace('.', '', $this->input->post('saldoakhir')) + str_replace('.', '', $this->input->post('saldoold')) - str_replace('.', '', $this->input->post('ubahInput'));
            }
          } else {
            if ($value->type === '1') {
              $i = $key === 0 ? $key : $key - 1;
              if ($key > 0) {
                $a = $updateDet[$key - 1]['saldo_akhir'] + $value->saldo;
              } else {
                $a = $datadb->result()[$i]->setoran_awal + $value->saldo;
              }
            } elseif ($value->type === '2') {
              $i = $key === 0 ? $key : $key - 1;
              if ($key > 0) {
                $a = $updateDet[$key - 1]['saldo_akhir'] - $value->saldo;
              } else {
                $a = $datadb->result()[$i]->setoran_awal + $value->saldo;
              }
            } else {
              $i = $key === 0 ? $key : $key - 1;
              if ($key > 0) {
                $a = $updateDet[$key - 1]['saldo_akhir'] + $value->saldo;
              } else {
                $a = $datadb->result()[$i]->setoran_awal + $value->saldo;
              }
            }
            $updateDet[$key]['saldo_akhir'] = $a;
          }
        }
      }
      $updateTab['jumlah_tabungan'] = $updateDet[count($updateDet) - 1]['saldo_akhir'];
    } else {
      $updateTab = [
        'setoran_awal' => $this->input->post('ubahInput'),
        'jumlah_tabungan' => $this->input->post('ubahInput'),
      ];
    }
    $this->db->trans_start();
    $this->db->where('id_tabungan', $this->input->post('id'));
    $this->db->update('tabungan', $updateTab);
    if ($datadb->num_rows() > 0) {
      $this->db->update_batch('tabungan_detil', $updateDet, 'id');
    }
    $this->db->trans_complete();
    echo json_encode(['status' => 'sukses', 'id' => $this->input->post('id')]);
    // redirect('Tabungan_c/edit/' . $this->input->post('id'));
  }

  public function rekap()
  {
    $data['title'] = "Rekap Tabungan";
    $last = date("t", strtotime(date('Y-m')));
    $data['rekap'] = $this->Tabungan_m->rekap(date('Y-m-'), $last);
    $data['rekap_count'] = $this->Tabungan_m->rekap(date('Y-m-'), $last, true);
    $data['bln_lalu'] = $this->Tabungan_m->getBulanLalu(date('Y-m-t'));
    $data['label'] = bln_th(date('Y-m'));
    $this->load->view('tabungan/rekap_v', $data);
  }

  public function loadjaxrekap()
  {
    $p = @$this->input->get('periode') ? $this->input->get('periode') : date('Y-m-');
    $last = date("t", strtotime($p));
    $data['rekap'] = $this->Tabungan_m->rekap(date($p . "-"), $last);
    $data['rekap_count'] = $this->Tabungan_m->rekap(date($p . "-"), $last, true);
    $data['label'] = bln_th(date($p));
    $datefix = date("Y-m-t", strtotime($p));
    $data['bln_lalu'] = $this->Tabungan_m->getBulanLalu($datefix);
    echo json_encode($data);
  }

  public function get_laporan_simpanan()
  {
    $a = @$this->input->get('awal') ? $this->input->get('awal') : date('Y-m-01');
    $b = @$this->input->get('akhir') ? $this->input->get('akhir') : date('Y-m-31');
    $c = @$this->input->get('kelompok') ? $this->input->get('kelompok') : 'semua';

    $data['awal'] = tgl_only($a);
    $data['akhir'] = tgl_only($b);
    $data['laporan'] = $this->Tabungan_m->laporan_simpanan($a, $b, $c);

    return $data;
  }

  public function loadajaxTab()
  {
    echo json_encode($this->get_laporan_simpanan());
  }

  public function getLaporan()
  {
    $data['title'] = "Laporan Tabungan";
    $data['lap'] = $this->get_laporan_simpanan();
    $this->load->view('tabungan/laporan_v', $data);
  }

  public function cetakHeader()
  {
    // $d['profil'] = ['nama' => 'adi', 'alamat' => 'alamat'];
    $data = $this->load->view('_partials/header_laporan', false, true);
    return $data;
  }

  public function cetak_laporan_tabungan()
  {
    $data['header'] = $this->cetakHeader();
    $data['lap'] = $this->get_laporan_simpanan();
    $html = $this->load->view('tabungan/cetak_tabungan_lap_v', $data, TRUE);
    $this->create_pdf->load($html, 'Laporan Tabungan', 'A4-P');
  }

  public function get_laporan_rekap()
  {
    $p = @$this->input->get('periode') ? $this->input->get('periode') : date('Y-m-');
    $datefix = date("Y-m-t", strtotime($p));
    $last = date("t", strtotime($p));
    $data['rekap'] = $this->Tabungan_m->rekap(date($p . "-"), $last);
    $data['rekap_count'] = $this->Tabungan_m->rekap(date($p . "-"), $last, true);
    $data['label'] = bln_th(date($p));
    $data['bln_lalu'] = $this->Tabungan_m->getBulanLalu($datefix);
    return $data;
  }

  public function cetak_rekap_tabungan()
  {
    $data['header'] = $this->cetakHeader();
    $data['lap'] = $this->get_laporan_rekap();
    $html = $this->load->view('tabungan/cetak_rekap_lap_v', $data, TRUE);
    $this->create_pdf->load($html, 'Laporan Tabungan', 'A3-L');
  }

  public function cetak_detil_tabungan($id)
  {
    $data['header'] = $this->cetakHeader();
    $data['datatabungan'] = $this->Tabungan_m->tabunganBy('id_tabungan', $id);
    $data['max'] = $this->Tabungan_m->getMinMaxTabungan(1, $id)->saldo;
    $data['min'] = $this->Tabungan_m->getMinMaxTabungan(2, $id)->saldo;
    $data['loop'] = $this->Tabungan_m->getDetail($id)->result();
    $html = $this->load->view('tabungan/cetak_detail_tabung_v', $data, TRUE);
    $this->create_pdf->load($html, 'Laporan Tabungan', 'A4-L');
  }
}

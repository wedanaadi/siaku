<?php
defined('BASEPATH') or die('No direct script access allowed!');

function bulan($m = 0)
{
    // return $m;
    $bulan_arr = [
        '01' => 'Januari',
        '02' => 'Februari',
        '03' => 'Maret',
        '04' => 'April',
        '05' => 'Mei',
        '06' => 'Juni',
        '07' => 'Juli',
        '08' => 'Agustus',
        '09' => 'September',
        '10' => 'Oktober',
        '11' => 'November',
        '12' => 'Desember'
    ];

    if ($m !== 0) {
        return $bulan_arr[$m];
    }
    return $bulan_arr;
}

function hari($d = 0)
{
    $hari_arr = [
        'Monday' => 'Senin',
        'Tuesday' => 'Selasa',
        'Wednesday' => 'Rabu',
        'Thursday' => 'Kamis',
        'Friday' => 'Jum\'at',
        'Saturday' => 'Sabtu',
        'Sunday' => 'Minggu',
    ];

    if ($d !== 0) {
        return $hari_arr[$d];
    }
    return $hari_arr;
}

function tgl_hari($tgl)
{
    $bulan = bulan(date('m', strtotime($tgl)));
    $hari = hari(date('l', strtotime($tgl)));
    return $hari . ', ' . date('d ', strtotime($tgl)) . $bulan . date(' Y', strtotime($tgl));
}

function tgl_only($tgl)
{
    $bulan = bulan(date('m', strtotime($tgl)));
    $hari = hari(date('l', strtotime($tgl)));
    return date('d ', strtotime($tgl)) . $bulan . date(' Y', strtotime($tgl));
}

function bln_th($tgl)
{
    $bulan = bulan(date('m', strtotime($tgl)));
    return $bulan . date(' Y', strtotime($tgl));
}

function bln_only($tgl)
{
    $bulan = bulan(date('m', strtotime($tgl)));
    return $bulan;
}

function bulan_des($b)
{
    $bulan = array(
        1 =>   'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    );
    return $bulan[(int)$b];
}

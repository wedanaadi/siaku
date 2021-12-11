<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Create_kode {
    var $CI = NULL;
    function __construct(){
        $this->ci =& get_instance();
    }

    function KodeGenerate($lastKode, $lenght, $start, $awalKode = NULL, $split = NULL)
    {
      $kode = substr($lastKode,$start);
      $angka = (int)$kode;
      $angka_baru = $awalKode.$split.str_repeat("0", $lenght - strlen($angka+1)).($angka+1);
      return $angka_baru;
    }
}
?>


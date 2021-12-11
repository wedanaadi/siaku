<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Create_pdf
{
  var $CI = NULL;
  function __construct()
  {
    $this->ci = &get_instance();
  }

  function load($html, $namaFile, $ukuran)
  {
    $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => $ukuran]);
    $mpdf->showImageErrors = true;
    $mpdf->SetTitle($namaFile);
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

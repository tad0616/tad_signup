<?php
use Xmf\Request;
use XoopsModules\Tadtools\TadDataCenter;
use XoopsModules\Tad_signup\Tad_signup_actions;
use XoopsModules\Tad_signup\Tad_signup_data;
/*-----------引入檔案區--------------*/
require_once __DIR__ . '/header.php';

if (!$_SESSION['can_add']) {
    redirect_header($_SERVER['PHP_SELF'], 3, _TAD_PERMISSION_DENIED);
}

$id = Request::getInt('id');

$action = Tad_signup_actions::get($id);

require_once XOOPS_ROOT_PATH . '/modules/tadtools/tcpdf/tcpdf.php';
$pdf = new TCPDF("P", "mm", "A4", true, 'UTF-8', false);
$pdf->setPrintHeader(false); //不要頁首
$pdf->setPrintFooter(false); //不要頁尾
$pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM); //設定自動分頁
$pdf->setFontSubsetting(true); //產生字型子集（有用到的字才放到文件中）
$pdf->SetFont('droidsansfallback', '', 11, '', true); //設定字型
$pdf->SetMargins(15, 15); //設定頁面邊界，
$pdf->AddPage(); //新增頁面，一定要有，否則內容出不來

$title = $action['title'] . _MD_TAD_SIGNUP_SIGNIN_TABLE;
$pdf->SetFont('droidsansfallback', 'B', 24, '', true); //設定字型
$pdf->MultiCell(190, 0, $title, 0, "C");
$pdf->SetFont('droidsansfallback', '', 16, '', true); //設定字型
$pdf->Cell(40, 20, _MD_TAD_SIGNUP_ACTION_DATE . _TAD_FOR, 0, 0);
$pdf->Cell(150, 20, $action['action_date'], 0, 1);

$TadDataCenter = new TadDataCenter('tad_signup');
$TadDataCenter->set_col('pdf_setup_id', $id);
$pdf_setup_col = $TadDataCenter->getData('pdf_setup_col', 0);
$col_arr = explode(',', $pdf_setup_col);

$col_count = count($col_arr);
if (empty($col_count)) {
    $col_count = 1;
}

$h = 15;
$w = 110 / $col_count;
$maxh = 15;
$pdf->Cell(15, $h, _MD_TAD_SIGNUP_ID, 1, 0, 'C');
foreach ($col_arr as $col_name) {
    $pdf->Cell($w, $h, $col_name, 1, 0, 'C');
}
$pdf->Cell(55, $h, _MD_TAD_SIGNUP_SIGNIN, 1, 1, 'C');

$signup = Tad_signup_data::get_all($action['id'], null, true, true);
// Utility::dd($signup);
$i = 1;
foreach ($signup as $signup_data) {
    $pdf2 = clone $pdf;
    $pdf2->SetMargins(15, 0);
    $pdf2->AddPage();
    $pdf2->MultiCell(15, $h, $i, 1, 'C', false, 0, '', '', true, 0, false, true, $maxh, 'M');
    foreach ($col_arr as $col_name) {
        $pdf2->MultiCell($w, $h, implode('、', $signup_data['tdc'][$col_name]), 1, 'C', false, 0, '', '', true, 0, false, true, $maxh, 'M');
    }
    $pdf2->MultiCell(55, $h, '', 1, 'C', false, 1, '', '', true, 0, false, true, $maxh, 'M');
    $height = $pdf2->getY();
    $pdf2->deletePage($pdf2->getPage());
    if ($pdf->checkPageBreak($height)) {
        $pdf->Cell(15, $h, _MD_TAD_SIGNUP_ID, 1, 0, 'C');
        foreach ($col_arr as $col_name) {
            $pdf->Cell($w, $h, $col_name, 1, 0, 'C');
        }
        $pdf->Cell(55, $h, _MD_TAD_SIGNUP_SIGNIN, 1, 1, 'C');
    }

    $pdf->MultiCell(15, $h, $i, 1, 'C', false, 0, '', '', true, 0, false, true, $maxh, 'M');
    foreach ($col_arr as $col_name) {
        $pdf->MultiCell($w, $h, implode('、', $signup_data['tdc'][$col_name]), 1, 'C', false, 0, '', '', true, 0, false, true, $maxh, 'M');
    }

    $pdf->MultiCell(55, $h, '', 1, 'C', false, 1, '', '', true, 0, false, true, $maxh, 'M');
    $i++;
}

$pdf->Output("{$title}.pdf", 'D');

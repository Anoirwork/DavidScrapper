<?php
require 'vendor/autoload.php';
require 'vendor/PHPExcel-1.8/Classes/PHPExcel.php';

function refineTitle($title)
{
    return substr($title, 0, strpos($title, "- Quality Tractor Parts LTD."));
}

function getRidKg($txt)
{
    return substr($txt, 0, strpos($txt, "k"));
}
function refineField($field, $lookingFor)
{
    return substr($field, strpos($field, $lookingFor) + 2, strlen($field));
}

function isNull($object)
{
    return isset($object) && $object == !null ? $object : "";
}

function outputCsv($fileName, $assocDataArray)
{
    ob_clean();
    header('Pragma: public');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Cache-Control: private', false);
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment;filename=' . $fileName);
    $fp = fopen('php://output', 'wr');
    foreach ($assocDataArray as $values) {
        foreach ($values as $value) {
            fputcsv($fp, $value, ";");
        }
    }
    fclose($fp);
    ob_flush();
}

function arrangeToArray($var)
{
    $html                   = file_get_html($var);
    $code                   = trim(refineField($html->find('.l-product__code', 0)->plaintext, ":"));
    $weight                 = getRidKg(trim(refineField($html->find('.l-product__weight', 0)->plaintext, ":")));
    $description            = trim($html->find('.product-description', 0)->plaintext);
    $title                  = trim(refineTitle($html->find('title', 0)->plaintext));
    $TractorCompatibility   = trim(isNull($html->find('.accordion-inner-wrap', 1)->plaintext));
    $Manufacture            = isset($TractorCompatibility) ? $html->find('.products__description__nested-categories', 0)->find('a', 0)->plaintext : ""; //isset($TractorCompatibility) ? substr($TractorCompatibility, 0, strpos("\n", $TractorCompatibility)) : "";
    $OERefrence             = trim(isNull($html->find('.accordion-inner-wrap', 2)->plaintext));
    $locatedIn              = trim(isNull($html->find('.accordion-inner-wrap', 3)->plaintext));
    $html->clear();
    unset($html);
    $list = array(
        //[$code, $title, $weight, $description, $OERefrence, $TractorCompatibility, $locatedIn],
        $code, $title, "", $code, "", "", "", "", "", "", 10, "", $Manufacture, "", "yes", 0, 0, date('Y-m-d h:m:i'), date("Y-m-d"), date("Y-m-d"), $weight, "kg", 0, 0, 0, "cm", true, 9, "<p>" . $description . " <br/> " . $OERefrence . " <br/> " . $TractorCompatibility . " <br/>  " . $locatedIn . "</p>", $title, $description, $title, 0, 0, "", "", "", 0, 1, 1,
        //"product_id", "name(en-gb)", "categories", "sku", "upc", "ean", "jan", "isbn", "mpn", "location", "quantity", "model", "manufacturer", "image_name", "shipping", "price", "points", "date_added", "date_modified", "date_available", "weight", "weight_unit", "length", "width", "height", "length_unit", "status", "tax_class_id", "description(en-gb)", "meta_title(en-gb)", "meta_description(en-gb)", "meta_keywords(en-gb)", "stock_status_id", "store_ids", "layout", "related_ids", "tags(en-gb)", "sort_order", "subtract", "minimum"
    );
    return $list;
}
function arrayToXlsxWriter($data, $header)
{
    $writer = new XLSXWriter();
    $writer->writeSheetHeader('Sheet1', $header);
    foreach ($data as $row)
        $writer->writeSheetRow('Sheet1', $row);

    header('Content-Type: application/vnd.ms-excel');
    //tell browser what's the file name
    header('Content-Disposition: attachment;filename="example.xlsx"');

    header('Cache-Control: max-age=0');
    $writer->writeToFile('example.xlsx');
}
function phpexcel($data, $header)
{

    $objPHPExcel = new PHPExcel();


    $filename = "report_" . date('Ymd_His') . ".xls";

    $sheet_count = 0;
    foreach ($data as $tb_name => $tb_data) {


        $sheet = $objPHPExcel->createSheet($sheet_count);

        $header_key = array_keys($tb_data[0]);

        $header = array($header_key[0], $header_key[1], $header_key[2]);
        $list = array($header);
        //$this->excel->setActiveSheetIndex($sheet_count);
        $sheet->setTitle($header[$sheet_count]);

        $tmp_row = array();
        foreach ($tb_data as $key => $curr_data) {
            //echo '<pre>'; print_r( $curr_data); exit;
            $list[] = $curr_data;
        }

        $sheet->fromArray($list);
        $sheet_count++; //break;

    }


    header('Content-Type: application/vnd.ms-excel');
    //tell browser what's the file name
    header('Content-Disposition: attachment;filename="' . $filename . '"');

    header('Cache-Control: max-age=0'); //no cache
    //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
    //if you want to save it as .XLSX Excel 2007 format

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

    //force user to download the Excel file without writing it to server's HD
    //$objWriter->save('php://output');

    $objWriter->save($filename);
}
function writeXLSX($filename, $rows, $keys = [], $formats = [])
{

    // instantiate the class
    $doc = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $doc->setActiveSheetIndex(0);
    $doc->getActiveSheet()->setTitle('Products');

    // $keys are for the header row.  If they are supplied we start writing at row 2


    // write the rows

    $i = 2;
    foreach ($rows as $values) {
        $a = "A";
        foreach ($values as $value) {
            $doc->getActiveSheet()->setCellValue($a . $i, $value);
            $a++;
        }
        $i++;
    }

    // write the header row from the $keys
    if ($keys) {
        $doc->setActiveSheetIndex(0);
        $doc->getActiveSheet()->fromArray($keys, null, 'A1');
    }

    // get last row and column for formatting
    $last_column = $doc->getActiveSheet()->getHighestColumn();
    $last_row = $doc->getActiveSheet()->getHighestRow();

    // autosize all columns to content width
    for ($i = 'A'; $i <= $last_column; $i++) {
        $doc->getActiveSheet()->getColumnDimension($i)->setAutoSize(TRUE);
    }

    // if $keys, freeze the header row and make it bold
    // if ($keys) {
    //     $doc->getActiveSheet()->freezePane('A2');
    //     $doc->getActiveSheet()->getStyle('A1:' . $last_column . '1')->getFont()->setBold(true);
    // }

    // format all columns as text
    $doc->getActiveSheet()->getStyle('A2:' . $last_column . $last_row)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);
    if ($formats) {
        // if there are user supplied formats, set each column format accordingly
        // $formats should be an array with column letter as key and one of the PhpOffice constants as value
        // https://phpoffice.github.io/PhpSpreadsheet/1.2.1/PhpOffice/PhpSpreadsheet/Style/NumberFormat.html
        // EXAMPLE:
        // ['C' => \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_00, 'D' => \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_00]
        foreach ($formats as $col => $format) {
            $doc->getActiveSheet()->getStyle($col . ':' . $col . $last_row)->getNumberFormat()->setFormatCode($format);
        }
    }
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');
    $objWriter = PhpOffice\PhpSpreadsheet\IOFactory::createWriter($doc, 'Xlsx');
    $objWriter->setPreCalculateFormulas(false);
    $objWriter->save('php://output');

    // Clear the spreadsheet caches
    clearstatcache();
    exit;
}

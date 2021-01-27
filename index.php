<?php

require_once 'simple_html_dom.php';
require_once 'functions.php';
ini_set('memory_limit', '512M');
$link                   = "https://www.qtponline.es/categories.html?filter_set[]=869&perpage=4"; //"https://www.qtponline.com/categories.html?filter_set[]=869&perpage=76";
$domaine                = 'https://www.qtponline.es';
$testLink               = "https://www.qtponline.es/products/hydraulic_pump_end_plate_bush_6374.html?filter_set[]=1093,1104,15431";
$list[] = "";
//['Categories', 'Code', 'Article', 'Weight', 'Description', 'OERefrence', 'Tractor', 'AlsoLocatedIn'],
//'product_id', 'model', 'sku', 'upc', 'ean', 'jan', 'isbn', 'mpn', 'location', 'quantity', 'stock_status_id', 'image', 'manufacturer_id', 'shipping', 'price', 'points', 'tax_class_id', 'date_available', 'weight', 'weight_class_id', 'length', 'width', 'height', 'length_class_id', 'subtract', 'minimum', 'sort_order', 'status', 'viewed', 'date_added', 'date_modified',
//"product_id", "name(en-gb)", "categories", "sku", "upc", "ean", "jan", "isbn", "mpn", "location", "quantity", "model", "manufacturer", "image_name", "shipping", "price", "points", "date_added", "date_modified", "date_available", "weight", "weight_unit", "length", "width", "height", "length_unit", "status", "tax_class_id", "description(en-gb)", "meta_title(en-gb)", "meta_description(en-gb)", "meta_keywords(en-gb)", "stock_status_id", "store_ids", "layout", "related_ids", "tags(en-gb)", "sort_order", "subtract", "minimum"
//["", $title, ,$code, , , , , , , ,1 , , ,"yes", 0, 0, date('Y-m-d h:m:i'), date("Y-m-d"), date("Y-m-d"), $weight, "kg", 0, 0 , 0, "cm", true, 9, $description . " <br/> " .$OERefrence . " <br/> " . $TractorCompatibility . " <br/>  " . $locatedIn, $title, $description, $title, 0, 0 , "", "", "", 0, true, 1],

$header = array(
    "product_id", "name(en-gb)", "categories", "sku", "upc", "ean", "jan", "isbn", "mpn", "location", "quantity", "model", "manufacturer", "image_name", "shipping", "price", "points", "date_added", "date_modified", "date_available", "weight", "weight_unit", "length", "width", "height", "length_unit", "status", "tax_class_id", "description(en-gb)", "meta_title(en-gb)", "meta_description(en-gb)", "meta_keywords(en-gb)", "stock_status_id", "store_ids", "layout", "related_ids", "tags(en-gb)", "sort_order", "subtract", "minimum"
);
// for ($i = 1; $i < 3; $i++) {
$html = file_get_html($link /*. "&page=" . $i*/);
//array_push($list,);
/*foreach ($html->find('.products__link') as $article) {
    array_push($list, arrangeToArray($domaine . $article->href));
}*/
$html->clear();
unset($html);
// }
//sleep(3);
//}
//arrayToXlsxWriter($list, $header);
//phpexcel($list, $header);
//outputCsv(date("Y-m-d h:i:s") . ".csv", $list);
//writeXLSX("Products" . date("Y-m-d") . ".xlsx", arrangeToArray($testLink), $header);
//print_r($list);

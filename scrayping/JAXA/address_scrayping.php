<?php
// Open this url on browger http://localhost:8888/PHP_Scraping/scrayping/JAXA/address_scrayping.php
require_once("../../phpQuery-onefile.php");

$root = "https://www.jaxa.jp/";
$url = "https://www.jaxa.jp/about/centers/index_j.html";


$csv_path = "../../csv/JAXA/addless.csv";
$f = fopen($csv_path , "w");


$html = file_get_contents($url);
$dom = phpQuery::newDocument($html);

$counter = 0;
foreach($dom->find('.elem_content_divide_pad.clearfix') as $box){
  $box = pq($box);

  foreach($box->find('.divide_block') as $address_box){
    $address_box = pq($address_box);

    $office_name = $address_box->find(".elem_paragraph")->find("a")->text();
    $office_url = "https://www.jaxa.jp/about/centers/" . $address_box->find(".elem_paragraph")->find("a")->attr("href");

    //echo $office_url . "</br>";

    $office_html = file_get_contents($office_url);
    $office_dom = phpQuery::newDocument($office_html);

    #echo $office_dom->find("#area_content");

    fputcsv($f, array($office_name,$office_url));

    //break;
  }


  //break;
  $couter++;

}






?>

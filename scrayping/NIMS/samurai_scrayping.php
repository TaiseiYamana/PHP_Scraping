<?php
// Open this url on browger http://localhost:8888/PHP_Scraping/scrayping/NIMS/sumrai_scrayping.php
require_once("../../phpQuery-onefile.php");

$root = "https://www.nims.go.jp";
$url = "https://samurai.nims.go.jp/profiles/aimi_junko";


$csv_path = "../../csv/NIMS/sumrai_researcher.csv";
$f = fopen($csv_path , "w");

$html = file_get_contents($url);
$dom = phpQuery::newDocument($html);

//echo $dom;

$info1 = $dom->find('.txt_area.col-md-9');

$info1 = pq($info1);
echo $info1;

$name = $info1->find('.name_txt')->text();
$unit = $info1->find('.unit')->text();
$email = $info1->find('.email');
$email  = str_replace(array("\r\n", "\r", "\n"), '', $email);
$address = $info1->find('.address')->text();

//echo $email;





?>

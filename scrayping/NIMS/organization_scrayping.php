<?php
// Open this url on browger http://localhost:8888/PHP_Scraping/scrayping/NIMS/organization_scrayping.php
require_once("../../phpQuery-onefile.php");

$root = "https://www.nims.go.jp";
$url = "https://www.nims.go.jp/nims/organization/index.html";


$csv_path = "../../csv/NIMS/organization.csv";
$f = fopen($csv_path , "w");

$html = file_get_contents($url);
$dom = phpQuery::newDocument($html);

//echo $dom;

$content_box = $dom->find('.maincontents')->find(".article");


foreach($content_box->find(".organization") as $organization_box){
  $organization_box = pq($organization_box);

  foreach($organization_box->find('li') as $org){
    $org = pq($org);
    $org_url = $root . $org->find("a")->attr("href");
    $org_name = $org->find("a")->text();
    fputcsv($f, array($org_name , $org_url));

  }


}



?>

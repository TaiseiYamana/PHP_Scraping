<?php
// Open this url on browger http://localhost:8888/PHP_Scraping/scrayping/JAXA/projects_scrayping.php
require_once("../../phpQuery-onefile.php");

$root = "https://www.jaxa.jp/";
$url = "https://www.jaxa.jp/projects/index_j.html";


$csv_path = "../../csv/JAXA/projects.csv";
$f = fopen($csv_path , "w");

fputcsv($f, array("プロジェクト名" , "プロジェクトURL" , "詳細URL" , "プロジェクト代表例"));

$html = file_get_contents($url);
$dom = phpQuery::newDocument($html);

//echo $dom;

foreach ($dom->find(".projects_general") as $project_box ){
$project_box = pq($project_box);

$project_name = $project_box->find('h2')->text();
$project_url = $root . $project_box->find('h2')->find("a")->attr("href");

$project_note = $project_box->find('.note');

$detail_url = $project_note->find("a")->attr("href");
$project_ex = $project_note->find('.icon_text.ii')->text();
$project_ex = str_replace(array("\r\n", "\r", "\n","	"), ',', $project_ex);
$project_ex = rtrim($project_ex , ',');

fputcsv($f, array($project_name , $project_url , $detail_url , $project_ex));
echo $project_box . "</br>";
echo $project_ex;

} #project box 's kakko'

//http://www.rocket.jaxa.jp/

?>

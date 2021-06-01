<?php
// Open this url on browger http://localhost:8888/PHP_Scraping/Scrayping.php
//echo 'Hello Wow!';

//ini_set('display_errors','On');
//error_reporting(E_ALL);

require_once("./phpQuery-onefile.php");

$top_url = "https://www.nii.ac.jp";
$list_url = "https://www.nii.ac.jp/faculty/list/professors/";

$html_1 = file_get_contents($list_url);
$dom_1 = phpQuery::newDocument($html_1);

foreach ($dom_1->find('#contentBox')->find('ul > li > a') as $a){
  $name = $a->textContent;
  $url = $a->getAttribute('href');
  $parson_url = $top_url . $url;

  echo $name .' url: <a href=' . $parson_url . '>' . $parson_url . '</a><br>';


  $html_2 = file_get_contents($parson_url);
  $dom_2 = phpQuery::newDocument($html_2);

  $alltitle = $dom_2->find('.bg-white.padding-all-md.bd-gray-bottom.margin-bottom-default.text-default.text-sm-sp.cf');
  $alltitle->find('span')->remove();

  echo $alltitle->text() . '<br>';
}



?>

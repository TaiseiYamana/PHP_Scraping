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

//$name = $dom_1->find('#contentBox')->find('ul　>　li')->text();
//$url = $dom->find('#contentBox')->find('ul　>　li > a')->attr("href");

//$name = $dom->find("#contentBox > ur > li > a") ->text();
//$name = $dom->find('ur > li > a')->text();
#$url = $dom

//echo $name;

foreach ($dom_1->find('#contentBox')->find('ul > li > a') as $a){
  $name = $a->textContent;
  $url = $a->getAttribute('href');
  $parson_url = $top_url . $url;

  echo $name .' url: <a href=' . $parson_url . '>' . $parson_url . '</a><br>';


  $html_2 = file_get_contents($parson_url);
  $dom_2 = phpQuery::newDocument($html_2);

  //$alltitle = $dom_2->find('#contentBox')->find('div');
  //$alltitle = $dom_2->find('#contentBox')->find('div');
  $alltitle = $dom_2->find('.bg-white.padding-all-md.bd-gray-bottom.margin-bottom-default.text-default.ext-sm-sp.cf');
  //$dom_3->find('a')->remove();

  //foreach ($dom_3 as $b){
  //echo $b->textContent . '<br>';
  //}

  echo $alltitle->text() . '<br>';
  /*
  foreach ($dom_4 as $c){
    $url2 = $c->getAttribute('href');
    echo 'url:' . $url2 . '<br>';
  }
  */

  //$content = $dom_2->find('.margin-top-default')->text();
  //$content = $dom_2->find("h1")->text();
  #$img = $dom_2->find("#bg-white padding-all-md bd-gray-bottom margin-bottom-default text-default text-sm-sp cf")->find('img');
  #$img = $img->getAttribute('src');
  #echo  '<img src="' . $top_url . $img . '"><br>';
  //echo $content . '<br>';
}


//echo "¥n";
//echo $url;
/*
//aタグの一覧を取得
foreach ($dom->find('ul')->find('li')->find('a') as $a){
  $name = $a->getText();
  echo $name;
}

https://www.nii.ac.jp/faculty/digital_content/aihara_kenro/
/*
foreach ($dom->find('img') as $img){
  $img = $img->getAttribute('src');
  echo '<img src=' . $img . '><br>';
}
*/

//echo phpQuery::newDocument($html)->find("h3")->text();


?>

<?php
// Open this url on browger http://localhost:8888/PHP_Scraping/Scrayping.php
echo 'Hello Wow!';



require_once("./phpQuery-onefile.php");

$html = file_get_contents("https://www.min-breeder.com/dogSearch_dogKind_shiba_1.html");
#$html = file_get_contents("https://my.royalcanin.jp/static_page/dogfood/bhn/shiba_inu")


$dom = phpQuery::newDocument($html);

foreach ($dom->find('img') as $img){
  $img = $img->getAttribute('src');
  echo '<img src=' . $img . '><br>';
}


//echo phpQuery::newDocument($html)->find("h3")->text();


?>

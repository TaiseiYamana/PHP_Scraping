<?php
// Open this url on browger http://localhost:8888/PHP_Scraping/scrayping/researchmap.php
require_once("../phpQuery-onefile.php");

$item = array("名前", "所属","学位", "研究キーワード", "研究分野", "researchmap", "J-GLOBAL", "外部リンク");

$csv_path = "../csv/rmlist.csv";
//ファイルがない時、作成する。
if(!file_exists($csv_path)){
  touch($csv_path);
}
$f = fopen($csv_path , "w");


fputcsv($f, $item);
$rm_url = "https://researchmap.jp/read0171664";
$rm_html = file_get_contents($rm_url);

$list_dom = phpQuery::newDocument($rm_html);

$name = $list_dom->find('.rm-researcher-name')->text();
//echo $name ."<br>";

$base_info = $list_dom->find('.panel.panel-default.rm-cv-panel');
$base_info = str_replace(array("\r\n", "\r", "\n"), '', $base_info); //htmlの整地
//学位,j-global,の取得
preg_match('@学位				</dt>				<dd class="col-xs-10">					<div>(.*?)</div>@s', $base_info, $array);
$degree = $array[1];
$array = array();
preg_match('@J-GLOBAL ID</dt><dd class="col-xs-4"><a href=\"(.*?)\" target="blank">@s', $base_info, $array);
$j_global_url = $array[1];
$array = array();
preg_match('@外部リンク</dt><dd class="col-xs-10"><div><a href=\"(.*?)\" target="blank">@s', $base_info, $array);
$external_url = $array[1];
$array = array();

$keyword_list = $list_dom->find(".list-inline.rm-cv-research-interests");
$keyword = $keyword_list->find(".rm-cv-list-title")->text();
$keyword =  str_replace(array("\r\n", "\r", "\n"),"/", $keyword);
//$array = explode(" ", $keyword);
//echo $keyword ."<br>";


//foreach ($list_dom->find('#contentBox')->find('ul > li > a') as $a){
//echo $degree->text() ."<br>";
//echo $base_info ."<br>";
//echo $degree ."<br>";
//echo $j_global_url ."<br>";
//echo $external_url ."<br>";

#"名前", "所属","学位", "研究キーワード", "研究分野", "researchmap", "J-GLOBAL", "外部リンク"
fputcsv($f,array($name, "", $degree, $keyword, "", $rm_url, $j_global_url, $external_url));
/*
//$position1 = array("所長","副署長");
//$position2 = array("教授", "准教授", "講師", "助教", "助手");

//$_POST["csv"] = 0;

  if ( $f ) {
      fputcsv($f, $item);

      //$top_url = "https://www.nii.ac.jp";
      //$list_url = "https://www.nii.ac.jp/faculty/list/professors/";
      $rm_url = "https://researchmap.jp/read0171664";


      //$list_html = file_get_contents($list_url);
      $rm_html = file_get_contents($rm_url);

      //$list_dom = phpQuery::newDocument($list_html);
      $list_dom = phpQuery::newDocument($rm_html);

      $name = $list_dom->find('.rm-researcher-name')->text();

      echo $name;
    }
 */
?>

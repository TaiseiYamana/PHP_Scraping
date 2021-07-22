<?php
// Open this url on browger http://localhost:8888/PHP_Scraping/scrayping/AIST/unit_scrayping.php
require_once("../../phpQuery-onefile.php");

$root = "https://www.aist.go.jp";

$url_list = array("エネルギー・環境領域" => "https://www.aist.go.jp/aist_j/dept/denvene.html",
                  "生命工学領域" => "https://www.aist.go.jp/aist_j/dept/dlsbt.html",
                  "情報・人間工学領域" => "https://www.aist.go.jp/aist_j/dept/dithf.html",
                  "材料・化学領域" => "https://www.aist.go.jp/aist_j/dept/dmc.html",
                  "エレクトロニクス・製造領域" => "https://www.aist.go.jp/aist_j/dept/delma.html",
                  "地質調査総合センター" => "https://www.aist.go.jp/aist_j/dept/gsj.html",
                  "計量標準総合センター" => "https://www.aist.go.jp/aist_j/dept/nmij.html");


$in_csv_path = "../../csv/AIST/field.csv";
$out_csv_path = "../../csv/AIST/unit.csv";
$in_f = fopen($in_csv_path, "r");
$out_f = fopen($out_csv_path, "w");

$item = array("研究分野", "ユニット名", "研究拠点", "参画する技術研究組合","住所","電話","Eメールアドレス", "FAX", "WebサイトURL");

fputcsv($out_f, $item);

$n = 0;
while($line = fgetcsv($in_f)){
  if($n >= 1){
    // 研究分野
    $filed_name = $line[1];
    // 研究分野URL
    $filed_url = $line[0];

    // HTMLの取得
    $html = file_get_contents($filed_url);
    $dom = phpQuery::newDocument($html)->find('.ContentPane');
    $dom = str_replace(array("\r\n", "\r", "\n"), '', $dom);
    echo $dom;

    //各ユニットを配列に入れる
    $unit_array = explode(',', $line[6]);
    //var_dump($unit_array);
    foreach($unit_array as $unit_name){
      //echo $dom;
      $pattern = '@<div class="title">' . $unit_name . '</div>' . '(.*?)<div class="title">@';
      echo $pattern . "<br>";
      //preg_match($pattern, $dom, $array);
      //var_dump($array);



      $base = "";
      $participater = "";
      $address = "";
      $telephone = "";
      $maile = "";
      $fax = "";
      $web_url = "";

      fputcsv($out_f, array($filed_name, $unit_name, $base, $participater, $address, $telephone,$maile, $fax, $web_url));

      //break;

}
 break;
}
$n++;
}




?>

<?php
// Open this url on browger http://localhost:8888/PHP_Scraping/scrayping/AIST/field_scrayping.php
require_once("../../phpQuery-onefile.php");

$root = "https://www.aist.go.jp";

$url_list = array("エネルギー・環境領域" => "https://www.aist.go.jp/aist_j/dept/denvene.html",
                  "生命工学領域" => "https://www.aist.go.jp/aist_j/dept/dlsbt.html",
                  "情報・人間工学領域" => "https://www.aist.go.jp/aist_j/dept/dithf.html",
                  "材料・化学領域" => "https://www.aist.go.jp/aist_j/dept/dmc.html",
                  "エレクトロニクス・製造領域" => "https://www.aist.go.jp/aist_j/dept/delma.html",
                  "地質調査総合センター" => "https://www.aist.go.jp/aist_j/dept/gsj.html",
                  "計量標準総合センター" => "https://www.aist.go.jp/aist_j/dept/nmij.html");


$csv_path = "../../csv/AIST/field.csv";
$f = fopen($csv_path , "w");
$item = array("URL", "研究分野", "データベース", "主な研究成果","イノベーションコーディネータURL","研究ユニットURL",
"研究ユニット", "イノベーションコーディネータURL", "連絡先1", "メール", "電話番号1", "連絡先2", "メール", "電話番号2");



if ( $f ) {
  fputcsv($f, $item);

  foreach($url_list as $key => $value){
    $filed_url = $value;
    $filed_name = $key;

    $html = file_get_contents($filed_url);
    $dom = phpQuery::newDocument($html);

    $box = $dom->find(".defaultList");

    #コメントアウトの削除
    preg_match('@<!--[\s\S]*?-->@', $box, $array);
    $box = str_replace($array[0], '', $box);

    preg_match('@<a href="(.*?)">データベース</a>@', $box, $array);
    if ($array[1] != "")
    $database_url = $root . $array[1];
    unset($array);

    preg_match('@"(.*?)">主な研究成果@', $box, $array);
    if ($array[1] != "")
    $achievement_url = $root . $array[1];
    unset($array);
    //echo $filed_name .":". $root . $array[1] . "<br>";

    preg_match('@<a(.*?)>イノベーションコーディネータ</a>@', $box, $array);
    preg_match('@href="(.*?)"@', $array[0],$array_2);
    if ($array_2[1] != "")
    $coordinator_url = $root . $array_2[1];
    unset($array_2);

    //echo $filed_name .":" . $array[1] . "<br>";

    preg_match('@<a href="(.*?)">研究ユニット</a>@', $box, $array);
    if ($array[1] != "")
    $unitlist_url = $filed_url . $array[1];
    unset($array);

    preg_match('@<a href="(.*?)" style="text-indent: -1em;">イノベーションコーディネータ</a>@', $box, $array);
    if ($array[1] != "")
    $coordinator_url = $root . $array[1];
    unset($array);



    $box = $dom->find(".title")->text();
    echo $filed_name .":" . "<br>" . "<br>";
    //echo $box;
    $unit = str_replace(array("\r\n", "\r", "\n"), '*', $box);
    $unit = str_replace('*', ',', $unit);
    $unit = rtrim($unit, ",");


    $box = $dom->find(".ContentPane")->find(".grayLineBox_h2");

    echo $box;

    preg_match_all('@連絡先：(.*?)</h3>@', $box, $array);
    $contact_info1= $array[1][0];
    $contact_info2= $array[1][1];
    unset($array);

    preg_match_all('/メール：(.*?)（\*を\@に変更して送信下さい。）/', $box, $array);
    $maile1 = str_replace("*", '@', $array[1][0]);
    $maile2 = str_replace("*", '@', $array[1][1]);
    unset($array);

    $array = array();
    preg_match_all('@話：(.*?)<@', $box, $array);
    $phone1 = $array[1][0];
    $phone2 = $array[1][1];

    print_r($array);



    fputcsv($f, array($filed_url, $filed_name, $database_url, $achievement_url,$coordinator_url, $unitlist_url, $unit, $coordinator_url, $contact_info1, $maile1, $phone1, $contact_info2, $maile2, $phone2));

    $filed_url = $filed_name = $database_url = $achievement_url = $coordinator_url = $unitlist_url = $unit = $coordinator_url = $contact_info1 = $contact_info2 = $maile1 = $maile2 = $phone1 = $phone2 = "";
  }
}

?>

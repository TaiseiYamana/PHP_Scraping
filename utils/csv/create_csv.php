<?php
// Open this url on browger http://localhost:8888/PHP_Scraping/utils/csv/create_csv.php
$ary = array(
  array("名前", "年齢", "血液型"),
  array("太郎", "21", "O"),
  array("ジョン", "23", "A"),
  array("ニキータ", "32", "AB"),
  array("次郎", "22", "B")
 );
// ファイルを書き込み用に開きます。
$f = fopen("../../csv/test.csv", "w");
// 正常にファイルを開くことができていれば、書き込みます。
if ( $f ) {
  // $ary から順番に配列を呼び出して書き込みます。
  foreach($ary as $line){
    fputcsv($f, $line);
  }
}
// ファイルを閉じます。
fclose($f);
?>

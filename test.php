<?php

$csv_path = "./csv/list.csv";
$f = fopen ($csv_path, "r" );

//$counter = 0;
$flag = false;


echo "<table border=\"1\">\n";
  while ( ( $data = fgetcsv ( $f, 1000, ",", '"' ) ) !== FALSE ) {
    echo "\t<tr>\n";
    for ($i = 0; $i < count( $data ); $i++ ) {
      if($flag && ($i == 1 || $i == 7))  //ulrのある列はリンクを埋め込む 個人ページURL:1 LabwebURL:7
        echo "\t\t<td> <a href=\"" . $data[$i] . "\">" . $data[$i] . "</a></td>\n";
      else
        echo "\t\t<td>{$data[$i]}</td>\n";
    }
    echo "\t</tr>\n";
    $flag = true;

  }
echo "</table>\n";
fclose ( $f );

 ?>

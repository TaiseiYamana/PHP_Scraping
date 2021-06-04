<?php

// http://localhost:8888/PHP_Scraping/test.php
$position1 = array("所長","副署長");
$position2 = array("教授", "准教授", "講師", "助教", "助手");

function create_pattern($array){
  $pattern = '/';
  for($i = 0; $i < count($array); $i++){
    echo $i;
    if ($i < (count($array)-1)){
      echo 'OK';
      $pattern .= $array[$i] . '|';
    }
    else{
      echo 'NO';
      $pattern .= $array[$i] . '/';
    }
  }
  return $pattern;
}

//echo count($position1);
//echo $position1[1];
echo create_pattern($position1);


 ?>

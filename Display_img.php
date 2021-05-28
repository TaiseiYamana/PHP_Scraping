<?php
  //open This url on web browser http://localhost:8888/PHP_Scraping/Display_img.php
  $filePath = './img/shiba_inu.jpeg';
  $data = file_get_contents($filePath);

  echo $data;

 ?>

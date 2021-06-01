<?php
  //open This url on web browser http://localhost:8888/PHP_Scraping/Display_img.php
  //$filePath = './img/shiba_inu.jpeg';
  //$data = file_get_contents($filePath);

  $url = 'data:image/jpeg;base64,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';
  $data = file_get_contents($url);

  header('Content-type: image/jpg');

  echo $data;
?>
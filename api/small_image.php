<?php
  // postされた
  ini_set('display_errors', "On");

  $date = date("Y-m-d");
  // $image_dir = "./sonner_images/{$date}"; // 画像ファイルを格納しているディレクトリ
  $percent = 0.3; // サイズの縮小率

  $filename = "{$_GET['file_name']}";


  // 縮小後の縦横サイズ
  list($width, $height, $type) = getimagesize($filename);
  $newwidth = $width * $percent;
  $newheight = $height * $percent;


  // ソース画像のロード
  $thumb = imagecreatetruecolor($newwidth, $newheight);


  
  // if (strpos($filename, ".jpg") != false){
  //   $source = imagecreatefromjpg($filename);
  //   // リサイズ
  //   imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
  //   header('Content-Type: image/jpg');
  //   imagepng($thumb);
  // }

  if (strpos($filename, ".jpeg") != false || strpos($filename, ".jpg") != false){
    $source = imagecreatefromjpeg($filename);
    // リサイズ
    imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
    header('Content-Type: image/jpeg');
    imagejpeg($thumb);
  }


  imagedestory($thumb);
?>

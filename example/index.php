<?php
include 'vendor/ClassLoader.php';

$loader = new ClassLoader('File', "vendor");
$loader->register();

$thumb = new \File\Image\Thumbnail('example.jpg');
$thumb->resize(200, 300, 'auto');
$thumb->save('example_jpg_thumb.jpg');

$thumb = new \File\Image\Thumbnail('example_png.png');
$thumb->resize(200, 300, 'auto');
$thumb->save('example_png_thumb.png');

$thumb = new \File\Image\Thumbnail('example_gif.gif');
$thumb->resize(200, 300, 'auto');
$thumb->save('example_gif_thumb.gif');


$thumb = new \File\Image\Thumbnail('example.txt');
$thumb->resize(200, 300, 'auto');
$thumb->save('example_gif_thumb_txt.gif');
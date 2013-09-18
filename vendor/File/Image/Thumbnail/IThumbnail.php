<?php
namespace File\Image\Thumbnail;

interface IThumbnail
{
    /**
     * @param string  $file_name
     * @param integer $quality
     *
     * @return mixed
     */
    public function save($file_name, $quality = 100);

    /**
     * @param integer $width
     * @param integer $height
     * @param string  $option exact|portrait|landscape|auto|crop
     *
     * @return void
     */
    public function resize($width, $height, $option='auto');
}

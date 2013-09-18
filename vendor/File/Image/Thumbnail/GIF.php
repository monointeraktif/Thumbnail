<?php
namespace File\Image\Thumbnail;

class GIF extends ThumbnailAbstract implements IThumbnail
{
    /**
     * @param string $file_name
     * @param array $image
     */
    public function __construct($file_name, $image)
    {
        $this->image = @imagecreatefromgif($file_name);
        parent::__construct($file_name, $image);
    }

    /**
     * @param string  $file_name
     * @param integer $quality
     *
     * @return void
     */
    public function save($file_name, $quality=100)
    {
        $temp_file = tempnam(sys_get_temp_dir(), 'Tux');
        imagegif($this->image_resize, $temp_file);
        copy($temp_file ,$file_name);
        imagedestroy($this->image_resize);
    }
}

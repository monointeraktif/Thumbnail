<?php
namespace File\Image\Thumbnail;

class JPEG extends ThumbnailAbstract implements IThumbnail
{
    /**
     * @param string $file_name
     * @param array $image
     */
    public function __construct($file_name, $image)
    {
        $this->image = @imagecreatefromjpeg($file_name);
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
        imagejpeg($this->image_resize, $temp_file, $quality);
        copy($temp_file, $file_name);
        imagedestroy($this->image_resize);
    }
}

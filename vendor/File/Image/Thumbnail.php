<?php
namespace File\Image;

use File\Image\Exception\UnknownImageType;
use File\Image\Thumbnail\GIF;
use File\Image\Thumbnail\IThumbnail;
use File\Image\Thumbnail\JPEG;
use File\Image\Thumbnail\PNG;

class Thumbnail
{
    /**
     * @var IThumbnail
     */
    protected $thumbnail;

    /**
     * @param $file_name
     * @throws UnknownImageType
     */
    public function __construct($file_name)
    {
        $image = getimagesize($file_name);
        switch ($image['mime']) {
            case 'image/jpg':
            case 'image/jpeg':
                $this->thumbnail = new JPEG($file_name, $image);
                break;
            case 'image/gif':
                $this->thumbnail = new GIF($file_name, $image);
                break;
            case 'image/png':
                $this->thumbnail = new PNG($file_name, $image);
                break;
            default:
                throw new UnknownImageType("Unknown image type:" . $image['mime']);
                break;
        }
    }

    /**
     * @param string  $file_name
     * @param integer $quality
     *
     * @return void
     */
    public function save($file_name, $quality=100)
    {
        $this->thumbnail->save($file_name, $quality);
    }

    /**
     * @param integer $width
     * @param integer $height
     * @param string  $option exact|portrait|landscape|auto|crop
     *
     * @return void
     */
    public function resize($width, $height, $option='auto')
    {
        $this->thumbnail->resize($width, $height, $option);
    }
}

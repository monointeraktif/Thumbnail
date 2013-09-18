<?php
namespace File\Image\Thumbnail;

class PNG extends ThumbnailAbstract implements IThumbnail
{
    /**
     * @param string $file_name
     * @param array $image
     */
    public function __construct($file_name, $image)
    {
        $this->image = @imagecreatefrompng($file_name);
        imagealphablending($this->image, true);
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

        // *** Scale quality from 0-100 to 0-9
        $scale_quality = round(($quality/100) * 9);

        // *** Invert quality setting as 0 is best, not 9
        $invert_scale_quality = 9 - $scale_quality;

        imagepng($this->image_resize, $temp_file, $invert_scale_quality);
        copy($temp_file, $file_name);
        imagedestroy($this->image_resize);
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
        $option_array = $this->getDimensions($width, $height, $option);
        $optimal_width  = $option_array['optimal_width'];
        $optimal_height = $option_array['optimal_height'];

        $this->image_resize = imagecreatetruecolor($optimal_width, $optimal_height);
        imagealphablending($this->image_resize, false);
        imagesavealpha($this->image_resize, true);

        imagecopyresampled(
            $this->image_resize, $this->image, 0, 0, 0, 0, $optimal_width, $optimal_height, $this->width, $this->height
        );

        if ($option == 'crop') {
            $this->crop($optimal_width, $optimal_height, $width, $height);
        }
    }
}

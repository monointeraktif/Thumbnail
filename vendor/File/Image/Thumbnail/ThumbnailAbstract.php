<?php
namespace File\Image\Thumbnail;

abstract class ThumbnailAbstract
{
    /**
     * @var resource
     */
    protected $image;

    /**
     * @var integer
     */
    protected $width;

    /**
     * @var integer
     */
    protected $height;

    /**
     * @var resource
     */
    protected $image_resize;

    /**
     * @param string $file_name
     * @param array $image
     */
    public function __construct($file_name, $image)
    {
        $this->width  = $image[0];
        $this->height = $image[1];
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
        imagecopyresampled(
            $this->image_resize, $this->image, 0, 0, 0, 0, $optimal_width, $optimal_height, $this->width, $this->height
        );

        if ($option == 'crop') {
            $this->crop($optimal_width, $optimal_height, $width, $height);
        }
    }

    /**
     * @param integer $optimal_width
     * @param integer $optimal_height
     * @param integer $width
     * @param integer $height
     */
    protected function crop($optimal_width, $optimal_height, $width, $height)
    {
        $crop_start_x = ( $optimal_width / 2) - ( $width /2 );
        $crop_start_y = ( $optimal_height/ 2) - ( $height/2 );

        $crop = $this->image_resize;

        $this->image_resize = imagecreatetruecolor($width , $height);
        imagecopyresampled($this->image_resize, $crop , 0, 0, $crop_start_x, $crop_start_y, $width, $height , $width, $height);
    }

    /**
     * @param integer $width
     * @param integer$height
     * @param string $option
     * @return array
     */
    protected function getDimensions($width, $height, $option)
    {
        switch ($option) {
            case 'exact':
                $optimal_width   = $width;
                $optimal_height  = $height;
                break;
            case 'portrait':
                $optimal_width   = $this->getSizeByFixedHeight($height);
                $optimal_height  = $height;
                break;
            case 'landscape':
                $optimal_width   = $width;
                $optimal_height  = $this->getSizeByFixedWidth($width);
                break;
            case 'auto':
                $option_array    = $this->getSizeByAuto($width, $height);
                $optimal_width   = $option_array['optimal_width'];
                $optimal_height  = $option_array['optimal_height'];
                break;
            case 'crop':
                $option_array   = $this->getOptimalCrop($width, $height);
                $optimal_width  = $option_array['optimal_width'];
                $optimal_height = $option_array['optimal_height'];
                break;
            default:
                $optimal_width   = $width;
                $optimal_height  = $height;
                break;
        }
        return array('optimal_width' => $optimal_width, 'optimal_height' => $optimal_height);
    }

    /**
     * @param integer $height
     * @return integer
     */
    protected function getSizeByFixedHeight($height)
    {
        return $height * ($this->width / $this->height);
    }

    /**
     * @param integer $width
     * @return integer
     */
    protected function getSizeByFixedWidth($width)
    {
        return $width * ($this->height / $this->width);
    }

    /**
     * @param integer $width
     * @param integer $height
     * @return array
     */
    protected function getSizeByAuto($width, $height)
    {
        if( $this->height < $this->width ){
            $optimal_width   = $width;
            $optimal_height  = $this->getSizeByFixedWidth($optimal_width);
            if( $optimal_height > $height ){
                $optimal_height  = $height;
                $optimal_width   = $this->getSizeByFixedHeight($optimal_height);
            }

        }
        elseif ($this->height > $this->width){
            $optimal_width   = $this->getSizeByFixedHeight($height);
            $optimal_height  = $height;
            if( $optimal_width > $width ){
                $optimal_width   = $width;
                $optimal_height  = $this->getSizeByFixedWidth($optimal_width);
            }
        } else {
            if ($height > $width) {
                $optimal_width   = $width;
                $optimal_height  = $this->getSizeByFixedWidth($width);
            } else if ($height < $width) {
                $optimal_width   = $this->getSizeByFixedHeight($height);
                $optimal_height  = $height;
            } else {
                $optimal_width   = $width;
                $optimal_height  = $height;
            }
        }
        return array('optimal_width' => $optimal_width, 'optimal_height' => $optimal_height);
    }

    /**
     * @param integer $width
     * @param integer $height
     * @return array
     */
    protected function getOptimalCrop($width, $height)
    {
        $height_ratio   = $this->height / $height;
        $width_ratio    = $this->width /  $width;

        if ($height_ratio < $width_ratio) {
            $optimal_ratio = $height_ratio;
        } else {
            $optimal_ratio = $width_ratio;
        }

        $optimal_height = $this->height / $optimal_ratio;
        $optimal_width  = $this->width  / $optimal_ratio;

        return array('optimal_width' => $optimal_width, 'optimal_height' => $optimal_height);
    }
}

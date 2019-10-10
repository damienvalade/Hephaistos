<?php


namespace Hephaistos;


class Cropper extends Source
{
    /**
     * @param string $target
     * @param array $size
     * @param array $percent
     * @param string $typeCrop
     * @return bool
     */
    public function cropper(string $target, Array $size, Array $percent = null, string $typeCrop = 'center-center'): bool
    {
        $width = NULL;
        $height = NULL;

        foreach ($size as $key => $val) {
            $width = $key === 'width' ? $val : $width;
            $height = $key === 'height' ? $val : $height;
        }

        $dest = $this->renameFile($target, $width, $height);

        copy($target, $dest);

        $im = $this->pictureCreateType($target);

        $cx = imagesx($im) / 2;
        $cy = imagesy($im) / 2;

        $value = $this->sizeCrop($cx,$cy,$typeCrop);

        $x = $value['x'] < 0? 0 : $value['x'] ;
        $y = $value['y'] < 0? 0 : $value['y'] ;

        $im2 = imagecrop($im, ['x' => $x, 'y' => $y, 'width' => $width, 'height' => $height]);

        if ($im2 !== FALSE) {
            $this->pictureType($target, $im2, $dest);
            imagedestroy($im2);
        }

        imagedestroy($im);

        return true;
    }

    /**
     * @param int $x
     * @param int $y
     * @param string $type
     * @return array
     */
    public function sizeCrop(int $x, int $y, string $type){
        switch ($type) {
            case 'top-left':
                return ['x' => 0, 'y' => 0];
                break;
            case 'top-right':
                return ['x' => $x, 'y' => 0];
                break;
            case 'bot-left':
                return ['x' => 0, 'y' => $y];
                break;
            case 'bot-right':
                return ['x' => $x, 'y' => $y];
                break;
            case 'center-left':
                return ['x' => 0, 'y' => $y/2];
                break;
            case 'center-top':
                return ['x' => $x/2, 'y' => 0];
                break;
            case 'center-right':
                return ['x' => $x, 'y' => $y/2];
                break;
            case 'center-bot':
                return ['x' => $x/2, 'y' => $y];
                break;
            case 'center-center':
                return ['x' => $x/2, 'y' => $y/2];
                break;
            default:
                return null;
                break;
        }
    }
}
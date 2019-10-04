<?php


namespace Hephaistos;


class Cropper extends Source
{
    /**
     * @param string $target
     * @param array $size
     * @return bool
     */
    public function cropper(string $target, Array $size): bool
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

        $x = $cx - $width / 2;
        $y = $cy - $height / 2;

        if ($x < 0) {
            $x = 0;
        }
        if ($y < 0) {
            $y = 0;
        }

        $im2 = imagecrop($im, ['x' => $x, 'y' => $y, 'width' => $width, 'height' => $height]);

        if ($im2 !== FALSE) {
            $this->pictureType($target, $im2, $dest);
            imagedestroy($im2);
        }

        imagedestroy($im);

        return true;
    }
}
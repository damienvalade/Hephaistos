<?php


namespace Hephaistos;


class Resize extends Source
{

    /**
     * @param string $target
     * @param bool $proportional
     * @param array $size
     * @return bool
     */
    public function resize(string $target, Array $size, $proportional = false): bool
    {

        $width = NULL;
        $height = NULL;
        $percent = NULL;

        [$oldwidth, $oldheight] = getimagesize($target);


        if ($proportional === true) {

            $percent = 1;

            foreach ($size as $key => $val) {
                $percent = $key === 'percent' ? $val : $percent;
            }

            $width = $oldwidth * $percent;
            $height = $oldheight * $percent;
        }

        if ($proportional === false) {
            foreach ($size as $key => $val) {
                $width = $key === 'width' ? $val : $width;
                $height = $key === 'height' ? $val : $height;
            }
        }

        $dest = $this->renameFile($target, $width, $height);

        $newWidth = $width;
        $newHeight = $height;

        $thumb = imagecreatetruecolor($newWidth, $newHeight);
        $sourceTargeted = $this->pictureCreateType($target);

        imagecopyresampled($thumb, $sourceTargeted, 0, 0, 0, 0, $newWidth, $newHeight, $oldwidth, $oldheight);

        $this->pictureType($target, $thumb, $dest);

        return true;

    }

}
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

        $oldWidth = NULL;
        $oldHeight = NULL;

        $newWidth = NULL;
        $newHeight = NULL;

        $percent = 1;

        [$oldWidth, $oldHeight] = getimagesize($target);


        if ($proportional === true) {

            foreach ($size as $key => $val) {
                $percent = $key === 'percent' ? $val : $percent;
            }

            $newWidth = $oldWidth * $percent;
            $newHeight = $oldHeight * $percent;
        }

        if ($proportional === false) {
            foreach ($size as $key => $val) {
                $newWidth = $key === 'width' ? $val : $newWidth;
                $newHeight = $key === 'height' ? $val : $newHeight;
            }
        }

        $dest = $this->renameFile($target, $newWidth, $newHeight);

        $thumb = imagecreatetruecolor($newWidth, $newHeight);
        $sourceTargeted = $this->pictureCreateType($target);

        imagecopyresampled($thumb, $sourceTargeted, 0, 0, 0, 0, $newWidth, $newHeight, $oldWidth, $oldHeight);

        $this->pictureType($target, $thumb, $dest);

        return true;

    }

}
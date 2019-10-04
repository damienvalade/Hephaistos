<?php


namespace Hephaistos\Media;


class Multiple
{

    public $cropper = NULL;
    public $resize = NULL;


    public function __construct()
    {
        $this->cropper = new Cropper();
        $this->resize = new Resize();
    }

    /**
     * @param string $type
     * @param string $target
     * @param array $size
     * @return bool
     */
    public function multipleUpload(string $type, string $target, array $size): bool
    {
        $width = null;
        $height = null;
        $percent = null;

        foreach ($size as $key => $val) {
            foreach ($val as $key2 => $val2) {
                $width = $key2 === 'width' ? $width : $val2;
                $height = $key2 === 'height' ? $height : $val2;
                $percent = $key2 === 'percent' ? ['percent' => $val2] : $percent;
            }

            $size = [
                'width' => $width,
                'height' => $height
            ];

            if ($type === 'Cropper') {
                $this->cropper->cropper($target, $size);
            }

            if ($type === 'Resize') {
                if ($percent !== null) {
                    $this->resize->resize($target,  $percent, true);
                }
                if ($percent === null) {
                    $this->resize->resize($target, $size);
                }
            }
        }
        return true;
    }
}
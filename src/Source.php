<?php


namespace App\Media;

use SplFileInfo;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Source extends AbstractController
{

    public function __construct()
    {

    }

    /**
     * @param string $target
     * @return string
     */
    public function fileType(string $target): string
    {
        $info = new SplFileInfo($target);
        $fileType = strtolower($info->getExtension());

        $fileType = ($fileType === 'jpg') ? 'jpeg' : $fileType;

        return '.' . $fileType;
    }

    /**
     * @param string $target
     * @return mixed
     */
    public function fileName(string $target)
    {
        $info = new SplFileInfo($target);
        return $info->getBasename($this->fileType($target));
    }

    /**
     * @param string $target
     * @return string
     */
    public function filePath(string $target): string
    {
        $info = new SplFileInfo($target);
        return $info->getPathInfo()->getPathname() . '/';
    }

    /**
     * @param string $target
     * @param string $width
     * @param string $height
     * @return string
     */
    public function renameFile(string $target, string $width, string $height): string
    {
        $FilePath = $this->filePath($target);
        $FileName = $this->fileName($target);
        $FileType = $this->fileType($target);

        return $FilePath . $FileName . '- ' . $width . 'x' . $height . $FileType;
    }

    /**
     * @return string
     */
    private function uniqName(): string
    {
        return md5(uniqid('', true));
    }

    /**
     * @param string $path
     * @param $target
     * @param string $destination
     * @param int $quality
     * @return bool
     */
    public function pictureType(string $path, $target, string $destination, int $quality = -1)
    {

        $fileType = $this->fileType($path);

        switch ($fileType) {
            case '.jpeg':
                return imagejpeg($target, $destination, $quality);
                break;
            case '.png':
                return imagepng($target, $destination, $quality);
                break;
            case '.gif':
                return imagegif($target, $destination);
                break;
            default:
                break;
        }

    }

    /**
     * @param string $target
     * @return false|resource
     */
    public function pictureCreateType(string $target)
    {

        $fileType = $this->fileType($target);

        switch ($fileType) {
            case '.jpeg':
                return imagecreatefromjpeg($target);
                break;
            case '.png':
                return imagecreatefrompng($target);
                break;
            case '.gif':
                return imagecreatefromgif($target);
                break;
            default:
                break;
        }

    }
}
<?php
/**
 * Class resized and uploads images
 * @author Hyrenko Alexandr
 * @version 1.0
 */
class img
{
    /**
     * Quality resize image
     * @var int
     */
    public $quality = 100;

    /**
     * Upload extensions images
     * @var string
     */
    public $uploadTypes = 'jpg, jpeg, png, gif';

    /**
     * Max upload size image
     * @var int 8Mb
     */
    public $maxUploadSize = 8192;

    /**
     * Offset votermark to image
     * @var int
     */
    public $offset = 10;

    /**
     * Library from resized
     * @var string[GD,Imagick]
     */
    public $lib = 'GD';

    /**
     * Generate upload name size
     * @var int
     */
    public $uploadNameSize = 10;

    /**
     * Generate upload name type
     * @var string
     */
    public $uploadNameType = 'number';

    public $fontFamily = 'fonts/tahoma.ttf';

    /**
     * Private method resized image
     * Library Imagick (imageMagick)
     * @param file $src
     * @param int $width
     * @param int $height
     * @param string[optional] $out
     * @param hex[optional] $bgColor
     * @return bool/string
     */
    private function imgResizeImagick($src, $width, $height, $out=null, $bgColor=null)
    {
        $image = new Imagick($src);

        $image->thumbnailImage($width, $height, true);

        if ($bgColor!==null)
        {
            $bg = new Imagick();
            $bg->newImage($width, $height, '#'.dechex($bgColor), 'jpg');

            $geometry = $image->getImageGeometry();

            $x = ($width - $geometry['width'])/2;
            $y = ($height - $geometry['height'])/2;

            $bg->compositeImage($image, imagick::COMPOSITE_OVER, $x, $y);
        }
        else $bg = $image;

        if ($out == null) $fullPath = $src;
        elseif (img::verificationDir($out)) $fullPath = $out.$src;
        elseif ($out=='view') $fullPath = '';
        else $fullPath = $out;

        if ($out=='view')
        {
            header('Content-type: image/jpeg');
            echo $bg;
            $image->destroy();
            $bg->destroy();
            return true;
        }
        else
        {
            if ($bg->writeImage($fullPath))
            {
                $image->destroy();
                $bg->destroy();
                return $fullPath;
            }
            else
            {
                img::error('Error write image. Library Imagick');
                return false;
            }
        }

    }

    /**
     * Private method resized image
     * Library GD
     * @param file $src
     * @param int $width
     * @param int $height
     * @param dir/string[optional] $out
     * @param hex[optional] $bgColor
     * @return bool/string
     */
    private function imgResizeGD($src, $width, $height, $out=null, $bgColor=null)
    {

        if ($src!=null and $width!=null and $height!=null)
        {
            if ($imageInfo = img::imgInfo($src))
            {
                $xRatio = $width/$imageInfo['width'];
                $yRatio = $height/$imageInfo['height'];

                $ratio = min($xRatio, $yRatio);
                $useXRatio = ($xRatio==$ratio);

                if ($bgColor===null)
                {
                    $newWidth = $useXRatio?$width:floor($imageInfo['width']*$ratio);
                    $newHeight = !$useXRatio?$height:floor($imageInfo['height']*$ratio);

                    $image = img::imagecreate($imageInfo['type'], $src);
                    $bgImage = imagecreatetruecolor($newWidth, $newHeight);

                    if ($imageInfo['extension']=='png')
                    {
                        $cc=imagecolorallocatealpha($bgImage, 255, 255, 255, 127);
                        imagefill($bgImage, 0, 0, $cc);
                    }

                    imagecopyresampled($bgImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $imageInfo['width'], $imageInfo['height']);
                }
                else
                {
                    if ($width > $imageInfo['width'] and $height > $imageInfo['height'])
                    {
                        $newWidth = $imageInfo['width'];
                        $newHeight = $imageInfo['height'];
                        $newLeft = ($width - $imageInfo['width'])/2;
                        $newTop = ($height - $imageInfo['height'])/2;
                    }
                    else
                    {
                        $newWidth = $useXRatio?$width:floor($imageInfo['width']*$ratio);
                        $newHeight = !$useXRatio?$height:floor($imageInfo['height']*$ratio);
                        $newLeft = $useXRatio?0:floor(($width-$newWidth)/2);
                        $newTop = !$useXRatio?0:floor(($height-$newHeight)/2);
                    }

                    $image = img::imagecreate($imageInfo['type'], $src);
                    $bgImage = imagecreatetruecolor($width, $height);
                    imagefill($bgImage, 0, 0, $bgColor);
                    imagecopyresampled($bgImage, $image, $newLeft, $newTop, 0, 0, $newWidth, $newHeight, $imageInfo['width'], $imageInfo['height']);
                }

                if ($out == null) $fullPath = $src;
                elseif (img::verificationDir($out)) $fullPath = $out.end(explode('/', $src));
                elseif ($out=='view') $fullPath = '';
                else $fullPath = $out;

                if ($out=='view')
                {
                    header('Content-type: image/jpeg');
                    imagejpeg($bgImage);
                    imagedestroy($image);
                    imagedestroy($bgImage);
                    return true;
                }
                else
                {
                    if (imagejpeg($bgImage, $fullPath, $this->quality))
                    {
                        imagedestroy($image);
                        imagedestroy($bgImage);
                        return $fullPath;
                    }
                    else
                    {
                        img::error('Error created image. Library GD');
                        return false;
                    }
                }
            }
        }
    }

    /**
     * Function imagecreatefrom...
     * @param string $type
     * @param file $src
     * @return resource
     */
    private function imagecreate($type, $src)
    {
        $imagecreate = 'imagecreatefrom'.$type;
        if (!function_exists($imagecreate))
        {
            img::error('Function `'.$imagecreate.'` not exists');
            return false;
        }
        return $imagecreate($src);
    }

    /**
     * Wotermark image
     * @param file $srcImage
     * @param file $srcWot
     * @param string[optional] $posX
     * @param string[optional] $posY
     */
    private function wotermarkImageGD($srcImage, $srcWot, $posX = 'right', $posY = 'bottom')
    {
        if (is_file($srcImage) and is_file($srcWot))
        {

            if ($imageInfo = img::imgInfo($srcImage) and $wotInfo = img::imgInfo($srcWot));
            else return false;

            $image = img::imagecreate($imageInfo['type'], $srcImage);
            $woter = img::imagecreate($wotInfo['type'], $srcWot);

            switch ($posX)
            {
                case 'left':
                    $posX = $this->offset;
                    break;
                case 'center':
                    $posX = ($imageInfo['width']/2) - ($wotInfo['width']/2);
                    break;
                case 'right':
                    $posX = ($imageInfo['width'] - $this->offset) - $wotInfo['width'];
                    break;
            }

            switch ($posY)
            {
                case 'top':
                    $posY = $this->offset;
                    break;
                case 'center':
                    $posY = ($imageInfo['height']/2) - ($wotInfo['height']/2);
                    break;
                case 'bottom':
                    $posY = ($imageInfo['height'] - $this->offset) - $wotInfo['height'];
                    break;
            }

            imagecopy($image, $woter, $posX, $posY, 0, 0, $wotInfo['width'], $wotInfo['height']);

            imagejpeg($image, $srcImage, $this->quality);
            imagedestroy($image);
            imagedestroy($woter);
        }
        else
        {
            img::error('File `'.$srcImage.'` or `'.$srcWot.'` not found');
            return false;
        }
    }

    /**
     * Wotermark text
     * @param file $src
     * @param string $text
     * @param string[optional] $posX
     * @param strin[optional] $posY
     * @param file[optional] $font
     * @param int[optional] $fontSize
     * @param hex[optional] $fontColor
     */
    private function wotermarkTextGD($src, $text, $posX='right', $posY='bottom', $font=null, $fontSize=20, $fontColor=0xFFFFFF)
    {
        if (is_file($src))
        {
            if ($imageInfo = img::imgInfo($src))
            {
                $image = img::imagecreate($imageInfo['type'], $src);

                if (is_file($font))
                {
                    $textBox = imagettfbbox($fontSize, 0, $font, $text);
                    $textWidth = abs($textBox[2]);
                    $textHeigh = abs($textBox[5]);
                }
                else
                {
                    $textWidth = imagefontwidth($fontSize)*strlen($text);
                    $textHeigh = imagefontheight($fontSize);
                }

                switch ($posX)
                {
                    case 'left':
                        $posX = $this->offset;
                        break;
                    case 'center':
                        $posX = ($imageInfo['width']/2) - ($textWidth/2);
                        break;
                    case 'right':
                        $posX = ($imageInfo['width'] - $this->offset) - $textWidth;
                        break;
                }

                switch ($posY)
                {
                    case 'top':
                        $posY = $this->offset + (is_file($font)?$textHeigh:'');
                        break;
                    case 'center':
                        $posY = ($imageInfo['height']/2) - ($textHeigh/2);
                        break;
                    case 'bottom':
                        $posY = ($imageInfo['height']) - $this->offset;
                        break;
                }

                if (is_file($font))
                {
                    imagettftext($image, $fontSize, 0, $posX, $posY, $fontColor, $font, $text);
                    imagejpeg($image, $src, $this->quality);
                    imagedestroy($image);
                    return true;
                }
                else
                {
                    imagestring($image, $fontSize, $posX, $posY-$this->offset, $text, $fontColor);
                    imagejpeg($image, $src, $this->quality);
                    imagedestroy($image);
                    return true;
                }
            }
        }
        else
        {
            img::error('File `'.$src.'` not found');
            return false;
        }
    }

    /**
     * Private method verification type image
     * @param string $type
     * @return bool
     */
    private function verificationType($type)
    {
        $types = explode(', ', $this->uploadTypes);
        if (array_search($type, $types)!==false) return true;
        else
        {
            img::error('Type file not is `'.$types.'`');
            return false;
        }
    }

    /**
     * Private method verification dir
     * and dir as writeable
     * @param dir $dir
     * @return bool
     */
    private function verificationDir($dir)
    {
        $dir==null ? $dir=getcwd():$dir;
        if (is_dir($dir))
        {
            if (is_writable($dir)) return true;
            else
            {
                if (chmod($dir, 0777)) return true;
                else
                {
                    img::error('Dir `'.$dir.'` is not writeable');
                    return false;
                }
            }
        }
        return false;
    }

    private function generate()
    {
        switch ($this->uploadNameType)
        {
            case 'number':
                $symbols = '0123456789';
                break;
            case 'low':
                $symbols = 'abcdefghijklmnopqrstuvwxyz';
                break;
            case 'up':
                $symbols = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                break;

            default:
                $symbols = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                break;
        }

        for($i=0;$i<=$this->uploadNameSize;$i++)
            $str .= $symbols{mt_rand(0, strlen($symbols)-1)};

        return $str;
    }

    /**
     * Method returned image information
     * @param file $src
     * @return assoc
     */
    public function imgInfo($src)
    {
        if ($src!=null and is_file($src))
        {
            $info = getimagesize($src);
            $return['width'] = $info[0];
            $return['height'] = $info[1];
            switch ($info[2])
            {
                case 1: $return['extension'] = 'gif'; break;
                case 2: $return['extension'] = 'jpg'; break;
                case 3: $return['extension'] = 'png'; break;
                case 4: $return['extension'] = 'swf'; break;
                case 5: $return['extension'] = 'psd'; break;
                case 6: $return['extension'] = 'bmp'; break;
                case 7: $return['extension'] = 'tiff'; break;
                case 8: $return['extension'] = 'tiff'; break;
                case 9: $return['extension'] = 'jpc'; break;
                case 10: $return['extension'] = 'jp2'; break;
                case 11: $return['extension'] = 'jpx'; break;
            }
            $return['mime'] = $info['mime'];
            $return['type'] = @strtolower(end(explode('/', $info['mime'])));
            return $return;
        }
        else
        {
            img::error('File `'.$src.'` not found');
            return false;
        }
    }

    /**
     * Method resized image or images
     * @param file/array $src
     * @param int $width
     * @param int $height
     * @param dir[optional] $out
     * @param hex[optional] $bgColor
     * @return array/string
     */
    public function resize($src, $width, $height, $out=null, $bgColor=null)
    {
        if (is_array($src))
        {
            for ($i=0; $i<count($src);$i++)
            {
                if (is_file($src[$i]))
                {
                    if ($this->lib=='GD' and extension_loaded('GD'))
                        $return[] = img::imgResizeGD($src[$i], $width, $height, $out, $bgColor);
                    elseif ($this->lib=='Imagick' and extension_loaded('Imagick'))
                        $return[] = img::imgResizeImagick($src[$i], $width, $height, $out, $bgColor);
                    else
                    {
                        img::error('Extension not is loaded');
                        return false;
                    }
                }
                else continue;
            }
        }
        elseif (is_file($src))
        {
            if ($this->lib=='GD' and extension_loaded('GD'))
                $return = img::imgResizeGD($src, $width, $height, $out, $bgColor);
            elseif ($this->lib=='Imagick' and extension_loaded('Imagick'))
                $return = img::imgResizeImagick($src, $width, $height, $out, $bgColor);
            else
            {
                img::error('Extension not is loaded');
                return false;
            }
        }
        else
        {
            img::error('File `'.$src.'` not is file and array files');
            return false;
        }

        return $return;
    }

    /**
     * Wotermark image
     * @param file/array $srcImage
     * @param file $srcWot
     * @param string[option] $posX
     * @param string[option] $posY
     * @return bool
     */
    public function wotermarkImage($srcImage, $srcWot, $posX = 'right', $posY = 'bottom')
    {
        if (is_array($srcImage))
        {
            for ($i=0;$i<count($srcImage);$i++)
            {
                if (is_file($srcImage[$i]))
                {
                    if (img::wotermarkImageGD($srcImage[$i], $srcWot, $posX, $posY)) return true;
                    else
                    {
                        img::error('Error create wotermark');
                        return false;
                    }
                }
                else continue;
            }
        }
        elseif (is_file($srcImage))
        {
            if (img::wotermarkImageGD($srcImage, $srcWot, $posX, $posY)) return true;
            else
            {
                img::error('Error create wotermark');
                return false;
            }
        }
        else
        {
            img::error($srcImage.' not is array or file');
            return false;
        }
    }

    /**
     * Wotermark text
     * @param file/array $srcImage
     * @param string $text
     * @param string[optional] $posX
     * @param string[optional] $posY
     * @param file[optional] $font
     * @param int[optional] $fontSize
     * @param hex[optional] $fontColor
     * @return bool
     */
    public function wotermarkText($srcImage,$text, $posX = 'right', $posY = 'bottom', $font=null, $fontSize=12, $fontColor=0x000000)
    {
        if (is_array($srcImage))
        {
            for ($i=0;$i<count($srcImage);$i++)
            {
                if (img::wotermarkTextGD($srcImage[$i], $text, $posX, $posY, $font, $fontSize, $fontColor));
                else
                {
                    img::error('Error create wotermark');
                    continue;
                }
            }
        }
        elseif (is_file($srcImage))
        {
            if (img::wotermarkTextGD($srcImage, $text, $posX, $posY, $font, $fontSize, $fontColor)) return true;
            else
            {
                img::error('Error create wotermark');
                return false;
            }
        }
        else
        {
            img::error($srcImage.' not is array or file');
            return false;
        }
    }

    /**
     * Upload image or images to dir
     * @param string/array $name
     * @param dir $dir
     * @return string/array
     */
    public function uploadImg($name, $dir = '', $imageName = '')
    {
        if ($name!=null and img::verificationDir($dir))
        {
            if (!isset($_FILES[$name]['name']))
            {
                img::error('Empty var $_FILES['.$name.']');
                return false;
            }
            if (is_array($_FILES[$name]['name']))
            {
                for ($i=0; $i<count($_FILES[$name]['name']);$i++)
                {
                    if ($_FILES[$name]['name'][$i]==null) continue;

                    if (round(($_FILES[$name]['size'][$i]/1024), 2)>$this->maxUploadSize)
                    {
                        img::error('File size `'.$name.'` > max upload size ('.$this->maxUploadSize.' Mb)');
                        return false;
                    }

                    $imgInfo = img::imgInfo($_FILES[$name]['tmp_name'][$i]);

                    if (!img::verificationType($imgInfo['extension'])) return false;
                    if (!is_uploaded_file($_FILES[$name]['tmp_name'][$i]))
                    {
                        img::error('Error uploading file');
                        return false;
                    }
                    $nameImg = join(DIRECTORY_SEPARATOR, array($dir, $imageName . '.' .$imgInfo['extension']));

                    if (@!copy($_FILES[$name]['tmp_name'][$i], $nameImg))
                    {
                        img::error('Error copyring tmp file to dir');
                        return false;
                    }

                    $return[] = $nameImg;
                }
                return $return;
            }
            else
            {
                $imgInfo = img::imgInfo($_FILES[$name]['tmp_name']);

                if (round(($_FILES[$name]['size']/1024), 2)>$this->maxUploadSize)
                {
                    img::error('File size `'.$name.'` > max upload size ('.$this->maxUploadSize.')');
                    return false;
                }

                if (!img::verificationType($imgInfo['extension'])) return false;
                if (!is_uploaded_file($_FILES[$name]['tmp_name']))
                {
                    img::error('Error uploading file');
                    return false;
                }
                $nameImg = join(DIRECTORY_SEPARATOR, array($dir, $imageName . '.' .$imgInfo['extension']));

                if (@!copy($_FILES[$name]['tmp_name'], $nameImg))
                {
                    img::error('Error copyring tmp file to dir');
                    return false;
                }

                return $nameImg;
            }
        }
        return false;
    }

    public function copyImages($files, $dir) {
        if ($files == null) {
            img::error('$files is null');
            return false;
        }

        if (!img::verificationDir($dir)) {
            img::error( $dir.' is not writeable');
            return false;
        }

        if (is_array($files)) {
            for ($i = 0, $c = count($files); $i < $c; $i++) {
                if (is_file($files[$i])) {
                    $newFile = $dir.end(explode('/', $files[$i]));
                    if (copy($files[$i], $newFile)){
                        $res[$i] = $newFile;
                        continue;
                    }
                    else {
                        img::error( $files[$i].' error copy file');
                        return false;
                    }
                }
            }
        }
        else {
            if (is_file($files)) {
                $newFile = $dir.end(explode('/', $files));
                if (copy($files, $newFile))
                    return $newFile;
                else {
                    img::error( $files.' error copy file');
                    return false;
                }
            }
        }

        return $res;
    }

    public function captcha($string, $width, $height, $fontSize=17, $bgColor=0xffffff)
    {
        if (is_file($this->fontFamily))
        {
            $colors = array('10','30','50','70','90','110','130','150','170','190','210');
            $letters = array('a','b','c','d','e','f','g','h','j','k','m','n','p','q','r','s','t','u','v','w','x','y','z','2','3','4','5','6','7','9');

            $image = imagecreatetruecolor($width,$height);
            $bg = imagecolorallocate($image,255,255,255);
            imagefill($image,0,0,$bg);

            for($i=0;$i<10;$i++)
            {
                $color = imagecolorallocatealpha($image,rand(0,255),rand(0,255),rand(0,255),100);
                $letter = $letters[rand(0,sizeof($letters)-1)];
                $size = rand($fontSize-2,$fontSize+2);
                imagettftext($image,$size,rand(0,45),rand($width*0.1,$width-$width*0.1),rand($height*0.2,$height),$color,$this->fontFamily,$letter);
            }

            for($i=0;$i<4;$i++)
            {
                $color = imagecolorallocatealpha($image,$colors[rand(0,sizeof($colors)-1)],$colors[rand(0,sizeof($colors)-1)],$colors[rand(0,sizeof($colors)-1)],rand(20,40));
                $size = rand($fontSize*2.1-2,$fontSize*2.1+2);
                $x = ($i+1)*$fontSize + rand(4,7);
                $y = (($height*2)/3) + rand(0,5);
                $cod[] = $letter;
                imagettftext($image,$fontSize,rand(0,15),$x,$y,$color,$this->fontFamily,$string);
            }

            header ("Content-type: image/gif");
            imagegif($image);
        }
        else
        {
            img::error('Font `'.$this->fontFamily.'` not found!');
            return false;
        }
    }

    /**
     * Private method error reporting to debug
     * @param string $text
     */
    private function error($text)
    {
        $GLOBALS['_ERRORS'][] = 'Error image resized: '.$text.'. Class `img`.';
    }
}
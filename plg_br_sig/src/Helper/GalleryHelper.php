<?php
declare(strict_types=1);

// Namespace do NOVO plugin
namespace Br\Plugin\Content\BrSig\Helper;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;
use Joomla\Filesystem\Folder;
use stdClass;

class GalleryHelper
{
    public string $srcImgFolder = '';
    public int $thb_width = 300;
    public int $thb_height = 200;
    public int $quality = 90;
    public int $cacheTime = 86400;
    public string $galleryId = '';
    public string $pluginName = 'br_sig';

    public function renderGallery(): string
    {
        $sitePath = JPATH_SITE . '/';
        $siteUrl = Uri::root(true);
        if (substr($siteUrl, -1) !== '/') { $siteUrl .= '/'; }

        // Pasta de Cache Nova (br_sig)
        $cacheFolderPath = JPATH_SITE . '/cache/br_sig';
        if (!is_dir($cacheFolderPath)) {
            mkdir($cacheFolderPath, 0755, true);
            file_put_contents($cacheFolderPath . '/index.html', ''); 
        }

        $sourceFullPath = $sitePath . $this->srcImgFolder;
        if (!is_dir($sourceFullPath)) {
            return ''; // Retorna vazio se pasta não existir
        }

        $files = Folder::files($sourceFullPath, '\.(gif|jpg|jpeg|png|webp)$', false, true);
        if (empty($files)) { return ''; }

        sort($files);

        $gallery = [];
        
        foreach ($files as $fullPath) {
            $filename = basename($fullPath);
            $thumbName = pathinfo($filename, PATHINFO_FILENAME) . '.jpg';
            
            // Definição de caminhos
            $thumbPath = $cacheFolderPath . '/' . $this->galleryId . '_' . $thumbName;
            $thumbUrl = $siteUrl . 'cache/br_sig/' . $this->galleryId . '_' . $thumbName;
            
            // URL da imagem original (Encode para caracteres especiais)
            $srcUrl = $siteUrl . $this->srcImgFolder . '/' . rawurlencode($filename);

            // Gera o thumb se não existir ou expirou
            if (!file_exists($thumbPath) || (filemtime($thumbPath) + $this->cacheTime) < time()) {
                $this->createThumbnail($fullPath, $thumbPath);
            }

            // Objeto simplificado para o Template
            $img = new stdClass();
            $img->source = $srcUrl;
            $img->thumb = $thumbUrl;
            $img->title = pathinfo($filename, PATHINFO_FILENAME);
            
            $gallery[] = $img;
        }

        // Renderização Simples (sem complexidade de temas legados)
        $templatePath = JPATH_PLUGINS . '/content/' . $this->pluginName . '/tmpl/default.php';
        
        if (!file_exists($templatePath)) {
             return '';
        }

        ob_start();
        include $templatePath;
        return ob_get_clean();
    }

    private function createThumbnail(string $sourcePath, string $destPath): bool
    {
        $info = getimagesize($sourcePath);
        if (!$info) return false;

        [$width, $height, $type] = $info;

        $source = match ($type) {
            IMAGETYPE_GIF => imagecreatefromgif($sourcePath),
            IMAGETYPE_JPEG => imagecreatefromjpeg($sourcePath),
            IMAGETYPE_PNG => imagecreatefrompng($sourcePath),
            IMAGETYPE_WEBP => imagecreatefromwebp($sourcePath),
            default => null,
        };

        if (!$source) return false;

        // --- CORREÇÃO DE ROTAÇÃO (EXIF) ---
        // Exatamente como no seu código antigo, que sabemos que funciona
        if ($type === IMAGETYPE_JPEG && function_exists('exif_read_data')) {
            $exif = @exif_read_data($sourcePath);
            if (!empty($exif['Orientation'])) {
                switch ($exif['Orientation']) {
                    case 3: $source = imagerotate($source, 180, 0); break;
                    case 6: $source = imagerotate($source, -90, 0); break;
                    case 8: $source = imagerotate($source, 90, 0); break;
                }
                $width = imagesx($source);
                $height = imagesy($source);
            }
        }
        // ----------------------------------

        $thumb = imagecreatetruecolor($this->thb_width, $this->thb_height);
        $white = imagecolorallocate($thumb, 255, 255, 255);
        imagefilledrectangle($thumb, 0, 0, $this->thb_width, $this->thb_height, $white);

        // Smart Resize Centralizado
        $srcRatio = $width / $height;
        $dstRatio = $this->thb_width / $this->thb_height;
        
        if ($srcRatio > $dstRatio) {
            $tempH = $height; 
            $tempW = (int)($height * $dstRatio);
            $srcX = (int)(($width - $tempW) / 2);
            $srcY = 0;
        } else {
            $tempW = $width;
            $tempH = (int)($width / $dstRatio);
            $srcX = 0;
            $srcY = (int)(($height - $tempH) / 2);
        }

        imagecopyresampled($thumb, $source, 0, 0, $srcX, $srcY, $this->thb_width, $this->thb_height, $tempW, $tempH);
        
        $success = imagejpeg($thumb, $destPath, $this->quality);
        
        imagedestroy($source);
        imagedestroy($thumb);

        return $success;
    }
}
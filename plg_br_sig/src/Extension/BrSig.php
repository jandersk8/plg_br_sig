<?php
declare(strict_types=1);

namespace Br\Plugin\Content\BrSig\Extension;

defined('_JEXEC') or die;

use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\Event\SubscriberInterface;
use Joomla\Event\Event;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;

final class BrSig extends CMSPlugin implements SubscriberInterface
{
    private const PLG_TAG = 'gallery';
    private const PLG_NAME = 'br_sig';

    public static function getSubscribedEvents(): array
    {
        return ['onContentPrepare' => 'onContentPrepare'];
    }

    public function onContentPrepare(Event $event): void
    {
        [$context, $article, $params, $page] = array_values($event->getArguments());
        $app = Factory::getApplication();

        if ($app->isClient('administrator') || empty($article->text) || strpos($article->text, self::PLG_TAG) === false) {
            return;
        }

        $regex = "#{" . self::PLG_TAG . "}(.*?){/" . self::PLG_TAG . "}#is";
        if (!preg_match_all($regex, $article->text, $matches)) {
            return;
        }

        $pluginParams = $this->params;
        $rootFolder = trim($pluginParams->get('galleries_rootfolder', 'images'), '/');
        
        // Carregamento Manual do Helper
        $helperPath = __DIR__ . '/../Helper/GalleryHelper.php';
        if (file_exists($helperPath)) {
            require_once $helperPath;
        } else {
            return; 
        }
        
        if (!class_exists('\\Br\\Plugin\\Content\\BrSig\\Helper\\GalleryHelper')) {
             return;
        }

        $helper = new \Br\Plugin\Content\BrSig\Helper\GalleryHelper();

        $helper->thb_width  = (int) $pluginParams->get('thb_width', 300);
        $helper->thb_height = (int) $pluginParams->get('thb_height', 200);
        $helper->quality    = (int) $pluginParams->get('jpg_quality', 90);
        $helper->cacheTime  = (int) $pluginParams->get('cache_expire_time', 86400);
        $helper->pluginName = self::PLG_NAME;

        foreach ($matches[0] as $key => $match) {
            $tagContent = trim(strip_tags(preg_replace("/{.+?}/", "", $match)));
            $folderParts = explode(':', $tagContent);
            $galleryFolder = $folderParts[0];

            $srcImgFolder = $rootFolder . '/' . $galleryFolder;
            $galleryId = substr(md5($key . $srcImgFolder), 1, 10);

            $helper->srcImgFolder = $srcImgFolder;
            $helper->galleryId = $galleryId;

            $galleryHtml = $helper->renderGallery();

            if (!$galleryHtml) {
                $errorHtml = '<div style="background:#fff3cd; color:#856404; padding:15px; border:1px solid #ffeeba; border-radius:4px; margin:10px 0;">';
                // MENSAGEM EM INGLÊS:
                $errorHtml .= '<strong>BR SIG:</strong> Folder not found or empty: <code>' . $srcImgFolder . '</code></div>';
                $article->text = str_replace($match, $errorHtml, $article->text);
                continue;
            }

            $this->loadAssets($app->getDocument());
            $article->text = str_replace($match, $galleryHtml, $article->text);
        }
    }

    private function loadAssets($document): void
    {
        HTMLHelper::_('jquery.framework');

        // CSS: Apenas o essencial para a grade da galeria (Leve e Limpo)
        $style = '
            .brSigContainer { display: flex; flex-wrap: wrap; gap: 15px; padding: 0; list-style: none !important; margin-bottom: 20px; }
            .brSigItem { margin: 0; padding: 0; list-style: none !important; }
            .brSigLink { display: block; box-shadow: 0 2px 5px rgba(0,0,0,0.15); transition: transform 0.2s; border: 4px solid #fff; background: #fff; }
            .brSigLink:hover { transform: scale(1.02); z-index: 5; }
            .brSigLink img { display: block; max-width: 100%; height: auto; object-fit: cover; }
        ';
        $document->addStyleDeclaration($style);

        $document->addStyleSheet('https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css');
        $document->addScript('https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js');
        
        // JS: Configuração sem o botão 'thumbs'
        $js = "jQuery(document).ready(function($) { 
            $('[data-fancybox]').fancybox({ 
                loop: true, 
                animationEffect: 'zoom-in-out',
                buttons: [
                    'zoom',
                    'slideShow',
                    'fullScreen',
                    // 'thumbs', 
                    'close'
                ]
            }); 
        });";
        $document->addScriptDeclaration($js);
    }
}
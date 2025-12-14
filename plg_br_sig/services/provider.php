<?php
declare(strict_types=1);

defined('_JEXEC') or die;

use Joomla\CMS\Extension\PluginInterface;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Event\DispatcherInterface;
use Joomla\Registry\Registry;
use Br\Plugin\Content\BrSig\Extension\BrSig;

return new class implements ServiceProviderInterface
{
    public function register(Container $container): void
    {
        $container->set(
            PluginInterface::class,
            function (Container $container) {
                $dispatcher = $container->get(DispatcherInterface::class);
                $pluginElement = PluginHelper::getPlugin('content', 'br_sig');
                $params = new Registry($pluginElement->params ?? []);
                $config = ['params' => $params];

                return new BrSig($dispatcher, $config);
            }
        );
    }
};
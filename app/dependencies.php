<?php

declare(strict_types=1);

use App\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;
use Doctrine\ORM\EntityManager;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        LoggerInterface::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);

            $loggerSettings = $settings->get('logger');
            $logger = new Logger($loggerSettings['name']);

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
            $logger->pushHandler($handler);

            return $logger;
        },
        EntityManager::class => function (ContainerInterface $c): EntityManager {
            /** @var array $doctrine */
            $doctrine = $c->get(SettingsInterface::class)->get('doctrine');

            $config = \Doctrine\ORM\Tools\Setup::createAttributeMetadataConfiguration(
                $doctrine['metadata_dirs'],
                $doctrine['dev_mode'],
            );

            return EntityManager::create($doctrine['connection'], $config);
        },
    ]);
};

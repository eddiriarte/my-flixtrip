<?php

declare(strict_types=1);

use App\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;
use Doctrine\Common\Cache\Psr6\DoctrineProvider;
use Doctrine\ORM\EntityManager;
use EventSauce\EventSourcing\AggregateRootRepository;
use EventSauce\EventSourcing\MessageDispatcher;
use EventSauce\EventSourcing\MessageRepository;
use EventSauce\MessageRepository\DoctrineMessageRepository\DoctrineUuidV4MessageRepository as DoctrineMessageRepository;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Cache\Adapter as CacheAdapter;

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

            $cache = $doctrine['dev_mode'] ?
                DoctrineProvider::wrap(new CacheAdapter\ArrayAdapter()) :
                DoctrineProvider::wrap(new CacheAdapter\FilesystemAdapter(directory: $doctrine['cache_dir']));

            $config = \Doctrine\ORM\Tools\Setup::createAttributeMetadataConfiguration(
                $doctrine['metadata_dirs'],
                $doctrine['dev_mode'],
                cache: $cache
            );

            return EntityManager::create($doctrine['connection'], $config);
        },
        MessageDispatcher::class => function (ContainerInterface $c): MessageDispatcher {
            /** @var array $eventSauce */
            $eventSauce = $c->get(SettingsInterface::class)->get('eventsauce');

            $consumers = [];
            foreach ($eventSauce['consumers'] as $consumerClass) {
                $consumers[] = $c->get($consumerClass);
            }

            return new \EventSauce\EventSourcing\SynchronousMessageDispatcher(...$consumers);
        },
        MessageRepository::class => function (ContainerInterface $c): MessageRepository {
            /** @var array $doctrine */
            $doctrine = $c->get(SettingsInterface::class)->get('doctrine');
            $connection = \Doctrine\DBAL\DriverManager::getConnection($doctrine['connection']);
            $serializer = new \EventSauce\EventSourcing\Serialization\ConstructingMessageSerializer();

            return new DoctrineMessageRepository($connection, 'trip_events', $serializer);
        },
        AggregateRootRepository::class => function (ContainerInterface $c) {
            $messageRepository = $c->get(MessageRepository::class);
            $messageDispatcher = $c->get(MessageDispatcher::class);

            return new \EventSauce\EventSourcing\EventSourcedAggregateRootRepository(
                \App\Domain\Booking\Trip::class,
                $messageRepository,
                $messageDispatcher
            );
        },
    ]);
};

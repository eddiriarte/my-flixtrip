<?php

declare(strict_types=1);

namespace Tests;

use DI\ContainerBuilder;
use Slim\App;
use Slim\Factory\AppFactory;

trait ApplicationInstanceTrait
{
//    protected ?App $app = null;

    protected function getAppInstance(): App
    {
        if (!isset($this->app)) {
            $this->app = $this->initializeApplicationInstance();
        }

        return $this->app;
    }

    protected function destroyAppInstance(): void
    {
        $this->app = null;
    }

    private function initializeApplicationInstance(): App
    {
        // Instantiate PHP-DI ContainerBuilder
        $containerBuilder = new ContainerBuilder();

        // Container intentionally not compiled for tests.

        // Set up settings
        $settings = require __DIR__ . '/../app/settings.php';
        $settings($containerBuilder);

        // Set up dependencies
        $dependencies = require __DIR__ . '/../app/dependencies.php';
        $dependencies($containerBuilder);

        // Set up repositories
        $repositories = require __DIR__ . '/../app/repositories.php';
        $repositories($containerBuilder);

        // Build PHP-DI Container instance
        $container = $containerBuilder->build();

        // Instantiate the app
        AppFactory::setContainer($container);
        $app = AppFactory::create();

        // Register middleware
        $middleware = require __DIR__ . '/../app/middleware.php';
        $middleware($app);

        // Register routes
        $routes = require __DIR__ . '/../app/routes.php';
        $routes($app);

        return $app;
    }
}

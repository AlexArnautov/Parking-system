#!/usr/bin/env php
<?php
declare(strict_types=1);

use App\Parking\Infrastructure\Cli\EmulateCommand;
use Symfony\Component;
use Symfony\Component\Console\Application;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

require __DIR__ . '/vendor/autoload.php';

$container = new ContainerBuilder();
$fileLocator = new Component\Config\FileLocator(__DIR__ . '/config');
$fileLoader = new PhpFileLoader(container: $container, locator: $fileLocator);
$fileLoader->load('services.php');

$container->compile();


$application = new Application();
$application->addCommands([
    $container->get(EmulateCommand::class)
]);

$application->run();



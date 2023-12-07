<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Factory;

use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

final class LoggerFactory
{
    public static function getLogger(): LoggerInterface
    {
        $logger = new Logger('app');
        $logger->pushHandler(new StreamHandler('app.log', Level::Debug));
        return $logger;
    }
}
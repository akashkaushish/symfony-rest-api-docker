<?php
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Dotenv\Dotenv;

require __DIR__.'/vendor/autoload.php';

(new Dotenv())->loadEnv(__DIR__.'/.env');
$kernel = new \App\Kernel($_SERVER['APP_ENV'] ?? 'dev', (bool)($_SERVER['APP_DEBUG'] ?? true));
$kernel->boot();

$application = new Application($kernel);
return $kernel->getContainer()->get('doctrine')->getManager();

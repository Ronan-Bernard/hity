#!/usr/bin/php
<?php

require __DIR__ . '/vendor/autoload.php';
require 'db.php';

use Symfony\Component\Console\Application;
use Hity\Console\Command\SyncCommand;

$app = new Application('Hity', '0.1.0');

$app->add(new SyncCommand());

$app->run();



<?php

/*
 * This file is part of the Rest-Control package.
 *
 * (c) Kamil Szela <kamil.szela@cothe.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RestControl\Tests;

error_reporting(E_ALL);
ini_set('display_errors', 1);

$autoloadFiles = [
    'loader' => [
        __DIR__ . '/../vendor/autoload.php',
        __DIR__ . '/../../../autoload.php',
    ],
    'helpers' => [
        __DIR__ . '/../src/helpers.php',
        __DIR__ . '/../rest-control/rest-control/src/helpers.php',
    ],
];

$loadedFiles = null;

foreach($autoloadFiles as $autoloadFileName => $autoloadFileDirs) {
    foreach($autoloadFileDirs as $dir) {
        if(file_exists($dir)) {
            $loadedFiles[$autoloadFileName] = require_once $dir;
        }
    }
}

if(!$loadedFiles['loader']) {
    throw new \Exception('Can\'t find autoload.php. Please install dependencies via composer.');
}
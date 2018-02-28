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

$autoloadPaths = [
    __DIR__ . '/../vendor/autoload.php',
    __DIR__ . '/../../../autoload.php',

    __DIR__ . '/../src/helpers.php',
    __DIR__ . '/../rest-control/rest-control/src/helpers.php',
];

$loader = null;

foreach($autoloadPaths as $path) {
    if(file_exists($path)) {
        $loader = require_once $path;
    }
}

if(!$loader) {
    throw new \Exception('Can\'t find autoload.php. Please install dependencies via composer.');
}
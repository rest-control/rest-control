<?php

/*
 * This file is part of the Rest-Control package.
 *
 * (c) Kamil Szela <kamil.szela@cothe.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RestControl\Utils;

/**
 * Trait ChainTrait
 *
 * @package RestControl\Utils
 */
trait DirTrait
{
    /**
     * Returns proper namespace.
     *
     * @param string $dir
     *
     * @return string
     */
    public function parseDir($dir)
    {
        if(!is_string($dir)) {
            throw new \InvalidArgumentException('Dir must be a string.');
        }

        $dir = str_replace(' ', '', $dir);
        $parts = explode('.', $dir);
        $namespace = '';

        foreach($parts as $i => $part){

            if(!$part) {
                continue;
            }

            $namespace .= ucfirst($part);

            if(!isset($parts[$i + 1 ])) {
                continue;
            }

            $namespace .= '\\';
        }

        return $namespace;
    }

    /**
     * @param string      $dir
     * @param null|string $namespaceToRemove
     *
     * @return string
     */
    public function virtualDirToDir($dir, $namespaceToRemove = null)
    {
        $parts = explode('.', $dir);
        $path = '';

        foreach($parts as $i => $part) {
            $path .= ucfirst($part);

            if(!isset($parts[$i + 1])) {
                continue;
            }

            $path .= DIRECTORY_SEPARATOR;
        }

        if(!$namespaceToRemove) {
            return $path;
        }

        $path = str_replace(
            str_replace('\\', '/', $namespaceToRemove),
            '',
            $path
        );

        if($path[0] === DIRECTORY_SEPARATOR) {
            $path = substr($path, 1);
        }

        return $path;
    }
}
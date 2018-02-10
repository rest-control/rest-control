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
 * Class Arr
 *
 * @package RestControl\Utils
 */
class Arr
{
    /**
     * @param array $arr1
     * @param array $arr2
     * @param bool  $exactlyTheSame
     *
     * @return bool
     */
    public static function containsIn($arr1, $arr2, $exactlyTheSame = true)
    {
        if($exactlyTheSame && count(array_keys($arr1))
            !== count(array_keys($arr2))) {
            return false;
        }

        foreach($arr1 as $key => $value) {

            if(!isset($arr2[$key])) {
                return false;
            }

            if(is_array($value)) {

                if(!is_array($arr2[$key])) {
                    return false;
                }

                $recRes = self::containsIn($value, $arr2[$key], $exactlyTheSame);

                if(!$recRes) {
                    return false;
                }

                continue;
            }

            if($value !== $arr2[$key]) {
                return false;
            }
        }

        return true;
    }
}
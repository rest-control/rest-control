<?php

/*
 * This file is part of the Rest-Control package.
 *
 * (c) Kamil Szela <kamil.szela@cothe.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RestControl\TestCase;

use RestControl\TestCase\ExpressionLanguage\Expression;

/**
 * Class AbstractTestCase
 *
 * @package RestControl\Utils
 */
abstract class AbstractTestCase
{
    /**
     * @return Request
     */
    public function send()
    {
        return new Request();
    }

    /**
     * @param mixed $value
     * @param bool  $exactlyTheSame
     *
     * @return Expression
     */
    public function equalsTo($value, $exactlyTheSame = false)
    {
        return new Expression('equalsTo', [$value, $exactlyTheSame]);
    }

    /**
     * @param string $string
     *
     * @return Expression
     */
    public function containsString($string)
    {
        return new Expression('containsString', [$string]);
    }

    /**
     * @param string $string
     *
     * @return Expression
     */
    public function startsWith($string)
    {
        return new Expression('startsWith', [$string]);
    }

    /**
     * @param string $string
     *
     * @return Expression
     */
    public function endsWith($string)
    {
        return new Expression('endsWith', [$string]);
    }

    /**
     * @param mixed $lessThan
     * @param bool  $orEqual
     *
     * @return Expression
     */
    public function lessThan($lessThan, $orEqual = false)
    {
        return new Expression('lessThan', [$lessThan, $orEqual]);
    }
}

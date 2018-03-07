<?php

/*
 * This file is part of the Rest-Control package.
 *
 * (c) Kamil Szela <kamil.szela@cothe.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RestControl\TestCase\ExpressionLanguage;

class EndsWith implements ExpressionValidatorInterface
{
    const FILTER_NAME = 'endsWith';

    /**
     * @return string
     */
    public function getName()
    {
        return self::FILTER_NAME;
    }

    /**
     * @param Expression $expression
     * @param string     $value
     *
     * @return bool
     */
    public function check(Expression $expression, $value)
    {
        $expectedValue  = $expression->getParam(0);

        if(!is_string($value) || !is_string($expectedValue)) {
            return false;
        }

        $end = substr($value, -strlen($expectedValue));

        return $end == $expectedValue;
    }
}
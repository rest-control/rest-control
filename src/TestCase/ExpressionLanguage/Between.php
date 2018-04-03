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

class Between implements ExpressionValidatorInterface
{
    const FILTER_NAME = 'between';

    /**
     * @return string
     */
    public function getName()
    {
        return self::FILTER_NAME;
    }

    /**
     * @param Expression $expression
     * @param mixed      $value
     *
     * @return bool
     */
    public function check(Expression $expression, $value)
    {
        $minValue  = $expression->getParam(0);
        $maxValue  = $expression->getParam(1);

        if(null !== $minValue && $value < $minValue) {
            return false;
        }

        if(null !== $maxValue && $value > $maxValue) {
            return false;
        }

        return true;
    }
}
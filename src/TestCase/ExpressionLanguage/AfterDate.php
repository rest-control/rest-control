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

class AfterDate implements ExpressionValidatorInterface
{
    const FILTER_NAME = 'afterDate';

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
        $date = strtotime($expression->getParam(0));
        $date2 = strtotime($value);

        if(!$date || !$date2) {
            return false;
        }

        return $date2 > $date;
    }
}
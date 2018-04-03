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

class GreaterThan extends LessThan
{
    const FILTER_NAME = 'greaterThan';

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
    protected function checkScalarValue(Expression $expression, $value)
    {
        $greaterThan = $expression->getParam(0);
        $orEqual  = (bool) $expression->getParam(1, false);

        if(!$orEqual) {
            return $value > $greaterThan;
        }

        return $value >= $greaterThan;
    }
}
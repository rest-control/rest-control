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

class MoreThan extends LessThan
{
    const FILTER_NAME = 'moreThan';

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
        $moreThan = $expression->getParam(0);
        $orEqual  = (bool) $expression->getParam(1, false);

        if(!$orEqual) {
            return $value > $moreThan;
        }

        return $value >= $moreThan;
    }
}
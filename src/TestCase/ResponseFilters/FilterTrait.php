<?php

/*
 * This file is part of the Rest-Control package.
 *
 * (c) Kamil Szela <kamil.szela@cothe.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RestControl\TestCase\ResponseFilters;

use RestControl\TestCase\ExpressionLanguage\Expression;
use RestControl\TestCase\ExpressionLanguage\Validators;

/**
 * Trait FilterTrait
 *
 * @package RestControl\TestCase
 */
trait FilterTrait
{
    /**
     * @param $value
     * @param $expression
     *
     * @return bool
     */
    protected function checkExpression($value, $expression)
    {
        if($expression instanceof Expression) {
            return Validators::checkExpressionValidator($expression, $value);
        } else if(is_callable($expression)) {
            return $expression($value);
        }

        return false;
    }
}
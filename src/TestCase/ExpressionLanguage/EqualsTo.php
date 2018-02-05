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

/**
 * Class EqualsTo
 *
 * @package RestControl\TestCase\ExpressionLanguage
 */
class EqualsTo implements ExpressionValidatorInterface
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'equalsTo';
    }

    /**
     * @param Expression $expression
     * @param mixed      $value
     *
     * @return bool
     */
    public function check(Expression $expression, $value)
    {
        $expectedValue  = $expression->getParam(0);
        $exactlyTheSame = (bool) $expression->getParam(1, false);

        if($exactlyTheSame) {
            return $expectedValue === $value;
        }

        return $expectedValue == $value;
    }
}
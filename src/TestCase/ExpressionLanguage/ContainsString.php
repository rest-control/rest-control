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
 * Class ContainsString
 *
 * @package RestControl\TestCase\ExpressionLanguage
 */
class ContainsString implements ExpressionValidatorInterface
{
    const FILTER_NAME = 'containsString';

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
        $expectedValue  = $expression->getParam(0);

        return strpos($value, $expectedValue) !== false;
    }
}
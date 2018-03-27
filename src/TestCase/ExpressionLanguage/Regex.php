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

class Regex implements ExpressionValidatorInterface
{
    const FILTER_NAME = 'regex';

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
        $regex = $expression->getParam(0);

        if(empty($regex) || !is_string($regex)) {
            return false;
        }

        return (bool) preg_match($regex, $value);
    }
}
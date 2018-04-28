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

class OneOf implements ExpressionValidatorInterface
{
    const FILTER_NAME = 'oneOf';

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
        foreach($expression->getParams() as $subExpression) {

            if(!$subExpression instanceof Expression) {
                return false;
            }

            if(Validators::checkExpressionValidator($subExpression, $value)) {
                return true;
            }
        }

        return false;
    }
}
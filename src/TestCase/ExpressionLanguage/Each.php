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

use Psr\Log\InvalidArgumentException;

/**
 * Class Each
 *
 * @package RestControl\TestCase\ExpressionLanguage
 */
class Each implements ExpressionValidatorInterface
{
    const FILTER_NAME = 'each';

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
        $subExpressions = $expression->getParam(0);

        if(!is_array($subExpressions)) {
            $subExpressions = [$subExpressions];
        }

        foreach($subExpressions as $subExpression) {

            if(!$subExpression instanceof Expression) {
                throw new InvalidArgumentException('Sub expression must be instance of ' . Expression::class . '.');
            }

            if(!$this->checkExpression($subExpression, $value)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param Expression $subExpression
     * @param mixed      $value
     *
     * @return bool
     */
    protected function checkExpression(Expression $subExpression, $value)
    {
        if(is_scalar($value)) {
            return Validators::checkExpressionValidator(
                $subExpression,
                $value
            );
        }

        if(!is_array($value)) {
            return false;
        }

        foreach($value as $iValue) {
            if(!Validators::checkExpressionValidator(
                $subExpression,
                $iValue
            )) {
                return false;
            }
        }

        return true;
    }
}
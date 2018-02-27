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
 * Class LessThan
 *
 * @package RestControl\TestCase\ExpressionLanguage
 */
class LessThan implements ExpressionValidatorInterface
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'lessThan';
    }

    /**
     * @param Expression $expression
     * @param mixed      $value
     *
     * @return bool
     */
    public function check(Expression $expression, $value)
    {
        if(is_scalar($value)) {
            return $this->checkScalarValue($expression, $value);
        }

        if(!is_array($value)) {
            return false;
        }

        foreach($value as $iValue) {
            if(!$this->checkScalarValue($expression, $iValue)){
                return false;
            }
        }
    }

    /**
     * @param Expression $expression
     * @param mixed      $value
     *
     * @return bool
     */
    protected function checkScalarValue(Expression $expression, $value)
    {
        $lessThan = $expression->getParam(0);
        $orEqual  = (bool) $expression->getParam(1, false);

        if(!$orEqual) {
            return $value < $lessThan;
        }

        return $value <= $lessThan;
    }
}
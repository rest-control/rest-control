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
 * Class LessThan
 *
 * @package RestControl\TestCase\ExpressionLanguage
 */
class LessThan implements ExpressionValidatorInterface
{
    const FILTER_NAME = 'lessThan';

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
        if(!is_scalar($value)) {
            throw new InvalidArgumentException('[ExpressionLanguage][' . $this->getName() . '] Value must be scalar.');
        }

        return $this->checkScalarValue($expression, $value);
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
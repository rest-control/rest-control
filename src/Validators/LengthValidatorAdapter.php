<?php

/*
 * This file is part of the Rest-Control package.
 *
 * (c) Kamil Szela <kamil.szela@cothe.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RestControl\Validators;

use Zend\Validator\StringLength;

/**
 * Class LengthValidator
 */
class LengthValidatorAdapter extends AbstractValidatorAdapter
{
    /**
     * - options[0] min length
     * - options[1] max length
     *
     * {@inheritdoc}
     */
    public function isValid($value, array $options = [])
    {
        $validator = $this->getValidator(StringLength::class);
        $min   = $validator->getMin();
        $max   = $validator->getMax();

        if(isset($options[0]) && !empty($options[0])) {
            $validator->setMin($options[0]);
        }

        if(isset($options[1]) && !empty($options[1])) {
            $validator->setMax($options[1]);
        }

        $result = $validator->isValid($value);

        $validator->setMin($min);
        $validator->setMax($max);

        return $result;
    }
}
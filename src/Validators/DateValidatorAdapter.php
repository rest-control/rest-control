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

use Zend\Validator\Date;

/**
 * Class DateValidator
 */
class DateValidatorAdapter extends AbstractValidatorAdapter
{
    /**
     * - options[0] date format
     *
     * {@inheritdoc}
     */
    public function isValid($value, array $options = [])
    {
        /** @var Date $validator */
        $validator = $this->getValidator(Date::class);

        if(isset($options[0])) {
            $validator->setFormat($options[0]);
        }

        $result = $validator->isValid($value);
        $validator->setFormat('Y-m-d');

        return $result;
    }
}
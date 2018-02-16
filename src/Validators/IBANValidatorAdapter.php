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

use Zend\Validator\Iban;

/**
 * Class IBANValidator
 *
 * @package RestControl\Validators
 */
class IBANValidatorAdapter extends AbstractValidatorAdapter
{
    /**
     * - options[0] country code of IBAN
     *
     * {@inheritdoc}
     */
    public function isValid($value, array $options = [])
    {
        $validator = $this->getValidator(Iban::class);

        if(isset($options[0])) {
            $validator->setCountryCode($options[0]);
        }

        $result = $validator->isValid($value);
        $validator->setCountryCode(null);

        return $result;
    }
}
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

use Zend\Validator\Regex;

/**
 * Class RegexValidator
 */
class RegexValidatorAdapter extends AbstractValidatorAdapter
{
    /**
     * - options[0] pattern
     *
     * {@inheritdoc}
     */
    public function isValid($value, array $options = [])
    {
        $validator = $this->getValidator(Regex::class, false, ['//']);
        $pattern   = $validator->getPattern();

        if(isset($options[0])) {
            $validator->setPattern($options[0]);
        }

        $result = $validator->isValid($value);
        $validator->setPattern($pattern);

        return $result;
    }
}
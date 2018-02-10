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

use Zend\Validator\Isbn;

/**
 * Class ISBNValidator
 *
 * @package RestControl\Validators
 */
class ISBNValidatorAdapter extends AbstractValidatorAdapter
{
    /**
     * - options[0] type isbn (auto, 10, 13)
     *
     * {@inheritdoc}
     */
    public function isValid($value, array $options = [])
    {
        $validator = $this->getValidator(Isbn::class);
        $type      = $validator->getType();
        $separator = $validator->getSeparator();

        if(isset($options[0]) && !empty($options[0])) {
            $validator->setType($options[0]);
        }

        if(isset($options[1])) {
            $validator->setSeparator($options[1]);
        }

        $result = $validator->isValid($value);

        $validator->setType($type);
        $validator->setSeparator($separator);

        return $result;
    }
}
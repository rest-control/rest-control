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

use Zend\Validator\Ip;

/**
 * Class IPValidator
 */
class IPValidatorAdapter extends AbstractValidatorAdapter
{
    /**
     * - options[0] ip version (ipv4, ipv6)
     *
     * {@inheritdoc}
     */
    public function isValid($value, array $options = [])
    {
        $validator = $this->getValidator(Ip::class);
        $defaultOptions  = $validator->getOptions();

        if(isset($options[0])) {
            $validator->setOptions([
                'allowipv4'    => $options[0] === 'ipv4',
                'allowipv6'    => $options[0] === 'ipv6',
                'allowliteral' => false,
            ]);
        }

        $result = $validator->isValid($value);
        $validator->setOptions($defaultOptions);

        return $result;
    }
}
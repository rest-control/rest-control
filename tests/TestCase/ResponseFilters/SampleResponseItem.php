<?php

/*
 * This file is part of the Rest-Control package.
 *
 * (c) Kamil Szela <kamil.szela@cothe.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RestControl\Tests\TestCase\ResponseFilters;

use RestControl\Utils\AbstractResponseItem;

class SampleResponseItem extends AbstractResponseItem
{
    /**
     * @return array
     */
    public function getStructure()
    {
        return [
            'id'    => 'optional|uuid',
            'name'  => [
                'firstName' => 'optional',
                'lastName'  => 'optional',
            ],
            'settings' => [
                'sample' => [
                    'recursive' => 'optional',
                ],
            ],
            'email' => 'optional',
        ];
    }
}
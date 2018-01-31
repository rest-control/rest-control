<?php

/*
 * This file is part of the Rest-Control package.
 *
 * (c) Kamil Szela <kamil.szela@cothe.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RestControl\Loader\Annotations;

/**
 * @Annotation
 */
class TestAnnotation implements AnnotationInterface
{
    /**
     * Test case short title.
     *
     * @var string
     */
    public $title;

    /**
     * Long test case description.
     *
     * @var string
     */
    public $description;

    /**
     * Test case tags separated by spaces.
     *
     * @var string
     */
    public $tags;

    /**
     * @return string
     */
    public function getName()
    {
        return 'test';
    }
}

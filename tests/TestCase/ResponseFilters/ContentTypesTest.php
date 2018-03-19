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

use RestControl\TestCase\ResponseFilters\ContentTypes\AbstractContentType;
use RestControl\TestCase\ResponseFilters\ContentTypes\ApplicationOctetStream;
use RestControl\TestCase\ResponseFilters\ContentTypes\ApplicationVndAmazonEbook;
use RestControl\TestCase\ResponseFilters\ContentTypes\ApplicationXAbiWord;
use RestControl\TestCase\ResponseFilters\ContentTypes\ApplicationXBzip;
use RestControl\TestCase\ResponseFilters\ContentTypes\ApplicationXBzip2;
use RestControl\TestCase\ResponseFilters\ContentTypes\ApplicationXCsh;
use RestControl\TestCase\ResponseFilters\ContentTypes\AudioAac;
use PHPUnit\Framework\TestCase;
use RestControl\TestCase\ResponseFilters\ContentTypes\TextCss;
use RestControl\TestCase\ResponseFilters\ContentTypes\TextCsv;
use RestControl\TestCase\ResponseFilters\ContentTypes\VideoXMsvideo;


class ContentTypesTest extends TestCase
{
    protected $filters = [
        [
            AudioAac::class,
            ['audio/aac'],
            'contentTypeAudioAac',
        ],
        [
            ApplicationXAbiWord::class,
            ['application/x-abiword'],
            'contentTypeApplicationXAbiWord',
        ],
        [
            ApplicationOctetStream::class,
            ['application/octet-stream'],
            'contentTypeApplicationOctetStream',
        ],
        [
            VideoXMsvideo::class,
            ['video/x-msvideo'],
            'contentTypeVideoXMsvideo',
        ],
        [
            ApplicationVndAmazonEbook::class,
            ['application/vnd.amazon.ebook'],
            'contentTypeApplicationVndAmazonEbook',
        ],
        [
            ApplicationXBzip::class,
            ['application/x-bzip'],
            'contentTypeApplicationXBzip',
        ],
        [
            ApplicationXBzip2::class,
            ['application/x-bzip2'],
            'contentTypeApplicationXBzip2',
        ],
        [
            ApplicationXCsh::class,
            ['application/x-csh'],
            'contentTypeApplicationXCsh',
        ],
        [
            TextCss::class,
            ['text/css'],
            'contentTypeTextCss',
        ],
        [
            TextCsv::class,
            ['text/csv'],
            'contentTypeTextCsv',
        ],
    ];

    public function testFilters()
    {
        foreach($this->filters as $filter) {

            /** @var AbstractContentType $filterObject */
            $filterObject = new $filter[0];

            $this->assertInstanceOf(AbstractContentType::class, $filterObject);
            $this->assertSame($filter[1], $filterObject->getHttpContentTypes());
            $this->assertSame($filter[2], $filterObject->getName());
        }
    }
}
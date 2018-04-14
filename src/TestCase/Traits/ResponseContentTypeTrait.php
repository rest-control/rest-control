<?php

/*
 * This file is part of the Rest-Control package.
 *
 * (c) Kamil Szela <kamil.szela@cothe.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RestControl\TestCase\Traits;

use RestControl\TestCase\ExpressionLanguage\ContainsString;
use RestControl\TestCase\ExpressionLanguage\Expression;
use RestControl\TestCase\ResponseFilters\ContentTypeFilter;

trait ResponseContentTypeTrait
{
    /**
     * @param callable|Expression $expression
     *
     * @return $this
     */
    public function contentType($expression)
    {
        return $this->_add(ContentTypeFilter::FILTER_NAME, func_get_args());
    }

    /**
     * @return $this
     */
    public function contentTypeAudioAac()
    {
        return $this->contentTypeContains('audio/aac');
    }

    /**
     * @return $this
     */
    public function contentTypeApplicationXAbiword()
    {
        return $this->contentTypeContains('application/x-abiword');
    }

    /**
     * @return $this
     */
    public function contentTypeApplicationOctetStream()
    {
        return $this->contentTypeContains('application/octet-stream');
    }

    /**
     * @return $this
     */
    public function contentTypeVideoXMsvideo()
    {
        return $this->contentTypeContains('video/x-msvideo');
    }

    /**
     * @return $this
     */
    public function contentTypeApplicationVndAmazonEbook()
    {
        return $this->contentTypeContains('application/vnd.amazon.ebook');
    }

    /**
     * @return $this
     */
    public function contentTypeApplicationXBzip()
    {
        return $this->contentTypeContains('application/x-bzip');
    }

    /**
     * @return $this
     */
    public function contentTypeApplicationXBzip2()
    {
        return $this->contentTypeContains('application/x-bzip2');
    }

    /**
     * @return $this
     */
    public function contentTypeApplicationXCsh()
    {
        return $this->contentTypeContains('application/x-csh');
    }

    /**
     * @return $this
     */
    public function contentTypeTextCss()
    {
        return $this->contentTypeContains('text/css');
    }

    /**
     * @return $this
     */
    public function contentTypeTextCsv()
    {
        return $this->contentTypeContains('text/csv');
    }

    /**
     * @return $this
     */
    public function contentTypeTextJavascript()
    {
        return $this->contentTypeContains('text/javascript');
    }

    /**
     * @return $this
     */
    public function contentTypeApplicationMsword()
    {
        return $this->contentTypeContains('application/msword');
    }

    /**
     * @return $this
     */
    public function contentTypeApplicationVndOpenxmlformatsOfficedocumentWordprocessingmlDocument()
    {
        return $this->contentTypeContains('application/vnd.openxmlformats-officedocument.wordprocessingml.document');
    }

    /**
     * @return $this
     */
    public function contentTypeApplicationVndMsFontobject()
    {
        return $this->contentTypeContains('application/vnd.ms-fontobject');
    }

    /**
     * @return $this
     */
    public function contentTypeApplicationEpubZip()
    {
        return $this->contentTypeContains('application/epub+zip');
    }

    /**
     * @return $this
     */
    public function contentTypeApplicationEcmascript()
    {
        return $this->contentTypeContains('application/ecmascript');
    }

    /**
     * @return $this
     */
    public function contentTypeImageGif()
    {
        return $this->contentTypeContains('image/gif');
    }

    /**
     * @return $this
     */
    public function contentTypeTextHtml()
    {
        return $this->contentTypeContains('text/html');
    }

    /**
     * @return $this
     */
    public function contentTypeImageXIcon()
    {
        return $this->contentTypeContains('image/x-icon');
    }

    /**
     * @return $this
     */
    public function contentTypeTextCalendar()
    {
        return $this->contentTypeContains('text/calendar');
    }

    /**
     * @return $this
     */
    public function contentTypeApplicationJavaArchive()
    {
        return $this->contentTypeContains('application/java-archive');
    }

    /**
     * @return $this
     */
    public function contentTypeImageJpeg()
    {
        return $this->contentTypeContains('image/jpeg');
    }


    /**
     * @return $this
     */
    public function contentTypeApplicationJavascript()
    {
        return $this->contentTypeContains('application/javascript');
    }

    /**
     * @return $this
     */
    public function contentTypeApplicationJson()
    {
        return $this->contentTypeContains('application/json');
    }

    /**
     * @return $this
     */
    public function contentTypeAudioMidi()
    {
        return $this->contentTypeContains('audio/midi');
    }

    /**
     * @return $this
     */
    public function contentTypeVideoMpeg()
    {
        return $this->contentTypeContains('video/mpeg');
    }

    /**
     * @return $this
     */
    public function contentTypeApplicationVndAppleInstallerXml()
    {
        return $this->contentTypeContains('application/vnd.apple.installer+xml');
    }

    /**
     * @return $this
     */
    public function contentTypeApplicationVndOasisOpendocumentPresentation()
    {
        return $this->contentTypeContains('application/vnd.oasis.opendocument.presentation');
    }

    /**
     * @return $this
     */
    public function contentTypeApplicationVndOasisOpendocumentSpreadsheet()
    {
        return $this->contentTypeContains('application/vnd.oasis.opendocument.spreadsheet');
    }

    /**
     * @return $this
     */
    public function contentTypeApplicationVndOasisOpendocumentText()
    {
        return $this->contentTypeContains('application/vnd.oasis.opendocument.text');
    }

    /**
     * @return $this
     */
    public function contentTypeAudioOgg()
    {
        return $this->contentTypeContains('audio/ogg');
    }

    /**
     * @return $this
     */
    public function contentTypeVideoOgg()
    {
        return $this->contentTypeContains('video/ogg');
    }

    /**
     * @return $this
     */
    public function contentTypeApplicationOgg()
    {
        return $this->contentTypeContains('application/ogg');
    }

    /**
     * @return $this
     */
    public function contentTypeFontOtf()
    {
        return $this->contentTypeContains('font/otf');
    }

    /**
     * @return $this
     */
    public function contentTypeImagePng()
    {
        return $this->contentTypeContains('image/png');
    }

    /**
     * @return $this
     */
    public function contentTypeApplicationPdf()
    {
        return $this->contentTypeContains('application/pdf');
    }

    /**
     * @return $this
     */
    public function contentTypeApplicationVndMsPowerpoint()
    {
        return $this->contentTypeContains('application/vnd.ms-powerpoint');
    }

    /**
     * @return $this
     */
    public function contentTypeApplicationVndOpenxmlformatsOfficedocumentPresentationmlPresentation()
    {
        return $this->contentTypeContains('application/vnd.openxmlformats-officedocument.presentationml.presentation');
    }

    /**
     * @return $this
     */
    public function contentTypeApplicationXRarCompressed()
    {
        return $this->contentTypeContains('application/x-rar-compressed');
    }

    /**
     * @return $this
     */
    public function contentTypeApplicationRtf()
    {
        return $this->contentTypeContains('application/rtf');
    }

    /**
     * @return $this
     */
    public function contentTypeApplicationXSh()
    {
        return $this->contentTypeContains('application/x-sh');
    }

    /**
     * @return $this
     */
    public function contentTypeImageSvgXml()
    {
        return $this->contentTypeContains('image/svg+xml');
    }

    /**
     * @return $this
     */
    public function contentTypeApplicationXShockwaveFlash()
    {
        return $this->contentTypeContains('application/x-shockwave-flash');
    }

    /**
     * @return $this
     */
    public function contentTypeApplicationXTar()
    {
        return $this->contentTypeContains('application/x-tar');
    }

    /**
     * @return $this
     */
    public function contentTypeImageTiff()
    {
        return $this->contentTypeContains('image/tiff');
    }

    /**
     * @return $this
     */
    public function contentTypeApplicationTypescript()
    {
        return $this->contentTypeContains('application/typescript');
    }

    /**
     * @return $this
     */
    public function contentTypeFontTtf()
    {
        return $this->contentTypeContains('font/ttf');
    }

    /**
     * @return $this
     */
    public function contentTypeApplicationVndVisio()
    {
        return $this->contentTypeContains('application/vnd.visio');
    }

    /**
     * @return $this
     */
    public function contentTypeAudioXWav()
    {
        return $this->contentTypeContains('audio/x-wav');
    }

    /**
     * @return $this
     */
    public function contentTypeAudioWebm()
    {
        return $this->contentTypeContains('audio/webm');
    }

    /**
     * @return $this
     */
    public function contentTypeVideoWebm()
    {
        return $this->contentTypeContains('video/webm');
    }

    /**
     * @return $this
     */
    public function contentTypeImageWebp()
    {
        return $this->contentTypeContains('image/webp');
    }

    /**
     * @return $this
     */
    public function contentTypeFontWoff()
    {
        return $this->contentTypeContains('font/woff');
    }

    /**
     * @return $this
     */
    public function contentTypeFontWoff2()
    {
        return $this->contentTypeContains('font/woff2');
    }

    /**
     * @return $this
     */
    public function contentTypeApplicationXhtmlXml()
    {
        return $this->contentTypeContains('application/xhtml+xml');
    }

    /**
     * @return $this
     */
    public function contentTypeApplicationVndMsExcel()
    {
        return $this->contentTypeContains('application/vnd.ms-excel');
    }

    /**
     * @return $this
     */
    public function contentTypeApplicationVndOpenxmlformatsOfficedocumentSpreadsheetmlSheet()
    {
        return $this->contentTypeContains('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }

    /**
     * @return $this
     */
    public function contentTypeApplicationXml()
    {
        return $this->contentTypeContains('application/xml');
    }

    /**
     * @return $this
     */
    public function contentTypeApplicationVndMozillaXulXml()
    {
        return $this->contentTypeContains('application/vnd.mozilla.xul+xml');
    }

    /**
     * @return $this
     */
    public function contentTypeApplicationZip()
    {
        return $this->contentTypeContains('application/zip');
    }

    /**
     * @return $this
     */
    public function contentTypeVideo3gpp()
    {
        return $this->contentTypeContains('video/3gpp');
    }

    /**
     * @return $this
     */
    public function contentTypeAudio3gpp()
    {
        return $this->contentTypeContains('audio/3gpp');
    }

    /**
     * @return $this
     */
    public function contentTypeVideo3gpp2()
    {
        return $this->contentTypeContains('video/3gpp2');
    }

    /**
     * @return $this
     */
    public function contentTypeAudio3gpp2()
    {
        return $this->contentTypeContains('audio/3gpp2');
    }

    /**
     * @return $this
     */
    public function contentTypeApplicationX7zCompressed()
    {
        return $this->contentTypeContains('application/x-7z-compressed');
    }

    /**
     * @param string $string
     *
     * @return $this
     */
    protected function contentTypeContains($string)
    {
        return $this->contentType(new Expression(
            ContainsString::FILTER_NAME,
            [$string]
        ));
    }
}
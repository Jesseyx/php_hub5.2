<?php

namespace App\Phphub\Markdown;

use League\HTMLToMarkdown\HtmlConverter;
use Parsedown;
use Purifier;

class Markdown
{
    protected $htmlParser;
    protected $markdownParser;

    public function __construct()
    {
        $this->htmlParser = new HtmlConverter(['header_style' => 'atx']);
        $this->markdownParser = new Parsedown();
    }

    public function convertHtmlToMarkdown($html)
    {
        return $this->htmlParser->convert($html);
    }

    public function convertMarkdownToHtml($markdown)
    {
        $convertedHtml = $this->markdownParser->text($markdown);
        $convertedHtml = Purifier::clean($convertedHtml, 'user_topic_body');

        $convertedHtml = str_replace("<pre><code>", '<pre><code class=" language-php">', $convertedHtml);

        return $convertedHtml;
    }
}

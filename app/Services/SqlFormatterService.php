<?php

namespace App\Services;

use Doctrine\SqlFormatter\SqlFormatter;
use Doctrine\SqlFormatter\NullHighlighter;
use Doctrine\SqlFormatter\HtmlHighlighter;

class SqlFormatterService
{
    private SqlFormatter $formatter;
    private SqlFormatter $htmlFormatter;

    public function __construct()
    {
        $this->formatter = new SqlFormatter(new NullHighlighter());
        $this->htmlFormatter = new SqlFormatter(new HtmlHighlighter());
    }

    public function format(string $sql): string
    {
        return $this->formatter->format($sql);
    }

    public function highlight(string $sql): string
    {
        return $this->htmlFormatter->format($sql);
    }

    public function compress(string $sql): string
    {
        return $this->formatter->compress($sql);
    }
}

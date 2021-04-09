<?php

namespace App\Helpers;

use \DOMDocument;
use \DOMNode;

/**
 * ezyang/htmlpurifier sử dụng phức tạp.
 */
class HtmlPurifier
{
    // https://www.w3schools.com/TAGS/default.ASP
    const WHITE_LIST_TAGS = [
        '#text',
        // ------------------
        'a',
        'abbr',
        // 'address',
        'article',
        // 'aside',
        'audio',
        'b',
        'body',
        // 'base',
        'blockquote',
        'br',
        // 'button',
        // 'canvas',
        'caption',
        'cite',
        'code',
        'col',
        'colgroup',
        // 'data',
        // 'datalist',
        'dd',
        'del',
        'details',
        'dfn',
        // 'dialog',
        'div',
        'dl',
        'dt',
        'em',
        // 'embed',
        'fieldset',
        'figcaption',
        'figure',
        'footer',
        // 'form',
        'h1',
        'h2',
        'h3',
        'h4',
        'h5',
        'h6',
        // 'head',
        'header',
        'hr',
        'html',
        'i',
        // 'iframe',
        'img',
        // 'input',
        'ins',
        'kbd',
        'label',
        'legend',
        'li',
        // 'link',
        // 'main',
        // 'map',
        // 'mark',
        // 'meta',
        // 'meter',
        // 'nav',
        // 'noscript',
        // 'object',
        'ol',
        'optgroup',
        // 'option',
        'output',
        'p',
        'param',
        'picture',
        'pre',
        // 'progress',
        'q',
        'rp',
        'rt',
        'ruby',
        's',
        'samp',
        // 'script',
        'section',
        // 'select',
        'small',
        'source',
        'span',
        'strong',
        'style',
        'sub',
        'summary',
        'sup',
        'svg',
        'table',
        'tbody',
        'td',
        'template',
        // 'textarea',
        'tfoot',
        'th',
        'thead',
        'time',
        'title',
        'tr',
        'track',
        'u',
        'ul',
        'var',
        'video',
        'wbr',
    ];

    // XSS phải bỏ thuộc tính onload
    const WHITE_LIST_ATTRIBUTES = [
        'class',
        'src',
        'href',
        'controls',
        'alt',
        'style'
    ];


    /**
     * Bỏ các thẻ nguy hiểm, đề phòng lỗi XSS.
     */
    public static function purifyHtml(String $html)
    {
        $doc = new DOMDocument(); // '1.0', 'UTF-8'
        // LIBXML_HTML_NOIMPLIED tắt việc tự thêm các thẻ HTML, BODY
        // LIBXML_HTML_NODEFDTD tắt việc tự thêm doctype nếu không có
        @$doc->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8')); // , LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        self::purifyNode($doc);

        // $doc->formatOutput = true;
        // $doc->encoding = 'UTF-8';
        // return $doc->saveHTML();
        return self::getBodyHtml($doc);
    }

    public static function getBodyHtml(DOMDocument $doc)
    {
        // Chỉ lấy nội dung của thẻ body
        $html = $doc->saveHTML($doc->getElementsByTagName('body')->item(0));
        $html = str_replace(['<body>', '</body>'], ['', ''], $html);
        return $html;
    }

    public static function purifyNode(DOMNode $parentNode)
    {
        $shouldRemove = [];
        foreach ($parentNode->childNodes as $childNode) {
            if (!in_array($childNode->nodeName, self::WHITE_LIST_TAGS)) {
                // echo $childNode->nodeName . "\n";
                $shouldRemove[] = $childNode;
            } else {
                self::purifyAttribute($childNode);
                if ($childNode->hasChildNodes()) {
                    self::purifyNode($childNode);
                }
            }
        }

        foreach ($shouldRemove as $childNode) {
            $parentNode->removeChild($childNode);
            // TODO: Tạo text node
        }
    }

    public static function purifyAttribute(DOMNode $node)
    {
        if ($node->hasAttributes()) {
            $attrs = $node->attributes;
            foreach ($attrs as $i => $attr) {
                if (!in_array($attr->name, self::WHITE_LIST_ATTRIBUTES)) {
                    $node->removeAttribute($attr->name);
                }
            }
        }
    }

    public static function test()
    {
        $a = [
            /*
            '<svg onload="alert(document.cookie)"></svg><iframe src="abc.html"/><script>alert(1)</script><img src="abc.jpg" style="width: 100px"/>',
            '<img src=x onerror=alert(1)//>',
            '<svg><g/onload=alert(2)//<p>',
            '<p>abc<iframe//src=jAva&Tab;script:alert(3)>def</p>',
            '<TABLE><tr><td>HELLO</tr></TABL>',
            '<UL><li><A HREF=//google.com>click</UL>',
            */
            'Tiếng Việt, Nguyễn Văn Huyên, Cao Thị Thùy Dương, Nguyễn Anh Tuấn'
        ];

        foreach ($a as $html) {
            echo HtmlPurifier::purifyHtml($html) . "\n";
        }
    }
}

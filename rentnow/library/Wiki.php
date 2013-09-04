<?php

class Wiki
{
    protected $raw = '';
    protected $output = '';

    public function __construct( $raw )
    {
        $this->raw = $raw;
        $this->parse();
    }

    public function __toString()
    {
        return $this->output;
    }

    protected function parse()
    {
        $this->output = '';

        $body = $this->raw;
        $body = preg_replace("/(\r\n|\n|\r)/", "\n", $body);
        $body = preg_replace("/\n\n+/", "\n\n", $body);

        $parts = explode("\n\n", $body);
        $partsParsed = array();

        foreach ( $parts as $part )
        {
            $lines = explode("\n", "$part\n");
            $linesParsed = array();

            for ( $i = 0, $l = sizeof($lines); $i < $l; $i++ )
            {
                $line = $lines[$i];
                $lineParsed = $this->parseLine($line);

                if ( $i < $l - 1 || !( empty($lineParsed) ) )
                    $linesParsed[] = rtrim($lineParsed, "\n");
            }

            $partParsed = implode("\n", $linesParsed);

            if ( !( preg_match('/^<.*>/', $partParsed) ) )
                $partParsed = nl2br($partParsed);

            if ( !( preg_match('/^<(?:h1|h2|h3|h4|h5|h6|table|list|ol|ul|pre|select|form|hr|dl)/i', $partParsed) ) )
            {
                if ( preg_match('/^<(blockquote)>(.*)<\/(blockquote)>$/msi', $partParsed, $matches) )
                    $partParsed = "<{$matches[1]}>\n<p>" . preg_replace('/(\n){2,}/ms', "</p>\n<p>", rtrim($matches[2], "\n")) . "</p>\n</{$matches[3]}>";
                else
                    $partParsed = '<p>' . $partParsed . '</p>';
            }

            $partsParsed[] = $partParsed;
        }

        $this->output .= $this->parseInline(
            implode("\n\n", $partsParsed)
        ) . "\n";

        return $this->output;
    }

    protected function parseLine( $line )
    {
        static $regexps = array(
         // 'newline'   => '^$',
            'sections'  => '^(={1,3})\s(.*?)\s(={1,3})$',
            'quote'     => '^>\s(.*?)$',
            'lists'     => '^([\*\#]+)\s(.*?)$',
            'defls'     => '^([\;\:])\s*(.*?)$'
        );

        $return = '';
        $called = array();
        $line = rtrim($line);

        foreach ( $regexps as $func => $regexp )
        {
            if ( preg_match("/$regexp/i", $line, $matches) )
            {
                $called[$func] = true;

                if ( method_exists('Wiki', $func) )
                    $line = call_user_func(array('Wiki', $func), $matches);
                break;
            }
        }

        $return .= isset($called['quote']) ? '' : call_user_func(array('Wiki', 'quote'), false);
        $return .= isset($called['lists']) ? '' : call_user_func(array('Wiki', 'lists'), false);
        $return .= isset($called['defls']) ? '' : call_user_func(array('Wiki', 'defls'), false);
        $return .= $line;

        // $return .= isset($called['newline'])
        //     ? '' : call_user_func(array('Wiki', 'newline'), false);

        return $return;
    }

    protected function parseInline( $text )
    {
        //
        // static $regexps = array(
        //     'bold'      => '\*([^\[\]]*?)\*',
        //     'italic'    => '_([^\[\]]*?)_'
        // );
        //
        // foreach ( $regexps as $func => $regexp )
        // {
        //     if ( !( method_exists('Wiki', $func) ) )
        //         continue;
        //
        //     if ( preg_match_all("/$regexp/i", $text, $matches) )
        //     {
        //         for ( $i = 0, $r = sizeof($matches), $l = sizeof($matches[0]); $i < $l; $i++ )
        //         {
        //             $params = array();
        //
        //             for ( $e = 0; $e < $r; $e++ )
        //                 $params[] = $matches[$e][$i];
        //
        //             $search  = $matches[0][$i];
        //             $replace = call_user_func(array('Wiki', $func), $params);
        //
        //             $text = str_replace($search, $replace, $text);
        //         }
        //     }
        // }
        //
        // return $text;
        //

        static $regexps = array(
            '/\*([^\[\]]*?)\*/i' => '<strong>$1</strong>', # Bold
            '/_([^\[\]]*?)_/i'   => '<em>$1</em>',         # Italic
            '/`([^\[\]]*?)`/i'   => '<tt>$1</tt>',         # Code
            '/--([^\[\]]*?)--/i' => '<span style="text-decoration: line-through;">$1</span>', # Strikeout

            # Images
            '/(?<=^|[\t\r\n >\(\[\]\|])([a-z]+?:\/\/[\w\-]+\.([\w\-]+\.)*\w+(:[0-9]+)?(\/[^ "\'\(\n\r\t<\)\[\]\|]*)?)((?<![,\.])|(?!\s))\.(png|gif|jpg|jpeg)/i' => '<img src="$1.$6" alt="" />',

            # Links w/Text
            '/\[((www|ftp)\.([\w\-]+\.)*[\w]+(:[0-9]+)?(\/[^ \"\'\(\n\r\t<\)\[\]\|]*)?) (.*?)\]/' => '<a href="http://$1">$6</a>',
            '/(?<=^|[\t\r\n >\(\[\]\|])\[([a-z]+?:\/\/[\w\-]+\.([\w\-]+\.)*\w+(:[0-9]+)?(\/[^ "\'\(\n\r\t<\)\[\]\|]*)?)((?<![,\.])|(?!\s)) (.*?)\]/i' => '<a href="$1">$6</a>',

            # Auto-Links
            '/(?<=^|[\t\r\n >\(\[\]\|])([a-z]+?:\/\/[\w\-]+\.([\w\-]+\.)*\w+(:[0-9]+)?(\/[^ "\'\(\n\r\t<\)\[\]\|]*)?)((?<![,\.])|(?!\s))/i' => '<a href="$1">$1</a>',
            '/([\t\r\n >\(\[\|])(www|ftp)\.(([\w\-]+\.)*[\w]+(:[0-9]+)?(\/[^ \"\'\(\n\r\t<\)\[\]\|]*)?)/i' => '$1<a href="http://$2.$3">$2.$3</a>'
        );

        return preg_replace(array_keys($regexps), array_values($regexps), $text);
    }

    protected static function newline( $matches )
    {
        return "\n";
    }

    protected static function sections( $matches )
    {
        $level = strlen($matches[1]);
        $content = $matches[2];

        return "<h{$level}>{$content}</h{$level}>";
    }

    protected static function quote( $matches )
    {
        static $active = false;

        if ( false === $matches )
        {
            $return = '';

            if ( true === $active )
            {
                $active = false;
                $return = "</blockquote>";
            }

            return $return;
        }

        $return = '';
        $content = $matches[1];

        if ( false === $active )
            $return .= '<blockquote>';

        $active = true;
        $return .= $content;

        return $return;
    }

    protected static function lists( $matches )
    {
        static $types = array(
            '*' => 'ul',
            '#' => 'ol'
        );

        static $level = 0;
        static $actives = array();

        $return = '';

        # if ( false === $matches )
        #     return $return;

        if ( false === $matches )
            $current = 0;
        else
        {
            $chars   = $matches[1];
            $content = $matches[2];
            $current = strlen($chars);
        }

        while ( $current != $level )
        {
            $enter = '';

            if ( $current > $level )
            {
                $char = substr($chars, -1);
                $type = $types[$char];

                $level++;
                $spaces = $level > 1 ? str_repeat(' ', ( $level - 1 ) * 2) : '';

                array_push($actives, $type);
            }
            else
            {
                $spaces = $level > 1 ? str_repeat(' ', ( $level - 1 ) * 2) : '';
                $type = '/' . array_pop($actives);
                $level--;
            }

            $return .= "$spaces<$type>\n";
        }

        if ( false === $matches )
            return $return;

        $spaces = $current > 1 ? str_repeat(' ', ( $current - 1 ) * 2) : '';
        $return .= "$spaces  <li>$content</li>";

        return $return;
    }

    protected static function defls( $matches )
    {
        static $active = false;

        $return = '';

        if ( false === $matches )
        {
            if ( $active )
            {
                $active = false;
                $return = "</dl>\n";
            }

            return $return;
        }

        if ( !( $active ) )
        {
            $active = true;
            $return = "<dl>\n";
        }

        switch ( $matches[1] )
        {
            case ';':
                $term = $matches[2];

                if ( strpos($term, ':') !== false )
                {
                    list($term, $definition) = explode(':', $term, 2);

                    $term = rtrim($term);
                    $definition = ltrim($definition);

                    $return .= "  <dt>$term</dt>\n  <dd>$definition</dd>";
                }
                else
                    $return .= "  <dt>$term</dt>";
                break;

            case ':':
                $definition = $matches[2];
                $return .= "  <dd>$definition</dd>";
                break;
        }

        return $return;
    }
}
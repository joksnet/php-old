<?php

class String
{
    /**
     * Print a text.
     *
     * @param string $string
     */
    public static function e( $string )
    {
        $string = self::entities($string);

        echo $string;
    }

    /**
     * Print a text in raw format.
     *
     * @param string $string
     */
    public static function eraw( $string )
    {
        echo $string;
    }

    /**
     * Translate all html entities but tags.
     *
     * @param string $string
     * @return string
     */
    public static function entities( $string )
    {
        $entities = get_html_translation_table(HTML_ENTITIES);
        $entitiesTags = get_html_translation_table(HTML_SPECIALCHARS);

        $chars = array_diff($entities, $entitiesTags);

        $string = strtr($string, $chars);
        $string = preg_replace('/&(?![A-Za-z]{0,4}\w{2,3};|#[0-9]{2,3};)/', '&amp;', $string);

        return $string;
    }

    public static function random( $length )
    {
        $key = '';
        $pattern = "bcdfghjklmnpqrstvwxyzBCDFGHJKLMNPQRSTVWXYZ";

        for ( $i = 0; $i < $length; $i++ )
            $key .= $pattern{rand(0, strlen($pattern))};

        return $key;
    }
}

/**
 * Alias for String::e().
 *
 * @param string $string
 */
function e( $string )
{
    String::e($string);
}

/**
 * Alias for String::eraw().
 *
 * @param string $string
 */
function eraw( $string )
{
    String::eraw($string);
}

/**
 * Validate email address.
 *
 * @param string $email
 * @return boolean
 */
function isEmail( $email )
{
    if ( strstr($email, '@') && strstr($email, '.') )
        return ( preg_match("/^([a-z0-9+_]|\\-|\\.)+@(([a-z0-9_]|\\-)+\\.)+[a-z]{2,6}\$/i", $email) ) ? true : false;

    return false;
}

/**
 * Validate TLDs.
 *
 * @param string $tld
 * @return boolean
 */
function isTLD( $tld )
{
    return ( preg_match("/^[a-z.]{2,6}+\$/i", $tld) ) ? true : false;
}

/**
 * Paginate function.
 *
 * @param string $url
 * @param integer $count
 * @param integer $perPage
 * @param integer $onPage
 * @return string
 */
function pagination( $url, $count, $perPage, $onPage = 1 )
{
    $seperator = '';

    $perPage = ( $perPage <= 0 ) ? 1 : $perPage;
    $pages = ceil($count / $perPage);

    if ( $pages == 1 || !( $count ) )
        return false;

    $string = ( $onPage == 1 ) ? '<span class="current">1</span>' : '<a href="' . $url . '">1</a>';

    if ( $pages > 5 )
    {
        $countStart = min( max(1, $onPage - 4), $pages - 5 );
        $countEnd = max( min($pages, $onPage + 4), 6 );

        $string .= ( $countStart > 1 ) ? '<span> ... </span>' : $seperator;

        for ( $i = $countStart + 1; $i < $countEnd; $i++ )
        {
            $string .= ( $i == $onPage ) ? '<span class="current">' . $i . '</span>' : '<a href="' . $url . "/page" . $i . '/">' . $i . '</a>';

            if ( $i < $countEnd - 1 )
                $string .= $seperator;
        }

        $string .= ( $countEnd < $pages ) ? '<span> ... </span>' : $seperator;
    }
    else
    {
        $string .= $seperator;

        for ( $i = 2; $i < $pages; $i++ )
        {
            $string .= ( $i == $onPage ) ? '<span class="current">' . $i . '</span>' : '<a href="' . $url . "page" . $i . '/">' . $i . '</a>';

            if ( $i < $pages )
                $string .= $seperator;
        }
    }

    $string .= ( $onPage == $pages ) ? '<span class="current">' . $pages . '</span>' : '<a href="' . $url . "page" . $pages . '/">' . $pages . '</a>';

    if ( 1 )
    {
        if ( $onPage != 1 )
            $string = '<a href="' . $url . "page" . ( $onPage - 1 ) . '/">' . __('Previous') . '</a>' . $string;
        else
            $string = '<span class="disabled">' . __('Previous') . '</span>' . $string;

        if ( $onPage != $pages )
            $string .= '<a href="' . $url . "page" . ( $onPage + 1 ) . '/">' . __('Next') . '</a>';
        else
            $string .= '<span class="disabled">' . __('Next') . '</span>';
    }

    return $string;
}
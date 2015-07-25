<?php

namespace GabiU\JobeetBundle\Utils;

use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;

class Jobeet
{
    static public function getCurrentDate()
    {
        return date("Y-m-d H:i:s", time());
    }

    static public function getPaginator(Query $qb)
    {
        return new Paginator($qb);
    }

    static public function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\\pL\d]+~u', '-', $text);

        // trim
        $text = trim($text, '-');

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // lowercase
        $text = strtolower($text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        if (empty($text))
        {
            return 'n-a';
        }

        return $text;
    }
}
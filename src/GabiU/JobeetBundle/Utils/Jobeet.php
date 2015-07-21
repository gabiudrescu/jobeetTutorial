<?php

namespace GabiU\JobeetBundle\Utils;

class Jobeet
{
    static public function slugify($text)
    {
        $text = preg_replace('/W+/', '-', $text);

        // trim and lowercase
        $text = strtolower(trim($text, '-'));

        return $text;
    }
}
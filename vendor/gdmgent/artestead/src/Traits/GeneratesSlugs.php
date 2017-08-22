<?php

namespace GdmGent\Artestead\Traits;

trait GeneratesSlugs
{
    /**
     * Generate a slug from a given string.
     *
     * @param  string  $string
     * @param  bool    $isDomain
     * @return string
     */
    protected function slug($string, $isDomain = false)
    {
        $pattern = ($isDomain ? '/[^A-Za-z0-9-\.]+/' : '/[^A-Za-z0-9-]+/');

        return strtolower(trim(preg_replace($pattern, '-', $string)));
    }
}

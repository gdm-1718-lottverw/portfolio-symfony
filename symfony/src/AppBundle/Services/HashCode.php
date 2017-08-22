<?php
/**
 * Created by PhpStorm.
 * User: Lotte
 * Date: 14/07/17
 * Time: 11:41
 */

namespace AppBundle\Services;


class HashCode
{
    /**
     * Generate a hashcode.
     *
     * @param $length
     * @return string
     */
    public function generateHashCode($length){
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }

}
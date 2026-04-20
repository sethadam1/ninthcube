<?php

namespace NinthCube;

class Encryption
{
    /*
     * encryption conversion
     */
    public static function convert_encryption($string): ?string
    {
        // legacy encryption fix
        if (substr($string, 0, 5) == '$fish') {
            return $string;
        }
        return self::encrypt($string);
    }

    /*
     * custom encryption
     */
    public static function encrypt($string): ?string
    {
        if (!defined('CRYPT_KEY') || !defined('CRYPT_IV')) {
            return $string;
        }
        if (empty($string)) {
            return '';
        }
        $new_string = preg_replace('/(\$)([a-zA-Z]+)(\$v=)([0-9]+)(\$)/', "", $string);
        // if the encryption prefix matched, it was already encrypted
        if ($new_string != $string) {
            return $string;
        }
        // it was not already encrypted, encrypt it now
        $cipher_method = "AES-128-CTR";
        $encrypted = openssl_encrypt($string, $cipher_method, CRYPT_KEY, 0, CRYPT_IV);
        $version = 1;
        $prefix = '$fish$v=' . $version . '$';
        return $prefix . $encrypted;
    }

    /*
     * custom decryption
     */
    public static function decrypt($string): ?string
    {
        if (!defined('CRYPT_KEY') || !defined('CRYPT_IV')) {
            return $string;
        }
        if (empty($string)) {
            return '';
        }
        $new_string = preg_replace('/(\$)([a-zA-Z]+)(\$v=)([0-9]+)(\$)/', "", $string);
        if ($new_string == $string) {
            return $string;
        }
        $cipher_method = "AES-128-CTR";
        $decrypted = openssl_decrypt($new_string, $cipher_method, CRYPT_KEY, 0, CRYPT_IV);
        return $decrypted;
    }
}

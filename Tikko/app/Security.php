<?php

namespace App;

Class Security
{

    private $key = "NOTVERYSECURE";
    private $cipher = "aes-128-gcm";
    private $tag;

    private function encrypt($message)
    {
        $ivlen = openssl_cipher_iv_length($this->cipher);
        $iv = openssl_random_pseudo_bytes($ivlen);
        $result = openssl_encrypt($message, $this->cipher, $this->key, $options = 0, $iv, $tag);
        $this->tag = $tag;
        return $result;
    }

    private function decrypt($ciphertext)
    {

        if (!empty($ciphertext)) {
            $result = array();
            foreach ($ciphertext as $account) {

                $ivlen = openssl_cipher_iv_length($this->cipher);
                $iv = openssl_random_pseudo_bytes($ivlen);
                $result[] = openssl_decrypt($account, $this->cipher, $this->key, $options = 0, $iv, $this->tag);
            }
            return $result;
        }
    }
}

?>
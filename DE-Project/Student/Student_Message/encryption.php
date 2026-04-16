<?php

// $messgae -> Input text you want to protect
// $key -> Secret password used for encryption
// $cipher -> Encryption algorithm used, AES = Advanced Encryption Standard, 128 = key size (128-bit), CTR = mode (Counter mode)
// $iv -> Adds randomness to encryption

    function encryptMessage($message) {
        $key = "my_secret_key_123";
        $cipher = "AES-128-CTR";
        $iv = "1234567891011121";

        // Converts plain text → encrypted string
        return openssl_encrypt($message, $cipher, $key, 0, $iv);
    }

    function decryptMessage($encryptedMessage) {
        $key = "my_secret_key_123";
        $cipher = "AES-128-CTR";
        $iv = "1234567891011121";

        // Converts encrypted → original text
        return openssl_decrypt($encryptedMessage, $cipher, $key, 0, $iv);
    }
?>
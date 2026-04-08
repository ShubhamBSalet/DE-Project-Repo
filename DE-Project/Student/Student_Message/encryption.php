<?php

function encryptMessage($message) {
    $key = "my_secret_key_123";
    $cipher = "AES-128-CTR";
    $iv = "1234567891011121";

    return openssl_encrypt($message, $cipher, $key, 0, $iv);
}

function decryptMessage($encryptedMessage) {
    $key = "my_secret_key_123";
    $cipher = "AES-128-CTR";
    $iv = "1234567891011121";

    return openssl_decrypt($encryptedMessage, $cipher, $key, 0, $iv);
}
?>
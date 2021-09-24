<?php

if (!function_exists('removeUtf8Bom')) {
    function removeUtf8Bom($text)
    {
        $bom = pack('H*', 'EFBBBF');
        $text = preg_replace("/^$bom/", '', $text);
        return utf8_encode($text);
    }
}

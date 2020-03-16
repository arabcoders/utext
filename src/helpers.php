<?php declare(strict_types=1);

use arabcoders\utext\UText;

if (!function_exists('u')) {
    function u(string $text): UText
    {
        return new UText($text);
    }
}
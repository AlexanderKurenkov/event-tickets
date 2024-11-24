<?php
function generateBarcode(int $maxAttempts = 10): string
{
    return str_pad(random_int(0, PHP_INT_MAX), 19, "0", STR_PAD_LEFT);
}

function randomBool(): bool
{
    return mt_rand(0, 1) === 1;
}

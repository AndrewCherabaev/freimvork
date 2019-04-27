<?php

function request() {
    return \Core\Http\Request::getInstance();
}

function dd() {
    \var_dump(\func_get_args());die;
}

function array_has($array, $keystring)
{
    $keypath = \explode('.', $keystring);
    $value = $array;
    foreach ($keypath as $key) {
        if (!\array_key_exists($key, $value)) {
            return false;   
        }

        $value = $value[$key];
    }

    return true;
}

function array_get($array, $keystring, $default = null)
{
    $keypath = \explode('.', $keystring);
    $value = $array;
    foreach ($keypath as $key) {
        if (!\array_key_exists($key, $value)) {
            return $default;   
        }

        $value = $value[$key];
    }

    return $value;
}

function array_set(&$array, $keystring, $value = null)
{
    $keypath = \explode('.', $keystring);
    $link = &$array;
    foreach ($keypath as $key) {
        if (!\array_key_exists($key, $link)) {
            $link[$key] = [];
        }
        $link = &$link[$key];
    }
    $link = $value;
    unset($link);
}
<?php

function request() {
    return \Core\Http\Request::getInstance();
}

function dd() {
    call_user_func_array('var_dump', func_get_args());die;
}
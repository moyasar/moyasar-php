<?php

namespace Moyasar\Contracts;

interface HttpClient
{
    function request($method, $url, $data = null);
    function get($url, $data = null);
    function post($url, $data = null);
    function put($url, $data = null);
    function patch($url, $data = null);
    function delete($url, $data = null);
}
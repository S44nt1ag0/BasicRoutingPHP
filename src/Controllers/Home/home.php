<?php

namespace SimpleApi\Controllers\Home;

class Home
{
    public function index()
    {
        echo json_encode(["message" => "Welcome to the Simple API"]);
    }
}


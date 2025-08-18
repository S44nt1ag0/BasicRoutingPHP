<?php

namespace SimpleApi;
use SimpleApi\Controllers\UserController;
class Application
{
  public function start()
  {
    $router = new Router();
    $userController = new UserController();

    $router->create("GET", "/", [$userController, "index"]);
    $router->create("POST", "/user", [$userController, "createUser"]);
    $router->create("POST", "/login", [$userController, "login"]);
    $router->create("GET", "/v1/dash", [$userController, "dashBoard"]);
    $router->init();

  }
}

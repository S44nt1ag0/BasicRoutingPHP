<?php

namespace SimpleApi;

use SimpleApi\Controllers\Users\CreateUser;
use SimpleApi\Controllers\Users\LoginUser;
use SimpleApi\Controllers\Users\DashUser;
use SimpleApi\Controllers\Users\DeleteUser;
use SimpleApi\Repositories\UserRepository;
use SimpleApi\Controllers\Home\Home;
class Application
{
  public function start()
  {
    $router = new Router();

    $router->create("GET", "/", [new Home(), 'index']);
    $router->create("POST", "/user", [new CreateUser(), 'index']);
    $router->create("POST", "/login", [new LoginUser(new UserRepository()), "index"]);
    $router->create("GET", "/v1/dash", [new DashUser(), 'index']);
    $router->create("DELETE", "/admin/delete", [new DeleteUser(new UserRepository()), "index"]);
    $router->init();

  }
}

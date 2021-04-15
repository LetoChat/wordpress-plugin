<?php

namespace LetoChat\PublicView\Business\Model\Api;

interface UserCartInterface
{
    public function registerRoutes();

    public function getUserCartPermissionsCheck($request);

    public function getUserCart($request);

    public function getUsersCartPermissionsCheck($request);

    public function getUsersCart($request);
}
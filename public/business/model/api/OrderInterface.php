<?php

namespace LetoChat\PublicView\Business\Model\Api;

interface OrderInterface
{
    public function registerRoutes();

    public function getOrderPermissionsCheck($request);

    public function getOrder($request);

    public function getOrdersPermissionsCheck($request);

    public function getOrders($request);
}
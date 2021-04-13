<?php

namespace LetoChat\AdminView\Business;

use LetoChat\AdminView\Core\AbstractFactory;
use LetoChat\AdminView\Business\Model\AdminPage;
use LetoChat\AdminView\Business\Model\AdminPageInterface;

class LetoChatBusinessFactory extends AbstractFactory
{
    public function createAdminPage()
    {
        return new AdminPage();
    }
}

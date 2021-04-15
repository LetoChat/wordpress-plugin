<?php

namespace LetoChat\AdminView\Business\Model;

interface AdminPageInterface
{
    public function adminMenu();

    public function adminPageContent();

    public function connectToLetoChat();

    public function switcherAjaxCall();
}

<?php

namespace LetoChat\AdminView\Business;

interface LetoChatFacadeInterface
{
    public function adminMenu();

    public function connectToLetoChat();

    public function switcherAjaxCall();
}

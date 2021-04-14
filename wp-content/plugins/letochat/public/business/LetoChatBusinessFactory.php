<?php

namespace LetoChat\PublicView\Business;

use LetoChat\PublicView\Business\Model\Widget;
use LetoChat\PublicView\Core\AbstractFactory;

class LetoChatBusinessFactory extends AbstractFactory
{
    public function createWidget()
    {
        return new Widget($this->getConfig());
    }
}

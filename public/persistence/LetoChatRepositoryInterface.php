<?php

namespace LetoChat\PublicView\Persistence;

interface LetoChatRepositoryInterface
{
    /**
     * @return array
     */
    public function getUsersIdFromWoocommerceSessions();
}

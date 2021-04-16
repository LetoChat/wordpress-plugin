<?php

namespace LetoChat\PublicView\Persistence;

class LetoChatRepository implements LetoChatRepositoryInterface
{
    /**
     * @return array
     */
    public function getUsersIdFromWoocommerceSessions()
    {
        global $wpdb;

        $query = "SELECT session_value, session_key FROM {$wpdb->prefix}woocommerce_sessions";

        $sessions = $wpdb->get_results($wpdb->prepare($query));

        if ($sessions === null) {
            return [];
        }

        $usersId = [];

        foreach ($sessions as $session) {
            $usersId[] = $session->session_key;
        }

        return $usersId;
    }
}

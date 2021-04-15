<?php

    namespace LetoChat;

    class Connector {

        private $url = 'https://api.letochat.com';

        private $channelId;
        private $channelSecret;
        private $authSecret;
        private $endpoints = [];

        private $error = null;

        /**
         * init
         */
        public function __construct( $channelId, $channelSecret, $authSecret, $endpoints = [] ){

            $this->channelId        = $channelId;
            $this->channelSecret    = $channelSecret;
            $this->authSecret       = $authSecret;
            $this->endpoints        = $endpoints;

        }

        /**
         * check if we can connect to LetoChat platform
         */
        public function check(){

            $ch = curl_init( $this->url .'/channel/check');

            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
                'channel_id'        => $this->channelId,
                'channel_secret'    => $this->channelSecret,
                'auth_secret'       => $this->authSecret
            ]));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            
            $response   = curl_exec($ch);
            $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            
            curl_close($ch);

            if( $statusCode == 200 ){
                return true;
            }

            $response = json_decode($response, true);

            if( isset($response['message']) ){
                $this->setError($response['message']);
            }

            return false;

        }

        /**
         * connect 
         */
        public function connect(){

            $ch = curl_init( $this->url .'/channel/connect');

            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
                'channel_id'        => $this->channelId,
                'channel_secret'    => $this->channelSecret,
                'auth_secret'       => $this->authSecret,
                'endpoints'         => $this->endpoints
            ]));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            
            $response   = curl_exec($ch);
            $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            
            curl_close($ch);

            if( $statusCode == 200 ){
                return true;
            }

            $response = json_decode($response, true);

            if( isset($response['message']) ){
                $this->setError($response['message']);
            }

            return false;

        }

        /**
         * get last error
         */
        public function getError(){
            return $this->error;
        }

        private function setError( $message ){
            if( trim($message) != '' )
                $this->error = $message;
        }

    }
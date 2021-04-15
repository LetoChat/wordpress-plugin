<?php

    namespace LetoChat;
	
	use Exception;
	
    class Widget {

        private $channelId;
        private $channelSecret;

        private $info 	= [];
		private $custom = [];
        private $events = [];

		private $availableInfos = [
			'id', 'logged',
            'name', 'avatar', 
			'email', 'phone',
			'company_name', 'company_position', 
		];
		
        /**
         * init
         */
        public function __construct( $channelId, $channelSecret = null ){

            $this->channelId = $channelId;
            $this->channelSecret = $channelSecret;

        }

        /**
         * add single info to current visitor
         */
        public function info( $name, $value ){

			// checks
			if( ! is_string($name) )
				throw new Exception('Invalid value type for info name. Info name must be string type.');
			
            $name = strtolower($name);

			if( in_array($name, ['custom']) )
				throw new Exception('You can not add `custom` info. If you want to add a custom info use instead Widget::custom');
			
			if( in_array($name, ['event', 'events']) )
				throw new Exception('You can not add `event` info. If you want to add an event use instead Widget::event');
						
			if( ! in_array($name, $this->availableInfos) )
				throw new Exception('Invalid info `'.$name.'`. Info must one of: '.implode(', ', $this->availableInfos).'.');
		
			if( ! is_string($value) && ! is_numeric($value) && ! is_bool($value) )
				throw new Exception('Invalid value type for `'.$name.'`. Value must be string, numeric or boolean.');
		
            if( $name == 'logged' && ! is_bool($value) )
                throw new Exception('Invalid value type for `'.$name.'`. Value must be boolean.');

			// done
			$this->info[ $name ] = $value;

            return $this;

        }

        /**
         * add multiple info values to current visitor
         */
        public function infoValues( $infos ){

			if( ! is_array($infos) )
				throw new Exception('If you want to add infos, you have to provide as array');
		
			foreach($infos as $n => $v){
				try{
					$this->info($n, $v);
				} catch (Exception $e ){
					throw $e;
				}
			}
		
            return $this;

        }

        private function getInfo( $name ){

            return isset($this->info[ $name ]) ? $this->info[ $name ] : null;

        }

        public function getInfos(){

            return $this->info;

        }

		
		public function custom($title, $value){

            // checks
			if( ! is_string($title) )
                throw new Exception('Invalid value type for custom info title. Title must be string type.');

            if( ! is_string($value) )
                throw new Exception('Invalid value type for custom info value. Value must be string type.');

            $this->custom[ $title ] = $value;

            return $this;

        }

        public function customValues( $custom ){

			if( ! is_array($custom) )
				throw new Exception('If you want to add custom infos, you have to provide as array');
		
			foreach($custom as $n => $v){
				try{
					$this->custom($n, $v);
				} catch (Exception $e ){
					throw $e;
				}
			}
		
            return $this;

        } 
		
		public function getCustom(){

            return $this->custom;

        }
		
		
        public function event( $eventName, $eventOptions = [] ){

            // checks
			if( ! is_string($eventName) )
                throw new Exception('Invalid value type for event name. Event name must be string type.');

            if( ! is_array($eventOptions) )
				throw new Exception('Event options must be array type.');

            $eventName = strtolower($eventName);

            // add event
            $this->events[] = array_merge([
                'event' => $eventName
            ], $eventOptions);

            return $this;

        }
		

        public function getEvent( $name ){

            return isset($this->events[ $name ]) ? $this->events[ $name ] : null;

        }

        public function getEvents(){

            return $this->events;

        }
		
        public function build( $scriptTag = true, $optimizeCode = true ){

            // defines
            $chatWidget = 'https://app.letochat.com/letochat-widget.js';
            $initData   = json_encode($this->composeInitData());

            // compose script
            $script = '(function (w, d, s, o, f, js, fjs) {
                        w["LetoChat"] = o;
                        w[o] =
                        w[o] ||
                        function () {
                            (w[o].q = w[o].q || []).push(arguments);
                        };
                        (js = d.createElement(s)), (fjs = d.getElementsByTagName(s)[0]);
                        js.id = o;
                        js.src = f;
                        js.async = 1;
                        fjs.parentNode.insertBefore(js, fjs);
                    })(window, document, "script", "w1", "'.$chatWidget.'");
                    w1("init", '.$initData.');';

            // optimize JS code
            if( $optimizeCode )
                $script = $this->optimizeCode($script);

            // check if script tag is required
            if( $scriptTag )
                $script = '<script>'.$script.'</script>';

            // done
            return $script;

        }

        private function composeInitData(){

            $init = [
                'apiKey' => $this->channelId
            ];

            if( $this->channelSecret == null ){
                return $init;
            }
            
            $data = [];

            // add general infos
            if( ! empty($this->info) )
                $data = $this->info;

            // add custom info
            if( ! empty($this->custom) )
                $data['custom'] = $this->custom;

            // add events
            if( ! empty($this->events) )
                $data['events'] = $this->events;

            // generate JWT
            if( ! empty($data) )
                $init['token'] = (new JWT\Token($data))->sign($this->channelSecret);

            // done
            return $init;

        }

        private function optimizeCode( $code ){

            $code = preg_replace('/\s+/', '', $code);

            return $code;

        }

    }
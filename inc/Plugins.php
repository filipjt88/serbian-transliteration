<?php if ( ! defined( 'WPINC' ) ) { die( "Don't mess with us." ); }
/**
 * Include plugins support if they are available
 *
 * @link              http://infinitumform.com/
 * @since             1.2.4
 * @package           Serbian_Transliteration
 * @author            Ivijan-Stefan Stipic
 */
if(!class_exists('Serbian_Transliteration_Plugins')) :
	class Serbian_Transliteration_Plugins extends Serbian_Transliteration
	{
		private $plugins = array(
			'revslider' => 'revslider',
			'woocommerce' => 'woocommerce'
		);
		
		/* Run this script */
		private static $__includes = NULL;
		public static function includes($options = array(), $only_object = false ) {
			if( !self::$__includes ) self::$__includes = new self($options, $only_object);
			return self::$__includes;
		}
		
		function __construct( $options=array(), $only_object = false ) {
			$this->options = (!empty($options) && is_array($options) ? $options : get_rstr_option());
			
			if($only_object === false)
			{				
				// Include important function
				if(!function_exists('is_plugin_active')) {
					include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
				}
				
				$this->plugins = apply_filters('rstr/plugins', $this->plugins);
				
				foreach($this->plugins as $dir_name=>$file_name)
				{
					if( is_plugin_active("{$dir_name}/{$file_name}.php") && file_exists(RSTR_INC . "/plugins/{$file_name}.php") )
					{
						$plugin_class = "Serbian_Transliteration__Plugin__{$file_name}";
						if(class_exists($plugin_class)) {
							$plugin_class::run($this->options);
						} else {
							include_once RSTR_INC . "/plugins/{$file_name}.php";
							if(class_exists($plugin_class)) {
								$plugin_class::run($this->options);
							}
						}
					}
				}
			}
		}
		
		public function active_filters () {
			// Include important function
			if(!function_exists('is_plugin_active')) {
				include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			}
			
			$this->plugins = apply_filters('rstr/plugins', $this->plugins);
			
			$return = array();
			
			foreach($this->plugins as $dir_name=>$file_name)
			{
				if( is_plugin_active("{$dir_name}/{$file_name}.php") && file_exists(RSTR_INC . "/plugins/{$file_name}.php") )
				{
					$plugin_class = "Serbian_Transliteration__Plugin__{$file_name}";
					if(class_exists($plugin_class)) {
						$return = array_merge($return, $plugin_class::filters());
					} else {
						include_once RSTR_INC . "/plugins/{$file_name}.php";
						if(class_exists($plugin_class)) {
							$return = array_merge($return, $plugin_class::filters());
						}
					}
				}
			}
			
			return $return;
		}
	}
endif;
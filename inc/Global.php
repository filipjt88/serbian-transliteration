<?php if ( ! defined( 'WPINC' ) ) { die( "Don't mess with us." ); }
/*
 * Main global classes with active hooks
 * @since     1.0.0
 * @verson    1.0.0
 */
if(!class_exists('Serbian_Transliteration') && class_exists('Serbian_Transliteration_Transliterating')) :
class Serbian_Transliteration extends Serbian_Transliteration_Transliterating{
	
	private static $__instance = NULL;
	private $html_tags;
	
	/*
	 * Plugin mode
	 * @return        array/string
	 * @author        Ivijan-Stefan Stipic
	*/
	public function plugin_mode($mode=NULL){
		$modes = array(
			'standard'	=> __('Standard mode (content, themes, plugins, translations, menu)', RSTR_NAME),
			'advanced'	=> __('Advanced mode (content, widgets, themes, plugins, translations, menu‚ permalinks, media)', RSTR_NAME),
			'forced'	=> __('Forced transliteration (everything)', RSTR_NAME)
		);
		
		if(RSTR_WOOCOMMERCE) {
			$modes = array_merge($modes, array(
				'woocommerce'	=> __('Only WooCommerce (It bypasses all other transliterations and focuses only on WooCommerce)', RSTR_NAME)
			));
		}
		
		$modes = apply_filters('rstr_plugin_mode', $modes);
		
		if($mode && isset($modes[$mode])){
			return $modes[$mode];
		}
		
		return $modes;
	}
	
	/*
	 * Transliteration mode
	 * @return        array/string
	 * @author        Ivijan-Stefan Stipic
	*/
	public function transliteration_mode($mode=NULL){
		$modes = array(
			'none'			=> __('Transliteration disabled', RSTR_NAME),
			'cyr_to_lat'	=> __('Cyrillic to Latin', RSTR_NAME),
			'lat_to_cyr'	=> __('Latin to Cyrillic', RSTR_NAME)
		);
		
		$modes = apply_filters('rstr_transliteration_mode', $modes);
		
		if($mode && isset($modes[$mode])){
			return $modes[$mode];
		}
		
		return $modes;
	}
	
	/*
	 * Decode content
	 * @return        string
	 * @author        Ivijan-Stefan Stipic
	*/
	public function decode($content){
		$content = rawurldecode($content);
		$content = htmlspecialchars_decode($content);
		$content = html_entity_decode($content);
		$content = strtr($content, array_flip(get_html_translation_table(HTML_ENTITIES, ENT_QUOTES)));
		return $content;
	}
	
	/*
	 * Translate from cyr to lat
	 * @return        string
	 * @author        Ivijan-Stefan Stipic
	*/
	public function cyr_to_lat($content){
		$content = $this->decode($content);
		
		if(method_exists('Serbian_Transliteration_Transliterating', $this->get_locale()))
		{
			$locale = $this->get_locale();
			$content = parent::$locale($content);
			// Filter special names from the list
			foreach($this->lat_exclude_list() as $item){
				$content = str_replace(parent::$locale($item), $item, $content);
			}
		}
		else
		{
			$content = str_replace($this->cyr(), $this->lat(), $content);
			// Filter special names from the list
			foreach($this->lat_exclude_list() as $item){
				$content = str_replace(str_replace($this->cyr(), $this->lat(), $item), $item, $content);
			}
		}
		
		$content = $this->fix_attributes($content);

		return $content;
	}
	
	/*
	 * Translate from cyr to lat
	 * @return        string
	 * @author        Ivijan-Stefan Stipic
	*/
	public function cyr_to_lat_sanitize($content){
		$content = $this->cyr_to_lat($content);
		
		$content = strtr($content, apply_filters('rstr_cyr_to_lat_sanitize', array(
			'Ć' => 'C',
			'ć' => 'c',
			'Č' => 'C',
			'č' => 'c',
			'Š' => 'S',
			'š' => 's',
			'Ž' => 'Z',
			'ž' => 'z',
			'Đ' => 'Dj',
			'dj' => 'dj',
			'DŽ' => 'DZ',
			'Dž' => 'Dz',
			'dž' => 'dz'
		)));

		if(function_exists('iconv'))
		{
			if($locale = $this->get_locales( $this->get_locale() )) {
				setlocale(LC_CTYPE, $locale);
			}
			
			if($converted = iconv("UTF-8","ASCII//TRANSLIT", $content)) {
				$content = str_replace(array("\"","'","`","^","~"), '', $converted);
			}
		}
		
		// Filter special names from the list
		foreach($this->lat_exclude_list() as $item){
			$content = str_replace(str_replace($this->cyr(), $this->lat(), $item), $item, $content);
		}
		
		return $content;
	}
	
	/*
	 * Translate from lat to cyr
	 * @return        string
	 * @author        Ivijan-Stefan Stipic
	*/
	public function lat_to_cyr($content, $fix_html = true){
		$content = $this->decode($content);
		
		if(method_exists('Serbian_Transliteration_Transliterating', $this->get_locale()))
		{
			$locale = $this->get_locale();
			$content = parent::$locale($content, 'lat_to_cyr');
			// Filter special names from the list
			foreach($this->cyr_exclude_list() as $item){
				$content = str_replace(parent::$locale($item, 'lat_to_cyr'), $item, $content);
			}
		}
		else
		{
			$content = str_replace($this->lat(), $this->cyr(), $content);
			// Filter special names from the list
			foreach($this->cyr_exclude_list() as $item){
				$content = str_replace(str_replace($this->lat(), $this->cyr(), $item), $item, $content);
			}
		}
		if($fix_html){
			$content = $this->fix_cyr_html($content);
			$content = $this->fix_attributes($content);
		}
		
		return $content;
	}
	
	/*
	 * Automatic transliteration
	 * @return        string
	 * @author        Ivijan-Stefan Stipic
	*/
	public function transliterate_text($content, $type, $fix_html = true){
		$content = $this->decode($content);
		if(method_exists('Serbian_Transliteration_Transliterating', $this->get_locale()))
		{
			$locale = $this->get_locale();
			$content = parent::$locale($content, $type);
			// Filter special names from the list
			foreach($this->cyr_exclude_list() as $item){
				$content = str_replace(parent::$locale($item, $type), $item, $content);
			}
		}
		else
		{
			
			switch($type)
			{
				case 'lat_to_cyr':
					$content = str_replace($this->lat(), $this->cyr(), $content);
					// Filter special names from the list
					foreach($this->lat_exclude_list() as $item){
						$content = str_replace(str_replace($this->lat(), $this->cyr(), $item), $item, $content);
						$content = $this->fix_attributes($content);
					}
					break;
				case 'cyr_to_lat':
					$content = str_replace($this->cyr(), $this->lat(), $content);
					// Filter special names from the list
					foreach($this->cyr_exclude_list() as $item){
						$content = str_replace(str_replace($this->cyr(), $this->lat(), $item), $item, $content);
					}
					break;
			}
		}
		
		if($type == 'lat_to_cyr' && $fix_html){
			$content = $this->fix_cyr_html($content);
			$content = $this->fix_attributes($content);
		}
		
		return $content;
	}
	
	/*
	 * Check is already cyrillic
	 * @return        string
	 * @author        Ivijan-Stefan Stipic
	*/
	public function already_cyrillic(){
        return in_array($this->get_locale(), apply_filters('rstr_already_cyrillic', array('sr_RS','mk_MK', 'bel', 'bg_BG', 'ru_RU', 'sah', 'uk', 'kk'))) !== false;
	}
	
	/*
	 * Check is latin letters
	 * @return        boolean
	 * @author        Ivijan-Stefan Stipic
	*/
	public function is_lat($c){
		return preg_match_all('/[\p{Latin}]+/ui', strip_tags($c, ''));
	}
	
	/*
	 * Check is cyrillic letters
	 * @return        boolean
	 * @author        Ivijan-Stefan Stipic
	*/
	public function is_cyr($c){
		return preg_match_all('/[\p{Cyrillic}]+/ui', strip_tags($c, ''));
	}
	
	/*
	 * All available HTML tags
	 * @return        array
	 * @author        Ivijan-Stefan Stipic
	*/
	public function html_tags() {
		if( empty($this->html_tags) )
		{		
			$tags = apply_filters('rstr_html_tags',  '!DOCTYPE,a,abbr,acronym,address,applet,area,article,aside,audio,b,base,basefont,bdi,bdo,big,blockquote,body,br,button,canvas,caption,center,cite,code,col,colgroup,data,details,dd,del,details,dfn,dialog,dir,div,dl,dt,em,embed,fieldset,figcaption,figure,font,footer,form,frame,frameset,h1,h2,h3,h4,h5,h6,head,header,hr,html,i,iframe,img,input,ins,kbd,label,legend,li,link,main,map,mark,meta,master,nav,noframes,noscript,object,ol,optgroup,option,output,p,param,picture,pre,progress,q,rp,rt,ruby,s,samp,script,section,select,small,source,span,strike,strong,style,sub,summary,sup,svg,table,tbody,td,template,textarea,tfoot,th,thead,time,title,tr,track,tt,u,ul,var,video,wbr');
			$tags_latin = explode(',', $tags);
			$tags_latin = array_map('trim', $tags_latin);
			$tags_latin = array_filter($tags_latin);
			$tags_latin = apply_filters('rstr_html_tags_lat', $tags_latin);
			
			$tags_cyr = $this->lat_to_cyr($tags, false);
			$tags_cyr = explode(',', $tags_cyr);
			$tags_cyr = array_map('trim', $tags_cyr);
			$tags_cyr = array_filter($tags_cyr);
			$tags_cyr = apply_filters('rstr_html_tags_cyr', $tags_cyr);
			
			$this->html_tags = (object)array(
				'cyr' => $tags_cyr,
				'lat' => $tags_latin
			);
		}
		
		return $this->html_tags;
	}
	
	/*
	 * Fix html codes
	 * @return        string/html
	 * @author        Ivijan-Stefan Stipic
	*/
	public function fix_cyr_html($content){
		$content = htmlspecialchars_decode($content);

		$tags = $this->html_tags();
		
		$tags_cyr = $tags_lat = array();
		foreach($tags->lat as $i=>$tag){
			$tag_cyr = $tags->cyr[$i];
			
			$tags_cyr[]='<' . $tag_cyr;
			$tags_cyr[]='</' . $tag_cyr . '>';
			
			$tags_lat[]= '<' . $tag;
			$tags_lat[]= '</' . $tag . '>';
		}
		$tags = $tag_cyr = NULL;
		
		$tags_cyr = array_merge($tags_cyr, array('&нбсп;','&лт;','&гт;','&ндасх;','&мдасх;','хреф','срц','&лдqуо;','&бдqуо;','&лсqуо;','&рсqуо;','&сцарон;','&Сцарон;','&тилде;'));
		$tags_lat = array_merge($tags_lat, array('&nbsp;','&lt;','&gt;','&ndash;','&mdash;','href','src','&ldquo;','&bdquo;','&lsquo;','&rsquo;','ш','Ш','&tilde;'));
		
		$content = str_replace($tags_cyr, $tags_lat, $content);
		
		$lastPos = 0;
		$positions = [];

		while (($lastPos = mb_strpos($content, '<', $lastPos, 'UTF-8')) !== false) {
			$positions[] = $lastPos;
			$lastPos = $lastPos + mb_strlen('<', 'UTF-8');
		}

		foreach ($positions as $position) {
			if(mb_strpos($content, '>', 0, 'UTF-8') !== false) {
				$end   = mb_strpos($content, '>', $position, 'UTF-8') - $position;
				$tag  = mb_substr($content, $position, $end, 'UTF-8');
				$tag_lat = $this->cyr_to_lat($tag);
				$content = str_replace($tag, $tag_lat, $content);
			}
		}
		
		// Fix open tags
		$content = preg_replace_callback ('/(<[\x{0400}-\x{04FF}0-9a-zA-Z\/\=\"\'_\-\s\.\;\,\!\?\*\:\#\$\%\&\(\)\[\]\+\@\€]+>)/iu', function($m){
			return $this->cyr_to_lat($m[1]);
		}, $content);
		
		// FIx closed tags
		$content = preg_replace_callback ('/(<\/[\x{0400}-\x{04FF}0-9a-zA-Z]+>)/iu', function($m){
			return $this->cyr_to_lat($m[1]);
		}, $content);
		
		// Fix HTML entities
		$content = preg_replace_callback ('/\&([\x{0400}-\x{04FF}0-9]+)\;/iu', function($m){
			return '&' . $this->cyr_to_lat($m[1]) . ';';
		}, $content);
		
		// Fix JavaScript
		$content = preg_replace_callback('/(?=<script(.*?)>)(.*?)(?<=<\/script>)/s', function($m) {
				return $this->cyr_to_lat($m[2]);
		}, $content);
		
		// Fix CSS
		$content = preg_replace_callback('/(?=<style(.*?)>)(.*?)(?<=<\/style>)/s', function($m) {
				return $this->cyr_to_lat($m[2]);
		}, $content);
		
		// Fix email
		$content = preg_replace_callback ('/(([\x{0400}-\x{04FF}0-9\_\-\.]+)@([\x{0400}-\x{04FF}0-9\_\-\.]+)\.([\x{0400}-\x{04FF}0-9]{3,10}))/iu', function($m){
			return $this->cyr_to_lat($m[1]);
		}, $content);

		// Fix URL
		$content = preg_replace_callback ('/(([\x{0400}-\x{04FF}]{4,5}):\/{2}([\x{0400}-\x{04FF}0-9\_\-\.]+)\.([\x{0400}-\x{04FF}0-9]{3,10})(.*?)($|\n|\s|\r|\"\'\.\;\,\:\)\]\>))/iu', function($m){
			return $this->cyr_to_lat($m[1]);
		}, $content);
		
		// Fix attributes with doublequote
		$content = preg_replace_callback ('/(title|alt|data-(title|alt))\s?=\s?"(.*?)"/iu', function($m){
			return sprintf('%1$s="%2$s"', $m[1], esc_attr($this->lat_to_cyr($m[3], false)));
		}, $content);
		
		// Fix attributes with single quote
		$content = preg_replace_callback ('/(title|alt|data-(title|alt))\s?=\s?\'(.*?)\'/iu', function($m){
			return sprintf('%1$s=\'%2$s\'', $m[1], esc_attr($this->lat_to_cyr($m[3], false)));
		}, $content);
		
		return $content;
	}
	
	public function upload_prefilter ($file) {
		$file['name']= $this->cyr_to_lat_sanitize($file['name']);
		return $file;
	}

	public function sanitize_file_name($filename){
		return $this->cyr_to_lat_sanitize($filename);
	}
	
	public function force_permalink_to_latin ($permalink) {
		$permalink = rawurldecode($permalink);
		$permalink= $this->cyr_to_lat_sanitize($permalink);
		return $permalink;
	}
	
	public function force_permalink_to_latin_on_save ($data, $postarr) {
		$data['post_name'] = rawurldecode($data['post_name']);
		$data['post_name'] = $this->cyr_to_lat_sanitize( $data['post_name'] );
		return $data;
	}
	
	/*
	 * Hook for register_uninstall_hook()
	 * @author        Ivijan-Stefan Stipic
	*/
	public static function register_uninstall_hook($function){
		return register_uninstall_hook( RSTR_FILE, $function );
	}
	
	/*
	 * Hook for register_deactivation_hook()
	 * @author        Ivijan-Stefan Stipic
	*/
	public static function register_deactivation_hook($function){
		return register_deactivation_hook( RSTR_FILE, $function );
	}
	
	/*
	 * Hook for register_activation_hook()
	 * @author        Ivijan-Stefan Stipic
	*/
	public static function register_activation_hook($function){
		return register_activation_hook( RSTR_FILE, $function );
	}
	/* 
	 * Hook for add_action()
	 * @author        Ivijan-Stefan Stipic
	*/
	public function add_action($tag, $function_to_add, $priority = 10, $accepted_args = 1){
		if(!is_array($function_to_add))
			$function_to_add = array(&$this, $function_to_add);
			
		return add_action( (string)$tag, $function_to_add, (int)$priority, (int)$accepted_args );
	}
	
	/* 
	 * Hook for remove_action()
	 * @author        Ivijan-Stefan Stipic
	*/
	public function remove_action($tag, $function_to_remove, $priority = 10){
		if(!is_array($function_to_remove))
			$function_to_remove = array(&$this, $function_to_remove);
			
		return remove_action( $tag, $function_to_remove, $priority );
	}
	
	/* 
	 * Hook for add_filter()
	 * @author        Ivijan-Stefan Stipic
	*/
	public function add_filter($tag, $function_to_add, $priority = 10, $accepted_args = 1){
		if(!is_array($function_to_add))
			$function_to_add = array(&$this, $function_to_add);
			
		return add_filter( (string)$tag, $function_to_add, (int)$priority, (int)$accepted_args );
	}
	
	/* 
	 * Hook for remove_filter()
	 * @author        Ivijan-Stefan Stipic
	*/
	public function remove_filter($tag, $function_to_remove, $priority = 10){
		if(!is_array($function_to_remove))
			$function_to_remove = array(&$this, $function_to_remove);
			
		return remove_filter( (string)$tag, $function_to_remove, (int)$priority );
	}
	
	/* 
	 * Hook for add_shortcode()
	 * @author        Ivijan-Stefan Stipic
	*/
	public function add_shortcode($tag, $function_to_add){
		if(!is_array($function_to_add))
			$function_to_add = array(&$this, $function_to_add);
		
		if(!shortcode_exists($tag)) {
			return add_shortcode( $tag, $function_to_add );
		}
		
		return false;
	}
	
	/* 
	 * Hook for add_options_page()
	 * @author        Ivijan-Stefan Stipic
	*/
	public function add_options_page($page_title, $menu_title, $capability, $menu_slug, $function = '', $position = null){
		if(!is_array($function))
			$function = array(&$this, $function);
		
		return add_options_page($page_title, $menu_title, $capability, $menu_slug, $function, $position);
	}
	
	/* 
	 * Hook for add_settings_section()
	 * @author        Ivijan-Stefan Stipic
	*/
	public function add_settings_section($id, $title, $callback, $page){
		if(!is_array($callback))
			$callback = array(&$this, $callback);
		
		return add_settings_section($id, $title, $callback, $page);
	}
	
	/* 
	 * Hook for register_setting()
	 * @author        Ivijan-Stefan Stipic
	*/
	public function register_setting($option_group, $option_name, $args = array()){
		if(!is_array($args) && is_callable($args))
			$args = array(&$this, $args);
		
		return register_setting($option_group, $option_name, $args);
	}
	
	/* 
	 * Hook for add_settings_field()
	 * @author        Ivijan-Stefan Stipic
	*/
	public function add_settings_field($id, $title, $callback, $page, $section = 'default', $args = array()){
		if(!is_array($callback))
			$callback = array(&$this, $callback);
		
		return add_settings_field($id, $title, $callback, $page, $section, $args);
	}
	
	/* 
	 * Generate unique token
	 * @author        Ivijan-Stefan Stipic
	*/
	public static function generate_token($length=16){
		if(function_exists('openssl_random_pseudo_bytes') || function_exists('random_bytes'))
		{
			if (version_compare(PHP_VERSION, '7.0.0', '>='))
				return substr(str_rot13(bin2hex(random_bytes(ceil($length * 2)))), 0, $length);
			else
				return substr(str_rot13(bin2hex(openssl_random_pseudo_bytes(ceil($length * 2)))), 0, $length);
		}
		else
		{
			return substr(str_replace(array('.',' ','_'),mt_rand(1000,9999),uniqid('t'.microtime())), 0, $length);
		}
	}
	
	/*
	 * Return plugin informations
	 * @return        array/object
	 * @author        Ivijan-Stefan Stipic
	*/
	function plugin_info($fields = array()) {
        if ( is_admin() ) {
			if ( ! function_exists( 'plugins_api' ) ) {
				include_once( WP_ADMIN_DIR . '/includes/plugin-install.php' );
			}
			/** Prepare our query */
			//donate_link
			//versions
			$plugin_data = plugins_api( 'plugin_information', array(
				'slug' => RSTR_NAME,
				'fields' => array_merge(array(
					'active_installs' => false,           // rounded int
					'added' => false,                     // date
					'author' => false,                    // a href html
					'author_block_count' => false,        // int
					'author_block_rating' => false,       // int
					'author_profile' => false,            // url
					'banners' => false,                   // array( [low], [high] )
					'compatibility' => false,            // empty array?
					'contributors' => false,              // array( array( [profile], [avatar], [display_name] )
					'description' => false,              // string
					'donate_link' => false,               // url
					'download_link' => false,             // url
					'downloaded' => false,               // int
					// 'group' => false,                 // n/a 
					'homepage' => false,                  // url
					'icons' => false,                    // array( [1x] url, [2x] url )
					'last_updated' => false,              // datetime
					'name' => false,                      // string
					'num_ratings' => false,               // int
					'rating' => false,                    // int
					'ratings' => false,                   // array( [5..0] )
					'requires' => false,                  // version string
					'requires_php' => false,              // version string
					// 'reviews' => false,               // n/a, part of 'sections'
					'screenshots' => false,               // array( array( [src],  ) )
					'sections' => false,                  // array( [description], [installation], [changelog], [reviews], ...)
					'short_description' => false,        // string
					'slug' => false,                      // string
					'support_threads' => false,           // int
					'support_threads_resolved' => false,  // int
					'tags' => false,                      // array( )
					'tested' => false,                    // version string
					'version' => false,                   // version string
					'versions' => false,                  // array( [version] url )
				), $fields)
			));
		 
			return $plugin_data;
		}
    }
	
	/**
	* Get current page ID
	* @autor    Ivijan-Stefan Stipic
	* @since    1.0.7
	* @version  1.0.0
	**/
	public function get_current_page_ID(){
		global $post, $wp_query, $wpdb;
		
		if(!is_null($wp_query) && isset($wp_query->post) && isset($wp_query->post->ID) && !empty($wp_query->post->ID))
			return $wp_query->post->ID;
		else if(function_exists('get_the_id') && !empty(get_the_id()))
			return get_the_id();
		else if(!is_null($post) && isset($post->ID) && !empty($post->ID))
			return $post->ID;
		else if($this->get('action') == 'edit' && $post = $this->get('post', 'int', false))
			return $post;
		else if($p = $this->get('p', 'int', false))
			return $p;
		else if($page_id = $this->get('page_id', 'int', false))
			return $page_id;
		else if(!is_admin() && $wpdb)
		{
			$actual_link = rtrim($_SERVER['REQUEST_URI'], '/');
			$parts = explode('/', $actual_link);
			if(!empty($parts))
			{
				$slug = end($parts);
				if(!empty($slug))
				{
					if($post_id = $wpdb->get_var(
						$wpdb->prepare(
							"SELECT ID FROM {$wpdb->posts} 
							WHERE 
								`post_status` = %s
							AND
								`post_name` = %s
							AND
								TRIM(`post_name`) <> ''
							LIMIT 1",
							'publish',
							sanitize_title($slug)
						)
					))
					{
						return absint($post_id);
					}
				}
			}
		}
		else if(!is_admin() && 'page' == get_option( 'show_on_front' ) && !empty(get_option( 'page_for_posts' )))
			return get_option( 'page_for_posts' );

		return false;
	}
	
	/* 
	* Generate and clean GET
	* @name          GET name
	* @option        string, int, float, bool, html, encoded, url, email
	* @default       default value
	*/
	public function get($name, $option='string', $default=''){
        $option = trim((string)$option);
        if(isset($_GET[$name]) && !empty($_GET[$name]))
        {           
            if(is_array($_GET[$name]))
                $is_array=true;
            else
                $is_array=false;
            
            if( is_numeric( $option ) || empty( $option ) ) return $default;
            else $input = $_GET[$name];
            
            switch($option)
            {
                default:
                    if($is_array) return array_map( 'sanitize_text_field', $input );
                    
                    return sanitize_text_field( $input );
                break;
                case 'encoded':
                    return (!empty($input)?$input:$default);
                break;
				case 'url':
					if($is_array) return array_map( 'esc_url', $input );
			
					return esc_url( $input );
				break;
				case 'url_raw':
					if($is_array) return array_map( 'esc_url_raw', $input );
		
					return esc_url_raw( $input );
				break;
                case 'email':
                    if($is_array) return array_map( 'sanitize_email', $input );
                    
                    return sanitize_email( $input );
                break;
                case 'int':
                    if($is_array) return array_map( 'absint', $input );
                    
                    return absint( $input );
                break;
                case 'float':
					if($is_array) return array_map( 'floatval', $input );
                    
                    return floatval( $input );
                break;
                case 'bool':
                    if($is_array) return array_map( 'boolval', $input );
                    
                    return boolval( $input );
				break;
				case 'html_class':
					if( $is_array ) return array_map( 'sanitize_html_class', $input );

					return sanitize_html_class( $input );
				break;
				case 'title':
					if( $is_array ) return array_map( 'sanitize_title', $input );

					return sanitize_title( $input );
				break;
				case 'user':
					if( $is_array ) return array_map( 'sanitize_user', $input );

					return sanitize_user( $input );
				break;
				case 'no_html':
					if( $is_array ) return array_map( 'wp_filter_nohtml_kses', $input );

					return wp_filter_nohtml_kses( $input );
				break;
				case 'post':
					if( $is_array ) return array_map( 'wp_filter_post_kses', $input );

					return wp_filter_post_kses( $input );
				break;
            }
        }
        else
        {
            return $default;
        }
    }
	
	/* 
	* Register language script
	* @since     1.0.9
	* @verson    1.0.0
	*/
	public static function attachment_taxonomies() {
		register_taxonomy( 'rstr-script', array( 'attachment' ), array(
			'hierarchical'      => true,
			'labels'            => array(
				'name'              => _x( 'Script', 'Language script', RSTR_NAME ),
				'singular_name'     => _x( 'Script', 'Language script', RSTR_NAME ),
				'search_items'      => __( 'Search by Script', RSTR_NAME ),
				'all_items'         => __( 'All Scripts', RSTR_NAME ),
				'parent_item'       => __( 'Parent Script', RSTR_NAME ),
				'parent_item_colon' => __( 'Parent Script:', RSTR_NAME ),
				'edit_item'         => __( 'Edit Script', RSTR_NAME ),
				'update_item'       => __( 'Update Script', RSTR_NAME ),
				'add_new_item'      => __( 'Add New Script', RSTR_NAME ),
				'new_item_name'     => __( 'New Script Name', RSTR_NAME ),
				'menu_name'         => __( 'Script', RSTR_NAME ),
			),
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'publicly_queryable'=> false,
			'show_in_menu'		=> false,
			'show_in_nav_menus'	=> false,
			'show_in_rest'		=> false,
			'show_tagcloud'		=> false,
			'show_in_quick_edit'=> false
		) );
	}
	
	/* 
	* Get current transliteration script
	* @since     1.0.9
	* @verson    1.0.0
	*/
	public function get_current_script($options=array()){		
		if(isset($_COOKIE['rstr_script']) && !empty($_COOKIE['rstr_script']))
		{
			if($_COOKIE['rstr_script'] == 'lat') {
				if(isset($options['transliteration-mode']) && $options['site-script'] == 'lat') return 'lat';
		
				return 'cyr_to_lat';
			} else if($_COOKIE['rstr_script'] == 'cyr') {
				if(isset($options['transliteration-mode']) && $options['site-script'] == 'cyr') return 'cyr';
				
				return 'lat_to_cyr';
			}
		}
		
		return (isset($options['transliteration-mode']) && !empty($options['transliteration-mode']) ? $options['transliteration-mode'] : 'none');
	}
	
	/* 
	* Set current transliteration script
	* @since     1.0.9
	* @verson    1.0.0
	*/
	public function set_current_script(){
		if(isset($_REQUEST['rstr']))
		{
			if(in_array($_REQUEST['rstr'], apply_filters('rstr/allowed_script', array('cyr', 'lat')), true) !== false)
			{
				$this->setcookie($_REQUEST['rstr']);

				if(wp_safe_redirect( preg_replace('~([?&]rstr\=(lat|cyr))~i', '', $this->get_current_url()) )){
					if(function_exists('nocache_headers')) nocache_headers();
					exit;
				}
			}
		}
		return false;
	}
	
	/*
	 * Set cookie
	 * @since     1.0.10
	 * @verson    1.0.0
	*/
	public function setcookie ($val){
		if( !headers_sent() ) {
			setcookie( 'rstr_script', $val, (time()+YEAR_IN_SECONDS), COOKIEPATH, COOKIE_DOMAIN );
		}
	}
	
	
	/*
	 * Get current URL
	 * @since     1.0.9
	 * @verson    1.0.0
	*/
	public function get_current_url()
	{
		global $wp;
		return add_query_arg( array(), home_url( $wp->request ) );
	}
	
	/*
	 * Alternate Links
	 * @since     1.0.13
	 * @verson    1.0.0
	*/
	public function alternate_links() {
		$url = $this->get_current_url();
		$locale = get_locale();
		$title = get_bloginfo('name');
?>
<link rel="alternate" title="<?php echo esc_attr($this->lat_to_cyr($title, false)); ?>" href="<?php echo add_query_arg('rstr', 'cyr', $url); ?>" hreflang="<?php echo strtr($locale, array('_'=>'_Cyrl_')); ?>" />
<link rel="alternate" title="<?php echo esc_attr($this->cyr_to_lat($title)); ?>" href="<?php echo add_query_arg('rstr', 'lat', $url); ?>" hreflang="<?php echo strtr($locale, array('_'=>'_Latn_')); ?>" />
<?php
	}
	
	/* 
	* Check is block editor screen
	* @since     1.0.9
	* @verson    1.0.0
	*/
	public function is_editor()
	{
		if (version_compare(get_bloginfo( 'version' ), '5.0', '>=')) {
			if(!function_exists('get_current_screen')){
				include_once ABSPATH  . '/wp-admin/includes/screen.php';
			}
			$get_current_screen = get_current_screen();
			if(is_callable(array($get_current_screen, 'is_block_editor')) && method_exists($get_current_screen, 'is_block_editor')) {
				return $get_current_screen->is_block_editor();
			}
		} else {
			return ( isset($_GET['action']) && isset($_GET['post']) && $_GET['action'] == 'edit' && is_numeric($_GET['post']) );
		}
		
		return false;
	}
	
	/*
	 * Fix attributes
	 * @return        string
	 * @author        Ivijan-Stefan Stipic
	*/
	public function fix_attributes($content){
		
		// Fix bad attribute space
		$content = preg_replace('/"([a-z-_]+\s?=)/i', ' $1', $content);
		
		// Fix entity
		$content = preg_replace_callback('/(data-[a-z-_]+\s?=\s?")(.*?)("(\s|\>|\/))/s', function($m) {
				return $m[1] . htmlentities($m[2], ENT_QUOTES | ENT_IGNORE, 'UTF-8') . $m[3];
		}, $content);
		
		$content = preg_replace_callback('/(data-[a-z-_]+\s?=\s?\')(.*?)(\'(\s|\>|\/))/s', function($m) {
				return $m[1] . htmlentities($m[2], ENT_QUOTES | ENT_IGNORE, 'UTF-8') . $m[3];
		}, $content);
		
		$content = preg_replace_callback('/(href\s?=\s?"#)(.*?)("(\s|\>|\/))/s', function($m) {
				return $m[1] . urlencode($m[2]) . $m[3];
		}, $content);
		
		$content = preg_replace_callback('/(href\s?=\s?\'#)(.*?)(\'(\s|\>|\/))/s', function($m) {
				return $m[1] . urlencode($m[2]) . $m[3];
		}, $content);
		
		// Fix broken things
		$tags = $this->html_tags();
		foreach($tags->lat as $i=>$tag){	
			$content = str_replace(array(
				'&lt;' . $tag,
				'&lt;/' . $tag . '&gt;'
			), array(
				'<' . $tag,
				'</' . $tag . '>'
			), $content);	
		}
		
		// Fix CSS
		$content = preg_replace_callback('/(?=<style(.*?)>)(.*?)(?<=<\/style>)/s', function($m) {
				return $this->decode($m[2]);
		}, $content);
		
		// Fix scripts
		$content = preg_replace_callback('/(?=<script(.*?)>)(.*?)(?<=<\/script>)/s', function($m) {
				return $this->decode($m[2]);
		}, $content);
		
		return $content;
	}
	
	/* 
	* Instance
	* @since     1.0.9
	* @verson    1.0.0
	*/
	public static function __instance()
	{
		if ( is_null( self::$__instance ) ) {
			self::$__instance = new self();
		}
		return self::$__instance;
	}
}
endif;
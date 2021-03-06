<?php if ( ! defined( 'WPINC' ) ) { die( "Don't mess with us." ); }
/**
 * Advanced Transliteration Mode
 *
 * @link              http://infinitumform.com/
 * @since             1.0.0
 * @package           Serbian_Transliteration
 * @author            Ivijan-Stefan Stipic
 * @contributor       Slobodan Pantovic
 */
if(!class_exists('Serbian_Transliteration_Mode_Advanced')) :
	class Serbian_Transliteration_Mode_Advanced extends Serbian_Transliteration
	{
		private $options;

		/* Run this script */
		private static $__run = NULL;
		public static function run($options = array()) {
			if( !self::$__run ) self::$__run = new self($options);
			return self::$__run;
		} 
		
		public static function filters ($options=array()) {
			if(empty($options)) $options = get_rstr_option();
			
			$filters = array(
				'single_cat_title'				=>'content',
				'the_category'					=>'content',
				'wp_list_categories'			=>'content',//Widget categories
				'wp_dropdown_cats'				=>'content',//Widget categories dropdown
				'get_archives_link'				=>'content', //Widget achives
				'get_the_terms'					=>'transliteration_wp_terms',//Sydney, Blocksy, Colormag
				'get_the_excerpt' 				=> 'content',
				'the_excerpt'					=>'content',
				'oceanwp_excerpt'				=>'content',//Oceanwp
				'get_calendar' 					=> 'content',
			//	'pre_kses' 						=> 'content',
				'date_i18n'						=> 'content',
				'get_comment_date' 				=> 'content',
				'wp_get_object_terms' 			=> 'transliteration_wp_terms', //Phlox
				'comment_text'					=> 'content',
				'comments_template' 			=> 'content',
				'the_content' 					=> 'content',
				'the_title' 					=> 'content',
				'the_date' 						=> 'content',
				'get_the_date' 					=> 'content',
				'get_post_time' 				=> 'content',
				'the_content_more_link' 		=> 'content',
				'wp_nav_menu_items' 			=> 'content',
				'wp_title' 						=> 'content',
				'pre_get_document_title'		=> 'content',
				'default_post_metadata'			=> 'content',
				'get_comment_metadata' 			=> 'content',
				'get_term_metadata' 			=> 'content',
				'get_user_metadata' 			=> 'content',
				'get_post_metadata' 			=> 'content',
				'get_page_metadata' 			=> 'content',
				'gettext' 						=> 'content',
				'ngettext' 						=> 'content',
				'gettext_with_context' 			=> 'content',
				'ngettext_with_context' 		=> 'content',
				'widget_text' 					=> 'content',
				'widget_title' 					=> 'content',
				'widget_text_content' 			=> 'content',
				'widget_custom_html_content' 	=> 'content',
				'sanitize_title' 				=> 'content',
				'wp_unique_post_slug' 			=> 'content',
				'option_blogdescription'		=> 'content',
				'option_blogname' 				=> 'content',
				'document_title_parts' 			=> 'transliterate_objects',
				'sanitize_title'				=> 'force_permalink_to_latin',
				'the_permalink'					=> 'force_permalink_to_latin',
				'wp_unique_post_slug'			=> 'force_permalink_to_latin'
			);
			asort($filters);
						
			return $filters;
		}

		function __construct($options=false){
			if($options !== false)
			{
				$this->options = $options;
				
				$filters = self::filters($this->options);
				$filters = apply_filters('rstr/transliteration/exclude/filters', $filters, $this->options);
				
				if(!is_admin())
				{
					foreach($filters as $filter=>$function) $this->add_filter($filter, $function, (PHP_INT_MAX-1), 1);
					
					if(get_rstr_option('enable-rss', 'no') == 'yes')
					{						
						$this->add_action('rss_head', 'rss_output_buffer_start', (PHP_INT_MAX-1));
						$this->add_action('rss_footer', 'rss_output_buffer_end', (PHP_INT_MAX-1));
						
						$this->add_action('rss2_head', 'rss_output_buffer_start', (PHP_INT_MAX-1));
						$this->add_action('rss2_footer', 'rss_output_buffer_end', (PHP_INT_MAX-1));
						
						$this->add_action('rdf_head', 'rss_output_buffer_start', (PHP_INT_MAX-1));
						$this->add_action('rdf_footer', 'rss_output_buffer_end', (PHP_INT_MAX-1));
						
						$this->add_action('atom_head', 'rss_output_buffer_start', (PHP_INT_MAX-1));
						$this->add_action('atom_footer', 'rss_output_buffer_end', (PHP_INT_MAX-1));
					}
					
					if(get_rstr_option('force-widgets', 'no') == 'yes')
					{
						$this->add_action('dynamic_sidebar_before', 'rss_output_buffer_start', (PHP_INT_MAX-1));
						$this->add_action('dynamic_sidebar_after', 'rss_output_buffer_end', (PHP_INT_MAX-1));
					}
				}
				
				$this->add_filter('bloginfo', 'bloginfo', (PHP_INT_MAX-1), 2);
				$this->add_filter('bloginfo_url', 'bloginfo', (PHP_INT_MAX-1), 2);
			}
		}
		
		function rss_output_buffer_start() {
			ob_start(NULL, 0, PHP_OUTPUT_HANDLER_REMOVABLE);
		}
		
		function rss_output_buffer_end() {
			$output = ob_get_clean();

			switch($this->get_current_script($this->options))
			{
				case 'cyr_to_lat' :
					$output = $this->cyr_to_lat($output);
					break;
					
				case 'lat_to_cyr' :
					$output = $this->lat_to_cyr($output);
					break;
			}

			echo $output;
		}
		
		
		/*
		 * Transliterate WP terms
		 * @author         Slobodan Pantović
		 * @contributor    Ivijan-Stefan Stipić
		 * @version        1.0.1
		**/
		public function transliteration_wp_terms($wp_terms)
		{
			if (! empty($wp_terms))
			{
				if(is_object($wp_terms) || is_array($wp_terms))
				{
					$count_wp_terms = count($wp_terms);
					for($i=0,$n=$count_wp_terms; $i<$n; $i++)
					{
						if (is_object($wp_terms[$i]))
						{
						   switch($this->get_current_script($this->options))
							{
								case 'cyr_to_lat' :
									$wp_terms[$i]->name = $this->cyr_to_lat($wp_terms[$i]->name);
									break;
								case 'lat_to_cyr' :
									$wp_terms[$i]->name = $this->lat_to_cyr($wp_terms[$i]->name);
									break;
							}                
						}
					}        
				}
			}
			return $wp_terms;
		}
		
		public function bloginfo($output, $show=''){
			if(!empty($show) && in_array($show, array('name','description')))
			{
				switch($this->get_current_script($this->options))
				{
					case 'cyr_to_lat' :
						$output = $this->cyr_to_lat($output);
						break;
						
					case 'lat_to_cyr' :
						$output = $this->lat_to_cyr($output);			
						break;
				}
			}
			return $output;
		}
		
		public function content ($content='') {
			if(empty($content)) return $content;
			
			
			if(is_array($content))
			{
				$content = $this->transliterate_objects($content);
			}
			else if(is_string($content) && !is_numeric($content))
			{
					
				switch($this->get_current_script($this->options))
				{
					case 'cyr_to_lat' :
						$content = $this->cyr_to_lat($content);
						break;
						
					case 'lat_to_cyr' :
						$content = $this->lat_to_cyr($content);			
						break;
				}
			}
			return $content;
		}
		
	}
endif;
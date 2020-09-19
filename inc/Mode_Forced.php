<?php if ( ! defined( 'WPINC' ) ) { die( "Don't mess with us." ); }
/**
 * Forced Transliteration Mode
 *
 * @link              http://infinitumform.com/
 * @since             1.0.0
 * @package           Serbian_Transliteration
 */
if(!class_exists('Serbian_Transliteration_Mode_Forced')) :
class Serbian_Transliteration_Mode_Forced extends Serbian_Transliteration
{
	private $options;
	
	function __construct($options){
		
		$this->options = $options;
		$transient = 'transliteration_cache_' . $this->get_current_script($this->options) . '_' . $this->get_current_page_ID();

		$filters = array(
			'comment_text'					=> 'content',
			'comments_template' 			=> 'content',
			'the_content' 					=> 'content',
			'the_title' 					=> 'content',
			'wp_nav_menu_items' 			=> 'content',
			'get_post_time' 				=> 'content',
			'wp_title' 						=> 'content',
			'the_date' 						=> 'content',
			'get_the_date' 					=> 'content',
			'the_content_more_link' 		=> 'content',
			'pre_get_document_title'		=> 'content',
			'default_post_metadata' 		=> 'content',
			'get_comment_metadata' 			=> 'content',
			'get_term_metadata' 			=> 'content',
			'get_user_metadata' 			=> 'content',
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
			'document_title_parts' 			=> 'title_parts',
			'sanitize_title'				=> 'force_permalink_to_latin',
			'the_permalink'					=> 'force_permalink_to_latin',
			'wp_unique_post_slug'			=> 'force_permalink_to_latin'
		);
		
		// WooCommerce
		if(!function_exists('is_plugin_active')) include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			$filters = array_merge($filters, array(
				'woocommerce_product_single_add_to_cart_text' => 'content',
				'woocommerce_email_footer_text' => 'content',
				'woocommerce_get_availability_text' => 'content',
				'woocommerce_get_price_html_from_text' => 'content',
				'woocommerce_order_button_text' => 'content',
				'woocommerce_pay_order_button_text' => 'content',
				'filter_woocommerce_product_add_to_cart_text' => 'content',
				'woocommerce_product_single_add_to_cart_text' => 'content',
				'woocommerce_thankyou_order_received_text' => 'content',
				'wc_add_to_cart_message_html' => 'content',
				'woocommerce_admin_stock_html' => 'content',
				'woocommerce_cart_no_shipping_available_html' => 'content',
				'sale_price_dates_from' => 'content',
				'sale_price_dates_to' => 'content',
				'woocommerce_dropdown_variation_attribute_options_html' => 'content',
				'woocommerce_date_input_html_pattern' => 'content',
				'woocommerce_cart_totals_taxes_total_html' => 'content',
				'woocommerce_cart_totals_fee_html' => 'content',
				'woocommerce_cart_totals_coupon_html' => 'content',
				'woocommerce_cart_totals_order_total_html' => 'content',
				'woocommerce_coupon_discount_amount_html' => 'content',
				'woocommerce_empty_price_html' => 'content',
				'woocommerce_grouped_price_html' => 'content',
				'woocommerce_grouped_empty_price_html' => 'content',
				'woocommerce_get_stock_html' => 'content',
				'woocommerce_get_price_html_from_to' => 'content',
				'woocommerce_get_price_html' => 'content',
				'woocommerce_layered_nav_term_html' => 'content',
				'woocommerce_no_shipping_available_html' => 'content',
				'woocommerce_order_item_quantity_html' => 'content',
				'woocommerce_order_button_html' => 'content',
				'woocommerce_product_get_rating_html' => 'content',
				'woocommerce_pay_order_button_html' => 'content',
				'wc_payment_gateway_form_saved_payment_methods_html' => 'content',
				'woocommerce_subcategory_count_html' => 'content',
				'woocommerce_stock_html' => 'content',
				'woocommerce_single_product_image_thumbnail_html' => 'content',
				'woocommerce_variable_price_html' => 'content',
				'woocommerce_variable_empty_price_html' => 'content'
			));
		}
		
		if(isset($this->options['avoid-admin']) && $this->options['avoid-admin'] == 'yes')
		{
			if(!is_admin())
			{
				foreach($filters as $filter=>$function) $this->add_filter($filter, $function, 9999999, 1);
			}
		}
		else
		{
			foreach($filters as $filter=>$function) $this->add_filter($filter, $function, 9999999, 1);
		}
		
		if(!is_admin())
		{
			$this->add_action('wp_loaded', 'output_buffer_start', 999);
			$this->add_action('shutdown', 'output_buffer_end', 999);
			
			$this->add_action('rss_head', 'rss_output_buffer_start', 999);
			$this->add_action('rss_footer', 'rss_output_buffer_end', 999);
			
			$this->add_action('rss2_head', 'rss_output_buffer_start', 999);
			$this->add_action('rss2_footer', 'rss_output_buffer_end', 999);
			
			$this->add_action('rdf_head', 'rss_output_buffer_start', 999);
			$this->add_action('rdf_footer', 'rss_output_buffer_end', 999);
			
			$this->add_action('atom_head', 'rss_output_buffer_start', 999);
			$this->add_action('atom_footer', 'rss_output_buffer_end', 999);
		}
		
		$this->add_filter('bloginfo', 'bloginfo', 99999, 2);
		$this->add_filter('bloginfo_url', 'bloginfo', 99999, 2);
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
	
	function output_buffer_start() { 
		ob_start(array(&$this, "output_callback"));
	}
	
	function output_buffer_end() { 
		ob_get_clean();
	}
	
	function rss_output_buffer_start() {
		ob_start();
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
	
	public function output_callback ($buffer='') {
		if(empty($buffer)) return $buffer;
		
		if(!(defined('DOING_AJAX') && DOING_AJAX))
		{
			$sufix = '_' . strlen($buffer);
			
			if (!is_admin() && false === ( $forced_cache = get_transient( $this->transient.$sufix ) ) )
			{
				$buffer = preg_replace_callback('/(?=<div(.*?)>)(.*?)(?<=<\/div>)/s', function($matches) {
					switch($this->get_current_script($this->options))
					{
						case 'cyr_to_lat' :
							$matches[2] = $this->cyr_to_lat($matches[2]);
							break;
							
						case 'lat_to_cyr' :
							$matches[2] = $this->lat_to_cyr($matches[2]);
							break;
					}
					return $matches[2];
				}, $buffer);
				
				if(!is_admin()) set_transient( $this->transient.$sufix, $buffer, MINUTE_IN_SECONDS*3 );
			}
			else
			{
				$buffer = $forced_cache;
			}
		}
		
		return $buffer;
	}
	
	public function content ($content='') {
		if(empty($content)) return $content;
		
		
		if(is_array($content))
		{
			$content = $this->title_parts($content);
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
	
	public function title_parts($titles=array()){
		switch($this->get_current_script($this->options))
		{
			case 'cyr_to_lat' :
				foreach($titles as $key => $val)
				{
					if(is_string($val) && !is_numeric($val)) $titles[$key]= $this->cyr_to_lat($titles[$key]);
				}
				break;
				
			case 'lat_to_cyr' :
				foreach($titles as $key => $val)
				{
					if(is_string($val) && !is_numeric($val)) $titles[$key]= $this->lat_to_cyr($titles[$key], true);
				}
				break;
		}
		
		return $titles;
	}
}
endif;
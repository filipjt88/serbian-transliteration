<?php if ( ! defined( 'WPINC' ) ) { die( "Don't mess with us." ); }

if(!class_exists('Serbian_Transliteration_Settings')) :
class Serbian_Transliteration_Settings_Sidebar extends Serbian_Transliteration
{
	private static $_instance = null;
	private $obj;
	
	function __construct($object)
	{
		$this->obj = $object;
		$this->add_action('rstr/settings/sidebar', 'postbox_infinitum');
		$this->add_action('rstr/settings/sidebar', 'postbox_cloud_hosting');
		$this->add_action('rstr/settings/sidebar', 'postbox_contributors');
		$this->add_action('rstr/settings/sidebar/tab/shortcodes', 'postbox_contributors');
		$this->add_action('rstr/settings/sidebar/tab/functions', 'postbox_contributors');
		$this->add_action('rstr/settings/sidebar/tab/permalink_tool', 'postbox_contributors');
	}
	
	public static function instance($object)
	{
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self($object);
		}
		return self::$_instance;
	}
	
	public function postbox_infinitum(){ ?>
<div class="postbox">
	<h3 class="hndle" style="margin-bottom:0;padding-bottom:0;"><span>INFINITUM FORM®</span></h3><hr>
	<div class="inside">
	<?php
		printf('<p>%s</p>', __('Hire professional developers, designers, SEO masters and marketing ninjas in one place.', RSTR_NAME));
		printf('<p><a href="%1$s" target="_blank">%2$s</a></p>', 'https://infinitumform.com/', __('Read more...', RSTR_NAME));
	?>
	</div>
</div>
	<?php }
	
		public function postbox_cloud_hosting(){ ?>
<div class="postbox">
	<h3 class="hndle" style="margin-bottom:0;padding-bottom:0;"><span><?php _e('Need CLOUD HOSTING ???', RSTR_NAME); ?></span></h3><hr>
	<div class="inside">
	<?php
		printf('<p>%s</p>', __('If you need hosting for your personal needs, for business, any web applications, cloud or dedicated servers, we have the ideal solution for you!', RSTR_NAME));
		printf('<p><a href="%1$s" target="_blank">%2$s</a></p>', 'https://www.draxhost.com/checkout/aff.php?aff=1', __('Read more...', RSTR_NAME));
	?>
	</div>
</div>
	<?php }
	
		public function postbox_contributors(){
			if($plugin_info = $this->plugin_info(array('contributors' => true, 'donate_link' => true))) : ?>
<div class="postbox" id="contributors">
	<h3 class="hndle" style="margin-bottom:0;padding-bottom:0;"><span><?php _e('Contributors & Developers', RSTR_NAME); ?></span></h3><hr>
	<div class="inside flex">
		<?php foreach($plugin_info->contributors as $username => $info) : $info = (object)$info; ?>
		<div class="contributor contributor-<?php echo $username; ?>" id="contributor-<?php echo $username; ?>">
			<a href="<?php echo esc_url($info->profile); ?>" target="_blank">
				<img src="<?php echo esc_url($info->avatar); ?>">
				<h3><?php echo $info->display_name; ?></h3>
			</a>
		</div>
		<?php endforeach; ?>
	</div>
	<div class="inside">
		<?php printf('<p>%s</p>', sprintf(__('If you want to support our work and effort, if you have new ideas or want to improve the existing code, %s.', RSTR_NAME), '<a href="https://github.com/CreativForm/serbian-transliteration" target="_blank">' . __('join our team', RSTR_NAME) . '</a>')); ?>
		<?php printf('<p>%s</p>', sprintf(__('If you want to help further plugin development, you can also %s.', RSTR_NAME), '<a href="' . esc_url($plugin_info->donate_link) . '" target="_blank">' . __('donate something for effort', RSTR_NAME) . '</a>')); ?>
	</div>
</div>
<?php endif;
	}
	
}
endif;
<?php if ( ! defined( 'WPINC' ) ) { die( "Don't mess with us." ); }

// Global class
global $RSTR_USERS, $RSTR_USERS_ARRAY;

// Find wp-admin file path
if (!defined('WP_ADMIN_DIR')) {
	if ( strrpos(WP_CONTENT_DIR, '/wp-content/', 1) !== false) {
		$WP_ADMIN_DIR = substr(WP_CONTENT_DIR, 0, -10) . 'wp-admin';
	} else {
		$WP_ADMIN_DIR = substr(WP_CONTENT_DIR, 0, -11) . '/wp-admin';
	}
	define('WP_ADMIN_DIR', $WP_ADMIN_DIR);
}

// Include Dependency
$include_dependency = false;
if( !function_exists( 'is_plugin_active_for_network' ) || !function_exists('is_plugin_active') ) {
	if(file_exists(WP_ADMIN_DIR . '/includes/plugin.php')){
		include WP_ADMIN_DIR . '/includes/plugin.php';
		$include_dependency = true;
	}
}

/*
 * Main plugin constants
 * @since     1.0.0
 * @verson    1.0.0
 */
 
// Find is localhost or not
if ( ! defined( 'RSTR_LOCAL' ) ) {
	if(isset($_SERVER['REMOTE_ADDR'])) {
		define('RSTR_LOCAL', in_array($_SERVER['REMOTE_ADDR'], array(
			'127.0.0.1',
			'::1'
		)));
	} else {
		define('RSTR_LOCAL', false);
	}
}

/**
 * DEBUG MODE
 *
 * This is need for plugin debugging.
 */
if ( defined( 'WP_DEBUG' ) ){
	if(WP_DEBUG === true || WP_DEBUG === 1)
	{
		if ( ! defined( 'RSTR_DEBUG' ) ) define( 'RSTR_DEBUG', true );
	}
}
if ( defined( 'RSTR_DEBUG' ) ){
	if(RSTR_DEBUG === true || RSTR_DEBUG === 1)
	{
		error_reporting( E_ALL );
		if(function_exists('ini_set'))
		{
			ini_set('display_startup_errors',1);
			ini_set('display_errors',1);
		}
	}
}

// Plugin basename
if ( ! defined( 'RSTR_BASENAME' ) )			define( 'RSTR_BASENAME', plugin_basename( RSTR_FILE ));

// Plugin root
if ( ! defined( 'RSTR_ROOT' ) )				define( 'RSTR_ROOT', rtrim(plugin_dir_path(RSTR_FILE), '/') );

// Plugin URL root
if ( ! defined( 'RSTR_URL' ) )				define( 'RSTR_URL', rtrim(plugin_dir_url( RSTR_FILE ), '/') );

// Assets URL
if ( ! defined( 'RSTR_ASSETS' ) )			define( 'RSTR_ASSETS', RSTR_URL.'/assets' );

// Classes
if ( ! defined( 'RSTR_INC' ) )				define( 'RSTR_INC', RSTR_ROOT.'/inc' );

// Plugin name
if ( ! defined( 'RSTR_NAME' ) )				define( 'RSTR_NAME', 'serbian-transliteration');

// Plugin table
if ( ! defined( 'RSTR_TABLE' ) )			define( 'RSTR_TABLE', 'serbian_transliteration');

// Plugin metabox prefix
if ( ! defined( 'RSTR_METABOX' ) )			define( 'RSTR_METABOX', RSTR_TABLE . '_metabox_');

// Alternate links
if ( ! defined( 'RSTR_ALTERNATE_LINKS' ) )	define( 'RSTR_ALTERNATE_LINKS', true);

// Current plugin version ( if change, clear also session cache )
$RSTR_version = NULL;
if(function_exists('get_file_data') && $plugin_data = get_file_data( RSTR_FILE, array('Version' => 'Version'), false ))
	$RSTR_version = $plugin_data['Version'];
if(!$RSTR_version && preg_match('/\*[\s\t]+?version:[\s\t]+?([0-9.]+)/i', file_get_contents( RSTR_FILE ), $v))
	$RSTR_version = $v[1];
if ( ! defined( 'RSTR_VERSION' ) )			define( 'RSTR_VERSION', $RSTR_version);

// Plugin session prefix (controlled by version)
if ( ! defined( 'RSTR_PREFIX' ) )			define( 'RSTR_PREFIX', RSTR_TABLE . '_' . preg_replace("~[^0-9]~Ui", '', RSTR_VERSION) . '_');

// Is multisite
if( ! defined( 'RSTR_MULTISITE' ) )
{
	define( 'RSTR_MULTISITE', is_plugin_active_for_network( RSTR_BASENAME ) );
}
if( ! defined( 'RSTR_MULTISITE' ) ) 		define( 'RSTR_MULTISITE', false );

// Is Woocommerce exists
if ( ! defined( 'RSTR_WOOCOMMERCE' ) )	define( 'RSTR_WOOCOMMERCE', is_plugin_active( 'woocommerce/woocommerce.php' ));
if ( ! defined( 'RSTR_WOOCOMMERCE' ) )		define( 'RSTR_WOOCOMMERCE', false);

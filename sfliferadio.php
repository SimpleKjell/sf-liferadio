<?php
/*
Plugin Name: Liferadio Doppeltes Gehalt
Plugin URI: http://simplefox.de
Description: Liferadio Kampagne: Doppeltes Gehalt
Version: 1.0.0
Author: Simplefox
Author URI: http://simplefox.de
*/

define('sfliferadio_url',plugin_dir_url(__FILE__ ));
define('sfliferadio_path',plugin_dir_path(__FILE__ ));
define('sfliferadio_template','basic');

// Plugin Version
function sfliferadio_get_plugin_version()
{
    $default_headers = array( 'Version' => 'Version' );
    $plugin_data = get_file_data( __FILE__, $default_headers, 'plugin' );
    return $plugin_data['Version'];
}

$plugin = plugin_basename(__FILE__);


/* Textdomain (localization) */
function sfliferadio_load_textdomain()
{
  $locale = apply_filters( 'plugin_locale', get_locale(), 'sf-liferadio' );
  $mofile = sfliferadio_path . "languages/sfliferadio-$locale.mo";

	// Global + Frontend Locale
	load_textdomain( 'sfliferadio', $mofile );
	load_plugin_textdomain( 'sfliferadio', false, dirname(plugin_basename(__FILE__)).'/languages/' );
}
add_action('init', 'sfliferadio_load_textdomain');

/* Master Class  */
require_once (sfliferadio_path . 'sflclasses/sfl.sfliferadio.class.php');
$sf_liferadio = new SFLiferadio();
$sf_liferadio->plugin_init();
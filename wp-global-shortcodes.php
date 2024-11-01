<?php
/**
 * Plugin Name: WP Global Shortcodes
 * Plugin URI: https://rarus.io/wp-global-shortcodes/?utm_source=rarus-plugin-wpgs&utm_medium=plugin-page&utm_campaign=Plugin%20to%20Plugin
 * Description: Write your content once, use it globally.
 * Author: Rarus
 * Author URI: https://rarus.io/?utm_source=rarus-plugin-wpgs&utm_medium=plugin-page&utm_campaign=Plugin%20to%20Rarus
 * Version: 1.0.0
 * Text Domain: wp-global-shortcodes
 * Domain Path: languages
 *
 * You should have received a copy of the GNU General Public License
 * along with WP Global Shortcodes. If not, see <http://www.gnu.org/licenses/>.
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

// Plugin name.
define( 'WPGS_NAME', 'WP Global Shortcodes' );

// Plugin version.
define( 'WPGS_VERSION', '1.0' );

// Define Rarus privacy if not defined.
//Commonly it is loaded by our plugin handler
if(!defined('RARUS_PRIVACY'))
    define( 'RARUS_PRIVACY', 'rarus_privacy' );

// Plugin Root File.
define( 'WPGS_PLUGIN_FILE', __FILE__ );

// Plugin nbabse.
define( 'WPGS_PLUGIN_BASE', plugin_basename( WPGS_PLUGIN_FILE ) );

// Plugin Folder Path.
define( 'WPGS_PLUGIN_DIR', plugin_dir_path( WPGS_PLUGIN_FILE ) );

// Plugin Folder URL.
define( 'WPGS_PLUGIN_URL', plugin_dir_url( WPGS_PLUGIN_FILE ) );

// Plugin Root File.
define( 'WPGS_TEXTDOMAIN', 'wp-global-shortcodes' );

/**
 * Load our main instance for the helper function
 */
require_once WPGS_PLUGIN_DIR . 'core/class-wpgs-core.php';

/**
 * Our helper object class
 *
 * @return object|WP_Global_Shortcodes
 */
function WPGS() {
    return WP_Global_Shortcodes::instance();
}

// Run WP Global Shortcodes helper object
WPGS();
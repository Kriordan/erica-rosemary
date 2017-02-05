<?php
//////////////////////////////////////////////////////////////////
// Set Content Width
//////////////////////////////////////////////////////////////////
if ( ! isset( $content_width ) )
	$content_width = 1080;

//////////////////////////////////////////////////////////////////
// Theme set up
//////////////////////////////////////////////////////////////////
add_action( 'after_setup_theme', 'solopine_theme_setup' );

if ( !function_exists('solopine_theme_setup') ) {

	function solopine_theme_setup() {
	
		// Register navigation menu
		register_nav_menus(
			array(
				'main-menu' => 'Primary Menu',
			)
		);
		
		// Title tag
		add_theme_support( 'title-tag' );
		
		// Localization support
		load_theme_textdomain('solopine', get_template_directory() . '/lang');
		
		// Post formats
		add_theme_support( 'post-formats', array( 'gallery', 'video', 'audio' ) );
		
		// Featured image
		add_theme_support( 'post-thumbnails' );
		add_image_size( 'full-thumb', 1080, 0, true );
		add_image_size( 'misc-thumb', 520, 400, true );
		
		// Feed Links
		add_theme_support( 'automatic-feed-links' );
	
	}

}

//////////////////////////////////////////////////////////////////
// Register & enqueue styles/scripts
//////////////////////////////////////////////////////////////////

add_action( 'wp_enqueue_scripts','solopine_load_scripts' );

function solopine_load_scripts() {

	// Register scripts and styles
	wp_register_style('sp_style', get_stylesheet_directory_uri() . '/style.css');
	wp_register_style('slicknav-css', get_template_directory_uri() . '/css/slicknav.css');
	wp_register_style('bxslider-css', get_template_directory_uri() . '/css/jquery.bxslider.css');
	wp_register_style('font-awesome', get_template_directory_uri() . '/css/font-awesome.min.css');
	wp_register_style('responsive', get_template_directory_uri() . '/css/responsive.css');
	
	wp_register_script('bxslider', get_template_directory_uri() . '/js/jquery.bxslider.min.js', 'jquery', '', true);
	wp_register_script('slicknav', get_template_directory_uri() . '/js/jquery.slicknav.min.js', 'jquery', '', true);
	wp_register_script('fitvids', get_template_directory_uri() . '/js/fitvids.js', 'jquery', '', true);
	wp_register_script('sp_scripts', get_template_directory_uri() . '/js/solopine.js', 'jquery', '', true);
	
	// Enqueue scripts and styles
	wp_enqueue_style('sp_style');
	wp_enqueue_style('slicknav-css');
	wp_enqueue_style('bxslider-css');
	wp_enqueue_style('font-awesome');
	
	if(!get_theme_mod('sp_responsive')) {
	wp_enqueue_style('responsive');
	}

	// Fonts
	wp_enqueue_style('default_body_font', 'http://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic&subset=latin,latin-ext');
	wp_enqueue_style('default_heading_font', 'http://fonts.googleapis.com/css?family=Playfair+Display:400,700,400italic,700italic&subset=latin,latin-ext');
	
	// JS
	wp_enqueue_script('jquery');
	wp_enqueue_script('bxslider');
	wp_enqueue_script('slicknav');
	wp_enqueue_script('fitvids');
	wp_enqueue_script('sp_scripts');

	
	if (is_singular() && get_option('thread_comments'))	wp_enqueue_script('comment-reply');
	
}


//////////////////////////////////////////////////////////////////
// Include files
//////////////////////////////////////////////////////////////////

// Theme Options
include('functions/customizer/sp_custom_controller.php');
include('functions/customizer/sp_customizer_settings.php');
include('functions/customizer/sp_customizer_style.php');

// Widgets
include("inc/widgets/about_widget.php");
include("inc/widgets/social_widget.php");
include("inc/widgets/post_widget.php");
include("inc/widgets/facebook_widget.php");

//////////////////////////////////////////////////////////////////
// Register footer widgets
//////////////////////////////////////////////////////////////////
if ( function_exists('register_sidebar') ) {
	register_sidebar(array(
		'name' => 'Sidebar',
		'id' => 'sidebar-1',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widget-title">',
		'after_title' => '</h4>',
	));
	register_sidebar(array(
		'name' => 'Instagram Footer',
		'id' => 'sidebar-2',
		'before_widget' => '<div id="%1$s" class="instagram-widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="instagram-title">',
		'after_title' => '</h4>',
		'description' => 'Use the Instagram widget here. IMPORTANT: For best result select "Large" under "Photo Size" and set number of photos to 6.',
	));
}




//////////////////////////////////////////////////////////////////
// COMMENTS LAYOUT
//////////////////////////////////////////////////////////////////
if ( !function_exists('solopine_comments') ) {
	function solopine_comments($comment, $args, $depth) {
		$GLOBALS['comment'] = $comment;
		
		?>
		<li <?php comment_class(); ?> id="comment-<?php comment_ID() ?>">
			
			<div class="thecomment">
						
				<div class="author-img">
					<?php echo get_avatar($comment,$args['avatar_size']); ?>
				</div>
				
				<div class="comment-text">
					<span class="reply">
						<?php comment_reply_link(array_merge( $args, array('reply_text' => __('Reply', 'solopine'), 'depth' => $depth, 'max_depth' => $args['max_depth'])), $comment->comment_ID); ?>
						<?php edit_comment_link(__('Edit', 'solopine')); ?>
					</span>
					<span class="author"><?php echo get_comment_author_link(); ?></span>
					<span class="date"><?php printf(__('%1$s at %2$s', 'solopine'), get_comment_date(),  get_comment_time()) ?></span>
					<?php if ($comment->comment_approved == '0') : ?>
						<em><i class="icon-info-sign"></i> <?php _e('Comment awaiting approval', 'solopine'); ?></em>
						<br />
					<?php endif; ?>
					<?php comment_text(); ?>
				</div>
						
			</div>
			
			
		</li>

		<?php 
	}
}

//////////////////////////////////////////////////////////////////
// PAGINATION
//////////////////////////////////////////////////////////////////
if ( !function_exists('solopine_pagination') ) {

function solopine_pagination() {
	
	?>
	
	<div class="pagination">

		<div class="older"><?php next_posts_link(__( 'Older Posts <i class="fa fa-angle-double-right"></i>', 'solopine')); ?></div>
		<div class="newer"><?php previous_posts_link(__( '<i class="fa fa-angle-double-left"></i> Newer Posts', 'solopine')); ?></div>
		
	</div>
					
	<?php
	
}

}
	
//////////////////////////////////////////////////////////////////
// AUTHOR SOCIAL LINKS
//////////////////////////////////////////////////////////////////
if ( !function_exists('solopine_contactmethods') ) {
function solopine_contactmethods( $contactmethods ) {

	$contactmethods['twitter']   = 'Twitter Username';
	$contactmethods['facebook']  = 'Facebook Username';
	$contactmethods['google']    = 'Google Plus Username';
	$contactmethods['tumblr']    = 'Tumblr Username';
	$contactmethods['instagram'] = 'Instagram Username';
	$contactmethods['pinterest'] = 'Pinterest Username';

	return $contactmethods;
}
add_filter('user_contactmethods','solopine_contactmethods',10,1);
}

//////////////////////////////////////////////////////////////////
// PREVENT SCROLL ON READ MORE LINK
//////////////////////////////////////////////////////////////////
if ( !function_exists('remove_more_link_scroll') ) {
function remove_more_link_scroll( $link ) {
	$link = preg_replace( '|#more-[0-9]+|', '', $link );
	return $link;
}
add_filter( 'the_content_more_link', 'remove_more_link_scroll' );
}

//////////////////////////////////////////////////////////////////
// EXCLUDE FEATURED CATEGORY
//////////////////////////////////////////////////////////////////
if ( !function_exists('sp_category') ) {
function sp_category($separator) {
	
	if(get_theme_mod( 'sp_featured_cat_hide' ) == true) {
		
		$excluded_cat = get_theme_mod('sp_featured_cat');
		
		$first_time = 1;
		foreach((get_the_category()) as $category) {
			if ($category->cat_ID != $excluded_cat) {
				if ($first_time == 1) {
					echo '<a href="' . get_category_link( $category->term_id ) . '" title="' . sprintf( __( "View all posts in %s", "solopine" ), $category->name ) . '" ' . '>'  . $category->name.'</a>';
					$first_time = 0;
				} else {
					echo $separator . '<a href="' . get_category_link( $category->term_id ) . '" title="' . sprintf( __( "View all posts in %s", "solopine" ), $category->name ) . '" ' . '>' . $category->name.'</a>';
				}
			}
		}
	
	} else {
		
		$first_time = 1;
		foreach((get_the_category()) as $category) {
			if ($first_time == 1) {
				echo '<a href="' . get_category_link( $category->term_id ) . '" title="' . sprintf( __( "View all posts in %s", "solopine" ), $category->name ) . '" ' . '>'  . $category->name.'</a>';
				$first_time = 0;
			} else {
				echo $separator . '<a href="' . get_category_link( $category->term_id ) . '" title="' . sprintf( __( "View all posts in %s", "solopine" ), $category->name ) . '" ' . '>' . $category->name.'</a>';
			}
		}
	
	}
}
}

//////////////////////////////////////////////////////////////////
// THE EXCERPT
//////////////////////////////////////////////////////////////////
if ( !function_exists('custom_excerpt_length') ) {
function custom_excerpt_length( $length ) {
	return 200;
}
add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );
}

if ( !function_exists('sp_string_limit_words') ) {
function sp_string_limit_words($string, $word_limit)
{
	$words = explode(' ', $string, ($word_limit + 1));
	
	if(count($words) > $word_limit) {
		array_pop($words);
	}
	
	return implode(' ', $words);
}
}

/**
 * This file represents an example of the code that themes would use to register
 * the required plugins.
 *
 * It is expected that theme authors would copy and paste this code into their
 * functions.php file, and amend to suit.
 *
 * @see http://tgmpluginactivation.com/configuration/ for detailed documentation.
 *
 * @package    TGM-Plugin-Activation
 * @subpackage Example
 * @version    2.6.1 for parent theme Solopine for publication on WordPress.org
 * @author     Thomas Griffin, Gary Jones, Juliette Reinders Folmer
 * @copyright  Copyright (c) 2011, Thomas Griffin
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       https://github.com/TGMPA/TGM-Plugin-Activation
 */

require_once get_template_directory() . '/class-tgm-plugin-activation.php';

add_action( 'tgmpa_register', 'solopine_register_required_plugins' );


function solopine_register_required_plugins() {

	$plugins = array(
		
		array(
			'name'     				=> 'Vafpress Post Formats UI', // The plugin name
			'slug'     				=> 'vafpress-post-formats-ui-develop', // The plugin slug (typically the folder name)
			'source'   				=> get_stylesheet_directory() . '/plugins/vafpress-post-formats-ui-develop.zip', // The plugin source
			'required' 				=> true, // If false, the plugin is only 'recommended' instead of required
			'version' 				=> '', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
			'force_activation' 		=> false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
			'force_deactivation' 	=> false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
			'external_url' 			=> '', // If set, overrides default API URL and points to an external URL
		),
		array(
			'name'     				=> 'WP Instagram Widget', // The plugin name
			'slug'     				=> 'wp-instagram-widget', // The plugin slug (typically the folder name)
			'required' 				=> false, // If false, the plugin is only 'recommended' instead of required
			'version' 				=> '', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
			'force_activation' 		=> false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
			'force_deactivation' 	=> false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
			'external_url' 			=> '', // If set, overrides default API URL and points to an external URL
		),
		array(
			'name'     				=> 'Contact Form 7', // The plugin name
			'slug'     				=> 'contact-form-7', // The plugin slug (typically the folder name)
			'required' 				=> false, // If false, the plugin is only 'recommended' instead of required
			'version' 				=> '', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
			'force_activation' 		=> false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
			'force_deactivation' 	=> false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
			'external_url' 			=> '', // If set, overrides default API URL and points to an external URL
		)
	);

	$config = array(
		'id'           => 'solopine',                 // Unique ID for hashing notices for multiple instances of TGMPA.
		'default_path' => '',                      // Default absolute path to bundled plugins.
		'menu'         => 'tgmpa-install-plugins', // Menu slug.
		'has_notices'  => true,                    // Show admin notices or not.
		'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => false,                   // Automatically activate plugins after installation or not.
		'message'      => '',                      // Message to output right before the plugins table.

	);

	tgmpa( $plugins, $config );
}

//////////////////////////////////////////////////////////////////
// TWITTER AMPERSAND ENTITY DECODE
//////////////////////////////////////////////////////////////////
if ( !function_exists('solopine_social_title') ) {
function solopine_social_title( $title ) {
    $title = html_entity_decode( $title );
    $title = urlencode( $title );
    return $title;
}
}
<?php
// Exit if trying to access directly
if ( ! defined( 'ABSPATH' ) ) exit;

// Display Errors ?
ini_set('display_errors',FALSE);

// GLOBALS
////////////////////////////////////////////////////////////////////////////////
require_once 'inc/globals.php';

// THEME OPTIONS
////////////////////////////////////////////////////////////////////////////////

// Disable Admin Email Check - WP 5.3
add_filter('admin_email_check_interval', '__return_false');

// Add Items To Theme Support
if (function_exists('add_theme_support')){

    // Add Menu Support
    add_theme_support('menus');
    // Add Title Tag Support
    add_theme_support('title-tag');
    // Add Thumbnail Theme Support
    add_theme_support('post-thumbnails');
    // Add Excerpt On Page Post Type
    add_post_type_support( 'page', 'excerpt' );

}

// Initialize Widgets Area
add_action( 'widgets_init', 'mkwvs_widgets_init' );
function mkwvs_widgets_init() {
    register_sidebar( array(
        'name' =>'Main Sidebar',
        'id' => 'main-sidebar',
        'description'   => '',
        'before_widget' => '<li id="%1$s" class="widget %2$s">',
        'after_widget'  => '</li>',
        'before_title'  => '<h2 class="widgettitle">',
        'after_title'   => '</h2>',
    ) );
}

// Add Mimes Types : SVG Support Upload
add_filter('upload_mimes', 'mkwvs_add_mime_types');
function mkwvs_add_mime_types($mimes) {
  $mimes['svg'] = 'image/svg+xml';
  return $mimes;
}

// Basic Load Custom Post Types
////////////////////////////////////////////////////////////////////////////////
//require_once 'cpt/cpt-slider.php';
//require_once 'cpt/cpt-projet.php';

// Dynamic Load Custom Post Types
////////////////////////////////////////////////////////////////////////////////
$a_activated_cpt = array();
$handle = fopen($_SERVER['DOCUMENT_ROOT'] . "/wp-content/themes/mw-blank/cpt/cpt-activation.txt", "r");
if ($handle) {
    while (($line = fgets($handle)) !== false) { $cpt = 'cpt/cpt-'.trim($line).'.php'; require_once $cpt; }
    fclose($handle);
} else { /* error opening the file.*/ }



// BACKEND
////////////////////////////////////////////////////////////////////////////////

// Admin Load Fonts, Styles & Scripts
function mkwvs_admin_scripts_styles(){

    // Load : Custom Admin Scripts JS
    wp_register_script('admin-scripts-js', get_template_directory_uri(). '/js/admin-scripts.js' , array('jquery'), '', true);
    wp_enqueue_script('admin-scripts-js'); // Enqueue it!

    // Admin CSS
    wp_register_style('admin-styles', get_template_directory_uri() . '/admin-style.css', array(), '', 'all');
    wp_enqueue_style('admin-styles'); // Enqueue it!
}

// Remove Help Tab
add_filter( 'contextual_help', 'mkwvs_remove_help', 9999, 3 );
function mkwvs_remove_help($old_help, $screen_id, $screen){
    $screen->remove_help_tabs();
    return $old_help;
}

// Load Admin Custom Dashboard Widget
if (current_user_can('manage_options') && is_admin() ){
    //require_once 'dashboxes/dashbox-social.php';
    require_once 'dashboxes/dashbox-identity.php';
}


// FRONT END
////////////////////////////////////////////////////////////////////////////////

// Optimize Head Section
if (!function_exists('mkwvs_clean_head')){
    add_action('init', 'mkwvs_clean_head');
    function mkwvs_clean_head(){
        // Hide Admin Bar On Front Office
        add_filter('show_admin_bar', '__return_false');
        // Add Scripts & Styles : Front End
        add_action('wp_enqueue_scripts', 'mkwvs_scripts_styles', 20 );
        // Add Scripts & Styles : Back End
        add_action('admin_enqueue_scripts', 'mkwvs_admin_scripts_styles', 21 );
        // REMOVE WP EMOJI
        /*remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('wp_print_styles', 'print_emoji_styles');
        remove_action('admin_print_scripts', 'print_emoji_detection_script' );
        remove_action('admin_print_styles', 'print_emoji_styles' );*/
        // DISABLED EMBED
        //add_action( 'wp_footer', 'mkwvs_deregister_embed' );
        // Remove Post Tags Default Post Taxonomy
        unregister_taxonomy_for_object_type('post_tag', 'post');
    }
}

// Deregister Embed Js
function mkwvs_deregister_embed(){
    wp_deregister_script( 'wp-embed' );
}

// Load Fonts, Styles & Scripts
function mkwvs_scripts_styles(){

    // FONTS

    // STYLES

    // Slick Carousel CSS
    wp_register_style('slick-slider-style', get_template_directory_uri(). '/js/slick/slick.css',array(), '', 'all' );
    wp_enqueue_style('slick-slider'); // Enqueue it!

    // Slick Carousel CSS Theme
    wp_register_style('slick-slider-style-theme', get_template_directory_uri(). '/js/slick/slick-theme.css',array(), '', 'all' );
    wp_enqueue_style('slick-slider-style-theme'); // Enqueue it!

    // Theme CSS
    wp_register_style('styles', get_template_directory_uri() . '/css/main.css', array(), '', 'all');
    wp_enqueue_style('styles'); // Enqueue it!


    // SCRIPTS

    // Load : Last Version Of jQuery
    wp_deregister_script('jquery');
    wp_register_script('jquery','https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js', array(), '', true);
    wp_enqueue_script('jquery');

    // Load : Slick JS
    wp_register_script('slick-carousel-js', get_template_directory_uri(). '/js/slick/slick.min.js',array('jquery'), '', true );
    wp_enqueue_script('slick-carousel-js'); // Enqueue it!

    if (is_home() || is_single()){
        // Load : PARRALAX
        wp_register_script('parallax-js', get_template_directory_uri(). '/js/parallax/jquery.parallax-1.1.3.js',array('jquery'), '', true );
        wp_enqueue_script('parallax-js'); // Enqueue it!
        // Load : jQuery-UI
        wp_register_script('jquery-ui', get_template_directory_uri(). '/js/jquery-ui.js',array('jquery'), '', true );
        wp_enqueue_script('jquery-ui'); // Enqueue it!

        // Load : Waypoints
        wp_register_script('waypoints-js', get_template_directory_uri(). '/js/waypoints/jquery.waypoints.min.js',array('jquery','typed-js'), '', true );
        wp_enqueue_script('waypoints-js'); // Enqueue it!


    }


    // Load : Custom Scripts JS
    wp_register_script('scripts-js', get_template_directory_uri(). '/js/scripts.js' , array('jquery', 'slick-carousel-js'), '', true);
    wp_enqueue_script('scripts-js'); // Enqueue it!

    // Make AjaxUrl Visible In Scripts
    wp_localize_script('scripts-js','ajaxurl', admin_url('admin-ajax.php'));

}

// Remove Vesion Number Form CSS & JS
add_filter( 'style_loader_src', 'mkwvs_remove_cssjs_ver', 10, 2 );
add_filter( 'script_loader_src', 'mkwvs_remove_cssjs_ver', 10, 2 );
function mkwvs_remove_cssjs_ver( $src ) {
    if( strpos( $src, '?ver=' ) ){ $src = remove_query_arg( 'ver', $src ); }
    return $src;
}

// Custom Excerpt Length
add_filter( 'excerpt_length', 'mkwvs_excerpt_length', 999 );
function mkwvs_excerpt_length( $length ) {
	return 30;
}

// Theme Pagination Fonction From GEEKPRESS.FR
// www.geekpress.fr/pagination-wordpress-sans-plugin/
if( !function_exists( 'theme_pagination' ) ) {

    function theme_pagination($custom_query, $custom_args = '') {

	global $wp_query, $wp_rewrite;
	$custom_query->query_vars['paged'] > 1 ? $current = $custom_query->query_vars['paged'] : $current = 1;

	$pagination = array(
		'base' => @add_query_arg('page','%#%'),
		'format' => '',
		'total' => $custom_query->max_num_pages,
		'current' => $current,
	        'show_all' => false,
	        'end_size'     => 1,
	        'mid_size'     => 2,
		'type' => 'list',
		'next_text' => '»',
		'prev_text' => '«'
	);
	if (!empty($custom_args)){
            if (is_array($custom_args)){
                $a_args = array();
                foreach ($custom_args as $custom_arg) {
                    $a_args[$custom_arg] = $_POST[$custom_arg];
                }

                $pagination['add_args'] = $a_args;
            }
        }else{
            if( $wp_rewrite->using_permalinks() )
                    $pagination['base'] = user_trailingslashit( trailingslashit( remove_query_arg( 's', get_pagenum_link( 1 ) ) ) . 'page/%#%/', 'paged' );

            if( !empty($wp_query->query_vars['s']) )
                    $pagination['add_args'] = array( 's' => str_replace( ' ' , '+', get_query_var( 's' ) ) );
        }

	echo str_replace('page/1/','', paginate_links( $pagination ) );
    }
}


// Add Contact Form Action On Init
add_action( 'init', 'mkwvs_contact_form_init' );
function mkwvs_contact_form_init(){
    add_action('wp_ajax_mkwvs_contact_form_submit', 'mkwvs_contact_form_submit');
    add_action('wp_ajax_nopriv_mkwvs_contact_form_submit', 'mkwvs_contact_form_submit');
}
// Ajax Method : Contact Form Submit
function mkwvs_contact_form_submit(){

    $return = array('success' => false, 'error' => '');

    // Verify Nonce
    if ( !wp_verify_nonce( $_REQUEST['contact-form-nonce'], 'contact-form-nonce')) exit();

    $contact_name      = (string)$_REQUEST['c-name'];
    $contact_firstname = (string)$_REQUEST['c-firstname'];
    $contact_email     = (string)$_REQUEST['c-mail'];
    $contact_message   = (string)$_REQUEST['c-message'];

    // Send Mail
    $headers = "MIME-Version: 1.0"."\n";
    $headers .= "Content-type: text/html; charset=utf-8"."\n";
    $headers .= "From: \"Makewaves - Blank : Contact\" <no-reply@makewaves.fr>" ;
    $content = file_get_contents(str_replace('/functions.php', '/emails/contact.html', __FILE__));
    $content = str_replace('[website_url]', get_bloginfo('url'), $content);
    $content = str_replace('[contact_name]', $contact_name, $content);
    $content = str_replace('[contact_firstname]', $contact_firstname, $content);
    $content = str_replace('[contact_email]', $contact_email, $content);
    $content = str_replace('[contact_message]', apply_filters('the_content',$contact_message), $content);
    // Remove Protected Chars
    $content = stripslashes($content);


    // Send Mail
    $destinataires = 'herve.thomas@labside.fr';
    if( wp_mail($destinataires, 'Demande de contact', $content, $headers )) {

        // Return : Success Feedback
        $return['success'] = true;
        wp_send_json($return);
        exit();

    }else{

        // Return : Error Message
        $return['error'] = 'mail-not-send';
        wp_send_json($return);
        exit();
    }
}

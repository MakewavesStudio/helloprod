<?php
// Makewaves : CPT SLIDER

// Slider Create Post Type Hook
add_action( 'init', 'mkwvs_slider_create_post_type' );
function mkwvs_slider_create_post_type(){
    
    $labels = array(
		'name'                => 'Sliders',
		'singular_name'       => 'Slide',
		'menu_name'           => 'Sliders',
		'parent_item_colon'   => 'Element parent',
		'all_items'           => 'Voir tous les slides',
		'view_item'           => 'Voir le slide',
		'add_new_item'        => 'Ajouter un slide',
		'add_new'             => 'Ajouter ',
		'edit_item'           => 'Editer le slide',
		'update_item'         => 'Mettre à jour',
		'search_items'        => 'Rechercher',
		'not_found'           => 'Aucun slide',
		'not_found_in_trash'  => 'Aucun slide dans la corbeille',
	);
	$args = array(
		'label'               => 'slide',
		'description'         => 'Custom Post Type Slide',
		'labels'              => $labels,
		'supports'            => array( 'title', 'thumbnail'),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 6,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'page',
        'menu_icon'           => 'dashicons-images-alt2',
	);
	register_post_type( 'slide', $args );
}

// Slider Admin Init Hook
add_action('admin_init','mkwvs_slider_admin_init');
function mkwvs_slider_admin_init(){
    
    // Add Meta Box : Infos Complémentaires 
    add_meta_box( 'mkwvs_slider_meta_box', 'Informations complémentaires', 'mkwvs_slider_infos_metabox_render', 'slide', 'normal', 'high');
    add_action( 'save_post', 'mkwvs_slider_infos_save_postdata');
    // Add Custom Columns In Post Type List
    add_filter( 'manage_edit-slide_columns', 'mkwvs_slider_add_column' ) ;
    add_action( 'manage_slide_posts_custom_column', 'mkwvs_slider_add_custom_columns_render', 10, 2);
    add_action( 'admin_head', 'mkwvs_slider_admin_styles');
}

// Slider Add Custom Styles
function mkwvs_slider_admin_styles() {
    echo '<style type="text/css">';
    echo '.column-thumb { text-align: center; width:10% !important; }';
    echo '#display-order{width:10%;}';
    echo '</style>';
}

// Slider Meta Box Render
function mkwvs_slider_infos_metabox_render (){
    global $post;

    $slider_accroche_1    = get_post_meta($post->ID, 'slider_accroche_1', true);
    $slider_accroche_2    = get_post_meta($post->ID, 'slider_accroche_2', true);
    $slider_accroche_3    = get_post_meta($post->ID, 'slider_accroche_3', true);
    $slider_button_text   = get_post_meta($post->ID, 'slider_button_text', true);
    $slider_button_link   = get_post_meta($post->ID, 'slider_button_link', true);
    
    // Use nonce for verification
    echo '<input type="hidden" name="slider_metabox_nonce" value="'. wp_create_nonce(basename(__FILE__)). '" />';

    // Makewaves Slider Accroche Field
    echo '<label style="width:25%;display:block;float:left;">Accorche (Partie 1) :</label>';
    echo '<input style="width:50%;" type="text" name="slider_accroche_1" id="slider_accroche_1" value="'.$slider_accroche_1.'" />';
    
    // Makewaves Slider Accroche Field
    echo '<label style="width:25%;display:block;float:left;">Accorche (Partie 2) :</label>';
    echo '<input style="width:50%;" type="text" name="slider_accroche_2" id="slider_accroche_2" value="'.$slider_accroche_2.'" />';
    
    // Makewaves Slider Accroche Field
    echo '<label style="width:25%;display:block;float:left;">Accorche (Partie 3) :</label>';
    echo '<input style="width:50%;" type="text" name="slider_accroche_3" id="slider_accroche_3" value="'.$slider_accroche_3.'" />';
    
    // Makewaves Slider Button Text Field
    echo '<label style="width:25%;display:block;float:left;">Text Bouton :</label>';
    echo '<input style="width:50%;" type="text" name="slider_button_text" id="slider_button_text" value="'.$slider_button_text.'" />';
    
    // Makewaves Slider Button Link Field
    echo '<label style="width:25%;display:block;float:left;">Lien Bouton :</label>';
    echo '<input style="width:50%;" type="text" name="slider_button_link" id="slider_button_link" value="'.$slider_button_link.'" />';

}

// Slider Meta Box Save Data
function mkwvs_slider_infos_save_postdata($post_id){
    
    // Check Autosave

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;

    if (!wp_verify_nonce($_POST['slider_metabox_nonce'], basename(__FILE__))) return $post_id;

    // Update Slider Accroche
    $slider_accroche_1 = $_POST['slider_accroche_1'];
    if (!empty($slider_accroche_1)) update_post_meta ($post_id, 'slider_accroche_1', $slider_accroche_1);
    else update_post_meta ($post_id, 'slider_accroche_1', '');
    
    // Update Slider Accroche
    $slider_accroche_2 = $_POST['slider_accroche_2'];
    if (!empty($slider_accroche_2)) update_post_meta ($post_id, 'slider_accroche_2', $slider_accroche_2);
    else update_post_meta ($post_id, 'slider_accroche_2', '');
    
    // Update Slider Accroche
    $slider_accroche_3 = $_POST['slider_accroche_3'];
    if (!empty($slider_accroche_3)) update_post_meta ($post_id, 'slider_accroche_3', $slider_accroche_3);
    else update_post_meta ($post_id, 'slider_accroche_3', '');
    
    // Update Slider Button Text
    $slider_button_text = sanitize_text_field($_POST['slider_button_text']);
    if (!empty($slider_button_text)) update_post_meta ($post_id, 'slider_button_text', $slider_button_text);
    else update_post_meta ($post_id, 'slider_button_text', '');
    
    // Update Slider Button Link
    $slider_button_link = sanitize_text_field($_POST['slider_button_link']);
    if (!empty($slider_button_link)) update_post_meta ($post_id, 'slider_button_link', $slider_button_link);
    else update_post_meta ($post_id, 'slider_button_link', '');

}

// Slider Post List : Add Custom Columns
function mkwvs_slider_add_column($columns){
    $custom_columns = array();
    foreach($columns as $key => $title) {
        if ($key=='title') {
            $custom_columns['thumb']             = 'Visuel';
            $custom_columns[$key]                = $title;
        } else { 
            $custom_columns[$key] = $title;
        }
    }
    return $custom_columns;
}

// Slider Custom Column Render
function mkwvs_slider_add_custom_columns_render($column_name, $post_id){
    switch ($column_name) {
        case 'thumb' :    
            // Display Thumbnail
            $partner_thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($post_id),'full');
            if (!empty($partner_thumbnail))
                echo '<img src="'.$partner_thumbnail[0].'" alt="" style="max-width:100%;"/>';
        break;
    }
}
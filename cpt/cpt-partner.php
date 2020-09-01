<?php
// CPT PARTNER

// Foool Partner Create Post Type Hook
add_action( 'init', 'foool_partner_create_post_type' );
function foool_partner_create_post_type(){

    $labels = array(
        'name'                => 'Partners',
        'singular_name'       => 'Partner',
        'menu_name'           => 'Partners',
        'parent_item_colon'   => 'Element parent',
        'all_items'           => 'Voir tous les partenaires',
        'view_item'           => 'Voir le partenaire',
        'add_new_item'        => 'Ajouter un partenaire',
        'add_new'             => 'Ajouter ',
        'edit_item'           => 'Editer le partenaire',
        'update_item'         => 'Mettre à jour',
        'search_items'        => 'Rechercher',
        'not_found'           => 'Aucun partenaire',
        'not_found_in_trash'  => 'Aucun partenaire dans la corbeille',
    );
    $args = array(
        'label'               => 'partner',
        'description'         => 'Custom Post Type Partenaire',
        'labels'              => $labels,
        'supports'            => array( 'title', 'thumbnail'),
        'taxonomies'          => array(),
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
        'menu_icon'           => 'dashicons-groups',

    );
    register_post_type( 'partner', $args );
}

// Foool Partner Action : Admin Init
add_action('admin_init','foool_partner_admin_partner_init');
function foool_partner_admin_partner_init(){

    // Custom Columns In Post Type List
    add_filter( 'manage_edit-partner_columns', 'foool_partner_add_column' ) ;
    add_action( 'manage_partner_posts_custom_column', 'foool_partner_custom_columns_render', 10, 2);
    add_action( 'admin_head', 'foool_partner_admin_styles');
    
    // Custom Metabox : Infos
    add_meta_box( 'foool_partner_infos_meta_box', 'Informations complémentaires', 'foool_partner_infos_metabox_render', 'partner', 'normal', 'high');
    add_action( 'save_post', 'foool_partner_infos_save_postdata');
}

// Foool Partner ListView : Add Custom Styles
function foool_partner_admin_styles() {
    echo '<style type="text/css">';
    echo '.column-thumb { text-align: center; width:15% !important; }';
    echo '</style>';
}

// Foool Partner ListView : Add Custom Columns
function foool_partner_add_column($columns){

    $custom_columns = array();
    foreach($columns as $key => $title) {

        if ($key=='title') {
            $custom_columns['thumb']           = 'Visuel';
            $custom_columns[$key]              = $title;
        } else { 
            $custom_columns[$key] = $title;
        }
    }
    return $custom_columns;
}

// Foool Partner ListView : Custom Column Render
function foool_partner_custom_columns_render($column_name, $post_id){

    switch ($column_name) {
        case 'thumb' :    
            // Display Thumbnail
            $partner_thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($post_id),'large');
            if (!empty($partner_thumbnail))
                echo '<img src="'.$partner_thumbnail[0].'" alt="" style="max-width:100%;"/>';
        break;
    }
}

// Foool Partner Metabox Render
function foool_partner_infos_metabox_render (){
    global $post;

    $partner_url  = get_post_meta($post->ID, 'partner_url', true);
    
    // Use nonce for verification
    echo '<input type="hidden" name="partner_metabox_nonce" value="'. wp_create_nonce(basename(__FILE__)). '" />';

    // Url Field
    echo '<label style="width:25%;display:block;float:left;">Site Web :</label>';
    echo '<input style="width:50%;" type="text" name="partner_url" id="partner_url" value="'.$partner_url.'" />';
}

// Foool Partner Metabox Save Post Data
function foool_partner_infos_save_postdata($post_id){
    
    // Check Autosave

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;

    if (!wp_verify_nonce($_POST['partner_metabox_nonce'], basename(__FILE__))) return $post_id;

    // Update Temoignage Author
    $temoignage_author = sanitize_text_field($_POST['partner_url']);
    if (!empty($temoignage_author)) update_post_meta ($post_id, 'partner_url', $temoignage_author);
    else update_post_meta ($post_id, 'partner_url', '');

}


<?php
// Custom Post Type Equipe
add_action( 'init', 'lswd_create_post_type_membre' );
function lswd_create_post_type_membre(){
    
    $labels = array(
		'name'                => 'Membre',
		'singular_name'       => 'Membres',
		'menu_name'           => 'Membre',
		'parent_item_colon'   => 'Element parent',
		'all_items'           => 'Voir tous les membres',
		'view_item'           => 'Voir le membre',
		'add_new_item'        => 'Ajouter un membre',
		'add_new'             => 'Ajouter ',
		'edit_item'           => 'Editer le membre',
		'update_item'         => 'Mettre à jour',
		'search_items'        => 'Rechercher',
		'not_found'           => 'Aucun membre',
		'not_found_in_trash'  => 'Aucun membre dans la corbeille',
	);
	$args = array(
		'label'               => 'membre',
		'description'         => 'Custom Post Type Equipe',
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'thumbnail'),
		'taxonomies'          => array( 'ville' ),
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
                'menu_icon'           => 'dashicons-networking',
	);
	register_post_type( 'membre', $args );
}

// Action : Admin Init
add_action('admin_init','admin_membre_init');
function admin_membre_init(){
    
    // Add Custom Meta Box : Location
    add_meta_box( 'cnajep-membre-location-meta-box', 'Localisation', 'cnajep_membre_location_metabox_render', 'membre', 'normal', 'high');
    add_action( 'save_post', 'cnajep_membre_location_save_postdata');
    
    // Add Custom Meta Box : Infos Membre
    add_meta_box( 'cnajep-membre-info-meta-box', 'Infos Complémentaires', 'cnajep_membre_info_metabox_render', 'membre', 'normal', 'high');
    add_action( 'save_post', 'cnajep_membre_info_save_postdata');
}


// Render Custom Meta Box : Location
function cnajep_membre_location_metabox_render(){
    
    global $post, $a_depts, $a_regions;
    
    $location_address        = get_post_meta($post->ID, 'location_address', true);
    $location_zip_code       = get_post_meta($post->ID, 'location_zip_code', true);
    $location_city           = get_post_meta($post->ID, 'location_city', true);
    $location_dept           = get_post_meta($post->ID, 'location_dept', true);
    $location_region         = get_post_meta($post->ID, 'location_region', true);
    $location_latitude       = get_post_meta($post->ID, 'location_latitude', true);
    $location_longitude      = get_post_meta($post->ID, 'location_longitude', true);
    $location_phone          = get_post_meta($post->ID, 'location_phone', true);
    $location_fax            = get_post_meta($post->ID, 'location_fax', true);
    $location_email          = get_post_meta($post->ID, 'location_email', true);
    $location_website        = get_post_meta($post->ID, 'location_website', true);
    
    // Use nonce for verification
    echo '<input type="hidden" name="cnajep_membre_location_metabox_nonce" value="'. wp_create_nonce(basename(__FILE__)). '" />';
    
    // Structure Geo Field : Latitude
    echo '<label style="width:25%;display:block;float:left;">Latitude :</label>';
    echo '<input style="width:50%;" type="text" name="location_latitude" id="location_latitude" value="'.$location_latitude.'" />';
    
    // Structure Geo Field : Longitude
    echo '<label style="width:25%;display:block;float:left;">Longitude :</label>';
    echo '<input style="width:50%;" type="text" name="location_longitude" id="location_longitude" value="'.$location_longitude.'" />';
    
    // Product Address
    echo '<label style="width:25%;display:block;float:left;">Adresse :</label>';
    echo '<input style="width:50%;" type="text" name="location_address" id="location_address" value="'.$location_address.'" />';
    // Product Zip Code
    echo '<label style="width:25%;display:block;float:left;">Code Postal :</label>';
    echo '<input style="width:50%;" type="text" name="location_zip_code" id="location_zip_code" value="'.$location_zip_code.'" />';
    // Product City
    echo '<label style="width:25%;display:block;float:left;">Ville :</label>';
    echo '<input style="width:50%;" type="text" name="location_city" id="location_city" value="'.$location_city.'" />';
    
    // Product Dept
    echo '<label style="width:25%;display:block;float:left;">Département :</label>';
    echo '<select style="width:50%;" name="location_dept" id="location_dept" >';
    echo '  <option value=""> -- </option>';
    foreach($a_depts as $key => $value){
        echo '<option value="'.$key.'" '.($location_dept == $key ? 'selected="selected"' : '').'>'.$value.'</option>';
    }
    echo '</select>';
    
    // Product Region
    echo '<label style="width:25%;display:block;float:left;">Région :</label>';
    echo '<select style="width:50%;" name="location_region" id="location_region" >';
    echo '  <option value=""> -- </option>';
    foreach($a_regions as $key => $value){
        echo '<option value="'.$key.'" '.($location_region == $key ? 'selected="selected"' : '').'>'.$value.'</option>';
    }
    echo '</select>';
    
    // Structure Geo Field : Phone
    echo '<label style="width:25%;display:block;float:left;">Téléphone :</label>';
    echo '<input style="width:50%;" type="text" name="location_phone" id="location_phone" value="'.$location_phone.'" />';
    
    // Structure Geo Field : Fax
    echo '<label style="width:25%;display:block;float:left;">Fax :</label>';
    echo '<input style="width:50%;" type="text" name="location_fax" id="location_fax" value="'.$location_fax.'" />';
    
    // Structure Geo Field : Email
    echo '<label style="width:25%;display:block;float:left;">Email :</label>';
    echo '<input style="width:50%;" type="text" name="location_email" id="location_email" value="'.$location_email.'" />';
    
    // Structure Geo Field : Website
    echo '<label style="width:25%;display:block;float:left;">Website :</label>';
    echo '<input style="width:50%;" type="text" name="location_website" id="location_website" value="'.$location_website.'" />';
    
}

// Save Custom Meta Box : Product Location
function cnajep_membre_location_save_postdata($post_id){
    
    // Check Nonce
    if (!wp_verify_nonce($_POST['cnajep_membre_location_metabox_nonce'], basename(__FILE__))) return $post_id;
	
    // Check Autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;
    
    // Update Formation Infos : Adresse
    $location_address = sanitize_text_field($_POST['location_address']);
    if (!empty($location_address)) update_post_meta ($post_id, 'location_address', $location_address);
    else update_post_meta ($post_id, 'location_address', '');
    
    // Update Formation Infos : Code Postal
    $location_zip_code = sanitize_text_field($_POST['location_zip_code']);
    if (!empty($location_zip_code)) update_post_meta ($post_id, 'location_zip_code', $location_zip_code);
    else update_post_meta ($post_id, 'location_zip_code', '');
    
    // Update Formation Infos : City
    $location_city = sanitize_text_field($_POST['location_city']);
    if (!empty($location_city)) update_post_meta ($post_id, 'location_city', $location_city);
    else update_post_meta ($post_id, 'location_city', '');


    // Update Formation Infos : Dept
    $location_dept = sanitize_text_field($_POST['location_dept']);
    if (!empty($location_dept)) update_post_meta ($post_id, 'location_dept', $location_dept);
    else update_post_meta ($post_id, 'location_dept', ''); 
    
    // Update Formation Infos : Region
    $location_region = sanitize_text_field($_POST['location_region']);
    if (!empty($location_region)) update_post_meta ($post_id, 'location_region', $location_region);
    else update_post_meta ($post_id, 'location_region', ''); 
    
    // Update Structure Geo Field : Display On Map
    $location_display_on_map = sanitize_text_field($_POST['location_display_on_map']);
    if (!empty($location_display_on_map)) update_post_meta ($post_id, 'location_display_on_map', $location_display_on_map);
    else update_post_meta ($post_id, 'location_display_on_map', '');
    
    // Update Structure Geo Field : Latitude
    $location_latitude = sanitize_text_field($_POST['location_latitude']);
    if (!empty($location_latitude)) update_post_meta ($post_id, 'location_latitude', $location_latitude);
    else update_post_meta ($post_id, 'location_latitude', '');
    
    // Update Structure Geo Field : Longitude
    $location_longitude = sanitize_text_field($_POST['location_longitude']);
    if (!empty($location_longitude)) update_post_meta ($post_id, 'location_longitude', $location_longitude);
    else update_post_meta ($post_id, 'location_longitude', '');
    
    // Update Structure Geo Field : Phone
    $location_phone = sanitize_text_field($_POST['location_phone']);
    if (!empty($location_phone)) update_post_meta ($post_id, 'location_phone', $location_phone);
    else update_post_meta ($post_id, 'location_phone', '');
    
    // Update Structure Geo Field : Fax
    $location_fax = sanitize_text_field($_POST['location_fax']);
    if (!empty($location_fax)) update_post_meta ($post_id, 'location_fax', $location_fax);
    else update_post_meta ($post_id, 'location_fax', '');
    
    // Update Structure Geo Field : Email
    $location_email = sanitize_text_field($_POST['location_email']);
    if (!empty($location_email)) update_post_meta ($post_id, 'location_email', $location_email);
    else update_post_meta ($post_id, 'location_email', '');
    
    // Update Structure Geo Field : Website
    $location_website = sanitize_text_field($_POST['location_website']);
    if (!empty($location_website)) update_post_meta ($post_id, 'location_website', $location_website);
    else update_post_meta ($post_id, 'location_website', '');
    
    // Update Structure Geo Field : Responsable
    $location_responsable = sanitize_text_field($_POST['location_responsable']);
    if (!empty($location_responsable)) update_post_meta ($post_id, 'location_responsable', $location_responsable);
    else update_post_meta ($post_id, 'location_responsable', '');
    
    // Update Structure Geo Field : Link Catalogue
    $location_link_catalogue = sanitize_text_field($_POST['location_link_catalogue']);
    if (!empty($location_link_catalogue)) update_post_meta ($post_id, 'location_link_catalogue', $location_link_catalogue);
    else update_post_meta ($post_id, 'location_link_catalogue', '');
    
}

function cnajep_membre_info_metabox_render(){
    global $post;
    
    $info_sub_title        = get_post_meta($post->ID, 'info_sub_title', true);
    $info_responsable      = get_post_meta($post->ID, 'info_responsable', true);
    $info_date_creation    = get_post_meta($post->ID, 'info_date_creation', true);

    // Use nonce for verification
    echo '<input type="hidden" name="cnajep_membre_info_metabox_nonce" value="'. wp_create_nonce(basename(__FILE__)). '" />';
    
    // Structure Geo Field : Fax
    echo '<label style="width:25%;display:block;float:left;">Sous-Titre :</label>';
    echo '<input style="width:50%;" type="text" name="info_sub_title" id="info_sub_title" value="'.$info_sub_title.'" />';
    
    // Structure Geo Field : Email
    echo '<label style="width:25%;display:block;float:left;">Responsable :</label>';
    echo '<input style="width:50%;" type="text" name="info_responsable" id="info_responsable" value="'.$info_responsable.'" />';
    
    // Structure Geo Field : Website
    echo '<label style="width:25%;display:block;float:left;">Date de création :</label>';
    echo '<input style="width:50%;" type="text" name="info_date_creation" id="info_date_creation" value="'.$info_date_creation.'" />';
}

function cnajep_membre_info_save_postdata($post_id){
    
    // Check Nonce
    if (!wp_verify_nonce($_POST['cnajep_membre_info_metabox_nonce'], basename(__FILE__))) return $post_id;
	
    // Check Autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;
    
    // Update Infos : Sub Title
    $info_sub_title = sanitize_text_field($_POST['info_sub_title']);
    if (!empty($info_sub_title)) update_post_meta ($post_id, 'info_sub_title', $info_sub_title);
    else update_post_meta ($post_id, 'info_sub_title', '');
    
    // Update Infos : Responsable
    $info_responsable = sanitize_text_field($_POST['info_responsable']);
    if (!empty($info_responsable)) update_post_meta ($post_id, 'info_responsable', $info_responsable);
    else update_post_meta ($post_id, 'info_responsable', '');
    
    // Update Infos : Date Creation
    $info_date_creation = sanitize_text_field($_POST['info_date_creation']);
    if (!empty($info_date_creation)) update_post_meta ($post_id, 'info_date_creation', $info_date_creation);
    else update_post_meta ($post_id, 'info_date_creation', '');


}
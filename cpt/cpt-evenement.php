<?php
// Makewaves - CPT Evenement

// Custom Post Type Event
add_action( 'init', 'mkwvs_create_post_type_event' );
function mkwvs_create_post_type_event(){


    $labels = array(
        'name'                => 'Evenements',
        'singular_name'       => 'Evenement',
        'menu_name'           => 'Evenements',
        'parent_item_colon'   => 'Element parent',
        'all_items'           => 'Voir tous les évenements',
        'view_item'           => 'Voir l\'évenement',
        'add_new_item'        => 'Ajouter un évenement',
        'add_new'             => 'Ajouter ',
        'edit_item'           => 'Editer l\'évenement',
        'update_item'         => 'Mettre à jour',
        'search_items'        => 'Rechercher',
        'not_found'           => 'Aucune un évenement',
        'not_found_in_trash'  => 'Aucun évenement dans la corbeille',
    );
    $args = array(
        'label'               => 'Evenement',
        'description'         => 'Custom Post Type Evenement',
        'labels'              => $labels,
        'supports'            => array( 'title', 'editor', 'thumbnail'),
        'taxonomies'          => array( ),
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_rest'        => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 6,
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'page',
        'menu_icon'           => 'dashicons-calendar-alt',
    );
    register_post_type( 'evenement', $args );

    // Taxonomy "Section"
    $labels = array(
        'name'              => 'Sections',
        'singular_name'     => 'Section',
        'search_items'      => __( 'Rechercher une section' ),
        'all_items'         => __( 'Tout les sections' ),
        'parent_item'       => __( 'Section parent' ),
        'parent_item_colon' => __( 'Section parent :' ),
        'edit_item'         => __( 'Editer la section' ),
        'update_item'       => __( 'Mettre à jour la section' ),
        'add_new_item'      => __( 'Ajouter unz nouvelle section' ),
        'new_item_name'     => __( 'Nouvelle section' ),
        'menu_name'         => __( 'Section' ),
    );
    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'section', 'with_front' => false ),
    );
    register_taxonomy( 'section', array( 'evenement' ), $args );
}

// Add Custom Meta Box On CPT Event Post Type
add_action('admin_init','mkwvs_event_admin_init');
function mkwvs_event_admin_init(){

    // Add Custom Meta Box : Event Dates 
    add_meta_box( 'mkwvs_event_meta_box', 'Dates de l\'évènement', 'mkwvs_event_metabox_render', 'evenement', 'normal', 'high');
    add_action( 'save_post', 'mkwvs_event_save_postdata');

    // Add Custom Columns In Post Type List
    add_filter( 'manage_edit-evenement_columns', 'mkwvs_event_add_column' ) ;
    add_action( 'manage_event_posts_custom_column', 'mkwvs_event_add_custom_columns_render', 10, 2);

    // Add Custom Field On Taxonomy "Section"
    add_action('section_add_form_fields','mkwvs_add_section_color_code_hexa_render');
    add_action('section_edit_form_fields','mkwvs_edit_section_color_code_hexa_render');

    // Save Custom Field On Taxonomy Save & Edit
    add_action( 'create_section', 'mkwvs_section_color_code_hexa_save' );
    add_action( 'edited_section', 'mkwvs_section_color_code_hexa_save' );

    // Add Custom Meta Box : Information Complémentaires
    add_meta_box( 'event_info_meta_box', 'Information complémentaires', 'mkwvs_event_info_metabox_render', 'evenement', 'normal', 'high');
    add_action( 'save_post', 'mkwvs_event_info_save_postdata');

}

// Hook : Add Custom Columns
function mkwvs_event_add_column($columns){
    $custom_columns = array();
    foreach($columns as $key => $title) {
        if ($key=='name') {
            $custom_columns[$key]                = $title;
            $custom_columns['event_date']                = 'Date';
        } else {
            $custom_columns[$key] = $title;
        }
    }
    return $custom_columns;
}

// Hook : Render Custom Columns
function mkwvs_event_add_custom_columns_render($column_name, $post_id){
    global $wpdb;
    switch ($column_name) {
        case 'event_date' :
            // Display Thumbnail
            $event_start_date           = get_post_meta($post_id, 'event_start_date', true);
            $event_end_date             = get_post_meta($post_id, 'event_end_date', true);
            if (!empty($event_end_date) && !empty($event_start_date))
                echo 'du '.date('d/m/Y',$event_start_date). ' au ' . date('d/m/Y',$event_end_date);

            break;

            break;
    }
}

// Render Custom Meta Box : Event Dates
function mkwvs_event_metabox_render(){

    // Use Global Arrays : Post, Days, Months, Years
    global $post, $a_days, $a_months, $a_years, $a_hours, $a_minutes;

    // Get Dates Meta Data 
    $event_start_date           = get_post_meta($post->ID, 'event_start_date', true);
    $event_end_date             = get_post_meta($post->ID, 'event_end_date', true);
    $event_start_time           = get_post_meta($post->ID, 'event_start_time', true);
    $event_end_time             = get_post_meta($post->ID, 'event_end_time', true);
    //$event_diffusion_start_date = get_post_meta($post->ID, 'event_diffusion_start_date', true);
    //$event_diffusion_end_date   = get_post_meta($post->ID, 'event_diffusion_end_date', true);


    // Process On Diffusion Start Date Meta Value
    /*$event_diffusion_start_date_day   = '';
    $event_diffusion_start_date_month = '';
    $event_diffusion_start_date_year  = '';
    if (!empty($event_diffusion_start_date)){
        // Extract + Convert + Explode Timestamp Meta Value
        $a_event_event_diffusion_start_date = explode('/',date('d/m/Y',$event_diffusion_start_date));
        // Get Day + Month + Year
        $event_diffusion_start_date_day   = $a_event_event_diffusion_start_date[0];
        $event_diffusion_start_date_month = $a_event_event_diffusion_start_date[1];
        $event_diffusion_start_date_year  = $a_event_event_diffusion_start_date[2];
    }*/

    // Process On Diffusion End Date Meta Value
    /*$event_diffusion_end_date_day   = '';
    $event_diffusion_end_date_month = '';
    $event_diffusion_end_date_year  = '';
    if (!empty($event_diffusion_end_date)){
        // Extract + Convert + Explode Timestamp Meta Value
        $a_event_event_diffusion_end_date = explode('/',date('d/m/Y',$event_diffusion_end_date));
        // Get Day + Month + Year
        $event_diffusion_end_date_day   = $a_event_event_diffusion_end_date[0];
        $event_diffusion_end_date_month = $a_event_event_diffusion_end_date[1];
        $event_diffusion_end_date_year  = $a_event_event_diffusion_end_date[2];
    }*/

    // Process On Start Date Meta Value
    $event_start_date_day   = '';
    $event_start_date_month = '';
    $event_start_date_year  = '';

    if (!empty($event_start_date)){
        // Extract + Convert + Explode Timestamp Meta Value
        $a_event_event_start_date = explode('/',date('d/m/Y',$event_start_date));
        // Get Day + Month + Year
        $event_start_date_day   = $a_event_event_start_date[0];
        $event_start_date_month = $a_event_event_start_date[1];
        $event_start_date_year  = $a_event_event_start_date[2];

    }

    // Process On Start Time Meta
    $event_start_time_hour   = '';
    $event_start_time_minute = '';
    if (!empty($event_start_time)){
        // Extract + Convert + Explode Timestamp Meta Value
        $a_event_start_time = explode(':',$event_start_time);
        // Get Hour + Minutes
        $event_start_time_hour   = $a_event_start_time[0];
        $event_start_time_minute = $a_event_start_time[1];
    }

    // Process On End Date Meta Value
    $event_end_date_day   = '';
    $event_end_date_month = '';
    $event_end_date_year  = '';

    if (!empty($event_end_date)){
        // Extract + Convert + Explode Timestamp Meta Value
        $a_event_event_end_date = explode('/',date('d/m/Y',$event_end_date));
        // Get Day + Month + Year
        $event_end_date_day   = $a_event_event_end_date[0];
        $event_end_date_month = $a_event_event_end_date[1];
        $event_end_date_year  = $a_event_event_end_date[2];
        // Extract + Convert + Explode Timestamp Meta Value
        $a_event_end_time = explode('/',date('h:i',$event_end_date));
        // Get Hour + Minutes
        $event_end_time_hour   = $a_event_end_time[0];
        $event_end_time_minute = $a_event_end_time[1];
    }

    // Proccess On End Time Meta Value
    $event_end_time_hour   = '';
    $event_end_time_minute = '';
    if (!empty($event_end_time)){
        // Extract + Convert + Explode Timestamp Meta Value
        $a_event_end_time = explode(':',$event_end_time);
        // Get Hour + Minutes
        $event_end_time_hour   = $a_event_end_time[0];
        $event_end_time_minute = $a_event_end_time[1];
    }

    // Use nonce for verification
    echo '<input type="hidden" name="mkwvs_event_event_metabox_nonce" value="'. wp_create_nonce(basename(__FILE__)). '" />';

    // Evenement Diffusion Start Date
    /*echo '<h5>Dates de publication</h5>';
    echo '<label style="width:25%;display:block;float:left;">Date de début :</label>';
    echo '<select name="event_diffusion_start_date_day" id="event_diffusion_start_date_day">';
    echo '<option value="">--</option>';
    foreach($a_days as $day){
        echo '<option value="'.$day.'" '.($event_diffusion_start_date_day == $day ? 'selected="selected"' : '').'>'.$day.'</option>';
    }
    echo '</select>';
    echo '<select name="event_diffusion_start_date_month" id="event_diffusion_start_date_month">';
    echo '<option value="">--</option>';
    foreach($a_months as $key => $value){
        echo '<option value="'.$key.'" '.($event_diffusion_start_date_month == $key ? 'selected="selected"' : '').'>'.$value.'</option>';
    }
    echo '</select>';
    echo '<select name="event_diffusion_start_date_year" id="event_diffusion_start_date_year">';
    echo '<option value="">--</option>';
    foreach($a_years as $year){
        echo '<option value="'.$year.'" '.($event_diffusion_start_date_year == $year ? 'selected="selected"' : '').'>'.$year.'</option>';
    }
    echo '</select><br />';
    
    // Evenement Diffusion End Date   
    echo '<label style="width:25%;display:block;float:left;">Date de fin :</label>';
    echo '<select name="event_diffusion_end_date_day" id="event_diffusion_end_date_day">';
    echo '<option value="">--</option>';
    foreach($a_days as $day){
        echo '<option value="'.$day.'" '.($event_diffusion_end_date_day == $day ? 'selected="selected"' : '').'>'.$day.'</option>';
    }
    echo '</select>';
    echo '<select name="event_diffusion_end_date_month" id="event_diffusion_end_date_month">';
    echo '<option value="">--</option>';
    foreach($a_months as $key => $value){
        echo '<option value="'.$key.'" '.($event_diffusion_end_date_month == $key ? 'selected="selected"' : '').'>'.$value.'</option>';
    }
    echo '</select>';
    echo '<select name="event_diffusion_end_date_year" id="event_diffusion_end_date_year">';
    echo '<option value="">--</option>';
    foreach($a_years as $year){
        echo '<option value="'.$year.'" '.($event_diffusion_end_date_year == $year ? 'selected="selected"' : '').'>'.$year.'</option>';
    }
    echo '</select><br />';
    */

    // Evenement Start Date
    echo '<h5>Date de l\'évènement</h5>';
    echo '<label style="width:25%;display:block;float:left;">Date de début :</label>';
    echo '<select name="event_start_date_day" id="event_start_date_day">';
    echo '<option value="">--</option>';
    foreach($a_days as $day){
        echo '<option value="'.$day.'" '.($event_start_date_day == $day ? 'selected="selected"' : '').'>'.$day.'</option>';
    }
    echo '</select>';
    echo '<select name="event_start_date_month" id="event_start_date_month">';
    echo '<option value="">--</option>';
    foreach($a_months as $key => $value){
        echo '<option value="'.$key.'" '.($event_start_date_month == $key ? 'selected="selected"' : '').'>'.$value.'</option>';
    }
    echo '</select>';
    echo '<select name="event_start_date_year" id="event_start_date_year">';
    echo '<option value="">--</option>';
    foreach($a_years as $year){
        echo '<option value="'.$year.'" '.($event_start_date_year == $year ? 'selected="selected"' : '').'>'.$year.'</option>';
    }
    echo '</select> - Heure : ';
    // Start Time
    echo '<select name="event_start_time_hour" id="event_start_time_hour">';
    echo '<option value="00">--</option>';
    foreach($a_hours as $hour){
        echo '<option value="'.$hour.'" '.($event_start_time_hour == $hour ? 'selected="selected"' : '').'>'.$hour.'</option>';
    }
    echo '</select>';
    echo '<select name="event_start_time_minutes" id="event_start_time_minutes">';
    echo '<option value="00">--</option>';
    foreach($a_minutes as $minutes){
        echo '<option value="'.$minutes.'" '.($event_start_time_minute == $minutes ? 'selected="selected"' : '').'>'.$minutes.'</option>';
    }
    echo '</select><br />';

    // Evenement End Date
    echo '<label style="width:25%;display:block;float:left;">Date de fin :</label>';
    echo '<select name="event_end_date_day" id="event_end_date_day">';
    echo '<option value="">--</option>';
    foreach($a_days as $day){
        echo '<option value="'.$day.'" '.($event_end_date_day == $day ? 'selected="selected"' : '').'>'.$day.'</option>';
    }
    echo '</select>';
    echo '<select name="event_end_date_month" id="event_end_date_month">';
    echo '<option value="">--</option>';
    foreach($a_months as $key => $value){
        echo '<option value="'.$key.'" '.($event_end_date_month == $key ? 'selected="selected"' : '').'>'.$value.'</option>';
    }
    echo '</select>';
    echo '<select name="event_end_date_year" id="event_end_date_year">';
    echo '<option value="">--</option>';
    foreach($a_years as $year){
        echo '<option value="'.$year.'" '.($event_end_date_year == $year ? 'selected="selected"' : '').'>'.$year.'</option>';
    }
    echo '</select>  - Heure : ';

    // End Time
    echo '<select name="event_end_time_hour" id="event_end_time_hour">';
    echo '<option value="">--</option>';
    foreach($a_hours as $hour){
        echo '<option value="'.$hour.'" '.($event_end_time_hour == $hour ? 'selected="selected"' : '').'>'.$hour.'</option>';
    }
    echo '</select>';
    echo '<select name="event_end_time_minutes" id="event_end_time_minutes">';
    echo '<option value="">--</option>';
    foreach($a_minutes as $minutes){
        echo '<option value="'.$minutes.'" '.($event_end_time_minute == $minutes ? 'selected="selected"' : '').'>'.$minutes.'</option>';
    }
    echo '</select><br />';
}

// Save Custom Meta Box : Event Dates
function mkwvs_event_save_postdata($post_id){
    // Check Nonce
    if (!wp_verify_nonce($_POST['mkwvs_event_event_metabox_nonce'], basename(__FILE__))) return $post_id;

    // Check Autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;

    // Date Diffusion
    /*    
    // Get Post Diffusion Start Date Day
    $event_diffusion_start_date_day   = isset($_POST['event_diffusion_start_date_day']) && !empty($_POST['event_diffusion_start_date_day']) ? $_POST['event_diffusion_start_date_day'] : null;
    // Get Post Diffusion Start Date Month
    $event_diffusion_start_date_month = isset($_POST['event_diffusion_start_date_month']) && !empty($_POST['event_diffusion_start_date_month']) ? $_POST['event_diffusion_start_date_month'] : null;
    // Get Post Diffusion Start Date Year
    $event_diffusion_start_date_year  = isset($_POST['event_diffusion_start_date_year']) && !empty($_POST['event_diffusion_start_date_year']) ? $_POST['event_diffusion_start_date_year'] : null;
    // Update Attribut Diffusion Start Date
    if (!is_null($event_diffusion_start_date_day) && !is_null($event_diffusion_start_date_month) && !is_null($event_diffusion_start_date_year)){
        $event_diffusion_start_date = $event_diffusion_start_date_day . '-' . $event_diffusion_start_date_month . '-' . $event_diffusion_start_date_year;
        update_post_meta ($post_id, 'event_diffusion_start_date', strtotime($event_diffusion_start_date));
    }else{
        update_post_meta ($post_id, 'event_diffusion_start_date', '');
    }
    
    // Get Post Diffusion End Date Day
    $event_diffusion_end_date_day   = isset($_POST['event_diffusion_end_date_day']) && !empty($_POST['event_diffusion_end_date_day']) ? $_POST['event_diffusion_end_date_day'] : null;
    // Get Post Diffusion End Date Month
    $event_diffusion_end_date_month = isset($_POST['event_diffusion_end_date_month']) && !empty($_POST['event_diffusion_end_date_month']) ? $_POST['event_diffusion_end_date_month'] : null;
    // Get Post Diffusion End Date Year
    $event_diffusion_end_date_year  = isset($_POST['event_diffusion_end_date_year']) && !empty($_POST['event_diffusion_end_date_year']) ? $_POST['event_diffusion_end_date_year'] : null;
    // Update Attribut Diffusion End Date
    if (!is_null($event_diffusion_end_date_day) && !is_null($event_diffusion_end_date_month) && !is_null($event_diffusion_end_date_year)){
        $event_diffusion_end_date = $event_diffusion_end_date_year . '-' . $event_diffusion_end_date_month . '-' . $event_diffusion_end_date_day;
        update_post_meta ($post_id, 'event_diffusion_end_date', strtotime($event_diffusion_end_date));
    }else{
        update_post_meta ($post_id, 'event_diffusion_end_date', '');
    }
    */
    // Date Evenement

    // Get Post Start Date Day
    $event_start_date_day   = isset($_POST['event_start_date_day']) && !empty($_POST['event_start_date_day']) ? $_POST['event_start_date_day'] : null;
    // Get Post Start Date Month
    $event_start_date_month = isset($_POST['event_start_date_month']) && !empty($_POST['event_start_date_month']) ? $_POST['event_start_date_month'] : null;
    // Get Post Start Date Year
    $event_start_date_year  = isset($_POST['event_start_date_year']) && !empty($_POST['event_start_date_year']) ? $_POST['event_start_date_year'] : null;
    // Update Attribut Start Date
    if (!is_null($event_start_date_day) && !is_null($event_start_date_month) && !is_null($event_start_date_year)){
        $event_start_date = $event_start_date_year . '-' . $event_start_date_month . '-' . $event_start_date_day;
        update_post_meta ($post_id, 'event_start_date', strtotime($event_start_date));
    }else{
        update_post_meta ($post_id, 'event_start_date', '');
    }

    // Get Post Start Time Hour
    $event_start_time_hour  = isset($_POST['event_start_time_hour']) && !empty($_POST['event_start_time_hour']) ? $_POST['event_start_time_hour'] : null;
    // Get Post Start Time Minutes
    $event_start_time_minutes  = isset($_POST['event_start_time_minutes']) && !empty($_POST['event_start_time_minutes']) ? $_POST['event_start_time_minutes'] : null;
    // Update Attribut Start Time
    if (!is_null($event_start_time_hour) && !is_null($event_start_time_minutes)){
        $event_start_time = $event_start_time_hour.':'.$event_start_time_minutes;
        update_post_meta ($post_id, 'event_start_time', $event_start_time);
    }else{
        update_post_meta ($post_id, 'event_start_time', '');
    }


    // Get Post End Date Day
    $event_end_date_day   = isset($_POST['event_end_date_day']) && !empty($_POST['event_end_date_day']) ? $_POST['event_end_date_day'] : null;
    // Get Post End Date Month
    $event_end_date_month = isset($_POST['event_end_date_month']) && !empty($_POST['event_end_date_month']) ? $_POST['event_end_date_month'] : null;
    // Get Post End Date Year
    $event_end_date_year  = isset($_POST['event_end_date_year']) && !empty($_POST['event_end_date_year']) ? $_POST['event_end_date_year'] : null;
    // Get Post Start Time Hour
    $event_end_time_hour  = isset($_POST['event_end_time_hour']) && !empty($_POST['event_end_time_hour']) ? $_POST['event_end_time_hour'] : null;
    // Get Post Start Time Minutes
    $event_end_time_minutes  = isset($_POST['event_end_time_minutes']) && !empty($_POST['event_end_time_minutes']) ? $_POST['event_end_time_minutes'] : null;
    // Update Attribut End Date
    if (!is_null($event_end_date_day) && !is_null($event_end_date_month) && !is_null($event_end_date_year)){
        $event_end_date = $event_end_date_year . '-' . $event_end_date_month . '-' . $event_end_date_day. ' '.$event_end_time_hour.':'.$event_end_time_minutes.':00';
        update_post_meta ($post_id, 'event_end_date', strtotime($event_end_date));
    }else{
        update_post_meta ($post_id, 'event_end_date', '');
    }

    // Get Post Start Time Hour
    $event_end_time_hour  = isset($_POST['event_end_time_hour']) && !empty($_POST['event_end_time_hour']) ? $_POST['event_end_time_hour'] : null;
    // Get Post Start Time Minutes
    $event_end_time_minutes  = isset($_POST['event_end_time_minutes']) && !empty($_POST['event_end_time_minutes']) ? $_POST['event_end_time_minutes'] : null;
    // Update Attribut Start Time
    if (!is_null($event_end_time_hour) && !is_null($event_end_time_minutes)){
        $event_end_time = $event_end_time_hour.':'.$event_end_time_minutes;
        update_post_meta ($post_id, 'event_end_time', $event_end_time);
    }else{
        update_post_meta ($post_id, 'event_end_time', '');
    }
}

// Add Category - Display Meta Data Field : Color Code Hexa
function mkwvs_add_section_color_code_hexa_render($term){
    $mkwvs_section_color_code_hexa = get_term_meta($term->term_id, 'mkwvs_section_color_code_hexa', true );
    ?>
    <div class="form-field">
        <label for="mkwvs_section_color_code_hexa">Code Couleur (ex : #FFFFFF)</label>
        <input type="text" id="mkwvs_section_color_code_hexa" name="mkwvs_section_color_code_hexa" value="<?php echo $mkwvs_section_color_code_hexa; ?>" />
        <p>Utiliser ce champs pour préciser le code couleur de cette catégorie</p>
    </div>
    <?php
}

// Edit Category - Display Meta Data Field : Color Code Hexa
function mkwvs_edit_section_color_code_hexa_render($term){
    $mkwvs_section_color_code_hexa = get_term_meta($term->term_id, 'mkwvs_section_color_code_hexa', true );
    ?>
    <tr class="form-field">
        <th valign="top" scope="row">
            <label for="mkwvs_section_color_code_hexa">Code Couleur (ex : #FFFFFF)</label>
        </th>
        <td>
            <input type="text" id="mkwvs_section_color_code_hexa" name="mkwvs_section_color_code_hexa" value="<?php echo $mkwvs_section_color_code_hexa; ?>" />
            <p>Utiliser ce champs pour préciser le code couleur de cette catégorie</p>
        </td>
    </tr>
    <?php
}

// Category - Save Term Meta Data : Color Code Hexa
function mkwvs_section_color_code_hexa_save($category_id){
    // Update Meta Data : Sub-Title
    $mkwvs_color_code_hexa = sanitize_text_field($_POST['mkwvs_section_color_code_hexa']);
    if (!empty($mkwvs_color_code_hexa)) update_term_meta ($category_id, 'mkwvs_section_color_code_hexa', $mkwvs_color_code_hexa);
    else update_term_meta ($category_id, 'mkwvs_section_color_code_hexa', '');
}

// Event Info Meta Box Render
function mkwvs_event_info_metabox_render (){
    global $post;

    $event_location       = get_post_meta($post->ID, 'event_location', true);

    // Use nonce for verification
    echo '<input type="hidden" name="mkwvs_evenement_info_metabox_nonce" value="'. wp_create_nonce(basename(__FILE__)). '" />';

    // Location Field
    echo '<label style="width:25%;display:block;float:left;">Lieu :</label>';
    echo '<input style="width:50%;" type="text" name="event_location" id="event_location" value="'.$event_location.'" />';


}

// Event Infos Save Post Data
function mkwvs_event_info_save_postdata($post_id){

    // Check Autosave

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;

    if (!wp_verify_nonce($_POST['mkwvs_evenement_info_metabox_nonce'], basename(__FILE__))) return $post_id;

    // Update Project Subtitle
    $event_location = sanitize_text_field($_POST['event_location']);
    if (!empty($event_location)) update_post_meta ($post_id, 'event_location', $event_location);
    else update_post_meta ($post_id, 'event_location', '');

}


/* Fix Sticky Option On CTP Evenement - GeekPress */
add_action( 'admin_footer-post.php', 'mkwvs_event_add_sticky_post_support' );
add_action( 'admin_footer-post-new.php', 'mkwvs_event_add_sticky_post_support' );
function mkwvs_event_add_sticky_post_support()
{   global $post, $typenow; ?>

    <?php if ( $typenow == 'evenement' && current_user_can( 'edit_others_posts' ) ) : ?>
    <script>
        jQuery(function($) {
            var sticky = "<br/><span id='sticky-span'><input id='sticky' name='sticky' type='checkbox' value='sticky' <?php checked( is_sticky( $post->ID ) ); ?> /> <label for='sticky' class='selectit'><?php _e( "Stick this post to the front page" ); ?></label><br /></span>";
            $('[for=visibility-radio-public]').append(sticky);
        });
    </script>
<?php endif;
}
<?php
// Makewaves : CPT Projet

// Custom Post Type Projet
add_action( 'init', 'mkwvs_create_post_type_projet' );
function mkwvs_create_post_type_projet(){
    
    $labels = array(
		'name'                => 'Projets',
		'singular_name'       => 'Projet',
		'menu_name'           => 'Projets',
		'parent_item_colon'   => 'Element parent',
		'all_items'           => 'Voir toutes les projets',
		'view_item'           => 'Voir le projet',
		'add_new_item'        => 'Ajouter un projet',
		'add_new'             => 'Ajouter ',
		'edit_item'           => 'Editer le projet',
		'update_item'         => 'Mettre à jour',
		'search_items'        => 'Rechercher',
		'not_found'           => 'Aucun projet',
		'not_found_in_trash'  => 'Aucun projet dans la corbeille',
	);
	$args = array(
		'label'               => 'projet',
		'description'         => 'Custom Post Type Projet',
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
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
        'menu_icon'           => 'dashicons-portfolio',
        'rewrite'             => array('slug' => 'projet','with_front' => true),
	);
	register_post_type( 'projet', $args );
        
	// Taxonomy "Types"
    $labels = array(
            'name'              => 'Types',
            'singular_name'     => 'Type',
            'search_items'      => __( 'Rechercher un type' ),
            'all_items'         => __( 'Toutes les types' ),
            'parent_item'       => __( 'Type parent' ),
            'parent_item_colon' => __( 'Type parent :' ),
            'edit_item'         => __( 'Editer le type' ),
            'update_item'       => __( 'Mettre à jour le type' ),
            'add_new_item'      => __( 'Ajouter un nouveau type' ),
            'new_item_name'     => __( 'Nouveau type' ),
            'menu_name'         => __( 'Types' ),
    );

    $args = array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_in_rest'       => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'type_projet', 'with_front' => false ),
    );
    register_taxonomy( 'type_projet', array( 'projet' ), $args );
}

// Dependencie : WP Plugin MultiPostThumbnails
if (class_exists('MultiPostThumbnails')) {
        new MultiPostThumbnails(array(
                'label' => 'Preview Background',
                'id' => 'preview-background',
                'post_type' => 'projet'
            )
        );
        new MultiPostThumbnails(array(
                'label' => 'Preview Tablette',
                'id' => 'preview-tablette',
                'post_type' => 'projet'
            )
        );
}

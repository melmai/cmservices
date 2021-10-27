<?php

function theme_enqueue_styles() {
    wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', [] );
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles', 20 );

function avada_lang_setup() {
	$lang = get_stylesheet_directory() . '/languages';
	load_child_theme_textdomain( 'Avada', $lang );
}
add_action( 'after_setup_theme', 'avada_lang_setup' );

/**
 * Enable SVG Uploads
 * 
 */
add_filter( 'wp_check_filetype_and_ext', function($data, $file, $filename, $mimes) {

  global $wp_version;
  if ( $wp_version !== '4.7.1' ) {
     return $data;
  }

  $filetype = wp_check_filetype( $filename, $mimes );

  return [
      'ext'             => $filetype['ext'],
      'type'            => $filetype['type'],
      'proper_filename' => $data['proper_filename']
  ];

}, 10, 4 );

function cc_mime_types( $mimes ){
  $mimes['svg'] = 'image/svg+xml';
  return $mimes;
}
add_filter( 'upload_mimes', 'cc_mime_types' );

function fix_svg() {
  echo '<style type="text/css">
        .attachment-266x266, .thumbnail img {
             width: 100% !important;
             height: auto !important;
        }
        </style>';
}
add_action( 'admin_head', 'fix_svg' );

/**
 * Adds custom body classes
 * 
 */
function cms_body_classes( $classes ) {
	
	if ( has_term( 'podcasts', 'resource_category' ) ) {
		$classes[] = 'tax_podcast';
	}
	
	if ( has_term( 'whitepapers', 'resource_category' ) ) {
		$classes[] = 'tax_whitepaper';
	}
	
	if ( has_term( 'training-materials', 'resource_category' ) ) {
		$classes[] = 'tax_training';
	}
	    
    return $classes;
    
}
add_filter( 'body_class','cms_body_classes' );

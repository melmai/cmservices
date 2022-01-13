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

/**
 * Adds ACF to single portfolio details
 * 
 */
add_action( 'fusion_after_portfolio_side_content', 'cms_add_fields_to_single_portfolio_post' );
function cms_add_fields_to_single_portfolio_post() {
  $association = get_field( 'association' );

  if ( $association != '' ) : ?>
    <?php $association_link = get_field( '$association_link' ); ?>

    <div class="project-info-box">
			<h4>Association:</h4>
      <div class="project-terms"><a href="<?php echo $association_link; ?>" target="_blank"><?php echo $association; ?><a></div>
		</div>
	<?php endif; 
}

add_action( 'fusion_before_additional_portfolio_content', 'cms_add_content_to_single_portfolio_post' );
function cms_add_content_to_single_portfolio_post() {
  $has_link = get_field( 'has_additional_info' );

 if ( $has_link ) : ?>
    <?php $link = get_field( 'link' ); ?>
    
		<div class="project-details-link">
			<a href="<?php echo $link; ?>" target="_blank">See Additional Information <span class="ext-link-icon"><i class="fas fa-external-link-alt"></i></span></a>
		</div>
	<?php 
  endif;
}
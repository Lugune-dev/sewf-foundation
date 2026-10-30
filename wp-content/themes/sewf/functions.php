<?php
/**
 * SEWF Site child theme functions.
 *
 * @package sewf
 */

add_action( 'wp_enqueue_scripts', function () {
	// Parents that enqueue their stylesheet via get_stylesheet_uri() would load
	// the child's near-empty style.css instead — enqueue the parent's directly.
	wp_enqueue_style(
		'astra-parent-style',
		get_template_directory_uri() . '/style.css',
		array(),
		wp_get_theme( get_template() )->get( 'Version' )
	);
	wp_enqueue_style(
		'sewf-style',
		get_stylesheet_directory_uri() . '/style.css',
		array( 'astra-parent-style' ),
		wp_get_theme()->get( 'Version' )
	);
} );

add_action( 'after_setup_theme', function () {
	add_editor_style( 'style.css' );

	add_theme_support( 'editor-styles' );
} );

add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style(
		'sewf-fonts',
		'https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=Nunito+Sans:wght@400;600;700&display=swap',
		array(),
		null
	);

	wp_enqueue_script(
		'sewf-theme-toggle',
		get_stylesheet_directory_uri() . '/assets/js/theme-toggle.js',
		array(),
		wp_get_theme()->get( 'Version' ),
		true
	);
} );

// Reusable Card treatment for program and team cards.
add_action( 'init', function () {
	register_block_style( 'core/group', array(
		'name'  => 'card',
		'label' => 'Card',
	) );
} );

// Inject a Polylang EN / SW language switcher as a small fixed control in the
// lower-right corner, stacked above the theme toggle.
add_action( 'wp_footer', function () {
	if ( ! function_exists( 'pll_the_languages' ) ) {
		return;
	}
	$langs = pll_the_languages( array(
		'display_names_as' => 'slug',
		'hide_if_empty'    => 0,
		'raw'              => 1,
		'show_flags'       => 0,
	) );
	if ( empty( $langs ) ) {
		return;
	}
	$out = '<div class="ewf-lang-bar" role="group" aria-label="Language">';
	foreach ( $langs as $l ) {
		$out .= '<a class="ewf-lang' . ( ! empty( $l['current_lang'] ) ? ' current' : '' ) . '" href="' . esc_url( $l['url'] ) . '">' . esc_html( strtoupper( $l['slug'] ) ) . '</a>';
	}
	$out .= '</div>';
	echo $out; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}, 20 );

// Flush permalinks so Polylang language URLs (e.g. /sw/) resolve.
add_action( 'init', function () {
	if ( get_option( 'sewf_pll_flushed' ) !== '1' ) {
		flush_rewrite_rules();
		update_option( 'sewf_pll_flushed', '1' );
	}
} );

// Always show just the SEWF brand in the browser tab, not the page name (e.g. Home).
add_filter( 'pre_get_document_title', function () {
	return get_bloginfo( 'name' );
}, 100 );

// Allow SVG uploads so the logo mark can be used as the site logo and favicon.
add_filter( 'upload_mimes', function ( $mimes ) {
	$mimes['svg'] = 'image/svg+xml';
	$mimes['svgz'] = 'image/svg+xml';
	return $mimes;
} );
add_filter( 'mime_types', function ( $mimes ) {
	$mimes['svg'] = 'image/svg+xml';
	return $mimes;
} );

<?php
/**
 * Ets Gurhem — functions.php
 * Thème sur mesure pour artisan couvreur · Verneuil-en-Halatte 60550
 *
 * @package AdnMetal
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

define( 'ADNMETAL_VERSION', '2.0.0' );
define( 'ADNMETAL_DIR', get_template_directory() );
define( 'ADNMETAL_URI', get_template_directory_uri() );

/** Business identity — single source of truth */
function adnmetal_info() {
	return array(
		'name'       => 'Ets Gurhem',
		'tagline'    => 'Couvreur · Zingueur',
		'phone'      => '06 69 02 51 57',
		'phone_e164' => '+33669025157',
		// Adresse de réception du formulaire de devis.
		// TODO : remplacer par la vraie adresse email de l'entreprise (puis installer WP Mail SMTP).
		'email'      => 'contact@ets-gurhem.fr',
		'address'    => '8 rue du Bac',
		'city'       => 'Verneuil-en-Halatte',
		'zip'        => '60550',
		'region'     => 'Oise (60), Hauts-de-France',
		'hours'      => 'Lun – Ven · 7h30 – 21h · Sam 8h – 20h',
	);
}

require_once ADNMETAL_DIR . '/inc/helpers.php';
require_once ADNMETAL_DIR . '/inc/seeder.php';

/* ------------------------------------------------------------------ */
/* 1. Theme setup                                                     */
/* ------------------------------------------------------------------ */
function adnmetal_setup() {
	load_theme_textdomain( 'adnmetal', ADNMETAL_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'custom-logo', array( 'height' => 80, 'width' => 240, 'flex-height' => true, 'flex-width' => true ) );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );

	register_nav_menus( array(
		'primary' => __( 'Menu principal', 'adnmetal' ),
		'footer'  => __( 'Menu pied de page', 'adnmetal' ),
	) );

	// Portfolio crop sizes
	add_image_size( 'adnmetal-card', 800, 1000, true );
	add_image_size( 'adnmetal-wide', 1400, 900, true );
}
add_action( 'after_setup_theme', 'adnmetal_setup' );

/* ------------------------------------------------------------------ */
/* 2. Assets                                                          */
/* ------------------------------------------------------------------ */
function adnmetal_assets() {
	// Google Fonts — Fraunces (serif chaleureux pour les titres) + Inter (corps lisible)
	wp_enqueue_style(
		'adnmetal-fonts',
		'https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,500;0,9..144,600;1,9..144,400;1,9..144,500&family=Inter:wght@400;500;600;700&display=swap',
		array(),
		null
	);

	wp_enqueue_style( 'adnmetal-main', ADNMETAL_URI . '/assets/css/main.css', array( 'adnmetal-fonts' ), ADNMETAL_VERSION );

	wp_enqueue_script( 'adnmetal-main', ADNMETAL_URI . '/assets/js/main.js', array(), ADNMETAL_VERSION, true );
	wp_localize_script( 'adnmetal-main', 'AdnMetal', array(
		'ajax'  => admin_url( 'admin-ajax.php' ),
		'nonce' => wp_create_nonce( 'adnmetal_devis' ),
	) );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'adnmetal_assets' );

// Preconnect to Google Fonts for performance
function adnmetal_resource_hints( $hints, $relation ) {
	if ( 'preconnect' === $relation ) {
		$hints[] = array( 'href' => 'https://fonts.gstatic.com', 'crossorigin' );
	}
	return $hints;
}
add_filter( 'wp_resource_hints', 'adnmetal_resource_hints', 10, 2 );

/* ------------------------------------------------------------------ */
/* 3. Performance — trim WP bloat                                     */
/* ------------------------------------------------------------------ */
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );
add_filter( 'the_generator', '__return_empty_string' );

/* ------------------------------------------------------------------ */
/* 4. Excerpt tweaks                                                  */
/* ------------------------------------------------------------------ */
add_filter( 'excerpt_more', function () { return '…'; } );
add_filter( 'excerpt_length', function () { return 26; } );

/* ------------------------------------------------------------------ */
/* 5. Devis / contact form (AJAX)                                     */
/* ------------------------------------------------------------------ */
function adnmetal_handle_devis() {
	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'adnmetal_devis' ) ) {
		wp_send_json_error( array( 'message' => 'Session expirée, merci de recharger la page.' ), 403 );
	}

	// Honeypot
	if ( ! empty( $_POST['website'] ) ) {
		wp_send_json_success( array( 'message' => 'Merci, votre demande a bien été envoyée.' ) );
	}

	$name    = sanitize_text_field( wp_unslash( $_POST['name'] ?? '' ) );
	$email   = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
	$phone   = sanitize_text_field( wp_unslash( $_POST['phone'] ?? '' ) );
	$project = sanitize_text_field( wp_unslash( $_POST['project'] ?? '' ) );
	$message = sanitize_textarea_field( wp_unslash( $_POST['message'] ?? '' ) );

	if ( empty( $name ) || empty( $message ) || ! is_email( $email ) ) {
		wp_send_json_error( array( 'message' => 'Merci de renseigner votre nom, un email valide et votre message.' ), 400 );
	}

	$info = adnmetal_info();
	$to      = $info['email'];
	$subject = sprintf( '[Devis site] %s — %s', $project ? $project : 'Demande', $name );
	$body    = "Nouvelle demande de devis depuis le site Ets Gurhem\n\n";
	$body   .= "Nom        : $name\n";
	$body   .= "Email      : $email\n";
	$body   .= "Téléphone  : " . ( $phone ?: '—' ) . "\n";
	$body   .= "Projet     : " . ( $project ?: '—' ) . "\n";
	$body   .= "------------------------------------------\n";
	$body   .= "$message\n";

	$headers = array(
		'Content-Type: text/plain; charset=UTF-8',
		'Reply-To: ' . $name . ' <' . $email . '>',
	);

	$sent = wp_mail( $to, $subject, $body, $headers );

	if ( $sent ) {
		wp_send_json_success( array( 'message' => 'Merci ' . $name . ', votre demande est bien partie. Nous revenons vers vous très vite.' ) );
	}
	wp_send_json_error( array( 'message' => 'L\'envoi a échoué. Appelez-nous au ' . $info['phone'] . '.' ), 500 );
}
add_action( 'wp_ajax_adnmetal_devis', 'adnmetal_handle_devis' );
add_action( 'wp_ajax_nopriv_adnmetal_devis', 'adnmetal_handle_devis' );

/* ------------------------------------------------------------------ */
/* 6. SEO local — JSON-LD LocalBusiness (RoofingContractor)           */
/* ------------------------------------------------------------------ */
function adnmetal_schema() {
	$info = adnmetal_info();
	$schema = array(
		'@context' => 'https://schema.org',
		'@type'    => array( 'RoofingContractor', 'LocalBusiness', 'HomeAndConstructionBusiness' ),
		'name'     => $info['name'],
		'description' => 'Artisan couvreur-zingueur à Verneuil-en-Halatte (60). Couverture neuve et rénovation de toiture, zinguerie, gouttières, démoussage, traitement hydrofuge, fenêtres de toit et ravalement de façade. Devis gratuit.',
		'url'      => home_url( '/' ),
		'telephone' => $info['phone_e164'],
		'image'    => ADNMETAL_URI . '/assets/img/og.jpg',
		'priceRange' => '€€',
		'areaServed' => array( 'Verneuil-en-Halatte', 'Creil', 'Senlis', 'Pont-Sainte-Maxence', 'Oise', 'Hauts-de-France' ),
		'address'  => array(
			'@type' => 'PostalAddress',
			'streetAddress'   => $info['address'],
			'addressLocality' => $info['city'],
			'postalCode'      => $info['zip'],
			'addressRegion'   => 'Hauts-de-France',
			'addressCountry'  => 'FR',
		),
		'knowsAbout' => array( 'Couverture', 'Toiture', 'Zinguerie', 'Gouttières', 'Démoussage', 'Traitement hydrofuge', 'Étanchéité', 'Fenêtre de toit', 'Ravalement de façade' ),
		'slogan'     => 'L\'art de bien couvrir, depuis plus de 20 ans.',
		'openingHours' => array( 'Mo-Fr 07:30-21:00', 'Sa 08:00-20:00' ),
	);
	echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
}
add_action( 'wp_head', 'adnmetal_schema', 20 );

/* ------------------------------------------------------------------ */
/* 7. Open Graph / Twitter cards                                      */
/* ------------------------------------------------------------------ */
function adnmetal_open_graph() {
	$title = wp_get_document_title();
	$desc  = is_singular() && has_excerpt() ? get_the_excerpt() : 'Artisan couvreur-zingueur à Verneuil-en-Halatte (60) : couverture, rénovation de toiture, zinguerie, démoussage & façade. Devis gratuit.';
	$url   = is_singular() ? get_permalink() : home_url( add_query_arg( null, null ) );
	$img   = ADNMETAL_URI . '/assets/img/og.jpg';
	if ( is_singular() && has_post_thumbnail() ) {
		$thumb = wp_get_attachment_image_src( get_post_thumbnail_id(), 'adnmetal-wide' );
		if ( $thumb ) { $img = $thumb[0]; }
	}
	$tags = array(
		'og:type'        => is_singular() && ! is_front_page() ? 'article' : 'website',
		'og:site_name'   => 'Ets Gurhem',
		'og:locale'      => 'fr_FR',
		'og:title'       => $title,
		'og:description' => $desc,
		'og:url'         => $url,
		'og:image'       => $img,
		'twitter:card'   => 'summary_large_image',
		'twitter:title'  => $title,
		'twitter:description' => $desc,
		'twitter:image'  => $img,
	);
	foreach ( $tags as $k => $v ) {
		$attr = 0 === strpos( $k, 'twitter' ) ? 'name' : 'property';
		printf( '<meta %s="%s" content="%s">' . "\n", esc_attr( $attr ), esc_attr( $k ), esc_attr( $v ) );
	}
}
add_action( 'wp_head', 'adnmetal_open_graph', 5 );

/* ------------------------------------------------------------------ */
/* 8. Body classes                                                    */
/* ------------------------------------------------------------------ */
add_filter( 'body_class', function ( $classes ) {
	if ( is_front_page() ) { $classes[] = 'is-home'; }
	return $classes;
} );

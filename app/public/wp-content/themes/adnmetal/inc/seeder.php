<?php
/**
 * Seeder + migration — crée et maintient le contenu du site (réalisations,
 * pages, catégories, menu) pour Ets Gurhem, couvreur.
 *
 * - Sur une installation neuve : `after_switch_theme` sème tout le contenu.
 * - Sur ce site (déjà rempli avec l'ancien contenu) : une migration versionnée
 *   se déclenche au premier chargement, supprime l'ancien contenu et installe
 *   le contenu couvreur. Idempotente (option `gurhem_content_v`).
 *
 * @package AdnMetal
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

define( 'GURHEM_CONTENT_VERSION', 2 );

add_action( 'after_switch_theme', 'adnmetal_seed_content' );
add_action( 'init', 'adnmetal_maybe_migrate', 20 );

/** Anciens slugs « métal » à retirer lors de la migration. */
function adnmetal_legacy_post_slugs() {
	return array( 'escalier', 'garde-corps', 'verriere', 'escalier-divers', 'barbecue-exterieur', 'table-design', 'concours-maf' );
}
function adnmetal_legacy_category_slugs() {
	return array( 'escaliers', 'garde-corps', 'verrieres', 'mobilier', 'exterieur', 'distinctions' );
}

/**
 * Migration auto — passe l'ancien site « Adn Métal » au site « Ets Gurhem ».
 */
function adnmetal_maybe_migrate() {
	if ( (int) get_option( 'gurhem_content_v' ) >= GURHEM_CONTENT_VERSION ) {
		return;
	}

	// 1. Supprimer les anciens articles « métal ».
	foreach ( adnmetal_legacy_post_slugs() as $slug ) {
		$post = get_page_by_path( $slug, OBJECT, 'post' );
		if ( $post ) {
			wp_delete_post( $post->ID, true );
		}
	}

	// 2. Supprimer les anciennes catégories.
	foreach ( adnmetal_legacy_category_slugs() as $slug ) {
		$term = get_term_by( 'slug', $slug, 'category' );
		if ( $term ) {
			wp_delete_term( $term->term_id, 'category' );
		}
	}

	// 3. (Re)semer le contenu couvreur (création idempotente par slug).
	adnmetal_seed_content( true );

	update_option( 'gurhem_content_v', GURHEM_CONTENT_VERSION );
}

/**
 * Sème le contenu du site.
 *
 * @param bool $force Lorsque true (migration), met aussi à jour les pages existantes.
 */
function adnmetal_seed_content( $force = false ) {
	if ( ! $force && get_option( 'adnmetal_seeded' ) ) {
		return;
	}

	/* 1. Permaliens « jolis » (slug simple) */
	global $wp_rewrite;
	$structure = '/%postname%/';
	update_option( 'permalink_structure', $structure );
	if ( $wp_rewrite ) {
		$wp_rewrite->set_permalink_structure( $structure );
	}

	/* 2. Catégories de réalisations */
	$cat_ids = array();
	foreach ( adnmetal_categories() as $slug => $label ) {
		if ( 'all' === $slug ) { continue; }
		$term = term_exists( $slug, 'category' );
		if ( ! $term ) {
			$term = wp_insert_term( $label, 'category', array( 'slug' => $slug ) );
		}
		if ( ! is_wp_error( $term ) ) {
			$cat_ids[ $slug ] = (int) ( is_array( $term ) ? $term['term_id'] : $term );
		}
	}

	/* 3. Articles « réalisations » */
	foreach ( adnmetal_realisations() as $r ) {
		if ( get_page_by_path( $r['slug'], OBJECT, 'post' ) ) {
			continue; // déjà présent, on ne touche pas
		}
		$content  = '<p>' . esc_html( $r['desc'] ) . "</p>\n\n";
		$content .= "<!-- Ajoutez ici vos photos HD de ce chantier : Médias → Ajouter, puis insérez-les ou définissez une Image mise en avant. -->\n";
		$content .= '<p><em>Un projet similaire ? <a href="' . esc_url( home_url( '/#devis' ) ) . '">Demandez votre devis gratuit</a>.</em></p>';

		$postarr = array(
			'post_title'   => $r['title'],
			'post_name'    => $r['slug'],
			'post_content' => $content,
			'post_excerpt' => $r['desc'],
			'post_status'  => 'publish',
			'post_type'    => 'post',
			'post_date'    => $r['date'] . ' 10:00:00',
			'post_author'  => 1,
		);
		if ( isset( $cat_ids[ $r['cat'] ] ) ) {
			$postarr['post_category'] = array( $cat_ids[ $r['cat'] ] );
		}
		wp_insert_post( $postarr );
	}

	/* 4. Pages (présentation, à propos) */
	$pages = array(
		'presentation' => array(
			'title'   => 'Présentation',
			'content' => adnmetal_seed_presentation_content(),
		),
		'a-propos' => array(
			'title'   => 'À propos & Contact',
			'content' => adnmetal_seed_apropos_content(),
		),
	);
	$page_ids = array();
	foreach ( $pages as $slug => $p ) {
		$existing = get_page_by_path( $slug );
		if ( $existing ) {
			$page_ids[ $slug ] = $existing->ID;
			if ( $force ) {
				wp_update_post( array(
					'ID'           => $existing->ID,
					'post_title'   => $p['title'],
					'post_content' => $p['content'],
				) );
			}
			continue;
		}
		$page_ids[ $slug ] = wp_insert_post( array(
			'post_title'   => $p['title'],
			'post_name'    => $slug,
			'post_content' => $p['content'],
			'post_status'  => 'publish',
			'post_type'    => 'page',
			'post_author'  => 1,
		) );
	}

	/* 5. Page d'accueil statique (front-page.php gère le rendu) */
	$home = get_page_by_path( 'accueil' );
	if ( ! $home ) {
		$home_id = wp_insert_post( array(
			'post_title'  => 'Accueil',
			'post_name'   => 'accueil',
			'post_status' => 'publish',
			'post_type'   => 'page',
			'post_author' => 1,
		) );
	} else {
		$home_id = $home->ID;
	}
	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', $home_id );

	/* 6. Menu principal */
	$menu_name = 'Menu principal';
	$menu = wp_get_nav_menu_object( $menu_name );
	if ( ! $menu ) {
		$menu_id = wp_create_nav_menu( $menu_name );
		$add = function ( $title, $url, $parent = 0 ) use ( $menu_id ) {
			return wp_update_nav_menu_item( $menu_id, 0, array(
				'menu-item-title'  => $title,
				'menu-item-url'    => $url,
				'menu-item-status' => 'publish',
				'menu-item-parent-id' => $parent,
			) );
		};
		$add( 'Accueil', home_url( '/' ) );
		$add( 'Savoir-faire', home_url( '/#savoir-faire' ) );
		$add( 'Réalisations', home_url( '/#realisations' ) );
		if ( ! empty( $page_ids['presentation'] ) ) {
			wp_update_nav_menu_item( $menu_id, 0, array(
				'menu-item-title' => 'Présentation', 'menu-item-object' => 'page',
				'menu-item-object-id' => $page_ids['presentation'], 'menu-item-type' => 'post_type', 'menu-item-status' => 'publish',
			) );
		}
		$add( 'Contact', home_url( '/#devis' ) );

		$locations = get_theme_mod( 'nav_menu_locations' );
		$locations['primary'] = $menu_id;
		$locations['footer']  = $menu_id;
		set_theme_mod( 'nav_menu_locations', $locations );
	}

	/* 7. Réglages généraux */
	update_option( 'blogname', 'Ets Gurhem' );
	update_option( 'blogdescription', 'Couvreur · Zingueur — Verneuil-en-Halatte 60550' );

	flush_rewrite_rules();
	update_option( 'adnmetal_seeded', 1 );
}

/** Contenu de la page Présentation. */
function adnmetal_seed_presentation_content() {
	return <<<HTML
<p class="lead">Ets Gurhem est une entreprise de couverture installée à Verneuil-en-Halatte (60). Depuis plus de 20 ans, nous réalisons tous vos travaux de toiture pour les particuliers de l'Oise : couverture neuve, rénovation, zinguerie, démoussage et façade.</p>

<h2>Notre métier</h2>
<p>Couverture en tuiles, ardoises ou bac acier ; réfection complète ou partielle de toiture ; pose de gouttières, chéneaux et descentes en zinc ; démoussage et traitement hydrofuge ; pose de fenêtres de toit ; ravalement de façade. Nous intervenons du diagnostic à la pose, avec un chantier propre et soigné.</p>

<h2>Une entreprise de confiance</h2>
<p>Plus de 20 ans d'expérience, des devis gratuits et sans engagement, un travail garanti et une assurance décennale. À votre écoute pour tous vos projets, petits ou grands.</p>

<blockquote>L'art de bien couvrir : une toiture étanche, durable et bien finie.</blockquote>

<p><em>Un projet de toiture ? <a href="/#devis">Demandez votre devis gratuit</a> ou appelez le 06 69 02 51 57.</em></p>
HTML;
}

/** Contenu de la page À propos / Contact. */
function adnmetal_seed_apropos_content() {
	return <<<HTML
<p class="lead">Ets Gurhem — Artisan couvreur-zingueur à Verneuil-en-Halatte. Couverture, rénovation de toiture, zinguerie, démoussage et façade.</p>

<h2>Nous contacter</h2>
<ul>
<li><strong>Téléphone :</strong> 06 69 02 51 57</li>
<li><strong>Adresse :</strong> 8 rue du Bac, 60550 Verneuil-en-Halatte</li>
<li><strong>Secteur :</strong> Verneuil-en-Halatte et alentours (Oise, Hauts-de-France)</li>
<li><strong>Horaires :</strong> Lun – Ven 7h30 – 21h · Sam 8h – 20h</li>
</ul>

<p>Décrivez-nous votre projet : nous nous déplaçons pour évaluer la toiture et vous remettre un devis personnalisé et gratuit.</p>

<p><a href="/#devis">Demander un devis</a></p>
HTML;
}

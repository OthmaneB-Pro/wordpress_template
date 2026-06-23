<?php
/**
 * Helpers : données métier, placeholders, icônes.
 *
 * @package AdnMetal
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Réalisations (curées) — réutilisées par la home, la galerie et le seeder.
 * L'ordre + les tailles composent le « bento » éditorial sur desktop.
 */
function adnmetal_realisations() {
	return array(
		array(
			'slug'  => 'refection-toiture-tuiles', 'date' => '2024-09-12',
			'title' => 'Réfection complète en tuiles',
			'cat'   => 'renovation', 'cat_label' => 'Rénovation',
			'size'  => 'feature',
			'desc'  => 'Dépose de l\'ancienne couverture, reprise de la charpente, pose de tuiles neuves et écran sous-toiture. Une toiture refaite à neuf, étanche et durable.',
		),
		array(
			'slug'  => 'couverture-ardoise', 'date' => '2024-07-03',
			'title' => 'Couverture en ardoises',
			'cat'   => 'toiture', 'cat_label' => 'Toiture',
			'size'  => 'tall',
			'desc'  => 'Pose d\'ardoises naturelles au crochet, finitions de rives et faîtage soignées. L\'élégance d\'un toit qui traverse les décennies.',
		),
		array(
			'slug'  => 'gouttieres-zinc', 'date' => '2024-05-21',
			'title' => 'Gouttières & descentes zinc',
			'cat'   => 'zinguerie', 'cat_label' => 'Zinguerie',
			'size'  => 'tall',
			'desc'  => 'Pose de gouttières et descentes en zinc, chéneaux et noues. Une évacuation des eaux pluviales nette, sans fuite ni débordement.',
		),
		array(
			'slug'  => 'demoussage-traitement', 'date' => '2024-04-08',
			'title' => 'Démoussage & hydrofuge',
			'cat'   => 'demoussage', 'cat_label' => 'Démoussage',
			'size'  => 'tall',
			'desc'  => 'Nettoyage complet de la toiture, traitement anti-mousse puis application d\'un hydrofuge protecteur. Un toit assaini qui respire à nouveau.',
		),
		array(
			'slug'  => 'toiture-bac-acier', 'date' => '2024-03-14',
			'title' => 'Toiture bac acier',
			'cat'   => 'toiture', 'cat_label' => 'Toiture',
			'size'  => 'std',
			'desc'  => 'Couverture en bac acier pour abri, dépendance ou bâtiment. Pose rapide, étanchéité fiable et excellent rapport qualité-prix.',
		),
		array(
			'slug'  => 'habillage-cheminee-solins', 'date' => '2024-02-19',
			'title' => 'Habillage de cheminée & solins',
			'cat'   => 'zinguerie', 'cat_label' => 'Zinguerie',
			'size'  => 'wide',
			'desc'  => 'Reprise des solins, habillage de souche de cheminée et abergement en zinc. Les points sensibles du toit, rendus parfaitement étanches.',
		),
		array(
			'slug'  => 'ravalement-facade', 'date' => '2024-01-23',
			'title' => 'Ravalement de façade',
			'cat'   => 'facade', 'cat_label' => 'Façade',
			'size'  => 'tall',
			'desc'  => 'Nettoyage, reprise des enduits et mise en peinture de façade. Une maison protégée des intempéries et remise en valeur.',
		),
	);
}

/** Catégories de filtre pour la galerie. */
function adnmetal_categories() {
	return array(
		'all'        => 'Tout',
		'toiture'    => 'Toiture',
		'renovation' => 'Rénovation',
		'zinguerie'  => 'Zinguerie',
		'demoussage' => 'Démoussage',
		'facade'     => 'Façade',
	);
}

/** Savoir-faire / services. */
function adnmetal_services() {
	return array(
		array( 'icon' => 'roof',     'title' => 'Couverture & toiture neuve', 'desc' => 'Tuiles, ardoises, bac acier. Pose complète avec écran sous-toiture pour une couverture étanche et durable.' ),
		array( 'icon' => 'reno',     'title' => 'Rénovation de toiture',      'desc' => 'Réfection totale ou partielle, reprise de couverture, remplacement de tuiles cassées et reprise de charpente.' ),
		array( 'icon' => 'gutter',   'title' => 'Zinguerie & gouttières',     'desc' => 'Gouttières, chéneaux, descentes et habillage zinc. Une évacuation des eaux pluviales nette et sans fuite.' ),
		array( 'icon' => 'moss',     'title' => 'Démoussage & hydrofuge',     'desc' => 'Nettoyage, traitement anti-mousse et application d\'hydrofuge pour protéger et prolonger la vie de votre toit.' ),
		array( 'icon' => 'skylight', 'title' => 'Fenêtres de toit',           'desc' => 'Pose et remplacement de fenêtres de toit (type Velux) : étanchéité parfaite et lumière en plus sous les combles.' ),
		array( 'icon' => 'facade',   'title' => 'Ravalement de façade',       'desc' => 'Nettoyage, reprise des enduits et peinture de façade pour protéger la maison et lui redonner tout son éclat.' ),
	);
}

/**
 * Image d'une réalisation : miniature du post correspondant si elle existe,
 * sinon emplacement vide « Votre photo ici » prêt à recevoir la photo du client.
 */
function adnmetal_realisation_media( $r ) {
	$post = get_page_by_path( $r['slug'], OBJECT, 'post' );
	if ( $post && has_post_thumbnail( $post->ID ) ) {
		return get_the_post_thumbnail( $post->ID, 'adnmetal-wide', array( 'loading' => 'lazy', 'alt' => esc_attr( $r['title'] ) ) );
	}
	return adnmetal_placeholder( array( 'title' => $r['title'], 'sub' => 'Votre photo ici' ) );
}

/**
 * Emplacement photo élégant — balisé « Votre photo ici » pour le client.
 *
 * @param array $args title, sub, dark (bool), class.
 */
function adnmetal_placeholder( $args = array() ) {
	$a = wp_parse_args( $args, array( 'title' => '', 'sub' => 'Votre photo ici', 'dark' => false, 'class' => '' ) );
	$cls = 'ph' . ( $a['dark'] ? ' ph--dark' : '' ) . ( $a['class'] ? ' ' . $a['class'] : '' );
	ob_start(); ?>
	<div class="<?php echo esc_attr( $cls ); ?>" role="img" aria-label="<?php echo esc_attr( $a['title'] ? $a['title'] . ' — ' . $a['sub'] : $a['sub'] ); ?>">
		<span class="ph__label">
			<?php echo adnmetal_icon( 'photo' ); // phpcs:ignore ?>
			<?php if ( $a['title'] ) : ?><b><?php echo esc_html( $a['title'] ); ?></b><?php endif; ?>
			<span><?php echo esc_html( $a['sub'] ); ?></span>
		</span>
	</div>
	<?php
	return ob_get_clean();
}

/** Nav par défaut (avant l'activation/seed du menu). */
function adnmetal_default_nav() {
	$items = array(
		home_url( '/' )                => 'Accueil',
		home_url( '/#savoir-faire' )   => 'Savoir-faire',
		home_url( '/#realisations' )   => 'Réalisations',
		home_url( '/#devis' )          => 'Contact',
	);
	foreach ( $items as $url => $label ) {
		printf( '<a class="nav-link" href="%s">%s</a>', esc_url( $url ), esc_html( $label ) );
	}
}

/** Walker : applique la classe .nav-link aux liens du menu WP. */
class Adnmetal_Nav_Walker extends Walker_Nav_Menu {
	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$current = in_array( 'current-menu-item', $classes, true ) ? ' aria-current="page"' : '';
		$url     = ! empty( $item->url ) ? $item->url : '#';
		$output .= sprintf(
			'<a class="nav-link" href="%s"%s>%s</a>',
			esc_url( $url ),
			$current,
			esc_html( $item->title )
		);
	}
	public function end_el( &$output, $item, $depth = 0, $args = null ) {}
}

/** Bibliothèque d'icônes SVG (stroke, currentColor). */
function adnmetal_icon( $name ) {
	$o = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">';
	$c = '</svg>';
	$paths = array(
		'roof'     => '<path d="M3 12l9-7 9 7"/><path d="M5 10.5V20h14v-9.5"/><path d="M9 20v-5h6v5"/>',
		'reno'     => '<path d="M3 12l9-7 9 7"/><path d="M5 10.5V20h14v-9.5"/><path d="M14.5 13.5l2 2-4.5 4.5H10v-2z"/>',
		'gutter'   => '<path d="M4 5l8-2 8 2"/><path d="M4 5v6a3 3 0 0 0 3 3h7"/><path d="M14 10v8M11 18h6"/>',
		'moss'     => '<path d="M5 21v-5M5 16c0-2 1.5-3 1.5-5M5 16c0-2-1.5-3-1.5-5"/><path d="M9 4l9 3-1 3-9-3z"/><path d="M14 13l4 1.5M12 16l5 2"/>',
		'skylight' => '<path d="M3 11l9-6 9 6"/><rect x="6" y="12" width="12" height="8" rx="1"/><path d="M12 12v8M6 16h12"/>',
		'facade'   => '<rect x="4" y="3" width="16" height="18" rx="1"/><path d="M8 7h2M14 7h2M8 11h2M14 11h2M8 15h2M14 15h2"/>',
		'window'   => '<rect x="4" y="3" width="16" height="18" rx="1"/><path d="M12 3v18M4 12h16"/>',
		'table'    => '<path d="M3 9h18M5 9v11M19 9v11M4 9l2-4h12l2 4"/>',
		'shield'   => '<path d="M12 3l7 3v5c0 4.5-3 7.5-7 9-4-1.5-7-4.5-7-9V6z"/><path d="M9.5 12l2 2 3.5-4"/>',
		'photo'    => '<rect x="3" y="5" width="18" height="14" rx="2"/><circle cx="8.5" cy="10" r="1.5"/><path d="M21 16l-5-5L5 19"/>',
		'phone'    => '<path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L17 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2z"/>',
		'mail'     => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/>',
		'pin'      => '<path d="M12 21s7-6 7-11a7 7 0 1 0-14 0c0 5 7 11 7 11z"/><circle cx="12" cy="10" r="2.5"/>',
		'clock'    => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
		'arrow'    => '<path d="M5 12h14M13 6l6 6-6 6"/>',
		'arrow-up' => '<path d="M12 19V5M6 11l6-6 6 6"/>',
		'check'    => '<circle cx="12" cy="12" r="9"/><path d="M8.5 12l2.5 2.5 4.5-5"/>',
		'star'     => '<path d="M12 3l2.6 5.3 5.9.9-4.3 4.1 1 5.8L12 16.8 6.8 19.6l1-5.8L3.5 9.7l5.9-.9z"/>',
		'spark'    => '<path d="M12 3v6M12 15v6M3 12h6M15 12h6"/>',
		'close'    => '<path d="M6 6l12 12M18 6L6 18"/>',
		'plus'     => '<path d="M12 5v14M5 12h14"/>',
		'chev-l'   => '<path d="M15 6l-6 6 6 6"/>',
		'chev-r'   => '<path d="M9 6l6 6-6 6"/>',
	);
	return $o . ( $paths[ $name ] ?? '' ) . $c;
}

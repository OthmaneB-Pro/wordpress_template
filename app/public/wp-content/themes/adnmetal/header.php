<?php
/**
 * Header.
 * @package AdnMetal
 */
$info = adnmetal_info();
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
	<meta name="theme-color" content="#faf6f1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="sr-only" href="#main">Aller au contenu</a>

<header class="site-header<?php echo is_front_page() ? ' is-light' : ''; ?>">
	<div class="wrap header-inner">
		<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( $info['name'] ); ?> — accueil">
			<?php if ( has_custom_logo() ) : the_custom_logo(); else : ?>
				<span class="brand-text"><span class="brand-name">Ets&nbsp;<b>Gurhem</b></span><span class="brand-sub">Couvreur · Zingueur</span></span>
			<?php endif; ?>
		</a>

		<nav class="primary-nav" aria-label="Navigation principale">
			<?php
			if ( has_nav_menu( 'primary' ) ) {
				wp_nav_menu( array(
					'theme_location' => 'primary',
					'container'      => false,
					'items_wrap'     => '%3$s',
					'walker'         => new Adnmetal_Nav_Walker(),
					'depth'          => 1,
				) );
			} else {
				adnmetal_default_nav();
			}
			?>
			<a class="btn btn--gold nav-cta" href="<?php echo esc_url( home_url( '/#devis' ) ); ?>">Devis gratuit</a>
		</nav>

		<button class="nav-toggle" aria-label="Ouvrir le menu" aria-expanded="false" aria-controls="mobile-nav">
			<span></span><span></span><span></span>
		</button>
	</div>
</header>

<nav class="mobile-nav" id="mobile-nav" aria-label="Menu mobile">
	<a href="<?php echo esc_url( home_url( '/' ) ); ?>">Accueil</a>
	<a href="<?php echo esc_url( home_url( '/#savoir-faire' ) ); ?>">Savoir-faire</a>
	<a href="<?php echo esc_url( home_url( '/#realisations' ) ); ?>">Réalisations</a>
	<a href="<?php echo esc_url( home_url( '/#devis' ) ); ?>">Contact &amp; Devis</a>
	<div class="mnav-foot">
		<a href="tel:<?php echo esc_attr( $info['phone_e164'] ); ?>"><?php echo esc_html( $info['phone'] ); ?></a><br>
		<?php echo esc_html( $info['city'] . ' ' . $info['zip'] ); ?>
	</div>
</nav>

<main id="main">

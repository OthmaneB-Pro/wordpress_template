<?php
/**
 * 404.
 * @package AdnMetal
 */
get_header(); ?>
<section class="hero" style="min-height:80svh">
	<div class="hero__bg"></div>
	<div class="wrap hero__inner">
		<span class="eyebrow hero__eyebrow">Erreur 404</span>
		<h1 class="display" style="font-size:var(--step-4)">Cette page<br>s'est <em>envolée.</em></h1>
		<div class="hero__meta">
			<p class="hero__lead">La page que vous cherchez n'existe pas (ou plus). Revenons sur la bonne voie.</p>
			<div class="hero__actions">
				<a class="btn btn--gold" href="<?php echo esc_url( home_url( '/' ) ); ?>">Retour à l'accueil <?php echo adnmetal_icon( 'arrow' ); // phpcs:ignore ?></a>
				<a class="btn btn--ghost" href="<?php echo esc_url( home_url( '/#realisations' ) ); ?>">Voir les réalisations</a>
			</div>
		</div>
	</div>
</section>
<?php get_footer();

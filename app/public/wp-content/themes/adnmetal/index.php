<?php
/**
 * Listing générique (blog, archives, recherche, catégories).
 * @package AdnMetal
 */
get_header();

$title = 'Réalisations';
if ( is_category() ) { $title = single_cat_title( '', false ); }
elseif ( is_search() ) { $title = 'Recherche : ' . get_search_query(); }
elseif ( is_archive() ) { $title = get_the_archive_title(); }
?>
<header class="page-hero">
	<div class="wrap">
		<span class="eyebrow">Ets Gurhem · Couvreur</span>
		<h1 class="display"><?php echo esc_html( wp_strip_all_tags( $title ) ); ?></h1>
	</div>
</header>

<div class="section">
	<div class="wrap">
		<?php if ( have_posts() ) : ?>
			<div class="work-grid" style="grid-template-columns:repeat(auto-fill,minmax(280px,1fr));grid-auto-rows:auto">
				<?php while ( have_posts() ) : the_post();
					$cats = get_the_category();
					$cat  = ! empty( $cats ) ? $cats[0]->name : 'Réalisation'; ?>
					<article class="work-card" style="grid-column:auto;grid-row:auto">
						<a href="<?php the_permalink(); ?>" aria-label="<?php the_title_attribute(); ?>">
							<span class="work-card__media" style="position:absolute;inset:0">
								<?php
								if ( has_post_thumbnail() ) {
									the_post_thumbnail( 'adnmetal-card', array( 'loading' => 'lazy' ) );
								} else {
									echo adnmetal_placeholder( array( 'title' => get_the_title(), 'sub' => 'Votre photo ici' ) ); // phpcs:ignore
								}
								?>
							</span>
							<span class="work-card__overlay">
								<span class="work-card__cat"><?php echo esc_html( $cat ); ?></span>
								<span class="work-card__title"><?php the_title(); ?></span>
							</span>
						</a>
					</article>
				<?php endwhile; ?>
			</div>

			<div style="margin-top:clamp(2.5rem,4vw,4rem)">
				<?php the_posts_pagination( array( 'mid_size' => 1, 'prev_text' => '←', 'next_text' => '→' ) ); ?>
			</div>
		<?php else : ?>
			<p class="lead">Aucune réalisation pour l'instant. <a class="link-arrow" href="<?php echo esc_url( home_url( '/#devis' ) ); ?>">Démarrons la vôtre →</a></p>
		<?php endif; ?>
	</div>
</div>
<?php
get_footer();

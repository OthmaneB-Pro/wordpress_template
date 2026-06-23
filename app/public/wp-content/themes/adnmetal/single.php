<?php
/**
 * Article unique (réalisation).
 * @package AdnMetal
 */
get_header();
while ( have_posts() ) : the_post();
	$cats = get_the_category();
	$cat  = ! empty( $cats ) ? $cats[0]->name : 'Réalisation';
	?>
	<article class="page-single">
		<header class="page-hero">
			<div class="wrap">
				<span class="eyebrow"><?php echo esc_html( $cat ); ?></span>
				<h1 class="display"><?php the_title(); ?></h1>
			</div>
		</header>

		<div class="section section--tight">
			<div class="wrap">
				<?php if ( has_post_thumbnail() ) : ?>
					<div style="border-radius:var(--radius);overflow:hidden;margin-bottom:clamp(2rem,4vw,4rem)">
						<?php the_post_thumbnail( 'adnmetal-wide', array( 'style' => 'width:100%;height:auto;display:block' ) ); ?>
					</div>
				<?php else : ?>
					<div style="margin-bottom:clamp(2rem,4vw,4rem)">
						<?php echo adnmetal_placeholder( array( 'title' => get_the_title(), 'sub' => 'Votre photo ici', 'class' => 'about__media' ) ); // phpcs:ignore ?>
					</div>
				<?php endif; ?>

				<div class="entry">
					<?php the_content(); ?>
				</div>

				<nav class="post-nav">
					<?php
					$prev = get_previous_post();
					$next = get_next_post();
					if ( $prev ) : ?>
						<a class="link-arrow" href="<?php echo esc_url( get_permalink( $prev ) ); ?>" style="transform:scaleX(1)">← <?php echo esc_html( get_the_title( $prev ) ); ?></a>
					<?php endif; ?>
					<a class="link-arrow" href="<?php echo esc_url( home_url( '/#realisations' ) ); ?>">Toutes les réalisations</a>
					<?php if ( $next ) : ?>
						<a class="link-arrow" href="<?php echo esc_url( get_permalink( $next ) ); ?>"><?php echo esc_html( get_the_title( $next ) ); ?> →</a>
					<?php endif; ?>
				</nav>

				<div style="margin-top:clamp(3rem,5vw,5rem);text-align:center">
					<a class="btn btn--primary" href="<?php echo esc_url( home_url( '/#devis' ) ); ?>">Un projet similaire ? Demander un devis <?php echo adnmetal_icon( 'arrow' ); // phpcs:ignore ?></a>
				</div>
			</div>
		</div>
	</article>
<?php endwhile;
get_footer();

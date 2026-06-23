<?php
/**
 * Page générique.
 * @package AdnMetal
 */
get_header();
while ( have_posts() ) : the_post(); ?>
	<article class="page-single">
		<header class="page-hero">
			<div class="wrap">
				<span class="eyebrow">Ets Gurhem · Couvreur</span>
				<h1 class="display"><?php the_title(); ?></h1>
			</div>
		</header>

		<div class="section section--tight">
			<div class="wrap">
				<?php if ( has_post_thumbnail() ) : ?>
					<div class="ph" style="margin-bottom:clamp(2rem,4vw,4rem);border-radius:var(--radius);overflow:hidden">
						<?php the_post_thumbnail( 'adnmetal-wide', array( 'style' => 'width:100%;height:auto' ) ); ?>
					</div>
				<?php endif; ?>
				<div class="entry">
					<?php the_content(); ?>
				</div>
			</div>
		</div>
	</article>
<?php endwhile;
get_footer();

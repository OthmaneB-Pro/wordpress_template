<?php
/**
 * Lightbox de la galerie.
 * @package AdnMetal
 */
?>
<div class="lightbox" role="dialog" aria-modal="true" aria-label="Aperçu de la réalisation">
	<button class="lightbox__close" aria-label="Fermer"><?php echo adnmetal_icon( 'close' ); // phpcs:ignore ?></button>
	<button class="lightbox__nav lightbox__nav--prev" aria-label="Précédent"><?php echo adnmetal_icon( 'chev-l' ); // phpcs:ignore ?></button>
	<button class="lightbox__nav lightbox__nav--next" aria-label="Suivant"><?php echo adnmetal_icon( 'chev-r' ); // phpcs:ignore ?></button>
	<div class="lightbox__inner">
		<div class="lightbox__media"></div>
		<div class="lightbox__cap">
			<div>
				<span class="cat"></span>
				<h3></h3>
			</div>
			<p class="muted" style="max-width:42ch;color:var(--steel-soft)"></p>
		</div>
	</div>
</div>

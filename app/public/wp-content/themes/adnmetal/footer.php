<?php
/**
 * Footer.
 * @package AdnMetal
 */
$info = adnmetal_info();
?>
</main><!-- #main -->

<footer class="site-footer" id="contact-foot">
	<div class="wrap">
		<div class="footer-top">
			<div class="footer-brand">
				<span class="brand-name">Ets&nbsp;<b>Gurhem</b></span>
				<p>Artisan couvreur-zingueur à <?php echo esc_html( $info['city'] . ' ' . $info['zip'] ); ?>. Couverture, rénovation de toiture, zinguerie, gouttières, démoussage &amp; façade dans l'Oise.</p>
				<p style="margin-top:1rem"><a class="link-arrow" href="<?php echo esc_url( home_url( '/#devis' ) ); ?>">Demander un devis <?php echo adnmetal_icon( 'arrow' ); // phpcs:ignore ?></a></p>
			</div>

			<div class="footer-col">
				<h4>Naviguer</h4>
				<ul>
					<li><a href="<?php echo esc_url( home_url( '/#savoir-faire' ) ); ?>">Savoir-faire</a></li>
					<li><a href="<?php echo esc_url( home_url( '/#realisations' ) ); ?>">Réalisations</a></li>
					<li><a href="<?php echo esc_url( home_url( '/presentation/' ) ); ?>">Présentation</a></li>
					<li><a href="<?php echo esc_url( home_url( '/a-propos/' ) ); ?>">À propos</a></li>
				</ul>
			</div>

			<div class="footer-col">
				<h4>Contact</h4>
				<ul>
					<li><a href="tel:<?php echo esc_attr( $info['phone_e164'] ); ?>"><?php echo esc_html( $info['phone'] ); ?></a></li>
					<li><?php echo esc_html( $info['address'] ); ?></li>
					<li><?php echo esc_html( $info['zip'] . ' ' . $info['city'] ); ?></li>
					<li><?php echo esc_html( $info['hours'] ); ?></li>
				</ul>
			</div>
		</div>

		<div class="footer-bottom">
			<span>&copy; <span data-year><?php echo esc_html( gmdate( 'Y' ) ); ?></span> Ets Gurhem — Tous droits réservés.</span>
			<a class="to-top" href="#main">Haut de page <?php echo adnmetal_icon( 'arrow-up' ); // phpcs:ignore ?></a>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>

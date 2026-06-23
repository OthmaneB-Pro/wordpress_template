<?php
/**
 * Front page — la vitrine Ets Gurhem, couvreur.
 * @package AdnMetal
 */
get_header();
$info = adnmetal_info();
?>

<!-- ============================ HERO ============================ -->
<section class="hero" id="accueil">
	<div class="hero__bg"></div>

	<div class="wrap hero__inner">
		<span class="eyebrow hero__eyebrow">Couvreur · Zingueur — <?php echo esc_html( $info['city'] . ' ' . substr( $info['zip'], 0, 2 ) ); ?></span>
		<h1 class="display">
			<span class="ln"><span>L'art de</span></span>
			<span class="ln"><span><em>bien couvrir</em></span></span>
		</h1>
		<p class="hero__lead">Couverture, rénovation de toiture, zinguerie, démoussage &amp; façade — un toit étanche et durable, posé avec le soin de l'artisan, dans l'Oise.</p>
		<div class="hero__actions">
			<a class="btn btn--gold" href="<?php echo esc_url( home_url( '/#devis' ) ); ?>">Demander un devis <?php echo adnmetal_icon( 'arrow' ); // phpcs:ignore ?></a>
			<a class="btn btn--ghost" href="tel:<?php echo esc_attr( $info['phone_e164'] ); ?>"><?php echo esc_html( $info['phone'] ); ?></a>
		</div>
	</div>

	<span class="hero__scroll">Défiler</span>

	<div class="hero__medal" aria-label="Plus de 20 ans d'expérience">
		<span class="hero__medal-disc"><?php echo adnmetal_icon( 'shield' ); // phpcs:ignore ?></span>
		<span class="hero__medal-line">+ de 20 ans d'expérience</span>
	</div>
</section>

<!-- ============================ MARQUEE ============================ -->
<div class="marquee" aria-hidden="true">
	<div class="marquee__track">
		<span class="marquee__item">Toiture</span>
		<span class="marquee__item">Rénovation</span>
		<span class="marquee__item">Zinguerie</span>
		<span class="marquee__item">Gouttières</span>
		<span class="marquee__item">Démoussage</span>
		<span class="marquee__item">Ardoise</span>
		<span class="marquee__item">Tuile</span>
		<span class="marquee__item">Façade</span>
	</div>
</div>

<!-- ============================ INTRO / SIGNATURE ============================ -->
<section class="section" id="signature">
	<div class="wrap">
		<div class="intro-head" data-reveal>
			<span class="section-num">01 — L'entreprise</span>
		</div>
		<div class="intro">
			<p class="intro__manifesto" data-reveal>Chez <b>Ets Gurhem</b>, votre toit est entre de bonnes mains. Nous protégeons votre maison des intempéries avec un travail soigné, propre et durable — du simple démoussage à la réfection complète.</p>
			<div class="intro__text" data-reveal>
				<p>Installés à <?php echo esc_html( $info['city'] ); ?>, dans l'Oise, nous accompagnons les particuliers pour tous leurs travaux de toiture : couverture neuve, rénovation, zinguerie, traitement et façade.</p>
				<p>Plus de <strong>20 ans d'expérience</strong>, des conseils honnêtes, un devis gratuit et un chantier mené proprement, du premier rendez-vous à la pose.</p>
				<p class="intro__sign"><span class="intro__sign-name">Ets Gurhem</span><span class="intro__sign-role">Artisan couvreur — Verneuil-en-Halatte</span></p>
			</div>
		</div>

		<div class="stats" data-reveal-stagger>
			<div class="stat"><span class="stat__num">20<em>+</em></span><span class="stat__label">Ans d'expérience</span></div>
			<div class="stat"><span class="stat__num">100<em>%</em></span><span class="stat__label">Devis gratuits</span></div>
			<div class="stat"><span class="stat__num">10<em>ans</em></span><span class="stat__label">Garantie décennale</span></div>
			<div class="stat"><span class="stat__num">60</span><span class="stat__label">Oise &amp; alentours</span></div>
		</div>
	</div>
</section>

<!-- ============================ SAVOIR-FAIRE / SERVICES ============================ -->
<section class="section services" id="savoir-faire">
	<div class="wrap">
		<div class="section-head" data-reveal>
			<span class="section-num">02 — Savoir-faire</span>
			<h2 class="section-title">Ce que nous <em>réalisons</em></h2>
			<p class="lead services__lead">Tous les travaux de votre toiture, du neuf à l'entretien, par un seul artisan de confiance.</p>
		</div>
		<div class="svc-grid" data-reveal-stagger>
			<?php foreach ( adnmetal_services() as $i => $s ) : ?>
				<article class="svc">
					<span class="svc__num"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
					<span class="svc__icon"><?php echo adnmetal_icon( $s['icon'] ); // phpcs:ignore ?></span>
					<h3 class="svc__title"><?php echo esc_html( $s['title'] ); ?></h3>
					<p class="svc__desc"><?php echo esc_html( $s['desc'] ); ?></p>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- ============================ RÉALISATIONS ============================ -->
<section class="section" id="realisations">
	<div class="wrap">
		<div class="work__head">
			<div class="section-head" data-reveal>
				<span class="section-num">03 — Réalisations</span>
				<h2 class="section-title">Nos <em>chantiers</em></h2>
			</div>
			<a class="link-arrow" href="<?php echo esc_url( home_url( '/#devis' ) ); ?>" data-reveal>Votre projet ici <?php echo adnmetal_icon( 'arrow' ); // phpcs:ignore ?></a>
		</div>

		<div class="filters" data-reveal>
			<?php $first = true; foreach ( adnmetal_categories() as $slug => $label ) : ?>
				<button class="filter<?php echo $first ? ' is-active' : ''; ?>" data-filter="<?php echo esc_attr( $slug ); ?>"><?php echo esc_html( $label ); ?></button>
			<?php $first = false; endforeach; ?>
		</div>

		<div class="work-grid">
			<?php foreach ( adnmetal_realisations() as $r ) :
				$post = get_page_by_path( $r['slug'], OBJECT, 'post' );
				$url  = $post ? get_permalink( $post ) : home_url( '/#devis' );
				$size_class = array(
					'feature' => ' work-card--feature',
					'tall'    => ' work-card--tall',
					'wide'    => ' work-card--wide',
				);
				$cls = isset( $size_class[ $r['size'] ] ) ? $size_class[ $r['size'] ] : '';
				?>
				<article class="work-card<?php echo esc_attr( $cls ); ?>"
					data-cat="<?php echo esc_attr( $r['cat'] ); ?>"
					data-cat-label="<?php echo esc_attr( $r['cat_label'] ); ?>"
					data-title="<?php echo esc_attr( $r['title'] ); ?>"
					data-desc="<?php echo esc_attr( $r['desc'] ); ?>">
					<a href="<?php echo esc_url( $url ); ?>" aria-label="<?php echo esc_attr( $r['title'] ); ?>">
						<span class="work-card__media"><?php echo adnmetal_realisation_media( $r ); // phpcs:ignore ?></span>
						<span class="work-card__plus"><?php echo adnmetal_icon( 'plus' ); // phpcs:ignore ?></span>
						<span class="work-card__overlay">
							<span class="work-card__cat"><?php echo esc_html( $r['cat_label'] ); ?></span>
							<span class="work-card__title"><?php echo esc_html( $r['title'] ); ?></span>
						</span>
					</a>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- ============================ CONFIANCE ============================ -->
<section class="section maf" id="confiance">
	<div class="maf__bg"></div>
	<div class="wrap maf__inner">
		<div class="maf__seal" data-reveal>
			<?php get_template_part( 'template-parts/seal' ); ?>
		</div>
		<div data-reveal>
			<span class="section-num">04 — Confiance</span>
			<h2 class="maf__title">Une entreprise<br><em>de confiance</em></h2>
			<p class="maf__text">Depuis plus de 20 ans, Ets Gurhem intervient sur les toitures de l'Oise avec la même exigence : un travail propre, des matériaux de qualité et des finitions soignées. Devis gratuit, conseils honnêtes et chantier respecté de A à Z.</p>
			<div class="maf__medals">
				<span class="medal"><?php echo adnmetal_icon( 'check' ); // phpcs:ignore ?> + de 20 ans d'expérience</span>
				<span class="medal"><?php echo adnmetal_icon( 'check' ); // phpcs:ignore ?> Garantie décennale</span>
				<span class="medal"><?php echo adnmetal_icon( 'check' ); // phpcs:ignore ?> Devis gratuit</span>
			</div>
		</div>
	</div>
</section>

<!-- ============================ MATÉRIAUX ============================ -->
<section class="section" id="materiaux">
	<div class="wrap materials">
		<div class="section-head" data-reveal>
			<span class="section-num">05 — Matières</span>
			<h2 class="section-title">Le bon matériau,<br>au bon <em>endroit</em></h2>
			<p class="lead">Tuile, ardoise ou zinc : nous choisissons avec vous la couverture adaptée à votre maison et à votre budget.</p>
		</div>
		<ul class="mat-list" data-reveal-stagger>
			<li class="mat"><span class="mat__idx">T.</span><span class="mat__name">Tuile</span><span class="mat__desc">Terre cuite ou béton, le grand classique du toit. Robuste, économique et chaleureux, pour le neuf comme la rénovation.</span></li>
			<li class="mat"><span class="mat__idx">A.</span><span class="mat__name">Ardoise</span><span class="mat__desc">Naturelle, élégante et durable. Une couverture noble qui traverse les décennies sans faiblir.</span></li>
			<li class="mat"><span class="mat__idx">Z.</span><span class="mat__name">Zinc</span><span class="mat__desc">Pour la zinguerie, les gouttières et les points sensibles : étanchéité parfaite et longévité exceptionnelle.</span></li>
		</ul>
	</div>
</section>

<!-- ============================ PROCESS ============================ -->
<section class="section process" id="process">
	<div class="wrap">
		<div class="section-head" data-reveal>
			<span class="section-num">06 — Méthode</span>
			<h2 class="section-title">Du diagnostic à la <em>garantie</em></h2>
		</div>
		<div class="steps">
			<div class="step"><h3>Diagnostic</h3><p>On se déplace, on monte sur le toit et on évalue l'état réel de votre couverture. Conseils clairs et honnêtes.</p></div>
			<div class="step"><h3>Devis gratuit</h3><p>Un devis détaillé, sans engagement, avec le choix des matériaux et des finitions adaptés à votre budget.</p></div>
			<div class="step"><h3>Intervention</h3><p>Travaux réalisés proprement et dans les règles de l'art, chantier protégé et nettoyé chaque jour.</p></div>
			<div class="step"><h3>Garantie</h3><p>Réception du chantier avec vous, finitions vérifiées et travaux couverts par notre assurance décennale.</p></div>
		</div>
	</div>
</section>

<!-- ============================ CONTACT / DEVIS ============================ -->
<section class="section contact" id="devis">
	<a id="contact"></a>
	<div class="wrap">
		<div class="section-head" data-reveal>
			<span class="section-num">07 — Contact</span>
			<h2 class="section-title">Parlons de votre <em>toiture</em></h2>
			<p class="lead" style="color:var(--steel-soft)">Une fuite, un toit à refaire, des gouttières à changer ? Décrivez votre besoin : devis gratuit et sans engagement. Le plus rapide reste de nous appeler.</p>
		</div>

		<div class="contact__grid">
			<div class="contact__info" data-reveal>
				<div class="coord">
					<div class="coord__item">
						<span class="coord__icon"><?php echo adnmetal_icon( 'phone' ); // phpcs:ignore ?></span>
						<span><span class="coord__k">Téléphone</span><span class="coord__v"><a href="tel:<?php echo esc_attr( $info['phone_e164'] ); ?>"><?php echo esc_html( $info['phone'] ); ?></a></span></span>
					</div>
					<div class="coord__item">
						<span class="coord__icon"><?php echo adnmetal_icon( 'pin' ); // phpcs:ignore ?></span>
						<span><span class="coord__k">Adresse</span><span class="coord__v"><?php echo esc_html( $info['address'] . ', ' . $info['zip'] . ' ' . $info['city'] ); ?></span></span>
					</div>
					<div class="coord__item">
						<span class="coord__icon"><?php echo adnmetal_icon( 'pin' ); // phpcs:ignore ?></span>
						<span><span class="coord__k">Secteur</span><span class="coord__v"><?php echo esc_html( $info['city'] ); ?> &amp; alentours (Oise)</span></span>
					</div>
					<div class="coord__item">
						<span class="coord__icon"><?php echo adnmetal_icon( 'clock' ); // phpcs:ignore ?></span>
						<span><span class="coord__k">Horaires</span><span class="coord__v" style="font-size:var(--step-0)"><?php echo esc_html( $info['hours'] ); ?></span></span>
					</div>
				</div>
			</div>

			<form class="form" data-ajax data-reveal>
				<div class="form__row form__row--2">
					<div class="field">
						<label for="f-name">Nom *</label>
						<input type="text" id="f-name" name="name" required autocomplete="name" placeholder="Votre nom">
					</div>
					<div class="field">
						<label for="f-phone">Téléphone</label>
						<input type="tel" id="f-phone" name="phone" autocomplete="tel" placeholder="06 00 00 00 00">
					</div>
				</div>
				<div class="form__row form__row--2">
					<div class="field">
						<label for="f-email">Email *</label>
						<input type="email" id="f-email" name="email" required autocomplete="email" placeholder="vous@email.fr">
					</div>
					<div class="field">
						<label for="f-project">Type de projet</label>
						<select id="f-project" name="project">
							<option value="">Choisir…</option>
							<option>Couverture / toiture neuve</option>
							<option>Rénovation de toiture</option>
							<option>Zinguerie / gouttières</option>
							<option>Démoussage / hydrofuge</option>
							<option>Fenêtre de toit</option>
							<option>Ravalement de façade</option>
							<option>Autre / dépannage</option>
						</select>
					</div>
				</div>
				<div class="field">
					<label for="f-message">Votre projet *</label>
					<textarea id="f-message" name="message" required placeholder="Décrivez votre besoin, le type de toiture, votre adresse…"></textarea>
				</div>
				<div class="field" style="position:absolute;left:-9999px" aria-hidden="true">
					<label>Ne pas remplir</label>
					<input type="text" name="website" tabindex="-1" autocomplete="off">
				</div>
				<label class="form__consent">
					<input type="checkbox" required>
					<span>J'accepte d'être recontacté(e) au sujet de ma demande. Mes données ne sont pas cédées à des tiers.</span>
				</label>
				<button type="submit" class="btn btn--gold form__submit">Envoyer ma demande <?php echo adnmetal_icon( 'arrow' ); // phpcs:ignore ?></button>
				<p class="form__note" role="status" aria-live="polite"></p>
			</form>
		</div>
	</div>
</section>

<?php
// Lightbox (galerie)
get_template_part( 'template-parts/lightbox' );
get_footer();

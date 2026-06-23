# Thème Ets Gurhem — Couvreur

Thème WordPress sur mesure pour **Ets Gurhem**, artisan couvreur-zingueur à
Verneuil-en-Halatte (60550). Direction artistique **« Tuile & Crème »** : fond crème
chaleureux, accents terre cuite, détails ardoise, typographies *Fraunces* (serif chaleureux)
+ *Inter* (corps lisible), animations élégantes (scroll-reveal, parallax léger, lightbox),
100 % responsive.

> Note technique : pour des raisons de compatibilité, le dossier du thème, les préfixes de
> fonctions (`adnmetal_…`) et l'objet JS (`AdnMetal`) sont restés inchangés. Ils sont
> purement internes et invisibles pour les visiteurs ; tout le contenu affiché est bien
> celui d'Ets Gurhem.

---

## 1. Activation & contenu

Le thème est déjà actif. Au premier chargement après la mise à jour, une **migration
automatique** (versionnée, idempotente) s'exécute et :

- supprime l'ancien contenu de démonstration ;
- crée les **réalisations** (articles) couvreur ;
- crée / met à jour les pages `/presentation/` et `/a-propos/` ;
- définit la page d'accueil statique et le nom du site ;
- passe les permaliens en `/slug/`.

Pour rejouer la migration : `wp option delete gurhem_content_v` (ou via la base, table
`wp_options`).

---

## 2. Ajouter vos photos

Le site est livré avec des **emplacements vides** balisés *« Votre photo ici »*.
Pour les remplacer par vos vraies photos :

1. **Réalisations (galerie d'accueil + pages chantier)**
   Allez dans **Articles**, ouvrez le chantier concerné, et définissez une
   **Image mise en avant**. Elle remplace automatiquement l'emplacement dans la galerie
   d'accueil *et* sur la page du chantier. Idéal : format paysage, ≥ 1400 px de large.

2. **Image d'accueil (hero)**
   La photo plein écran de l'accueil est `assets/img/couvreur.png`. Remplacez ce fichier
   par votre propre photo (même nom) pour la changer.

3. **Image de partage social (Open Graph)**
   Remplacez `assets/img/og.jpg` (1200 × 630 px).

4. **Logo**
   **Apparence → Personnaliser → Identité du site → Logo** (sinon, le nom « Ets Gurhem »
   typographique s'affiche par défaut).

---

## 3. Formulaire de devis

Le formulaire (section Contact) envoie un email via `wp_mail()` (AJAX, anti-spam honeypot,
validation). L'adresse de réception est définie dans `functions.php` → `adnmetal_info()`
(clé `email`) — **à remplacer par la vraie adresse de l'entreprise**. Pour fiabiliser la
délivrabilité, installez un plugin SMTP (ex. *WP Mail SMTP*).

> Le contact mis en avant sur le site est le **téléphone** (06 69 02 51 57).

---

## 4. Structure du thème

```
adnmetal/
├── style.css              en-tête du thème
├── functions.php          setup, assets, AJAX devis, SEO (JSON-LD + OG), identité
├── front-page.php         page d'accueil (toutes les sections)
├── header.php / footer.php
├── page.php / single.php / index.php / 404.php
├── assets/
│   ├── css/main.css       design system « Tuile & Crème »
│   ├── js/main.js         animations, galerie, lightbox, nav, form
│   └── img/couvreur.png   image d'accueil · og.jpg image de partage
├── inc/
│   ├── helpers.php        données (réalisations, services), emplacements, icônes, menu
│   └── seeder.php         création + migration du contenu
└── template-parts/
    ├── seal.php           emblème « confiance »
    └── lightbox.php
```

Le contenu éditorial (réalisations, services, coordonnées) est centralisé dans
`inc/helpers.php` et `adnmetal_info()` — un seul endroit à modifier.

---

## 5. Accessibilité & performance

- Respecte `prefers-reduced-motion` (animations désactivées si demandé).
- Images en `loading="lazy"`, polices `display=swap`, preconnect.
- HTML sémantique, contrastes soignés, navigation clavier (lightbox, menu).
- Données structurées `RoofingContractor` + zones desservies (SEO local Oise).

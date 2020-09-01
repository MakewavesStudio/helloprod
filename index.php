<?php get_header(); ?>

    <section class="o-wrapper o-landing">

      <h1>Accueil</h1>

      Style de caractères et paragraphes<br>
      ==================================<br>

      [TITLES] Title ->  <?php echo(htmlspecialchars("<h1>, .a-title")); ?><br>
      <h1 class="a-title">Titre de la page</h1>

      [TITLES] Title 1 alternatif ->  <?php echo(htmlspecialchars("<h1>, .a-title--alt")); ?><br>
      <h1 class="a-title--alt">Gros titre de section</h1>

      [TITLES] Title 2 ->  <?php echo(htmlspecialchars("<h2>, .a-title--2")); ?><br>
      <h2 class="a-title--2">Titre encart</h2>

      [TITLES] Title 3 ->  <?php echo(htmlspecialchars("<h3>, .a-title--3")); ?><br>
      <h3 class="a-title--3">Titre de paragraphe</h3>

      [TEXTS] Paragraphe -> <?php echo(htmlspecialchars("<p>, .a-paragraph, <strong>, <em>")); ?><br>
      <p class="a-paragraph">
        Paragraphe : <br>
        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor <strong>incididunt ut labore et dolore</strong> magna aliqua. <br>
        Ut enim ad minim veniam, <em>quis nostrud exercitation</em> ullamco laboris nisi ut aliquip ex ea commodo consequat.
      </p>

      [TEXTS] Surtitre -> <?php echo(htmlspecialchars("<span class='.a-subtitle'>")); ?><br>
      <span class="a-subtitle">Titre de paragraphe</span>

      [TEXTS] Mots clés ->  <?php echo(htmlspecialchars("<h5>, .a-title--5")); ?><br>
      <h5 class="a-title--5">Titre de paragraphe</h5>


      [TEXTS] Liste à puce -> <?php echo(htmlspecialchars("<ul>, .a-list")); ?><br>
      <ul>
        <li>Liste à puce</li>
        <li>Liste à puce</li>
        <li>Liste à puce</li>
      </ul>


      [TEXTS] Liste numérotée -> <?php echo(htmlspecialchars("<ol>, .a-list--num")); ?><br>
      <ul>
        <li>Liste numérotée</li>
        <li>Liste numérotée</li>
        <li>Liste numérotée</li>
      </ul>

      [TEXTS] Citation -> <?php echo(htmlspecialchars("<blockquote>, .a-quote")); ?><br>
      <blockquote>Être créateur de valeurs et révélateur d’opportunités pour nos clients et partenaires.</blockquote>

      Liens et boutons d’actions<br>
      ==========================<br>

      [TEXTS] Lien hypertexte -> <?php echo(htmlspecialchars("<a>, .a-link")); ?><br>
      <a href="javascript:void(0);">Lien hypertexte</a>

      [BUTTONS] Bouton d'action principal / version Light (fond dark) -> <?php echo(htmlspecialchars("<a>, .a-button, .a-button--light")); ?><br>
      <a class="a-button" href="javascript:void(0);">Bouton d'action principal</a><br>
      <a class="a-button--light" href="javascript:void(0);">Bouton d'action principal</a><br>

      [BUTTONS] icone + Bouton d'action principal + flèche / version Light (fond dark)<br>

      [BUTTONS] icone + Bouton d’action / version Light (fond dark)<br>

      [BUTTONS] Bouton d'action principal / couleur unie<br>

      [BUTTONS] icone + Bouton d'action principal / couleur unie<br>

      [BUTTONS] icone + Bouton d'action secondaire / version Light (fond dark)<br>

      [ICONS] icone alignée gauche / alignée droite -> <?php echo(htmlspecialchars(".a-icon")); ?><br>
      <span class="a-icon">icône exemple</span>

      Formulaire<br>
      ==========================<br>

      [FORMS] Liste déroulante / select joli<br>

      [FORMS] Input formulaire / avec icône gauche, placeholder<br>

      [FORMS] Input formulaire / sans icône gauche, placeholder<br>

      [FORMS] Label formulaire<br>

      [FORMS] Label erreur Formulaire<br>

      [FORMS] Input formulaire / erreur<br>

      [FORMS] Checkbox cochée / non cochée<br>

      [FORMS] Textarea / placeholder<br>

      [FORMS] Bouton sumbit / placement<br>


    </section>


<?php get_footer();

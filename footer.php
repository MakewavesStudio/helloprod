</div class="o-global-container">

			<footer class="c-footer o-wrapper--max">

	                <?php
	                // Get Existing Options
	                $existing_coordonnees = get_option('mkwvs_dashbox_coordonnees');
	                $existing_social = get_option('mkwvs_dashbox_social');
	                //
	                ?>

					<img class="c-footer__logo" alt="<?php echo get_bloginfo('name'); ?>" src="<?php echo get_stylesheet_directory_uri() . '/img/logo.svg' ?>"/>

					<div class="c-footer__social">
						<h3 class="c-footer__social-title">Réseaux sociaux</h3>
						<ul class="c-footer__social-menu">
                            <?php foreach($existing_social as $social_item) : ?>
                                <?php if ($social_item[0]) : ?>
                                        <li><a href="<?php echo $social_item[2]; ?>" target="_blank" class="<?php echo strtolower($social_item[1]); ?>"><?php echo $social_item[1]; ?></a></li>
                                <?php endif; ?>
                            <?php endforeach; ?>
						</ul>
					</div>

					<div class="c-footer__contact">
	      		<h3 class="c-footer__contact-title">Coordonnées</h3>
	          <ul class="c-footer__contact-menu">
	          	<li><a href="mailto:<?php echo $existing_coordonnees[0]; ?>"><?php echo $existing_coordonnees[0]; ?></a></li>
	          	<li><a href="tel:<?php echo $existing_coordonnees[1]; ?>"><?php echo $existing_coordonnees[1]; ?></a></li>
							<li><a target="_blank" href="#"><?php echo $existing_coordonnees[2]; ?><br><?php echo $existing_coordonnees[3]; ?> <?php echo $existing_coordonnees[4]; ?></a></li>
					  </ul>
					</div>


					<p class="c-footer__last-details">
						Création <a href="#">Makewaves</a> 2019 -
						<a href="#">Mentions légales</a> -
						<a href="#">Confidentialité</a>
					</p>

	    </footer>

        <?php wp_footer(); ?>
    </body>

</html>

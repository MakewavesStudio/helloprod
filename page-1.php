<?php get_header(); ?>

<?php if (have_posts()) : ?>
    <?php while (have_posts()) : the_post(); ?>

      <section class="o-wrapper o-landing">

        <h1><?php echo get_the_title(); ?></h1>

        <?php $post_thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()),'medium'); ?>
        <?php if (false !== $post_thumbnail) : ?>

        <figure>
          <img src="<?php echo $post_thumbnail[0] ?>" alt="<?php echo get_the_title(); ?>"/>
        </figure>

        <?php endif; ?>

        <?php echo apply_filters('the_content', get_the_content()); ?>

        <!-- Contact Form -->

        <form name="contact-form" id="contact-form" method="post" action="">
          <input type="hidden" name="contact-form-nonce" id="contact-form-nonce" value="<?php echo wp_create_nonce('contact-form-nonce'); ?>" />
          <input type="hidden" name="action" value="mkwvs_contact_form_submit" />

          <div class="row">
              <label for="c-subject">Objet de ma demande</label>

              <select id="c-subject" name="c-subject" required>
                  <option value="">Sélectionnez ...</option>
                  <option value="Service 1">Service 1</option>
                  <option value="Service 2">Service 2</option>
                  <option value="Service 3">Service 3</option>
                  <option value="Autre">Autre</option>
              </select>
          </div>

          <div class="row">
              <label for="c-name">Je m'appelle</label>
              <input id="c-name" class="input-saisie" name="c-name" required type="text" placeholder="Nom, prénom">
          </div>

          <div class="row">
              <label for="c-mail">Adresse de contact</label>
              <input id="c-mail" class="input-saisie" name="c-mail" required type="email" placeholder="Email">
          </div>


          <div class="row">
              <label for="c-message">Ma demande</label>
              <textarea id="c-message" name="c-message" rows="5" cols="20" required style="border:1px solid #333333;color:#000000"></textarea>
          </div>

          <div class="row">
                    <span class="submit-button">
                        <input id="c-validator" class="cta" value="Envoyer" type="submit">
                    </span>
          </div>
          <div id="contact-form-notification"></div>
        </form>

        <!-- End | Contact Form -->

      </section>

    <?php endwhile; ?>
<?php endif; ?>


<?php get_footer();

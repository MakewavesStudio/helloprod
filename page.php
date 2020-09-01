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

      </section>

    <?php endwhile; ?>
<?php endif; ?>


<?php get_footer();

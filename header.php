<?php // Foool Header ?>
<!DOCTYPE html>
<html lang="fr" class="loading">
    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height, target-densitydpi=device-dpi" />
        <title><?php wp_title(''); ?></title>
        <link rel="icon" type="image/png" href="<?php echo get_stylesheet_directory_uri() . '/img/favicon.png' ?>" />
        <?php wp_head(); ?>

        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

    </head>

    <body <?php body_class(); ?>>

        <header class="c-header o-wrapper--max">

            <!-- <a class="c-header__burger" href="#"> -->
            <div class="c-header__burger" href="#">
              <div class="burger burger-rotate">
                <div class="burger-lines"></div>
              </div>
            </div>

            <a class="c-header__logolink" href="<?php echo get_bloginfo('url') ?>">
                <img class="c-header__logo" alt="<?php echo get_bloginfo('name'); ?>" src="<?php echo get_stylesheet_directory_uri() . '/img/logo.svg' ?>"/>
            </a>

            <div class="c-header__menulayout">

                <nav class="c-header__navmenu" class="modale">
                    <?php echo wp_nav_menu( array('menu' => 'menu-navigation')); ?>
                </nav>

                <ul class="c-header__social-menu">
                    <?php
                    // Get Existing Options
                    $existing_social = get_option('mkwvs_dashbox_social'); ?>
                    <?php foreach($existing_social as $social_item) : ?>
                        <?php if ($social_item[0]) : ?>
                        <li><a href="<?php echo $social_item[2]; ?>" target="_blank" class="<?php echo strtolower($social_item[1]); ?>"><?php echo $social_item[1]; ?></a></li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>

            </div>



        </header>

        <div class="o-global-container">

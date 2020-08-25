<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <?php wp_head(); ?>
</head>
<body>
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <header id="bb-header-container-" class="bb-header-container">
            <div id="bb-header-inside-" class="bb-header-inside">
                <?php the_content(); ?>
            </div>
        </header>
    <?php endwhile; endif; ?>	

    <?php wp_footer(); ?>
</body>
</html>
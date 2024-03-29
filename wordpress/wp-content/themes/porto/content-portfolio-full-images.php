<?php
global $porto_settings, $porto_layout;

$portfolio_layout = 'full-images';

$portfolio_info = get_post_meta($post->ID, 'portfolio_info', true);
$portfolio_link = get_post_meta($post->ID, 'portfolio_link', true);
$skill_list = get_the_term_list($post->ID, 'portfolio_skills', '', '</li><li><i class="fa fa-check-circle"></i> ', '');
$portfolio_client = get_post_meta($post->ID, 'portfolio_client', true);
$portfolio_client_link = get_post_meta($post->ID, 'portfolio_client_link', true);
$portfolio_author_quote = get_post_meta($post->ID, 'portfolio_author_quote', true);
$portfolio_author_name = get_post_meta($post->ID, 'portfolio_author_name', true);
$portfolio_author_image = get_post_meta($post->ID, 'portfolio_author_image', true);
$portfolio_author_role = get_post_meta($post->ID, 'portfolio_author_role', true);

$share = porto_get_meta_value('portfolio_share');
?>

<article <?php post_class('portfolio portfolio-' . $portfolio_layout); ?>>

    <div class="portfolio-title<?php echo ($porto_layout === 'widewidth' ? ' container m-t-lg' : '') ?>">
        <div class="row">
            <?php if ($porto_settings['portfolio-page-nav']) : ?>
                <div class="portfolio-nav-all col-md-1">
                    <a title="<?php _e('Back to list', 'porto') ?>" data-tooltip href="<?php echo get_post_type_archive_link( 'portfolio' ) ?>"><i class="fa fa-th"></i></a>
                </div>
            <?php endif; ?>
            <?php if ($porto_settings['portfolio-page-nav']) : ?>
            <div class="col-md-10 text-center">
            <?php else : ?>
            <div class="col-md-12 text-center">
            <?php endif; ?>
                <h2 class="entry-title shorter"><?php the_title(); ?></h2>
                <?php porto_render_rich_snippets( false ); ?>
            </div>
            <?php if ($porto_settings['portfolio-page-nav']) : ?>
                <div class="portfolio-nav col-md-1">
                    <?php
                    previous_post_link('%link', '<div data-tooltip title="'.__('Previous', 'porto').'" class="portfolio-nav-prev"><i class="fa"></i></div>');
                    next_post_link('%link', '<div data-tooltip title="'.__('Next', 'porto').'" class="portfolio-nav-next"><i class="fa"></i></div>');
                    ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <hr class="<?php echo ($porto_layout === 'widewidth' ? 'm-t-xl m-b-none solid' : 'tall') ?>">

    <div class="row<?php echo ($porto_layout === 'widewidth' ? ' m-t-lg m-r m-l' : '') ?> portfolio-container">
        <?php
        // Portfolio Full Images
        $slideshow_type = get_post_meta($post->ID, 'slideshow_type', true);
        $video_code = get_post_meta($post->ID, 'video_code', true);

        if (!$slideshow_type)
            $slideshow_type = 'images';

        $featured_images = porto_get_featured_images();
        $image_count = count($featured_images);

        $options = array();
        $options['delegate'] = 'a.lightbox-portfolio';
        $options['type'] = 'image';
        $options['gallery']['enabled'] = true;
        $options = json_encode($options);

        if (($slideshow_type == 'images' && $image_count) || ($slideshow_type == 'video' && $video_code)) : ?>
        <div class="col-md-<?php echo ($porto_layout === 'widewidth' || $porto_layout === 'fullwidth') ? '7' : '12' ?>">
            <?php if ($slideshow_type == 'images' && $image_count) : ?>
                <div<?php if ($porto_settings['portfolio-zoom']) : ?> class="lightbox" data-plugin-options="<?php echo esc_attr($options) ?>"<?php endif ?>>
                    <ul class="portfolio-list">
                        <?php
                        foreach ($featured_images as $featured_image) {
                            $attachment = porto_get_attachment($featured_image['attachment_id']);
                            if ($attachment) {
                                ?>
                                <li class="portfolio-item">
                                    <span class="thumb-info<?php echo $porto_settings['portfolio-zoom'] ? '' : ' thumb-info-lighten' ?> thumb-info-centered-icons thumb-info-no-borders">
                                        <span class="thumb-info-wrapper">
                                            <img width="<?php echo $attachment['width'] ?>" height="<?php echo $attachment['height'] ?>" src="<?php echo $attachment['src'] ?>" class="img-responsive" alt="<?php echo $attachment['alt'] ?>">
                                            <?php if ($porto_settings['portfolio-zoom']) : ?>
                                                <span class="thumb-info-action">
                                                <a href="<?php echo $attachment['src'] ?>" class="lightbox-portfolio">
                                                    <span class="thumb-info-action-icon thumb-info-action-icon-light"><i class="fa fa-search-plus"></i></span>
                                                </a>
                                            </span>
                                            <?php endif ?>
                                        </span>
                                    </span>
                                </li>
                            <?php
                            }
                        }
                        ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php
            if ($slideshow_type == 'video' && $video_code) {
                ?>
                <div class="portfolio-image single">
                    <div class="img-thumbnail fit-video<?php if ($porto_layout === 'widewidth') echo ' img-thumbnail-no-borders' ?>">
                        <?php echo do_shortcode($video_code) ?>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
        <div class="col-md-<?php echo ($porto_layout === 'widewidth' || $porto_layout === 'fullwidth') ? '5' : '12' ?>">
        <?php else : ?>
        <div class="col-md-12">
        <?php endif; ?>
            <?php if (($porto_layout === 'widewidth' || $porto_layout === 'fullwidth') && porto_meta_sticky_sidebar()) : ?>
            <div data-plugin-sticky data-plugin-options="<?php echo esc_attr('{"autoInit": true, "minWidth": 991, "containerSelector": ".portfolio-container"}') ?>">
            <?php endif; ?>
            <div class="portfolio-info m-t-none pt-none">
                <ul>
                    <?php if (in_array('like', $porto_settings['portfolio-metas'])) : ?>
                        <li>
                            <?php echo porto_portfolio_like() ?>
                        </li>
                    <?php endif;
                    if (in_array('date', $porto_settings['portfolio-metas'])) : ?>
                        <li>
                            <i class="fa fa-calendar"></i> <?php echo get_the_date() ?>
                        </li>
                    <?php endif;
                    $cat_list = get_the_term_list($post->ID, 'portfolio_cat', '', ', ', '');
                    if (in_array('cats', $porto_settings['portfolio-metas']) && $cat_list) : ?>
                        <li>
                            <i class="fa fa-tags"></i> <?php echo $cat_list ?>
                        </li>
                    <?php endif; ?>
                    <?php
                    if (function_exists('Post_Views_Counter') && Post_Views_Counter()->options['display']['position'] == 'manual') {
                        $post_count = do_shortcode('[post-views]');
                        if ($post_count) : ?>
                            <li>
                                <?php echo $post_count ?>
                            </li>
                        <?php endif;
                    }
                    ?>
                </ul>
            </div>

            <h5 class="m-t-md portfolio-desc"><?php _e('Project Description', 'porto') ?></h5>

            <div class="post-content m-t-sm">

                <?php
                the_content();
                wp_link_pages( array(
                    'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'porto' ) . '</span>',
                    'after'       => '</div>',
                    'link_before' => '<span>',
                    'link_after'  => '</span>',
                    'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'porto' ) . ' </span>%',
                    'separator'   => '<span class="screen-reader-text">, </span>',
                ) );
                ?>

                <div class="post-gap-small"></div>

            </div>

            <?php if ($portfolio_info) : ?>
                <h5 class="m-t-sm"><?php _e('More Information', 'porto') ?></h5>
                <div class="m-b-lg">
                    <?php echo do_shortcode(wpautop($portfolio_info)) ?>
                </div>
            <?php endif; ?>

            <?php
            if ($portfolio_link) :
                ?>
                <div class="post-gap-small"></div>

                <a target="_blank" class="btn btn-primary btn-icon" href="<?php echo esc_url($portfolio_link) ?>">
                    <i class="fa fa-external-link"></i><?php _e('Live Preview', 'porto') ?>
                </a>

                <span data-appear-animation-delay="800" data-appear-animation="rotateInUpLeft" class="dir-arrow <?php echo 'hlb' ?>"></span>

                <div class="post-gap"></div>
            <?php endif; ?>

            <ul class="portfolio-details">
                <?php
                if (in_array('skills', $porto_settings['portfolio-metas']) && $skill_list) : ?>
                    <li>
                        <h5><?php _e('Skills', 'porto') ?></h5>
                        <ul class="list list-skills icons list-unstyled list-inline">
                            <li><i class="fa fa-check-circle"></i>
                                <?php echo $skill_list ?>
                            </li>
                        </ul>
                    </li>
                <?php
                endif;
                if (in_array('client', $porto_settings['portfolio-metas']) && $portfolio_client) : ?>
                    <li>
                        <h5><?php _e('Client', 'porto') ?></h5>
                        <p><?php echo esc_html($portfolio_client); if ($portfolio_client_link): ?> - <a href="<?php echo $portfolio_client_link ?>" target="_blank"><i class="fa fa-external-link"></i> <?php echo $portfolio_client_link ?></a><?php endif; ?></p>
                    </li>
                <?php
                endif;
                if (in_array('quote', $porto_settings['portfolio-metas']) && $portfolio_author_quote) :
                    ?>
                    <li>
                        <div class="testimonial testimonial-style-4">
                            <blockquote>
                                <p><?php echo $portfolio_author_quote ?></p>
                            </blockquote>
                            <div class="testimonial-arrow-down"></div>
                            <div class="testimonial-author">
                                <?php if ($portfolio_author_image) : ?>
                                    <div class="testimonial-author-thumbnail">
                                        <img alt="" class="img-responsive img-circle" src="<?php echo esc_url($portfolio_author_image) ?>">
                                    </div>
                                <?php endif; ?>
                                <p><strong><?php echo $portfolio_author_name ?></strong><span><?php echo $portfolio_author_role ?></span></p>
                            </div>
                        </div>
                    </li>
                <?php
                endif;
                if ($porto_settings['share-enable'] && 'no' !== $share && ('yes' === $share || ('yes' !== $share && $porto_settings['portfolio-share']))) : ?>
                    <li>
                        <h5><?php _e('Share', 'porto') ?></h5>
                        <?php get_template_part('share') ?>
                    </li>
                <?php endif; ?>
            </ul>
            <?php if (($porto_layout === 'widewidth' || $porto_layout === 'fullwidth') && porto_meta_sticky_sidebar()) : ?>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="<?php echo ($porto_layout === 'widewidth' ? ' container' : '') ?>">
        <?php if ($porto_settings['portfolio-author']) : ?>
            <div class="post-gap"></div>
            <div class="post-block post-author clearfix">
                <h3><i class="fa fa-user"></i><?php _e('Author', 'porto') ?></h3>
                <div class="img-thumbnail">
                    <?php echo get_avatar(get_the_author_meta('email'), '80'); ?>
                </div>
                <p><strong class="name"><?php the_author_posts_link(); ?></strong></p>
                <p><?php the_author_meta("description"); ?></p>
            </div>
        <?php endif; ?>

        <?php if ($porto_settings['portfolio-comments']) : ?>
            <div class="post-gap"></div>
            <?php
            wp_reset_postdata();
            comments_template();
            ?>
        <?php endif; ?>
    </div>

</article>
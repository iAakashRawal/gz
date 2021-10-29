<div class="h5 mb-40 mt-0 text-center"><?php echo mysql2date(get_option('date_format') . ', ' . get_option('time_format'), $match->date); ?></div>

<div class="row pb-40">
    <div class="col-md-5">
        <div class="youplay-match-left">
            <div class="youplay-match">
                <div class="youplay-match-data text-right mr-0">
                    <h3><?php esc_html_e($match->team1_title); ?></h3>
                </div><!--
                --><div class="angled-img">
                    <div class="img">
                        <?php echo wp_get_attachment_image(
                            $team1_logo,
                            array(200, 200),
                            false,
                            array(
                                'alt'   => $match->team1_title
                            )); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2 text-center">
        <div class="youplay-match-vs">
            <div class="lh-35">
            	<?php echo sprintf(__('%d : %d', WP_CLANWARS_TEXTDOMAIN), $match->team1_tickets, $match->team2_tickets); ?>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="youplay-match-right">
            <div class="youplay-match">
                <div class="angled-img">
                    <div class="img">
                        <?php echo wp_get_attachment_image(
                            $team2_logo,
                            array(200, 200),
                            false,
                            array(
                                'alt'   => $match->team2_title
                            )); ?>
                    </div>
                </div><!--
                --><div class="youplay-match-data ml-0">
                    <h3><?php esc_html_e($match->team2_title); ?></h3>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="clearfix"></div>

<div class="text-center pb-40">

    <?php if(count($rounds) > 0): ?>
        <div style="display: none;">
        <div class="youplay-game-maps">

        <?php
        // render maps/rounds
        foreach($rounds as $map_group) :
            $first = $map_group[0];
            $image = wp_get_attachment_image_src($first->screenshot, array(100, 100));
            ?>

            <div class="youplay-single-map">
                <?php if(!empty($image)) : ?>
                    <div class="angled-img mw-100">
                        <div class="img">
                            <img src="<?php esc_attr_e($image[0]); ?>" alt="<?php esc_attr_e($first->title); ?>" style="width: <?php echo $image[1]; ?>px; height: <?php echo $image[2]; ?>px;" />
                        </div>
                    </div>
                <?php endif; ?>
                <div class="youplay-single-map-description">
                    <div class="h3 mt-10"><?php esc_html_e($first->title); ?></div>

                    <?php foreach($map_group as $round) :
                        $t1 = $round->tickets1;
                        $t2 = $round->tickets2;
                        $round_class = $t1 < $t2 ? 'label-danger' : ($t1 > $t2 ? 'label-success' : '');
                        ?>

                        <span class="label <?php esc_attr_e($round_class); ?>"><?php echo sprintf(__('%d : %d', WP_CLANWARS_TEXTDOMAIN), $t1, $t2); ?></span>

                    <?php endforeach; ?>
                </div>

            </div>

        <?php endforeach; // maps ?>

        </div> <!-- .youplay-game-maps -->
        </div>

        <script type="text/javascript">
            var mapsModalContent = jQuery('.youplay-game-maps:eq(0)').parent().html();
            var mapsModal = [
                '<div class="modal fade" id="mapsModal" tabindex="-1" role="dialog" aria-labelledby="mapsModalLabel" aria-hidden="true">',
                '    <div class="modal-dialog">',
                '        <div class="modal-content">',
                '            <div class="modal-header">',
                '                <button type="button" class="close" data-dismiss="modal" aria-label="Close">',
                '                    <span aria-hidden="true">&times;</span>',
                '                </button>',
                '                <h4 class="modal-title" id="mapsModalLabel"><?php _e("Maps", "youplay"); ?></h4>',
                '            </div>',
                '            <div class="modal-body">',
                                mapsModalContent,
                '            </div>',
                '        </div>',
                '    </div>',
                '</div>'
            ].join('\n');
            jQuery('body').append(mapsModal);
        </script>

        <!-- Button trigger modal -->
        <button type="button" class="btn" data-toggle="modal" data-target="#mapsModal">
            <?php _e('Maps', WP_CLANWARS_TEXTDOMAIN); ?>
        </button>
    <?php endif; ?>

    <?php if(!empty($match->external_url)) : ?>
        <a class="btn" href="<?php esc_attr_e($match->external_url); ?>" target="_blank"><?php _e('External URL', WP_CLANWARS_TEXTDOMAIN); ?></a>
    <?php endif; ?>

</div><!-- .text-center -->

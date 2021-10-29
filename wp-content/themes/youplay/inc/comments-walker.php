<?php

/** COMMENTS WALKER */
class youplay_walker_comment extends Walker_Comment {

    // init classwide variables
    var $tree_type = 'comment';
    var $db_fields = array( 'parent' => 'comment_parent', 'id' => 'comment_ID' );

    /** CONSTRUCTOR
     * You'll have to use this if you plan to get to the top of the comments list, as
     * start_lvl() only goes as high as 1 deep nested comments */
    function __construct() { ?>

        <ul class="comments-list">

    <?php }

    /** START_LVL
     * Starts the list before the CHILD elements are added. */
    function start_lvl( &$output, $depth = 0, $args = array() ) {
        $GLOBALS['comment_depth'] = $depth + 1; ?>

                <ul class="child-comment">
    <?php }

    /** END_LVL
     * Ends the children list of after the elements are added. */
    function end_lvl( &$output, $depth = 0, $args = array() ) {
        $GLOBALS['comment_depth'] = $depth + 1; ?>

        </ul><!-- /.children -->

    <?php }

    /** START_EL */
    function start_el(&$output, $comment, $depth = 0, $args = Array(), $id = 0) {
        $depth++;
        $GLOBALS['comment_depth'] = $depth;
        $GLOBALS['comment'] = $comment;
        $parent_class = ( empty( $args['has_children'] ) ? '' : 'parent' ); ?>

        <li <?php comment_class( $parent_class ); ?> id="comment-<?php comment_ID() ?>">
            <article id="comment-body-<?php comment_ID() ?>" class="comment-body">

                <div class="comment-avatar pull-left">
                    <?php echo ( $args['avatar_size'] != 0 ? get_avatar( $comment, $args['avatar_size'] ) :'' ); ?>
                </div><!-- /.comment-author -->


                <div class="comment-cont clearfix">

                    <div class="comment-author h4"><?php echo get_comment_author_link(); ?></div>

                    <div class="date">
                        <i class="fa fa-calendar"></i> <?php comment_date(); ?> at <?php comment_time(); ?>
                        <?php edit_comment_link( '(Edit)' ); ?>
                        <span class="pull-right">
                            <?php
                            comment_reply_link( array_merge( $args, array(
                                'add_below' => isset($args['add_below'])?$args['add_below']:'comment',
                                'depth' => $depth,
                                'max_depth' => $args['max_depth'],
                                'reply_text' => sprintf( esc_html__('%s Reply', 'youplay'), '<i class="fa fa-reply"></i>' )
                            ) ), $comment->comment_ID );
                            ?>
                        </span><!-- /.reply -->
                    </div>

                    <div id="comment-content-<?php comment_ID(); ?>" class="comment-text">
                        <?php if( !$comment->comment_approved ) : ?>
                        <em class="comment-awaiting-moderation">Your comment is awaiting moderation.</em>

                        <?php else:
                            echo yp_comment_text();
                        ?>
                        <?php endif; ?>
                    </div><!-- /.comment-content -->

                </div>
            </article><!-- /.comment-body -->

    <?php }

    function end_el(&$output, $comment, $depth = 0, $args = array() ) { ?>

        </li><!-- /#comment-' . get_comment_ID() . ' -->

    <?php }

    /** DESTRUCTOR
     * I'm just using this since we needed to use the constructor to reach the top
     * of the comments list, just seems to balance out nicely:) */
    function __destruct() { ?>

    </ul><!-- /#comment-list -->

    <?php }
}

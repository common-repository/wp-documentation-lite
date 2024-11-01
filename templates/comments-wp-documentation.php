<?php
/**
 * The template for displaying comments
 *
 * The area of the page that contains both current comments
 * and the comment form.
 *
 * @package WordPress
 * @subpackage WP_Documentation
 * @since WP_Documentation 1.0
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
    return;
}
?>

<div id="comments" class="comments-area wp-documentation-comments clearfix">

    <?php if ( have_comments() ) : ?>
        <h2 class="comments-title">
            <?php
                $comments_number = get_comments_number();
                if ( 1 === $comments_number ) {
                    /* translators: %s: post title */
                    printf( _x( 'One thought on &ldquo;%s&rdquo;', 'comments title', 'wp-documentation-lite' ), get_the_title() );
                } else {
                    printf(
                        /* translators: 1: number of comments, 2: post title */
                        _nx(
                            '%1$s thought on &ldquo;%2$s&rdquo;',
                            '%1$s thoughts on &ldquo;%2$s&rdquo;',
                            $comments_number,
                            'comments title',
                            'wp-documentation', 'wp-documentation-lite' ),
                        number_format_i18n( $comments_number ),
                        get_the_title()
                    );
                }
            ?>
        </h2>

        <?php the_comments_navigation(); ?>

        <ol class="comment-list">
            <?php
                wp_list_comments( array(
                    'style'       => 'ol',
                    'short_ping'  => true,
                    'avatar_size' => 42,
                ) );
            ?>
        </ol><!-- .comment-list -->

        <?php the_comments_navigation(); ?>

    <?php endif; // Check for have_comments(). ?>

    <?php
        // If comments are closed and there are comments, let's leave a little note, shall we?
        if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
    ?>
        <p class="no-comments"><?php _e( 'Comments are closed.', 'wp-documentation-lite' ); ?></p>
    <?php endif; ?>

    <?php
$comment_args = array( 
    'title_reply'           => __('Got Something To Say ?', 'wp-documentation-lite'),
    'fields'                =>  apply_filters('comment_form_default_fields', 
                                    array(
                                        'author' => '<p class="comment-form-author">' . '<label for="author">' . __( 'Name', 'wp-documentation-lite' ) .( $req ? '<span class="required">*</span>' : '' ) . '</label> ' . 
                                        '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" />
                                        
                                        </p>',   

                                        'email'  => '<p class="comment-form-email">' .

                                        '<label for="email">' . __( 'Email', 'wp-documentation-lite' ) . 

                                        ( $req ? '<span class="required">*</span>' : '' ) .'</label> ' .

                                        '<input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"  />'."
                                        </p>",

                                        'url'    => '<p class="comment-form-url"> <label for="url">Website</label><input id="url" name="url" type="url" value="" size="30" maxlength="200"></p>'
                                        ) 
                                ),

    'comment_field'         => '<p class="comment-form-comment">' .

                                    '<label for="comment">' . __( 'Let us know what you have to say ?', 'wp-documentation-lite' ) . '</label>' .

                                    '<textarea id="comment" name="comment" cols="45" rows="7" aria-required="true"></textarea>'                                    
                                    //.$like_dislike_btn
                                    .'</p>'
                                ,

    'comment_notes_after' => '',

);?>

<?php comment_form($comment_args);  ?>
 
</div><!-- .comments-area -->

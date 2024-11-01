<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WP_Documentation
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

get_header(); ?>
  <?php do_action( 'wp_documentation_before_main_content' ); ?>

    <?php while ( have_posts() ) : the_post(); ?>

      <?php wp_documentation_get_template_part( 'content', 'single-wp_documentation' ); ?>

    <?php endwhile; // end of the loop. ?>
            
  <?php do_action( 'wp_documentation_after_main_content' ); ?>
<?php get_footer(); ?>

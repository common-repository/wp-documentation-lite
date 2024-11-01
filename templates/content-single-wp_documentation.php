<?php 
global $post;
$document_settings  = get_post_meta( $post->ID,'wp_documentation_settings', true );

?>
<header class="doc-top-head clearfix entry-header">
    <?php do_action( 'wp_documentation_single_header', $post->ID, $document_settings ); ?>    
</header>

  <?php do_action( 'wp_documentation_before_single_main_content', $post->ID, $document_settings  ); ?>
  <section class="ws-wp-docs clearfix toc-pos-<?php echo (isset($document_settings['toc_position']))?$document_settings['toc_position']:'left' ?>">
    <?php do_action( 'wp_documentation_single_main_content', $post->ID, $document_settings ); ?>
  </section>
<?php do_action( 'wp_documentation_after_single_main_content', $post->ID, $document_settings  ); ?>

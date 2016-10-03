<?php
/**
 * Archive pages.
 *
 * @package vogue
 * @since 1.0.0
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/*$config = presscore_get_config();
$config->set( 'template', 'archive' );
$config->set( 'layout', 'masonry' );
$config->set( 'template.layout.type', 'masonry' );
*/
get_header(); ?>

			<!-- Content -->
			<div id="content" class="content" role="main">


			<?php
				 $category = get_the_category();
				 $this_category = get_category($cat); // получаем id текущей категории
				 //$terms_cat = get_the_term_list();

				$thisCat = get_query_var('cat');
				$ancestors = get_ancestors( $thisCat,'category');
				$is_child = $ancestors[ count($ancestors) - 1];

				if( is_category( '30' ) || ($is_child == 30)){
					 //echo "<h1> Мы потомки ПоверПоинт </h1>";
					 echo "<h2>" . single_cat_title() . "</h2>"; //Почему название рубрики выводится за пределами h2?
					 child_term(30, $this_category);
				}
				if( is_category( '29' ) || ($is_child == 29)){
					 echo "<h1> Мы потомки КейНоте </h1>";
					 child_term(29, $this_category);
				}


				 //printf("<pre>%s</pre>", print_r($this_category->category_parent, true));
			?>

<?php if (have_posts()) : ?>
	<div class="stm-container">
		<div class='row'>

			<?php while (have_posts()) : the_post(); ?>
				<div class='col-sm-4'>
					<figure class='stm-vitrina-el'>

							<div class="stm-vitrina-img">
							  <?php the_post_thumbnail('vitrina'); ?> 
							  <a href="<?php the_permalink(); ?>" class="stm-vitrina-link">
							  	<span class='stm-vitrina-button'><i class='stm-vitrina-button-i'></i>Download page</span>
							  </a>

							</div>
							<figcaption>
			    			<h4 class='stm-vitrina-title'><?php the_title(); ?> </h4><!-- название поста -->
			   				<p class='secondary-text'><?php echo get_post_meta($post->ID, 'meta1_field_1', true); ?> </p><!-- произвольное поле, если использовать acf -->
			   				<p></p>

							</figcaption>
			 		</figure> <!-- END Vitrina El -->
			   </div> 
			 <?php endwhile; ?>
		 </div>
	 </div>
	<?php else : ?>
<?php endif; ?>

<?php dt_paginator(); ?>
</div><!-- #content -->

<?php do_action('presscore_after_content'); ?>

<?php get_footer(); ?>


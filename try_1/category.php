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

<?php // Эту функцию лучше в function.php
function child_term($arg, $cur_cat){
	$kat = $arg;

 	//if ($cur_cat->term_id == $kat){  echo "EHF!";  };
 	//if( $cur_cat->term_id == $dochernaya_kategoria->term_taxonomy_id )


	$na_akran = '<ul>' . "\n";
	$dochernii_kategorii = get_categories('child_of=' . $kat . '&hide_empty=0');
 		foreach ($dochernii_kategorii as $dochernaya_kategoria) :
 			if ( $cur_cat->term_id ==  $dochernaya_kategoria->term_taxonomy_id){ $class = "active"; };
	    if ($kat == $dochernaya_kategoria->category_parent){
	        $na_akran .= "<li class='therme-li " . $class ."'>"; // Почему я здесь не могу использовать IF
	      	$na_akran .='<a href=" '. get_category_link($dochernaya_kategoria->cat_ID) . '" title="' .
	            $dochernaya_kategoria->category_description . '">';
	        $na_akran .= $cur_cat->term_id;
	        $na_akran .= $dochernaya_kategoria->term_taxonomy_id;
	        $na_akran .= $dochernaya_kategoria->cat_name . '</a>';
	        $na_akran .= '</li>' . "\n";
	    }
	endforeach;
	$na_akran .= '</ul>' . "\n";
	print $na_akran;
}

?>




			<?php
				 $category = get_the_category();
				 $this_category = get_category($cat); // получаем id текущей категории
				 //$terms_cat = get_the_term_list();

				$thisCat = get_query_var('cat');
				$ancestors = get_ancestors( $thisCat,'category');
				$is_child = $ancestors[ count($ancestors) - 1];

				if( is_category( '30' ) || ($is_child == 30)){
					 echo "<h1> Мы потомки ПоверПоинт </h1>";
					 child_term(30, $this_category);
				}
				if( is_category( '29' ) || ($is_child == 29)){
					 echo "<h1> Мы потомки КейНоте </h1>";
					 child_term(29, $this_category);
				}
					
					if($this_category->category_parent == "0") {  // условие если id родительской категории = 0, то выводим подкатегории текущей категории, в противном случае, то если это подкатегория, то выводим список подкатегорий ее родителя
						/*получаем id текущей категории" это не id текущей категории, в wordpress id текущей категории уже записано в переменную $cat, а $this_category это массив данных, которые нужны для дальнейшей обработки*/
						$terms = get_terms("category", "hide_empty=0&child_of=".$cat);

					} else {
						$terms = get_terms("category", "hide_empty=0&child_of=".$this_category->category_parent);
					}

				 printf("<pre>%s</pre>", print_r($this_category->category_parent, true));


				  echo "<ul>";
				  	foreach ($terms as $term) {  ?>
					  <li <?php  if($cat == $term->term_id ) { ?> class="active" <?php  } ?>><a  href="<?php    echo get_term_link( $term ); ?>">
				 		<?php    echo $term->name; ?></a></li>

				 <?php  }
				  echo "</ul>";
			?>




<?php if (have_posts()) : ?>
 
<?php while (have_posts()) : the_post(); ?>
 <a href="<?php the_permalink(); ?>"> Ссылка на пост </a> <!-- выведет ссылку на пост -->
   <!-- <?php //the_post_thumbnail(); ?>   - выведет миниатюру поста -->
    <?php the_title(); ?> <!-- название поста -->
    <!-- <?php //the_field('field_name'); ?> произвольное поле, если использовать acf -->
 <?php endwhile; ?>  
<?php else : ?>
<?php endif; ?>

<?php dt_paginator(); ?>
			</div><!-- #content -->

			<?php do_action('presscore_after_content'); ?>

<?php get_footer(); ?>
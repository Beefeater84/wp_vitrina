<?php


function child_term($arg, $cur_cat){
	$kat = $arg;

	/*
		Выводит список дочерних подкатегорий с глубиной 1
		Аргументы: $arg - подкатегории кого выводить
		$cur_cat - ID текущей категории, чтобы придать ей активный класс
	*/

 	//if ($cur_cat->term_id == $kat){  echo "EHF!";  };
 	//if( $cur_cat->term_id == $dochernaya_kategoria->term_taxonomy_id )

 	//printf("<pre>%s</pre>", print_r(get_term($kat), true));


	$na_akran = '<ul class="stm-vitrina-therms-ul">' . "\n";
	$dochernii_kategorii = get_categories('child_of=' . $kat . '&hide_empty=0');

	$all_term = get_term($kat); // сохраняем и кешируем данные о родителе категорий для пункта All
	$na_akran .= '<li class="stm-vitrina-therms-li"><a href="'. get_category_link($all_term->term_id) .'"class="stm-vitrina-therms-a"><h4>'. $all_term->name .'</h4></a></li>';

 		foreach ($dochernii_kategorii as $dochernaya_kategoria) :
 			//if ( $cur_cat->term_id == $dochernaya_kategoria->term_taxonomy_id){ $class = "active"; };

	    if ($kat == $dochernaya_kategoria->category_parent){
	        $na_akran .= "<li class='stm-vitrina-therms-li " . $class ."'>"; // ВОПРОС Почему я здесь не могу использовать IF.
	      	$na_akran .='<a href=" '. get_category_link($dochernaya_kategoria->cat_ID) . '" class="stm-vitrina-therms-a" title="' .
	            $dochernaya_kategoria->category_description . '">';
	        $na_akran .= '<h4>'. $dochernaya_kategoria->cat_name . '</h4></a>';
	        $na_akran .= '</li>' . "\n";
	    }
	endforeach;
	$na_akran .= '</ul>' . "\n";
	print $na_akran;

	// ВОПРОС Проблема этого кода в том, что он выводит все категории одну за другой. Как мне изменить порядок их вывода? У нас по дизайну категория Premium должна идти последней. Как это сделать?
}


// Меняем количество постов на странице архива лендинга 
function post_count($query){
	$query->set('showposts','12');
	}
add_action('pre_get_posts','post_count');

// ВОПРОС Что за переменная $query и почему она не передается в add_action
// ВОПРОС Есть ли более простой способ заменить количество постов на странице?
// ВОПРОС Фактически вопрос какие вообще есть способы изменять количество постов. Как мне для разных страниц сделать разное количество постов.







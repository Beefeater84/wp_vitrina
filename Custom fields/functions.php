<?php
/*
		Взято отсюда: https://truemisha.ru/blog/wordpress/meta-boxes.html
		Добавляем в function.php создаются только простые поля 

*/
/****** Добавление своего поля ************/

class trueMetaBox {
	function __construct($options) {
		$this->options = $options;
		$this->prefix = $this->options['id'] .'_';
		add_action( 'add_meta_boxes', array( &$this, 'create' ) );
		add_action( 'save_post', array( &$this, 'save' ), 1, 2 );
	}
	function create() {
		foreach ($this->options['post'] as $post_type) {
			if (current_user_can( $this->options['cap'])) {
				add_meta_box($this->options['id'], $this->options['name'], array(&$this, 'fill'), $post_type, $this->options['pos'], $this->options['pri']);
			}
		}
	}
	function fill(){
		global $post; $p_i_d = $post->ID;
		wp_nonce_field( $this->options['id'], $this->options['id'].'_wpnonce', false, true );
		?>
		<table class="form-table"><tbody><?php
		foreach ( $this->options['args'] as $param ) {
			if (current_user_can( $param['cap'])) {
			?><tr><?php
				if(!$value = get_post_meta($post->ID, $this->prefix .$param['id'] , true)) $value = $param['std'];
				switch ( $param['type'] ) {
					case 'text':{ ?>
						<th scope="row"><label for="<?php echo $this->prefix .$param['id'] ?>"><?php echo $param['title'] ?></label></th>
						<td>
							<input name="<?php echo $this->prefix .$param['id'] ?>" type="<?php echo $param['type'] ?>" id="<?php echo $this->prefix .$param['id'] ?>" value="<?php echo $value ?>" placeholder="<?php echo $param['placeholder'] ?>" class="regular-text" /><br />
							<span class="description"><?php echo $param['desc'] ?></span>
						</td>
						<?php
						break;							
					}
					case 'textarea':{ ?>
						<th scope="row"><label for="<?php echo $this->prefix .$param['id'] ?>"><?php echo $param['title'] ?></label></th>
						<td>
							<textarea name="<?php echo $this->prefix .$param['id'] ?>" type="<?php echo $param['type'] ?>" id="<?php echo $this->prefix .$param['id'] ?>" value="<?php echo $value ?>" placeholder="<?php echo $param['placeholder'] ?>" class="large-text" /><?php echo $value ?></textarea><br />
							<span class="description"><?php echo $param['desc'] ?></span>
						</td>
						<?php
						break;							
					}
					case 'checkbox':{ ?>
						<th scope="row"><label for="<?php echo $this->prefix .$param['id'] ?>"><?php echo $param['title'] ?></label></th>
						<td>
							<label for="<?php echo $this->prefix .$param['id'] ?>"><input name="<?php echo $this->prefix .$param['id'] ?>" type="<?php echo $param['type'] ?>" id="<?php echo $this->prefix .$param['id'] ?>"<?php echo ($value=='on') ? ' checked="checked"' : '' ?> />
							<?php echo $param['desc'] ?></label>
						</td>
						<?php
						break;							
					}
					case 'select':{ ?>
						<th scope="row"><label for="<?php echo $this->prefix .$param['id'] ?>"><?php echo $param['title'] ?></label></th>
						<td>
							<label for="<?php echo $this->prefix .$param['id'] ?>">
							<select name="<?php echo $this->prefix .$param['id'] ?>" id="<?php echo $this->prefix .$param['id'] ?>"><option>...</option><?php
								foreach($param['args'] as $val=>$name){
									?><option value="<?php echo $val ?>"<?php echo ( $value == $val ) ? ' selected="selected"' : '' ?>><?php echo $name ?></option><?php
								}
							?></select></label><br />
							<span class="description"><?php echo $param['desc'] ?></span>
						</td>
						<?php
						break;							
					}
				} 
			?></tr><?php
			}
		}
		?></tbody></table><?php
	}
	function save($post_id, $post){
		if ( !wp_verify_nonce( $_POST[ $this->options['id'].'_wpnonce' ], $this->options['id'] ) ) return;
		if ( !current_user_can( 'edit_post', $post_id ) ) return;
		if ( !in_array($post->post_type, $this->options['post'])) return;
		foreach ( $this->options['args'] as $param ) {
			if ( current_user_can( $param['cap'] ) ) {
				if ( isset( $_POST[ $this->prefix . $param['id'] ] ) && trim( $_POST[ $this->prefix . $param['id'] ] ) ) {
					update_post_meta( $post_id, $this->prefix . $param['id'], trim($_POST[ $this->prefix . $param['id'] ]) );
				} else {
					delete_post_meta( $post_id, $this->prefix . $param['id'] );
				}
			}
		}
	}
}



$options = array(
	array( // первый метабокс
		'id'	=>	'meta1', // ID метабокса, а также префикс названия произвольного поля
		'name'	=>	'Доп. настройки 1', // заголовок метабокса
		'post'	=>	array('post'), // типы постов для которых нужно отобразить метабокс
		'pos'	=>	'normal', // расположение, параметр $context функции add_meta_box()
		'pri'	=>	'high', // приоритет, параметр $priority функции add_meta_box()
		'cap'	=>	'edit_posts', // какие права должны быть у пользователя
		'args'	=>	array(
			array(
				'id'			=>	'field_1', // атрибуты name и id без префикса, например с префиксом будет meta1_field_1
				'title'			=>	'Текст', // лейбл поля
				'type'			=>	'text', // тип, в данном случае обычное текстовое поле
				'placeholder'		=>	'плейсхолдер, например введите email', // атрибут placeholder
				'desc'			=>	'пример использования текстового поля ввода в метабоксе', // что-то типа пояснения, подписи к полю
				'cap'			=>	'edit_posts'
			),
			array(
				'id'			=>	'terms',
				'title'			=>	'Чекбокс',
				'type'			=>	'checkbox', // чекбокс
				'desc'			=>	'пример чекбокса',
				'cap'			=>	'edit_posts'
			),
			array(
				'id'			=>	'textfield',
				'title'			=>	'Текстовое поле',
				'type'			=>	'textarea', // большое текстовое поле
				'placeholder'		=>	'сюда тоже можно забацать плейсхолдер',
				'desc'			=>	'пример использования большого текстового поля ввода в метабоксе',
				'cap'			=>	'edit_posts'
			),
			array(
				'id'			=>	'select1',
				'title'			=>	'Выпадающий список',
				'type'			=>	'select', // выпадающий список
				'desc'			=>	'тут тоже можно написать пояснение к полю, значения же задаются через ассоциативный массив',
				'cap'			=>	'edit_posts',
				'args'			=>	array('value_1' => 'Значение 1', '2' => 'Значение 2', 'Значение_3' => 'Значение 3' ) // элементы списка задаются через массив args, по типу value=>лейбл
			)
		)
	),
	array( // второй метабокс
		'id'	=>	'meta2',
		'name'	=>	'Доп. настройки 2',
		'post'	=>	array('post', 'page'), // не только для постов, но и для страниц
		'pos'	=>	'normal',
		'pri'	=>	'high',
		'cap'	=>	'edit_posts',
		'args'	=>	array(
			array(
				'id'			=>	'featured',
				'title'			=>	'На главную',
				'desc'			=>	'Отображать пост на главной странице',
				'type'			=>	'checkbox',
				'cap'			=>	'edit_posts'
			)
		)
	)
);
 
foreach ($options as $option) {
	$truemetabox = new trueMetaBox($option);
}


/*
	Обращение к метаданным поста/страницы
	Понятное дело, что потом все эти настройки нужно как-то задействовать на сайте. Для этого отлично подойдет функция:

	get_post_meta($post_id, $key, $single);
	$post_id (целое) (обязательное)
	ID поста или страницы,
	$key (строка) (обязательное)
	значение произвольного поля,
	$single (логическое)
	если true — возвращает строку, false — массив, по умолчанию — false;
	Пример использования — выведем значение произвольного поля meta1_field_1, то есть текстового поля из предыдущего примера:

	echo get_post_meta($post->ID, 'meta1_field_1', true); - вот это важно. Состоит из 2-х заголовков
	По мере совершенствования класса содержимое этого поста будет обновляться.
*/


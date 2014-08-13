<?php
/*----------class-----------*/

class Conf_paginator {

	function __construct()
	{

	}
	
	function get_conf_backend($base_url, $uri_segment, $kol, $per_page,$main_div='paginator-body'){
	
      $oCI = & get_instance();

      $config['base_url'] = $base_url; //базовый урл, которому будет добавлена $page
	  $config['uri_segment']=$uri_segment;
	  $config['total_rows'] = $kol; //общее число записей
	  $config['per_page'] = $per_page; //записей на страницу

      $config['num_links'] = 311; // количество ссылок по бокам

      $config['first_tag_open']  = '<li class="prev">';
	  $config['first_link'] = '&lt;';  // &lt;
	  $config['first_tag_close'] = '</li>';

      $config['last_tag_open'] = '<li>';
	  $config['last_link'] ='>>';
	  $config['last_tag_close'] = '</li>';

      $config['next_tag_open'] = '<li class="next">';//<span class="paginator-text">
      $config['next_link'] = '&gt;';//след.&gt;
	  $config['next_tag_close'] = '</li>'; //</span>

	  $config['prev_tag_open'] = '<li class="prev">';//<span class="paginator-text">
	  $config['prev_link'] = '&lt;'; //пред. &lt;
	  $config['prev_tag_close'] = '</li>'; //</span>

	  $config['full_tag_open'] = '<div class="pagination"><ul class="pages">';
	  $config['full_tag_close'] = '</ul></div>';

	  $config['cur_tag_open'] = '<li><a href="#" class="active collapse-close">'; // выбранный пункт
	  $config['cur_tag_close'] = '</a></li>';

	  $config['num_tag_open']= '<li>';
	  $config['num_tag_close']='</li>';

	  return $config;
	}


	function get_conf($base_url, $uri_segment, $kol, $per_page,$main_div='paginator-body'){

      $oCI = & get_instance();

      $config['base_url'] = $base_url; //базовый урл, которому будет добавлена $page
	  $config['uri_segment']=$uri_segment;
	  $config['total_rows'] = $kol; //общее число записей
	  $config['per_page'] = $per_page; //записей на страницу

      $config['num_links'] = 1000; // количество ссылок по бокам

      $config['full_tag_open'] = '<div class="pagin_block" id="pagination" ><ul class="pagination">';
      $config['full_tag_close'] = '</ul></div>';

      $config['first_tag_open']  = '<li class="left" ><span class="blue_color_a">';
	  $config['first_link'] = '«';
	  $config['first_tag_close'] = '</span></li>';
      $config['last_tag_open'] = '<li class="right" ><span class="blue_color_a">';
	  $config['last_link'] = '»';
	  $config['last_tag_close'] = '</span></li>';

      $config['next_tag_open'] = '<li class="right" ><span class="blue_color_a">';//<span class="paginator-text">
      $config['next_link'] = 'Вперед';//след.
	  $config['next_tag_close'] = '</span></li>'; //</span>

	  $config['prev_tag_open'] = '<li class="left" ><span class="blue_color_a" >';//<span class="paginator-text">
	  $config['prev_link'] = 'Назад'; //пред.
	  $config['prev_tag_close'] = '</span></li>'; //</span>

	  $config['cur_tag_open'] = '<li><span class="pag_action">'; // выбранный пункт
	  $config['cur_tag_close'] = '</span></li>';

	  $config['num_tag_open']= '<li><span>';
	  $config['num_tag_close']='</span></li>';

	  return $config;
	}

}

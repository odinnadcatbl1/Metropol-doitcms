<?php
/*
	Модуль для работы с текстовыми страницами, для вывода меню, выода подстраниц
*/
class ProductsController
{
	function show($url)
	{
		// d()->this = d()->Catalog->find_by_url($url);
		d()->this = d()->Product->where('url = ?', $url);
		if (d()->this->is_empty) {
			d()->message="Страница не существует".d()->add(array('products','url'=>$url));
			return d()->error('404');
		} 

		d()->parent_catalog = d()->Catalog->where('id = ?', d()->this->catalog_id);

		$mas = array ();
		$mas[] = array ('title' => 'Главная', 'link' => '/');
		$mas[] = array ('title' => d()->parent_catalog->title, 'link' => '/catalogs/' . d()->parent_catalog->url);
		$mas[] = array ('title' => d()->this->title, 'link' => "/catalogs/" . d()->this->url);
		d()->breads = $mas;
		
		// print '<pre style="margin-top: 120px">';
		// var_dump(d()->breads);
		// print '</pre>';
	}
}


<?php
/*
	Модуль для работы с текстовыми страницами, для вывода меню, выода подстраниц
*/
class CatalogsController
{
	function index(){
		$url = url();

		//d()->this = d()->Page->find_by_url($url);
		d()->this = d()->Page->where('url = ?', $url);
		if (d()->this->is_empty) {
			d()->message="Страница не существует".d()->add(array('pages','url'=>$url));
			return d()->error('404');
		}

		$mas = array ();
		$mas[] = array ('title' => 'Главная', 'link' => '/');
		$mas[] = array ('title' => d()->this->title, 'link' => "/catalogs/" . d()->this->url);
		d()->breads = $mas;
	}
	
	
	function show($url)
	{
		// d()->this = d()->Catalog->find_by_url($url);
		d()->this = d()->Catalog->where('url = ?', $url);
		if (d()->this->is_empty) {
			d()->message="Страница не существует".d()->add(array('catalogs','url'=>$url));
			return d()->error('404');
		} 
		

		d()->parent_catalog = d()->Catalog->where('id = ?', d()->this->catalog_id);

		$mas = array ();
		$mas[] = array ('title' => 'Главная', 'link' => '/');
		$mas[] = array('title' => 'Каталог', 'link' => '/catalogs/');

		if (d()->parent_catalog->ne) {
			$mas[] = array ('title' => d()->parent_catalog->title, 'link' => '/catalogs/' . d()->parent_catalog->url);
			$mas[] = array ('title' => d()->this->title, 'link' => "/catalogs/" . d()->this->url);
		} else {
			$mas[] = array ('title' => d()->this->title, 'link' => "/catalogs/" . d()->this->url);
		}
		d()->breads = $mas;

		if (d()->Product->where('catalog_id = ?', d()->this->id)->ne) {
			return d()->call('catalogs#products_show', array($url));
		}

	}

	function products_show($url) {
		d()->this = d()->Catalog->where('url = ?', $url);
		if (d()->this->is_empty) {
			d()->message="Страница не существует".d()->add(array('catalogs','url'=>$url));
			return d()->error('404');
		} 

		d()->products = d()->Product->where('catalog_id = ?', d()->this->id);		
		print d()->catalogs_products_show_tpl();

		d()->products->paginate(1);
		d()->paginator = d()->Paginator->generate(d()->products);
	}

}


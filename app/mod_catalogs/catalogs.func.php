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
	}
	
	
	function show($url)
	{
		// d()->this = d()->Catalog->find_by_url($url);
		d()->this = d()->Catalog->where('url = ?', $url);
		if (d()->this->is_empty) {
			d()->message="Страница не существует".d()->add(array('catalogs','url'=>$url));
			return d()->error('404');
		}
		
	}
}


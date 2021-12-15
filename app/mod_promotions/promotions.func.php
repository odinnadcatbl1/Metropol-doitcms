<?php
/*
	Модуль для работы с текстовыми страницами, для вывода меню, выода подстраниц
*/
class PromotionsController
{
	function index()
	{
        $url = url();
        d()->this = d()->Page->where('url = ?', $url);
		if (d()->this->is_empty) {
			d()->message="Страница не существует".d()->add(array('pages','url'=>$url));
			return d()->error('404');
		} 

		$mas = array ();
		$mas[] = array ('title' => 'Главная', 'link' => '/');
		$mas[] = array('title' => 'Акции', 'link' => '/promotions/');
		d()->breads = $mas;
		
		d()->promotions = d()->Promotion->where('promotion_id = ?', 0);
		d()->promotions->paginate(4);
		d()->paginator = d()->Paginator->custom_template('/app/custom_pagination.html')->generate(d()->promotions);
		// print '<pre style="margin-top: 120px">';
		// var_dump(d()->breads);
		// print '</pre>';
	}

    function show($url)
	{
        d()->this = d()->Promotion->where('url = ?', $url);
		if (d()->this->is_empty) {
			d()->message="Страница не существует".d()->add(array('promotions','url'=>$url));
			return d()->error('404');
		} 

		$mas = array ();
		$mas[] = array ('title' => 'Главная', 'link' => '/');
		$mas[] = array('title' => 'Акции', 'link' => '/promotions/');
        $mas[] = array ('title' => d()->this->title, 'link' => "/catalogs/" . d()->this->url);
		d()->breads = $mas;
		
		// print '<pre style="margin-top: 120px">';
		// var_dump(d()->breads);
		// print '</pre>';
	}
}
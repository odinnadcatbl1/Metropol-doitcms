<?php
/*
	Модуль для работы с текстовыми страницами, для вывода меню, выода подстраниц
*/
class PagesController
{
	function show()
	{
		$url = url();
		d()->this = d()->Page->find_by_url($url);
		if (d()->this->is_empty) {
			d()->message="Страница не существует".d()->add(array('pages','url'=>$url));
			return d()->error('404');
		}

		$mas = array ();
		$mas[] = array ('title' => 'Главная', 'link' => '/');
		$mas[] = array('title' => 'Контакты', 'link' => '/contacts/');
		d()->breads = $mas;

	}

	function search()
    {
        $url = url();
        d()->this = d()->Page->find_by_url($url);
        if (d()->this->is_empty) {
            d()->message = "Страница не существует" . d()->add(array('pages', 'url' => $url));
            return d()->error('404');
        }

        $mas[] = array('title' => 'Главная', 'link' => '/');
        $mas[] = array('title' => d()->this->title, 'link' => d()->this->url);
        d()->breads = $mas;

        if (isset($_GET['search']) && $_GET['search'] != '') {
            d()->products = d()->Product;
            d()->products->search('title', 'text', 'describe', $_GET['search']);
			
			d()->products->paginate(4);
		d()->paginator = d()->Paginator->custom_template('/app/custom_pagination.html')->generate(d()->products);

            if (d()->products->is_empty) {
                return d()->pages_search_empty_tpl();
            }
		} else {
            header('Location: /');
            exit;
        }
	}

	function get_shops_json(){				
		$arr = array();				
		foreach(d()->Shop as $v){
			$coordinates = array();
			$coordinates[] = floatval($v->lat);
			$coordinates[] = floatval($v->lng);
			
			$properties = array();
			$properties['balloonContentHeader'] = $v->title_address;
			$properties['balloonContentBody'] = '<div><b>Режим работы:</b> ' . $v->work_time . '</div><div><b>Телефон:</b> ' . $v->phone . '</div><div><b>Почта:</b> ' . $v->email . '</div>';
			//$properties['balloonContentBody'] = '22';
			//$properties['balloonContentFooter'] = '333';
			//$properties['clusterCaptions'] = '444';
			//$properties['hintContent'] = '555';
			
			$arr[] = array('type' => 'Feature', 'id' => $v->id, 'geometry' => array('type' => 'Point', 'coordinates' => $coordinates), 'properties' => $properties);
			}
			
			//$arr[] = array('type' => 'Feature', 'id' => 0, 'geometry' => array('type' => 'Point', 'coordinates' => '[55.796289, 49.108795]'));		
	
			$json = array();
		$json['type'] = 'FeatureCollection';
		$json['features'] = $arr;
		
		header('Content-Type: application/json');
		
		print json_encode($json);
		exit;
	
	}
}


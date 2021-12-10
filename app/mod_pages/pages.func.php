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


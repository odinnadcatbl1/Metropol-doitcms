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


		if(d()->parent_catalog->catalog_id != 0){
			
			$par = d()->Catalog->where('id = ?', d()->parent_catalog->catalog_id);
			
			$extra_mas = array();
			
			while($par->ne){
				$cat_id = $par->catalog_id;
				$extra_mas[] = array('title' => $par->title, 'link' => '/catalogs/' . $par->url);
				$par = d()->Catalog->where('id = ?', $cat_id);
			}
			$narr = array_merge($mas, array_reverse($extra_mas));
			$mas = $narr;
		}

	

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

		d()->products->paginate(9);
		d()->paginator = d()->Paginator->custom_template('/app/custom_pagination.html')->generate(d()->products);


		//не понял
		///////////////////////////////////////
		///////////////////////////////////////
		d()->products = d()->Product->where('catalog_id = ?', d()->this->id);
		d()->products_ids = d()->products->fast_all_of('id');
		d()->prod_fields = d()->Product_field->where('product_id IN (?)', d()->products_ids);
        d()->field_id = d()->Field->where('id IN (?)', d()->prod_fields->fast_all_of('field_id'));
		
		foreach ($_GET['fields'] as $v) {
            $field = d()->Field->where('id = ?', $v);
            if ($field->razdel == 1 || $field->razdel == 2) {
                if (isset($_GET['field'][$field->id]) && $_GET['field'][$field->id] != '') {
                    d()->products->where('id  IN (?)', d()->Product_field->where('field_id = ? AND field_value_id IN (?)', $field->id, $_GET['field'][$field->id])->fast_all_of('product_id'));
                }
            } elseif ($field->razdel == 3) {
                d()->product_field = d()->Product_field->where('field_id = ? AND product_id IN (?)', $field->id, d()->products_ids);
                d()->min_num = floatval(d()->product_field->order_by('value*1 asc')->limit(1)->value);
                d()->max_num = floatval(d()->product_field->order_by('value*1 desc')->limit(1)->value);
                $get_min = floatval($_GET['field'][$field->id]['min']);
                $get_max = floatval($_GET['field'][$field->id]['max']);

                if ($get_min != d()->min_num || $get_max != d()->max_num) {
                    d()->products->where('id IN (?)', d()->Product_field->where('field_id = ? AND value*1 >= ? AND value*1 <= ?', $field->id, $get_min, $get_max)->fast_all_of('product_id'));
                }
            }
        }
		/////////////////////////////////////////
		/////////////////////////////////////////



		print d()->catalogs_products_show_tpl();
	}

}


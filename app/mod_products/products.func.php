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


		$arr = array();
        foreach (d()->Product_field->where('product_id = ?', d()->this->id) as $v) {
            $arr[$v->field_id][] = $v['field_value_id'];
        }
		
		d()->field_arr = $arr;

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

		$mas[] = array ('title' => d()->parent_catalog->title, 'link' => '/catalogs/' . d()->parent_catalog->url);
		$mas[] = array ('title' => d()->this->title, 'link' => "/catalogs/" . d()->this->url);
		d()->breads = $mas;
		
	}

	function field_values($id)
	{
		d()->field_id = $id;
		d()->this = d()->Field->where('id = ?', $id);
	}

	function product_fields($id)
    {
        d()->this = d()->Product($id);
        d()->fields = d()->Field;
        $field = array();
        foreach (d()->fields as $v) {
            $field[$v->id] = $v->razdel;
        }

        $mas = array();
        foreach (d()->Product_field->where('product_id = ? AND field_id IN (?)', d()->this->id, d()->fields->fast_all_of('id')) as $v) {
            if ($field[$v->field_id] == 1) {
                $mas[$v->field_id] = $v->field_value_id;
            } elseif ($field[$v->field_id] == 2) {
                $mas[$v->field_id][] = $v->field_value_id;
            } elseif ($field[$v->field_id] == 3) {
                $mas[$v->field_id] = $v->value;
            }
        }
        d()->isset_value = $mas;

    }


	function save_fields()
    {
        if (d()->validate('save_fields')) {
            d()->product_id = d()->params['product_id'];
            foreach (d()->params['fields'] as $v) {
                $field = d()->Field->where('id = ?', $v);

                if ($field->razdel == 1) {
                    $field_product = d()->Product_field->where('product_id = ? AND field_id = ?', d()->product_id, $field->id);
                    if ($field_product->is_empty) {
                        $field_pr = d()->Product_field->new();
                        $field_pr->product_id = d()->product_id;
                        $field_pr->field_id = $field->id;
                        $field_pr->save();
                        $field_product = d()->Product_field->where('product_id = ? AND field_id = ?', d()->product_id, $field->id);
                    }
                    if (isset(d()->params['field'][$field->id]) && d()->params['field'][$field->id] != '') {
                        $field_product->field_value_id = d()->params['field'][$field->id];
                        $field_product->save();
                    } else {
                        $field_product->delete();
                    }
                } elseif ($field->razdel == 2) {

                    $add = array();
                    $del = array();
                    $pr_fields = d()->Product_field->where('product_id = ? AND field_id = ?', d()->product_id, $field->id)->fast_all_of('field_value_id');
                    foreach ($pr_fields as $k) {
                        if (!in_array($k, d()->params['field'][$field->id])) {
                            $del[] = $k;
                        }
                    }
                    foreach (d()->params['field'][$field->id] as $k) {
                        if (!in_array($k, $pr_fields)) {
                            $add[] = $k;
                        }
                    }

                    foreach ($del as $key => $value) {
                        d()->Product_field->where('product_id = ? AND field_id = ? AND field_value_id = ?', d()->product_id, $field->id, $value)->delete;
                    }
                    foreach ($add as $key => $value) {
                        $row = d()->Product_field->where('product_id = ? AND field_id = ? AND field_value_id = ?', d()->product_id, $field->id, $value);
                        if ($row->is_empty) {
                            $new_row = d()->Product_field->new();
                            $new_row->product_id = d()->product_id;
                            $new_row->field_id = $field->id;
                            $new_row->field_value_id = $value;
                            $new_row->save();
                        }
                    }
                } elseif ($field->razdel == 3) {
                    $field_product = d()->Product_field->where('product_id = ? AND field_id = ?', d()->product_id, $field->id);
                    if ($field_product->is_empty) {
                        $field_pr = d()->Product_field->new();
                        $field_pr->product_id = d()->product_id;
                        $field_pr->field_id = $field->id;
                        $field_pr->save();
                        $field_product = d()->Product_field->where('product_id = ? AND field_id = ?', d()->product_id, $field->id);
                    }
                    if (isset(d()->params['field'][$field->id]) && d()->params['field'][$field->id] != '') {
                        $field_product->value = d()->params['field'][$field->id];
                        $field_product->save();
                    } else {
                        $field_product->delete();
                    }
                }
            }
            print "alert('Данные сохранены');";
            exit;
        }
    }

}


<?php
//Автоматически регистрировать все контроллеры
route_all();

route('/error_404', 'error_404');
route('/', 'pages#');

route('/catalogs/index', 'catalogs#index');
route('/catalogs/', 'catalogs#show');

route('/products/', 'products#show');

route('/promotions/index', 'promotions#index');
route('/promotions/', 'promotions#show');

route('/ajax/get_shops_json', 'main', 'pages#get_shops_json');

route('/get/appendizi', 'main', 'appendizi');
route('/ajax/callback', 'main', 'fosv_callback');

route('/admin/fields', 'products#all_fields');
route('/admin/field_values/field_id/', 'products#field_values');
route('/admin/product_fields/', 'products#product_fields');
route('/admin/ajax/save_fields', 'products#save_fields');


//route('/news/index', 'content', 'news#index');
//route('/news/index', 'news#index');
//зарегистрировать контроллер newscontroller по адресу /news/
//route('news');
//зарегистрировать контроллер newscontroller по адресу /press/
//route('/press/','news#');



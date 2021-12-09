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


//route('/news/index', 'content', 'news#index');
//route('/news/index', 'news#index');
//зарегистрировать контроллер newscontroller по адресу /news/
//route('news');
//зарегистрировать контроллер newscontroller по адресу /press/
//route('/press/','news#');



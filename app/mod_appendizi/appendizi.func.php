<?

function appendizi(){
	$get_params = explode('|', $_GET['params']);
	d()->get_params = array();
	foreach($get_params as $get_param){
		$par = explode('=', $get_param);
		d()->get_params[$par[0]] = $par[1];
	}
	
	$tmp = '_' . $_GET['templ'];
	print d()->view('appendizi' . $tmp . '_tpl');
}
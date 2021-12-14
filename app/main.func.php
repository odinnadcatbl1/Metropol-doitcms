<?php

function main()
{

	if(substr($_SERVER['REQUEST_URI'],-5)=='index' && !$_GET){
		header("HTTP/1.1 301 Moved Permanently");
		header('Location: '.substr($_SERVER['REQUEST_URI'],0,-5));
		exit;
	}

	d()->content = d()->content();
	print d()->render('main_tpl');
}

function valid_phone_old($value) {
	$value=strtolower($value);
	return ( 1 == preg_match(
	'/^\+7\s\(\d{3}\)\s\d{3}\-\d{4}$/' ,$value));
}


function clear_phone($phone){
	if($phone == ''){
	  return '';
	}
	$phone_arr = array();
	$nphone = preg_replace('/[^0-9]/', '', $phone);
	if(substr($nphone, 0, 1) == '7' || substr($nphone, 0, 1) == '8'){
	  $nphone = substr($nphone, 1);
	}
	$nphone = '7' . $nphone;
	$phone_arr[] = '+'.substr($nphone, 0, 1). '('.substr($nphone, 1, 3).')'.substr($nphone, 4, 3).'-'.substr($nphone, 7, 2).'-'.substr($nphone, 9, 2);//0 - +7(999)999-99-99
	$phone_arr[] = '+'.substr($nphone, 0, 1).'-'.substr($nphone, 1, 3).'-'.substr($nphone, 4, 3).'-'.substr($nphone, 7, 2).'-'.substr($nphone, 9, 2);//1 - +7-999-99-99
	$phone_arr[] = '+'.substr($nphone, 0, 1).' ('.substr($nphone, 1, 3).') '.substr($nphone, 4, 3).substr($nphone, 7, 2).substr($nphone, 9, 2); //2 - +7 (999) 999-9999 == в таком формате приходит от обратной связи(от первой отличается тем, что нет пробела) 
	$phone_arr[] = '8' . substr($nphone, 1);//3 - БД формат телефона
	$phone_arr[] = '+'.substr($nphone, 0, 1).' ('.substr($nphone, 1, 3).') '.substr($nphone, 4, 3).'-'.substr($nphone, 7, 2).'-'.substr($nphone, 9, 2);//4 - +7 (999) 999-99-99 == в таком формате в 1с
	$phone_arr[] = '+7' . substr($nphone, 1);//5 - Отправка в смс
	
	return $phone_arr;
  }
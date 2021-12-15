<?php

function fosv_callback(){
	
	
	//print '$(".wrap-outer").remove();';
	
	
	if(d()->validate('callback')){
		
		$callback = d()->Callback->new;
		$callback->name = d()->params['name'];
		$callback->phone = clear_phone(d()->params['phone'])[3];
		$callback->form_type = 1;
		$callback->save;
		
		$callback_id = $callback->insert_id;
		
		$emails = explode(',', d()->Option->email2);		
		
		foreach ($emails as $email) {
			$message = d()->fosv_callback_mail_tpl();

			if (d()->Option->is_smtp){
				d()->mail->setFrom(array(d()->Option->smtp_address => d()->Option->smtp_name));
			} else {
				d()->mail->setFrom(array($_ENV['EMAIL_FROM_ADDRESS'] => $_ENV['EMAIL_FROM_NAME']));
			}
			d()->mail->setTo(trim($email));
			d()->mail->setSubject('Обратная связь ID:' . $callback_id);
			d()->mail->setBody($message, 'text/html');
			if (d()->Option->is_smtp){
				if (d()->Option->is_smtp_ssl){
					$bb = new Swift_SmtpTransport(d()->Option->smtp_host, d()->Option->smtp_port, 'ssl');
					$transport = $bb->setUsername(d()->Option->smtp_login)->setPassword(d()->Option->smtp_password);
				} else {
					$bb = new Swift_SmtpTransport(d()->Option->smtp_host, d()->Option->smtp_port);
					$transport = $bb->setUsername(d()->Option->smtp_login)->setPassword(d()->Option->smtp_password) ;
				}
				d()->mail->setTransport($transport);
			}
			d()->mail->send();


		}
		
		print '
			$("#modal-ajax").iziModal("close");
			Swal.fire({
				allowOutsideClick: false,
				title: "Ваша заявка принята!",
				html: "<p>Скоро наш менеджер свяжется с Вами.</p>",
				type: "success",
				confirmButtonText: "Ок"
			});
		';
		
		
		
		exit;
	}
	d()->reload();
}


<?php
	if(!array_key_exists ('filename',$_REQUEST) || !array_key_exists ('auth',$_REQUEST)){
		return;
	}
    $file = $_REQUEST['filename'];
	$json=base64_decode($_REQUEST['auth']);

	$authentication = json_decode($json);

	if($authentication->username!='YOUR_USER_NAME' || $authentication->password!='YOUR_PASSWORD'){
		return;
	}
	if(substr($file, -4) === '.mp3' || substr($file, -4) === '.jpg'){
		$entityBody = file_get_contents('php://input');
		file_put_contents('upload/'.$file,$entityBody );
	}
?>
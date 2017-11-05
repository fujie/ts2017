<?php

// �p�����[�^��
$client_id = '{your_appId}';
$client_secret = '{your_appSecret}';
$redirect_uri = 'https://{your_website}/test.php';
$authorization_endpoint = 'https://login.microsoftonline.com/{your_domain}.onmicrosoft.com/oauth2/v2.0/authorize?p=b2c_1a_signup_signin';
$token_endpoint = 'https://login.microsoftonline.com/{your_domain}.onmicrosoft.com/oauth2/v2.0/token?p=b2c_1a_signup_signin';
$response_type = 'code';
$state =  'state_hoge';

// code�̎擾(code���p�����[�^�ɂ��ĂȂ���Ώ���A�N�Z�X�Ƃ��Ă݂Ȃ��Ă��܂��B�蔲���ł�)
$req_code = $_GET['code'];
if(!$req_code){
	// ����A�N�Z�X�Ȃ̂Ń��O�C���v���Z�X�J�n
	// session����
	session_start();
	$_SESSION['nonce'] = md5(microtime() . mt_rand());
	// GET�p�����[�^�֌W
	$query = http_build_query(array(
		'client_id'=>$client_id,
		'response_type'=>$response_type,
		'redirect_uri'=> $redirect_uri,
		'scope'=>'openid email',
		'state'=>$state,
		'nonce'=>$_SESSION['nonce']
	));
	// ���N�G�X�g
	header('Location: ' . $authorization_endpoint . '&' . $query );
	exit();
}

// session���nonce�̎擾
session_start();
$nonce = $_SESSION['nonce'];

// POST�f�[�^�̍쐬
$postdata = array(
	'grant_type'=>'authorization_code',
	'client_id'=>$client_id,
	'code'=>$req_code,
	'client_secret'=>$client_secret,
	'redirect_uri'=>$redirect_uri
);

// Token�G���h�|�C���g��POST
$ch = curl_init($token_endpoint);
curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query($postdata));
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
$response = json_decode(curl_exec($ch));
curl_close($ch);

// id_token�̎��o����decode
$id_token = explode('.', $response->id_token);
$payload = base64_decode(str_pad(strtr($id_token[1], '-_', '+/'), strlen($id_token[1]) % 4, '=', STR_PAD_RIGHT));
$payload_json = json_decode($payload, true);

// ���`�ƕ\��
print<<<EOF
	<html>
	<head>
	<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
	<title>Obtained claims</title>
	</head>
	<body>
	<table border=1>
	<tr><th>Claim</th><th>Value</th></tr>
EOF;
	// id_token�̒��g�̕\��
	foreach($payload_json as $key => $value){
		print('<tr><td>'.$key.'</td><td>'.$value.'</td></tr>');
	}
print<<<EOF
	</table>
	</body>
	</html>
EOF;

?>
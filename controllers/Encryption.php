<?php
define('MY_KEY','Ralf_S_Engelschall__trainofthoughts');

class Encryption extends CI_Controller{
	
	public function __construct(){
		parent::__construct();
	}

	public function index($val){
		$a = $this->aes_encrypt($val);
		echo "Encrypted using PHP - ".base64_encode($a).'</br>';

		$result = $this->db->query("SELECT AES_ENCRYPT('".$val."', '".MY_KEY."') AS enc");
		$res = $result->result();
		$b = $res[0]->enc;
		echo "Encrypted using MySQL - ".base64_encode($b).'</br>';

		$result1 = $this->db->query("SELECT AES_DECRYPT('".$a."', '".MY_KEY."') AS decc");
		$res1 = $result1->result();
		$c = $res1[0]->decc;
		echo "Decrypted using MySQL - ".$c."</br>";

		$d = $this->aes_decrypt($b);
		echo "Decrypted using PHP - ".$d."</br>";

		var_dump($a===$b);
		var_dump($c===$d);
	}

	function aes_encrypt($val){
	    $key = $this->mysql_aes_key(MY_KEY);
	    echo $pad_value = 16-(strlen($val) % 16);
	    $val = str_pad($val, (16*(floor(strlen($val) / 16)+1)), chr($pad_value));
	    return mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $val, MCRYPT_MODE_ECB, mcrypt_create_iv( mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB), MCRYPT_DEV_URANDOM));
	}

	function aes_decrypt($val){
	    $key = $this->mysql_aes_key(MY_KEY);
	    $val = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $val, MCRYPT_MODE_ECB, mcrypt_create_iv( mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB), MCRYPT_DEV_URANDOM));
	    return rtrim($val,"..16");
	}

	function mysql_aes_key($key){
	    $new_key = str_repeat(chr(0), 16);
	    for($i=0,$len=strlen($key);$i<$len;$i++)
	    {
	        $new_key[$i%16] = $new_key[$i%16] ^ $key[$i];
	    }
	    return $new_key;
	}

}
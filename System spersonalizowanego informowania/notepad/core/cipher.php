<?php
class szyfr{
	private $iv = "45287112549354892144548565456541";
	private $key = "anjueolkdiwpoida";
	
	public function encodingAES($text){

	  // to append string with trailing characters as for PKCS7 padding scheme
	  $block = mcrypt_get_block_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC);
	  $padding = $block - (strlen($text) % $block);
	  $text .= str_repeat(chr($padding), $padding);

	  $crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $this->key, $text, MCRYPT_MODE_CBC, $this->iv);

	  $crypttext64=base64_encode($crypttext);
	  
	  return $crypttext64;
	}
}
?>
<?php

namespace Super;

class SuperConverter
{
	public $hex;
	public $utf8Hex;

	public function __construct($hex)
	{
		$this->hex = $hex;
	}

	public static function strToHex($string)
	{
		$hex = '';
		for ($i=0; $i<strlen($string); $i++){
			$ord = ord($string[$i]);
			$hexCode = dechex($ord);

			$hex .= substr('0'.$hexCode, -2);
		}

		$hex = strToUpper($hex);
		return $hex;
	}

	public static function hexToStr($hex)
	{
		$string='';
		for ($i=0; $i < strlen($hex)-1; $i+=2){
			$string .= chr(hexdec($hex[$i].$hex[$i+1]));
		}
		return $string;
	}

	public function toString()
	{
		$hex = $this->utf8Hex;
		$string = self::hexToStr($hex);
		return $string;
	}

	public function convert($lookup)
	{
		$str = $this->hex;
		$parts = [];
		$reconstruct = [];
		while (strlen($str) > 0) {
			$parts[] = $hex = substr($str, 0, 2);
			$str = substr($str, 2);

			echo "\n" . $hex . '   :   ' . self::hexToStr($hex);
		}

		$skip = [];
		$offender = '';
		foreach ($parts as $part) {
			if (!empty($skip)) {
				$offender .= $part;

				array_pop($skip);

				if (empty($skip)) {
					$reconstruct[] = $lookup( $offender );
					$offender = '';
				}

				continue;
			}

			switch ($part{0}) {
				case 'C':
					$skip[] = true;
					$offender = $part;
					continue;
					break;
				case 'E':
					$skip[] = true;
					$skip[] = true;
					$offender = $part;
					continue;
					break;
				default:
					$reconstruct[] = $part;
			}
		}

		$this->utf8Hex = implode('', $reconstruct);

		return $this;
	}
}
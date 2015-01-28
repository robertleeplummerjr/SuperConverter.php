# SuperConverter.php

Convert hex values from Non-UTF8 to UTF8

use like this:

```php
use Super\SuperConverter;

$superC = new SuperConverter('hex value');

$out = $superC
	->convert(function($hex) {
		switch ($hex) {
			case 'C382':
				return SuperConverter::strToHex(' ');
				break;
			case 'C2A0':
				return SuperConverter::strToHex('&nbsp;');
				break;
			case 'E28093':
				return SuperConverter::strToHex('*');
				break;
			default:
				echo "Error:" . $hex;
				die;
		}
	})
	->toString();
```


Actual use case (used in tiki 13.x found at tiki.org to handle conversion of some 5000 pages):

```php
$results = $tikilib->fetchAll("SELECT data, pageName, HEX(data) as `hexdata` from tiki_pages WHERE NOT HEX(data) REGEXP '^([0-7][0-9A-F])*$' AND pageName NOT LIKE 'OLD%' ORDER  BY Length(data) DESC LIMIT 10");

if (empty($results)) {
	echo "No issues found";
	die;
}

foreach ($results as $result) {
	$oldData = $result['data'];
	$hexdata = $result['hexdata'];
	$data = (new SuperEncoder($hexdata))
		->convert(function($hex) use ($oldData) {
			switch ($hex) {
				case 'C382':
				case 'C3A2':
					return SuperConverter::strToHex(' ');
					break;
				
				case 'C2A0':
					return SuperConverter::strToHex('~hs~');
					break;
				
				case 'E282AC':
				case 'E28093':
				case 'E28094':
					return SuperConverter::strToHex('-');
					break;
					
				case 'C3AF':
				case 'E2809C':
				case 'E2809D':
					return SuperConverter::strToHex('"');
					break;
				
				case 'E280A2':
					return SuperConverter::strToHex('*');
					
				case 'E280A6':
					return SuperConverter::strToHex('...');
					break;
				
				case 'E28099':
				case 'E28098':
					return SuperConverter::strToHex("'");
					break;
				
				case 'E2809A':
					return SuperConverter::strToHex(",");
					break;
				case 'E2809E':
					return SuperConverter::strToHex(",,");
					break;
					
				case 'C2AE':
					return SuperConverter::strToHex("~np~&reg;~/np~");
					break;
				
				case 'C2BC':
					return SuperConverter::strToHex("1/4");
					break;
				
				case 'C389':
					return SuperConverter::strToHex("~np~&Eacute;~/np~");
					break;
				
				case 'E2889A':
					return SuperConverter::strToHex("~np~&#10003;~/np~");
					break;
				
				case 'C2A6':
					return SuperConverter::strToHex(":");
					break;
				
				case 'C3B7':
					return SuperConverter::strToHex("~np~&divide;~/np~");
					break;
				
				case 'E28886':
				case 'E296BA':
				case 'E28692':
				case 'E284A2':
				case 'EF8398':
				case 'EF818A':
				case 'EF82B7':
				case 'E28897':
				case 'C392':
				case 'C29D':
				case 'C593':
				case 'C290':
				case 'C3A0':
				case 'C2BF':
				case 'C2BD':
				case 'CB9C':
				case 'C2A2':
					return '';
					break;
				default:
					echo $oldData;
					echo "Error:" . $hex;
					die;
			}
		})
		->toString();

	$tikilib->query('update tiki_pages set data = ? where pageName = ?', array($data, $result['pageName']));
}

```

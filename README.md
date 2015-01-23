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
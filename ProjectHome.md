# Summary #
the API Official Website: http://api.aircamel.com.tw/apidoc/

your environment should have at least two extensions, including php5-json and php5-curl.

# Example #
```
require('aircamel_api.php');

$api_key = '1326711257';
$api_secret = 'a0cfb4d6349957648c84ad5394b3e0e2';
$output_type = 'serialize';

$AircamelAPI = new Aircamel_api($api_key, $api_secret);
```
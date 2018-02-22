# StarShipIT
StarShipIT API PHP library

## Overview

This is a PHP client library for the StarShipIT. API docs are here: https://developers.starshipit.com/

This library only implements the Address Validation, Delivery Services and Shipping Rates endpoints.

## Usage

### Initialise Web Service Client

This library connects to the APi using a WebServiceClient object

Your API key is found here: https://app.starshipit.com/Members/Settings/API.aspx

Your Developer subscription key is found here: https://developers.starshipit.com/developer (You'll need a developer login for this)

```php
<?php

$api_key  = 'xxxxxxxxxxxxxxxxxxxxxx'; // your StarShipIT API key
$apim_key = 'xxxxxxxxxxxxxxxxxxxxxx'; // your StarShipIT Developer Subscription key

$client = new WebServiceClient($api_key, $apim_key, 'https://api.starshipit.com/api/');

?>
```

### Address Validation

```php
<?php

// this address contains the minimum required fields
// to return a suggestion from the server
$address = new \Treloar\StarShipIT\Address([
  "street"    => "Parliament Drive",
  "post_code" => "2600",
  "country"   => "Australia",
]);

// call the API and get the validity of the address
$is_valid = $client->validateAddress($address);

// get valid addresses suggested by server
if (!$is_valid) {
  $suggestions = $address->suggestions;
}

?>
```

### Delivery Services

```php
<?php

$weight = 25.0 // kilograms
$services = $client->getServices($address, $weight);

?>
```


### Rates Calculation

```php
<?php

$weight = 25.0 // kilograms
$rates = $client->getRates($address, $weight);

?>
```

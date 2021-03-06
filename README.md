# Azart RPC PHP

Simple Azart JSON-RPC client based on GuzzleHttp  

## Installation
Run ```php composer.phar require azartpay/azart-rpc-php``` in your project directory or add following lines to composer.json
```javascript
"require": {
    "azartpay/azart-rpc-php": "2.0.3"
}
```
and run ```php composer.phar update```.

## Requirements
PHP 7.0 or higher (should also work on 5.6, but this is unsupported)

## Usage
Create new object with url as parameter
```php
use AzartPay\Azart\Client as AzartClient;

$azartd = new AzartClient('http://rpcuser:rpcpassword@localhost:9798/');
```
or use array to define your azartd settings
```php
use AzartPay\Azart\Client as AzartClient;

$azartd = new AzartClient([
    'scheme' => 'http',                 // optional, default http
    'host'   => 'localhost',            // optional, default localhost
    'port'   => 9798,                   // optional, default 9798
    'user'   => 'rpcuser',              // required
    'pass'   => 'rpcpassword',          // required
    'ca'     => '/etc/ssl/ca-cert.pem'  // optional, for use with https scheme
]);
```
Then call methods defined in [Dash Core API Documentation](https://dash-docs.github.io/en/developer-reference#dash-core-apis) with magic:
```php
/**
 * Get block info.
 */
$block = $azartd->getBlock('000009b9903dae4466d48db6c264d711ac554492da34cd0bfa4c0b6d230f29c9');

$block('hash')->get();     // 000009b9903dae4466d48db6c264d711ac554492da34cd0bfa4c0b6d230f29c9
$block['height'];          // 0 (array access)
$block->get('tx.0');       // 44701bbc011bdd471b75fa83e42acc8e067759a69cdeef723df57181a33e5467
$block->count('tx');       // 1
$block->has('version');    // key must exist and CAN NOT be null
$block->exists('version'); // key must exist and CAN be null
$block->contains(0);       // check if response contains value
$block->values();          // array of values
$block->keys();            // array of keys
$block->random(1, 'tx');   // random block txid
$block('tx')->random(2);   // two random block txid's
$block('tx')->first();     // txid of first transaction
$block('tx')->last();      // txid of last transaction

/**
 * Send transaction.
 */
$result = $azartd->sendToAddress('AqUM31KtkxgbMwYrrpUi6RVjaftK3Mv5mG', 0.1);
$txid = $result->get();

/**
 * Get transaction amount.
 */
$result = $azartd->listSinceBlock();
$totalAmount = $result->sum('transactions.*.amount');
$totalSatoshi = AzartClient::toSatoshi($totalAmount);
```
To send asynchronous request, add Async to method name:
```php
use AzartPay\Azart\AzartdResponse;

$promise = $azartd->getBlockAsync(
    '000009b9903dae4466d48db6c264d711ac554492da34cd0bfa4c0b6d230f29c9',
    function (AzartdResponse $success) {
        //
    },
    function (\Exception $exception) {
        //
    }
);

$promise->wait();
```

You can also send requests using request method:
```php
/**
 * Get block info.
 */
$block = $azartd->request('getBlock', '000009b9903dae4466d48db6c264d711ac554492da34cd0bfa4c0b6d230f29c9');

$block('hash');            // 000009b9903dae4466d48db6c264d711ac554492da34cd0bfa4c0b6d230f29c9
$block['height'];          // 0 (array access)
$block->get('tx.0');       // 44701bbc011bdd471b75fa83e42acc8e067759a69cdeef723df57181a33e5467
$block->count('tx');       // 1
$block->has('version');    // key must exist and CAN NOT be null
$block->exists('version'); // key must exist and CAN be null
$block->contains(0);       // check if response contains value
$block->values();          // get response values
$block->keys();            // get response keys
$block->first('tx');       // get txid of the first transaction
$block->last('tx');        // get txid of the last transaction
$block->random(1, 'tx');   // get random txid

/**
 * Send transaction.
 */
$result = $azartd->request('sendtoaddress', ['AqUM31KtkxgbMwYrrpUi6RVjaftK3Mv5mG', 0.06]);
$txid = $result->get();

```
or requestAsync method for asynchronous calls:
```php
use AzartPay\Azart\AzartdResponse;

$promise = $azartd->requestAsync(
    'getBlock',
    '000009b9903dae4466d48db6c264d711ac554492da34cd0bfa4c0b6d230f29c9',
    function (AzartdResponse $success) {
        //
    },
    function (\Exception $exception) {
        //
    }
);

$promise->wait();
```

## License

This product is distributed under MIT license.

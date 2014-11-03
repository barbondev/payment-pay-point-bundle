Barbon Payments
===============

PayPoint Hosted Gateway
-----------------------

A [PayPoint](http://www.paypoint.net/support/gateway/integration-guides/) hosted payment gateway plugin implementation for the [JMSPaymentPaypalBundle](http://jmsyst.com/bundles/JMSPaymentPaypalBundle) used with the Symfony framework.

Installation
------------

This is a [composer package](https://getcomposer.org/), so include as a composer dependency in `composer.json`

```json
"require": {
    ...
    "barbondev/payment-paypoint-hosted-bundle": "dev-master"
},
```

Update the composer vendors and autoloader

```
$ php composer.phar update
```

Update the Symfony kernel `app/AppKernel.php` by adding the bundle

```php
<?php

// in AppKernel::registerBundles()
$bundles = array(
    // ...
    new Barbondev\Payment\PayPointHostedBundle\BarbondevPaymentPayPointHostedBundle(),
    // ...
);
```
Barbon Payments
===============

[![Build Status](https://travis-ci.org/barbondev/payment-pay-point-bundle.svg?branch=develop)](https://travis-ci.org/barbondev/payment-pay-point-bundle)

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
// in AppKernel::registerBundles()
$bundles = array(
    // ...
    new JMS\Payment\CoreBundle\JMSPaymentCoreBundle(),
    new Barbondev\Payment\PayPointHostedBundle\BarbondevPaymentPayPointHostedBundle(),
    // ...
);
```

Install the routing configuration in `app/config/routing.yml`

```yml
barbondev_payment_paypoint_hosted:
    resource: "@BarbondevPaymentPayPointHostedBundle/Resources/config/routing.xml"
    prefix:   /
```

Configuration
-------------

In `app/config/config.yml` configure the following parameters:

```yml
barbon_payment_paypoint_hosted:
    merchant: ABC123
    gateway_url: https://www.secpay.com/java-bin/ValCard
    remote_password: f00b4r
    test_status: 'true'
    repeat: 'true'
    test_mpi_status: 'true'
    usage_type: E
    dups: 'true'
    template: https://www.example.com/path/to/my-template.html
```

In `app/config/config_prod.yml` the default parameters may be overridden with production variants:

```yml
barbon_payment_paypoint_hosted:
    merchant: ABC123
    gateway_url: https://www.secpay.com/java-bin/ValCard
    remote_password: sup3rs3cr3tp455
    test_status: 'live'
    repeat: 'true'
    test_mpi_status: 'live'
    usage_type: E
    dups: 'true'
    template: https://www.example.com/path/to/my-template.html
```

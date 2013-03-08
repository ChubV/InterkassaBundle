InterkassaBundle
================

Symfony2 InterkassaBundle allows us to get user payments using [Interkassa] system.
[Interkassa] is the one of the biggest payment system integrator in Russia and Ukraine.

[![Build Status](https://secure.travis-ci.org/ChubV/InterkassaBundle.png)](http://travis-ci.org/ChubV/InterkassaBundle)
[![knp](http://knpbundles.com/ChubV/InterkassaBundle/badge-short)](http://knpbundles.com/ChubV/InterkassaBundle)

[Interkassa]: http://interkassa.com

Installation
============

### Use composer

Add `"chub/interkassa-bundle": "*"` to your `required` section of _composer.json_ and run `php composer.phar update`.

### Register your bundle

Add it to your kernel

``` php
<?php
// app/AppKernel.php
public function registerBundles()
{
    $bundles = array(
        // ...
        new ChubProduction\InterkassaBundle\InterkassaBundle(),
    );
    // ...
}
```

Configuration
=============

Add interkassa section to your _app\config.yml_

``` yaml
interkassa:
  connections:  # contain shop descriptions
    intercassa:
      shop_id: ololo                     # Shop id (you can get it in your profile)
      secret_key: ololo                  # Secret key (you can get it in your profile)
      fail_url: /profile/balance         # Url to redirect user on transaction fail
      success_url: /profile/balance      # Url to redirect user on transaction success
    #another_shop:
    # ....
```

Usage
=====

* Create your payment item class

``` php
<?php
//..
use ChubProduction\InterkassaBundle\Entity\Payment;
use ChubProduction\InterkassaBundle\Service\PaymentItemInterface;

class PaymentItem implements PaymentItemInterface
{
	public function getAmount()
	{
		// return '1.00';
	}

	public function getDescription()
	{
		// return 'ololo';
	}

	public function setPayment(Payment $p)
	{
		// TODO: Implement setPayment() method.
	}
}
```

* Create a payment object

``` php
$po = new PaymentItem();
```

* Redirect user to pay

``` php
// Somewhere in your Action
$response = $this->get('payment')->createInvoice($po, 'intercassa');
return $response
```

* Check status of the payment

``` php
$po->getPayment()->isPaid();
```

Event system
============

You can also register your own event subscriber/dispatcher to handle invoice creation, successful or failed transaction.
There are `InterkassaPaymentEvent` object and `InterkassaPaymentEvent::ON_INVOICE`,
`InterkassaPaymentEvent::ON_STATUS_SUCCESS`, `InterkassaPaymentEvent::ON_STATUS_FAIL` events for this

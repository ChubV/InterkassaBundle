<?php

namespace ChubProduction\InterkassaBundle\Tests;

use ChubProduction\InterkassaBundle\Entity\Payment;
use ChubProduction\InterkassaBundle\Service\PaymentItemInterface;

/**
 * @author Vladimir Chub <v@chub.com.ua>
 */
class TestItem implements PaymentItemInterface
{
	/**
	 * @return mixed
	 */
	public function getAmount()
	{
		return '1.00';
	}

	/**
	 * @return mixed
	 */
	public function getDescription()
	{
		return 'ololo';
	}

	/**
	 * @param \ChubProduction\InterkassaBundle\Entity\Payment $p
	 *
	 * @return mixed
	 */
	public function setPayment(Payment $p)
	{
		// TODO: Implement setPayment() method.
	}
}

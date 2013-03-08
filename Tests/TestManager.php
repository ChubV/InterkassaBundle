<?php

namespace ChubProduction\InterkassaBundle\Tests;

use ChubProduction\InterkassaBundle\Manager\PaymentManagerInterface;
use ChubProduction\InterkassaBundle\Entity\Payment;

/**
 * TestManager
 *
 * @author Vladimir Chub <v@chub.com.ua>
 */
class TestManager implements PaymentManagerInterface
{
	/**
	 * Saves invoice, adds payment to item
	 *
	 * @param Payment $item
	 *
	 * @return mixed
	 */
	public function savePayment(Payment $item)
	{
		$x = new \ReflectionClass($item);
		$id = $x->getProperty('id');
		$id->setAccessible(true);
		$id->setValue($item, 100500);
	}

	/**
	 * @param $id
	 *
	 * @return mixed
	 */
	public function find($id)
	{
		if ($id == 100500) {
			$payment = new Payment();
			$payment->setAmount('1.0');
			$payment->setDescription('ololo');

			return $payment;
		} else {
			return null;
		}
	}

	/**
	 * Set item paid (on successful status request)
	 *
	 * @param Payment $item
	 *
	 * @return mixed
	 */
	public function setPaid(Payment $item)
	{
		$item->setPaid(true);
	}
}

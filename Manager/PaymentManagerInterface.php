<?php
namespace ChubProduction\InterkassaBundle\Manager;

use ChubProduction\InterkassaBundle\Entity\Payment;

/**
 * PaymentManagerInterface
 *
 * @author Vladimir Chub <v@chub.com.ua>
 */
interface PaymentManagerInterface
{
	/**
	 * Saves invoice, adds payment to item
	 * @param Payment $item
	 *
	 * @return mixed
	 */
	public function savePayment(Payment $item);

	/**
	 * Set item paid (on successful status request)
	 * @param Payment $item
	 *
	 * @return mixed
	 */
	public function setPaid(Payment $item);

	/**
	 * @param $id
	 *
	 * @return mixed
	 */
	public function find($id);
}

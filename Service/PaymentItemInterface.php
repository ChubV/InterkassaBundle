<?php
namespace ChubProduction\InterkassaBundle\Service;

use Symfony\Component\HttpFoundation\Response;
use ChubProduction\InterkassaBundle\Entity\Payment;

/**
 * PaymentItemInterface
 *
 * @author Vladimir Chub <v@chub.com.ua>
 */
interface PaymentItemInterface
{
	/**
	 * @return mixed
	 */
	public function getAmount();

	/**
	 * @return mixed
	 */
	public function getDescription();

	/**
	 * @param \ChubProduction\InterkassaBundle\Entity\Payment $p
	 *
	 * @return mixed
	 */
	public function setPayment(Payment $p);
}

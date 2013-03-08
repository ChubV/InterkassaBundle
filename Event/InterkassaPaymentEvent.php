<?php
namespace ChubProduction\InterkassaBundle\Event;
use Symfony\Component\EventDispatcher\Event;
use ChubProduction\InterkassaBundle\Entity\Payment;

/**
 * InterkassaPaymentEvent
 *
 * @author Vladimir Chub <v@chub.com.ua>
 */
class InterkassaPaymentEvent extends Event
{
	const ON_INVOICE = 'interkassa.invoice';
	const ON_STATUS_SUCCESS = 'interkassa.success';
	const ON_STATUS_FAIL = 'interkassa.fail';

	/**
	 * @var \ChubProduction\InterkassaBundle\Entity\Payment
	 */
	private $payment;

	/**
	 * @param \ChubProduction\InterkassaBundle\Entity\Payment $payment
	 */
	public function __construct(Payment $payment)
	{
		$this->payment = $payment;
	}

	/**
	 * @param $payment
	 *
	 * @return InterkassaPaymentEvent
	 */
	public function setPayment($payment)
	{
		$this->payment = $payment;

		return $this;
	}

	/**
	 * @return Payment
	 */
	public function getPayment()
	{
		return $this->payment;
	}
}

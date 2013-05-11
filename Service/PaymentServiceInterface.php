<?php
namespace ChubProduction\InterkassaBundle\Service;

use ChubProduction\InterkassaBundle\Entity\Payment;
use Symfony\Component\HttpFoundation\Request;
use ChubProduction\InterkassaBundle\Manager\PaymentManagerInterface;

/**
 * PaymentServiceInterface
 *
 * @author Vladimir Chub <v@chub.com.ua>
 */
interface PaymentServiceInterface
{
	/**
	 * @param PaymentItemInterface $item       Item to generate invoice for
	 * @param string               $connection Shop connection name
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function createInvoice(PaymentItemInterface $item, $connection);

	/**
	 * @param Payment $payment
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function createInvoiceForm(Payment $payment);

	/**
	 * @param PaymentManagerInterface $repository
	 *
	 * @return void
	 */
	public function setManager(PaymentManagerInterface $repository);

	/**
	 * @param Request $request
	 * @param string  $connection
	 *
	 * @return mixed
	 */
	public function checkStatus(Request $request, $connection);

	/**
	 * @param Request $request
	 * @param string  $connection
	 *
	 * @return mixed
	 */
	public function success(Request $request, $connection);

	/**
	 * @param Request $request
	 * @param string  $connection
	 *
	 * @return mixed
	 */
	public function fail(Request $request, $connection);
}

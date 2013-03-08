<?php
namespace ChubProduction\InterkassaBundle\Service;

use Symfony\Component\HttpFoundation\Response;
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
	 * @return Response
	 */
	public function createInvoice(PaymentItemInterface $item, $connection);

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

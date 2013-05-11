<?php
namespace ChubProduction\InterkassaBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use ChubProduction\InterkassaBundle\Event\InterkassaPaymentEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use ChubProduction\InterkassaBundle\Entity\Payment;
use ChubProduction\InterkassaBundle\Manager\PaymentManagerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * PaymentService
 *
 * @author Vladimir Chub <v@chub.com.ua>
 */
class PaymentService implements PaymentServiceInterface
{
	/**
	 * @var \Symfony\Component\DependencyInjection\ContainerInterface
	 */
	private $c;

	/**
	 * @var PaymentManagerInterface
	 */
	private $manager;

	/**
	 * @param \Symfony\Component\DependencyInjection\ContainerInterface $c
	 * @param mixed                                                     $params
	 */
	public function __construct(ContainerInterface $c, $params)
	{
		$this->c = $c;
		$this->params = $params;
	}

	/**
	 * @param PaymentItemInterface $item
	 * @param string               $connection
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function createInvoice(PaymentItemInterface $item, $connection)
	{
		$conn = $this->getConnection($connection);
		$payment = new Payment();
		$payment->setAmount($item->getAmount())
				->setDescription($item->getDescription())
				->setShopId($conn['shop_id'])
				->setPaid(false);

		$item->setPayment($payment);
		$this->manager->savePayment($payment);

		$this->dispatch(InterkassaPaymentEvent::ON_INVOICE, new InterkassaPaymentEvent($payment));

		return $this->createInvoiceForm($payment);
	}

	/**
	 * @param Payment $payment
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function createInvoiceForm(Payment $payment)
	{
		return $this->c
			->get('templating')
			->renderResponse('InterkassaBundle::invoice.html.twig', compact('payment'));
	}

	/**
	 * @param PaymentManagerInterface $manager
	 *
	 * @return void
	 */
	public function setManager(PaymentManagerInterface $manager)
	{
		$this->manager = $manager;
	}

	/**
	 * @param Request $request
	 * @param string  $connection
	 *
	 * @return bool|mixed
	 */
	public function checkStatus(Request $request, $connection)
	{
		$conn = $this->getConnection($connection);

		if ($this->checkParams($request, $conn)) {
			/** @var Payment $p */
			$p = $this->manager->find($request->get('ik_payment_id'));
			if ($p === null || $p->getAmount() != $request->get('ik_payment_amount')) {
				if ($p) {
					$this->dispatch(InterkassaPaymentEvent::ON_STATUS_FAIL, new InterkassaPaymentEvent($p));
				}

				return false;
			}
			$this->manager->setPaid($p);
			$this->dispatch(InterkassaPaymentEvent::ON_STATUS_SUCCESS, new InterkassaPaymentEvent($p));

			return $p;
		}

		return false;
	}

	/**
	 * @param Request $request
	 * @param string  $connection
	 *
	 * @return mixed
	 */
	public function success(Request $request, $connection)
	{
		$payment = $this->manager->find($request->get('ik_payment_id'));
		$conn = $this->getConnection($connection);
		if ($payment && $request->get('ik_payment_state') == 'success' && $payment->isPaid()) {
			return new RedirectResponse($conn['success_url']);
		} else {
			return new RedirectResponse($conn['fail_url']);
		}
	}

	/**
	 * @param Request $request
	 * @param string  $connection
	 *
	 * @return mixed
	 */
	public function fail(Request $request, $connection)
	{
		$conn = $this->getConnection($connection);

		return new RedirectResponse($conn['fail_url']);
	}

	/**
	 * @param Request $r
	 * @param array   $p
	 *
	 * @return bool
	 */
	private function checkParams(Request $r, $p)
	{
		$x = array();
		$keys = array('shop_id', 'payment_amount', 'payment_id', 'paysystem_alias', 'baggage_fields',
					'payment_state', 'trans_id', 'currency_exch', 'fees_payer');
		foreach ($keys as $key) {
			$m = $r->get('ik_' . $key);
			if ($m === null) {
				return false;
			}
			$x[] = $m;
		}
		$x[] = $p['secret_key'];
		if (strtoupper($r->get('ik_sign_hash')) != strtoupper(md5(implode(':', $x)))) {
			return false;
		} elseif ($r->get('ik_shop_id') != $p['shop_id'] || $r->get('ik_payment_state') != 'success') {
			return false;
		}

		return true;
	}

	/**
	 * @param string $connection
	 *
	 * @return array
	 */
	private function getConnection($connection)
	{
		$params = $this->params['connections'][$connection];

		return $params;
	}

	private function dispatch($name, InterkassaPaymentEvent $event)
	{
		$this->c->get('event_dispatcher')->dispatch($name, $event);
	}
}

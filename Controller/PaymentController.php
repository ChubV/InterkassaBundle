<?php
namespace ChubProduction\InterkassaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controller to handle user or interkassa payment requests
 *
 * @author Vladimir Chub <v@chub.com.ua>
 */
class PaymentController extends Controller
{
	/**
	 * Status action (handles status request from the remote Interkassa server)
	 * @param string $connection
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
	 */
	public function statusAction($connection)
    {
	    $p = $this->get('payment');
	    $payment = $p->checkStatus($this->getRequest(), $connection);
	    if (!$payment) {
		    throw $this->createNotFoundException();
	    }

	    return new Response('Ok');
    }

	/**
	 * Success action
	 *
	 * @param string $connection
	 * @return mixed
	 */
	public function successAction($connection)
	{
		$p = $this->get('payment');

		return $p->success($this->getRequest(), $connection);
	}

	/**
	 * Fail action
	 * @param string $connection
	 *
	 * @return mixed
	 */
	public function failAction($connection)
	{
		$p = $this->get('payment');

		return $p->fail($this->getRequest(), $connection);
	}
}

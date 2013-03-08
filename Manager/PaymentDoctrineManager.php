<?php
namespace ChubProduction\InterkassaBundle\Manager;

use ChubProduction\InterkassaBundle\Entity\Payment;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManager;

/**
 * Payment manager for doctrine-based payment records
 *
 * @author Vladimir Chub <v@chub.com.ua>
 */
class PaymentDoctrineManager implements PaymentManagerInterface
{
	/**
	 * @var EntityManager $m
	 */
	private $m;
	private $c;
	private $initialized;

	/**
	 * @param \Symfony\Component\DependencyInjection\ContainerInterface $c
	 */
	public function __construct(ContainerInterface $c)
	{
		$this->c = $c;
	}


	/**
	 * Saves invoice, adds payment to item
	 * @param Payment $item
	 *
	 * @return mixed
	 */
	public function savePayment(Payment $item)
	{
		$this->init();

		$this->m->persist($item);
		$this->m->flush();
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
		$this->init();
		$item->setPaid(true);

		$this->m->persist($item);
		$this->m->flush();
	}


	/**
	 * @param $id
	 *
	 * @return mixed
	 */
	public function find($id)
	{
		$this->init();
		return $this->m->find('ChubProduction\InterkassaBundle\Entity\Payment', $id);
	}

	/**
	 * Initialize manager
	 */
	private function init()
	{
		if (!$this->initialized) {
			$this->m = $this->c->get('doctrine')->getManager();
			$this->initialized = true;
		}
	}
}

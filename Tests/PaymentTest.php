<?php
namespace ChubProduction\InterkassaBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Response;
use ChubProduction\InterkassaBundle\Tests\TestManager;
use Symfony\Component\BrowserKit\Tests\TestClient;

/**
 * Test payment bundle
 *
 * @author Vladimir Chub <v@chub.com.ua>
 */
class PaymentTest extends WebTestCase
{
	/**
	 * @var \ChubProduction\InterkassaBundle\Service\PaymentServiceInterface
	 */
	private $payment;
	private $settings;
	private $connection;

	/**
	 * @var TestClient
	 */
	private $client;

	public function setUp()
	{
		$this->client = static::createClient();
		$this->payment = self::$kernel->getContainer()->get('payment');
		$this->payment->setManager(new TestManager());
		$params = self::$kernel->getContainer()->getParameter('payment_parameters');
		$this->settings = $params['connections'];
		$this->connection = array_keys($this->settings)[0];
	}

    public function testService()
    {
		$this->assertInstanceOf('ChubProduction\\InterkassaBundle\\Service\\PaymentServiceInterface'
					, $this->payment);
    }

	public function testInvoice()
	{
		$payment = new TestItem();
		$invoice = $this->payment->createInvoice($payment, $this->connection);
		$this->assertInstanceOf('Symfony\\Component\\HttpFoundation\\Response', $invoice);

		$crawl = new Crawler($invoice->getContent());
		$form = $crawl->filter('#payment_form');
		$this->assertEquals($this->settings[$this->connection]['submit_url'], $form->attr('action'));
		$this->assertEquals($this->settings[$this->connection]['shop_id'], $form->filter('input[name="ik_shop_id"]')->attr('value'));
		$this->assertEquals('1.0', $form->filter('input[name="ik_payment_amount"]')->attr('value'));

		/**
		 * Id is set in repo when saving payment
		 */
		$this->assertEquals(100500, $form->filter('input[name="ik_payment_id"]')->attr('value'));
		$this->assertEquals(1, $form->filter('input[name="ik_payment_desc"]')->count());
	}

	public function testSetStatus()
	{
		$url = self::$kernel->getContainer()
					->get('router')
					->generate('payment_status',
							array('connection' => $this->connection));

		foreach ($this->getTestStatusData() as $data)
		{
			$this->setUp();
			$dummy = array(
				'ik_sign_hash' => '',
				'ik_paysystem_alias' => '',
				'ik_baggage_fields' => '',
				'ik_payment_state' => '',
				'ik_trans_id' => '',
				'ik_currency_exch' => '',
				'ik_fees_payer' => '');
			list($status, $x) = $data;
			$data = array_merge($dummy, $x);
			$this->client->request('POST', $url, $data);

			$this->assertEquals($status, $this->client->getResponse()->getStatusCode());
		}
	}

	public function testSuccessfull()
	{
		$url = self::$kernel->getContainer()
			->get('router')
			->generate('payment_success',
			array('connection' => $this->connection));
		$this->setUp();

		$dummy = array(
			'ik_sign_hash' => '',
			'ik_paysystem_alias' => '',
			'ik_baggage_fields' => '',
			'ik_payment_state' => 'success',
			'ik_trans_id' => '',
			'ik_currency_exch' => '',
			'ik_fees_payer' => '');
		list($status, $x) = $this->getTestStatusData()[0];
		$data = array_merge($dummy, $x);
		$shopId = $this->settings[$this->connection]['shop_id'];
		$key = $this->settings[$this->connection]['secret_key'];
		$data['ik_sign_hash'] = md5($shopId . ':1.0:100500:::success::::' . $key);
		$this->client->request('POST', $url, $data);
		$this->assertTrue($this->client->getResponse()->isRedirect($this->settings[$this->connection]['success_url']));
	}

	public function testFail()
	{
		$url = self::$kernel->getContainer()
			->get('router')
			->generate('payment_fail',
			array('connection' => $this->connection));
		$this->setUp();

		$dummy = array(
			'ik_sign_hash' => '',
			'ik_paysystem_alias' => '',
			'ik_baggage_fields' => '',
			'ik_payment_state' => 'success',
			'ik_trans_id' => '',
			'ik_currency_exch' => '',
			'ik_fees_payer' => '');
		list($status, $x) = $this->getTestStatusData()[0];
		$data = array_merge($dummy, $x);
		$this->client->request('POST', $url, $data);
		$this->assertTrue($this->client->getResponse()->isRedirect($this->settings[$this->connection]['fail_url']));
	}

	private function getTestStatusData()
	{
		$shopId = $this->settings[$this->connection]['shop_id'];
		$key = $this->settings[$this->connection]['secret_key'];

		return array(
			array(200, array(
				'ik_shop_id' => $shopId,
				'ik_payment_amount' => '1.0',
				'ik_payment_id' => 100500,
				'ik_payment_desc' => 'ololo',
				'ik_payment_state' => 'success',
				'ik_sign_hash' => md5($shopId . ':1.0:100500:::success::::' . $key),
			)),
			array(404, array(
				'ik_shop_id' => $shopId,
				'ik_payment_amount' => '1.0',
				'ik_payment_id' => 100500,
				'ik_payment_desc' => 'ololo',
				'ik_sign_hash' => md5($shopId . '1.00500' . $key),
			)),
			array(404, array(
				'ik_shop_id' => '',
				'ik_payment_amount' => '1.0',
				'ik_payment_id' => 100500,
				'ik_payment_desc' => 'ololo',
				'ik_sign_hash' => md5($shopId . '1.0100500' . $key),
			)),
			array(404, array(
				'ik_shop_id' => $shopId,
				'ik_payment_amount' => '10.0',
				'ik_payment_id' => 100500,
				'ik_payment_desc' => 'ololo',
				'ik_sign_hash' => md5($shopId . '1.0100500' . $key),
			)),
		);
	}
}

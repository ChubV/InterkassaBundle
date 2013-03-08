<?php
namespace ChubProduction\InterkassaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Payment
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="ChubProduction\InterkassaBundle\Entity\PaymentRepository")
 * @codeCoverageIgnore
 * @author Vladimir Chub <v@chub.com.ua>
 */
class Payment
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var float
     *
     * @ORM\Column(name="amount", type="float")
     */
    private $amount;

    /**
     * @var string
     *
     * @ORM\Column(name="shopId", type="string", length=255)
     */
    private $shopId;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

	/**
	 * @var bool
	 *
	 * @ORM\Column(name="is_paid", type="boolean")
	 */
	private $paid;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set amount
     *
     * @param float $amount
     * @return Payment
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set shopId
     *
     * @param string $shopId
     * @return Payment
     */
    public function setShopId($shopId)
    {
        $this->shopId = $shopId;

        return $this;
    }

    /**
     * Get shopId
     *
     * @return string
     */
    public function getShopId()
    {
        return $this->shopId;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Payment
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

	/**
	 * @param boolean $paid
	 *
	 * @return mixed
	 */
	public function setPaid($paid)
	{
		$this->paid = $paid;

		return $this;
	}

	/**
	 * @return boolean
	 */
	public function isPaid()
	{
		return $this->paid;
	}
}

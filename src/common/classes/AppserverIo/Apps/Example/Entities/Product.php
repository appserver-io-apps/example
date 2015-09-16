<?php

/**
 * AppserverIo\Apps\Example\Actions\Product
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * PHP version 5
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io-apps/example
 * @link      http://www.appserver.io
 */

namespace AppserverIo\Apps\Example\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Search\Mapping\Annotations as MAP;
use JMS\Serializer\Annotation as JMS;

/**
 * Doctrine entity that represents a assertion.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io-apps/example
 * @link      http://www.appserver.io
 *
 * @ORM\Entity
 * @ORM\Table(name="product")
 * @JMS\ExclusionPolicy("all")
 */
class Product extends AbstractEntity
{

	const STATUS_ACTIVE = 1;
	const STATUS_PROCESSING = 2;
	const STATUS_INACTIVE = 0;

	protected $statusArray = array();

	/**
	 * @var int $id
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 * @ORM\Column(name="id", type="integer", nullable=false)
	 * @JMS\Groups({"search"})
	 * @JMS\Expose
	 * @JMS\Type("integer")
	 */
	protected $id;

	/**
	 * @var int $status
	 * @ORM\Column(name="status", type="integer", nullable=true)
	 * @JMS\Expose
	 * @JMS\Groups({"search"})
	 * @JMS\Type("integer")
	 */
	protected $status = 0;

	/**
	 * @var string $name
	 * @ORM\Column(name="name", type="string", nullable=false)
	 * @JMS\Expose
	 * @JMS\Groups({"search"})
	 * @JMS\Type("string")
	 */
	protected $name;

	/**
	 * @var string $urlKey
	 * @ORM\Column(name="url_key", type="string", nullable=false, unique=true)
	 * @JMS\Expose
	 * @JMS\Groups({"search"})
	 * @JMS\Type("string")
	 */
	protected $urlKey;

	/**
	 * @var string $productNumber
	 * @ORM\Column(name="product_number", type="string", nullable=false, unique=true)
	 * @JMS\Expose
	 * @JMS\Groups({"search"})
	 * @JMS\Type("string")
	 */
	protected $productNumber;

	/**
	 * @var float $salesPrice
	 * @ORM\Column(name="sales_price", type="float", nullable=false)
	 * @JMS\Expose
	 * @JMS\Groups({"search"})
	 * @JMS\Type("double")
	 */
	protected $salesPrice;

	/**
	 * @var float $strokePrice
	 * @ORM\Column(name="stroke_price", type="float", nullable=true)
	 */
	protected $strokePrice;

	/**
	 * @var string $description
	 * @ORM\Column(name="description", type="text", nullable=false)
	 * @JMS\Expose
	 * @JMS\Groups({"search"})
	 * @JMS\Type("string")
	 */
	protected $description;

	/**
	 * @var string $shortDescription
	 * @ORM\Column(name="short_description", type="text", nullable=true)
	 * @JMS\Expose
	 * @JMS\Groups({"search"})
	 * @JMS\Type("string")
	 */
	protected $shortDescription;

	/**
	 * @var \DateTime $newFrom
	 * @ORM\Column(name="new_from", type="date", nullable=true)
	 * @JMS\Expose
	 * @JMS\Groups({"search"})
	 * @JMS\Type("DateTime")
	 */
	protected $newFrom;

	/**
	 * @var \DateTime $newTo
	 * @ORM\Column(name="new_to", type="date", nullable=true)
	 * @JMS\Expose
	 * @JMS\Groups({"search"})
	 * @JMS\Type("DateTime")
	 */
	protected $newTo;

	/**
	 * @var int $voting
	 * @ORM\Column(name="voting", type="integer", nullable=false)
	 * @JMS\Type("integer")
	 * @JMS\Expose
	 * @JMS\Groups({"search"})
	 */
	protected $voting = 0;

	/**
	 * @var integer $parentId
	 * @ORM\Column(name="parent_id", type="integer", nullable=true)
	 */
	protected $parentId;

	/**
	 * INVERSE SIDE
	 * @var ArrayCollection $images
	 * @ORM\OneToMany(targetEntity="AppserverIo\Apps\Example\Entities\ProductImage", mappedBy="product")
	 * @JMS\Expose
	 * @JMS\MaxDepth(2)
	 * @JMS\Groups({"search"})
	 * @JMS\Type("ArrayCollection<AppserverIo\Apps\Example\Entities\ProductImage>")
	 */
	protected $images;

	/**
	 * INVERSE SIDE
	 * @var ArrayCollection $productVariants
	 * @ORM\OneToMany(targetEntity="AppserverIo\Apps\Example\Entities\Product", mappedBy="parentProduct")
	 * @JMS\Expose
	 * @JMS\MaxDepth(2)
	 * @JMS\Groups({"search"})
	 * @JMS\Type("ArrayCollection<AppserverIo\Apps\Example\Entities\Product>")
	 */
	protected $productVariants;

	/**
	 * Owning SIDE
	 * @var \AppserverIo\Apps\Example\Entities\Product $parentProduct
	 * @ORM\ManyToOne(targetEntity="AppserverIo\Apps\Example\Entities\Product", inversedBy="productVariants")
	 * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
	 */
	protected $parentProduct;

	public function __construct()
	{

        $this->updateCreatedUpdatedDate();

	    // initialize the array with the status
	    $this->statusArray = [self::STATUS_ACTIVE, self::STATUS_PROCESSING, self::STATUS_INACTIVE];

		$this->variants = new ArrayCollection();
		$this->images = new ArrayCollection();
		$this->productVariants = new ArrayCollection();
	}

	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @return int
	 */
	public function getStatus() {
		return $this->status;
	}

	/**
	 * @param int $status
	 */
	public function setStatus($status) {
		if (!in_array($status, $this->statusArray)) {
			throw new \InvalidArgumentException("Invalid status");
		}

		$this->status = $status;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getUrlKey() {
		return $this->urlKey;
	}

	/**
	 * @param string $urlKey
	 */
	public function setUrlKey($urlKey) {
		$this->urlKey = $urlKey;
	}

	/**
	 * @return string
	 */
	public function getProductNumber() {
		return $this->productNumber;
	}

	/**
	 * @param string $productNumber
	 */
	public function setProductNumber($productNumber) {
		$this->productNumber = $productNumber;
	}

	/**
	 * @return float
	 */
	public function getSalesPrice() {
		return $this->salesPrice;
	}

	/**
	 * @param float $salesPrice
	 */
	public function setSalesPrice($salesPrice) {
		$this->salesPrice = $salesPrice;
	}

	/**
	 * @return float
	 */
	public function getStrokePrice() {
		return $this->strokePrice;
	}

	/**
	 * @param float $strokePrice
	 */
	public function setStrokePrice($strokePrice) {
		$this->strokePrice = $strokePrice;
	}

	/**
	 * @return string
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @param string $description
	 */
	public function setDescription($description) {
		$this->description = $description;
	}

	/**
	 * @return string
	 */
	public function getShortDescription() {
		return $this->shortDescription;
	}

	/**
	 * @param string $shortDescription
	 */
	public function setShortDescription($shortDescription) {
		$this->shortDescription = $shortDescription;
	}

	/**
	 * @return \DateTime
	 */
	public function getNewFrom() {
		return $this->newFrom;
	}

	/**
	 * @param \DateTime $newFrom
	 */
	public function setNewFrom($newFrom) {
		$this->newFrom = $newFrom;
	}

	/**
	 * @return \DateTime
	 */
	public function getNewTo() {
		return $this->newTo;
	}

	/**
	 * @param \DateTime $newTo
	 */
	public function setNewTo($newTo) {
		$this->newTo = $newTo;
	}

	/**
	 * @return int
	 */
	public function getVoting() {
		return $this->voting;
	}

	/**
	 * @param int $voting
	 */
	public function setVoting($voting) {
		$this->voting = $voting;
	}

	/**
	 * @return ArrayCollection<AppserverIo\Apps\Example\Entities\Product>
	 */
	public function getVariants() {
		return $this->variants;
	}

	/**
	 * @param Product $product
	 */
	public function addVariant($product) {
		$this->variants->add($product);
	}

	/**
	 * @return ArrayCollection
	 */
	public function getImages() {
		return $this->images;
	}

	/**
	 * @param ArrayCollection $images
	 */
	public function setImages($images) {
		$this->images = $images;
	}

	/**
	 * @param ProductImage $image
	 */
	public function addImage($image) {
		$this->images->add($image);
	}

	/**
	 * @param ProductImage $image
	 */
	public function removeImage($image) {
		$this->images->removeElement($image);
	}

	/**
	 * @param Product $parentProduct
	 * @return void
	 */
	public function setParentProduct($parentProduct) {
		$this->parentProduct = $parentProduct;
	}

}
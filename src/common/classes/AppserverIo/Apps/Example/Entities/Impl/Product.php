<?php

/**
 * AppserverIo\Apps\Example\Entities\Impl\Product
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
namespace AppserverIo\Apps\Example\Entities\Impl;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Doctrine\Search\Mapping\Annotations as MAP;
use Doctrine\Common\Collections\ArrayCollection;
use AppserverIo\Apps\Example\Entities\AbstractEntity;

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

    /**
     * Status of an active product.
     *
     * @var integer
     */
    const STATUS_ACTIVE = 1;

    /**
     * Status of a processing product.
     *
     * @var integer
     */
    const STATUS_PROCESSING = 2;

    /**
     * Status of an inactive product.
     *
     * @var integer
     */
    const STATUS_INACTIVE = 0;

    /**
     * The array with the product status.
     *
     * @var array
     */
    protected $statusArray = array();

    /**
     * The unique ID of this entity.
     *
     * @var integer
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @JMS\Groups({"search"})
     * @JMS\Expose
     * @JMS\Type("integer")
     */
    protected $id;

    /**
     * The product status.
     *
     * @var integer
     * @ORM\Column(name="status", type="integer", nullable=true)
     * @JMS\Expose
     * @JMS\Groups({"search"})
     * @JMS\Type("integer")
     */
    protected $status = 0;

    /**
     * The product name.
     *
     * @var string
     * @ORM\Column(name="name", type="string", nullable=false)
     * @JMS\Expose
     * @JMS\Groups({"search"})
     * @JMS\Type("string")
     */
    protected $name;

    /**
     * The product's URL key.
     *
     * @var string
     * @ORM\Column(name="url_key", type="string", nullable=false, unique=true)
     * @JMS\Expose
     * @JMS\Groups({"search"})
     * @JMS\Type("string")
     */
    protected $urlKey;

    /**
     * The unique product number.
     *
     * @var string
     * @ORM\Column(name="product_number", type="string", nullable=false, unique=true)
     * @JMS\Expose
     * @JMS\Groups({"search"})
     * @JMS\Type("string")
     */
    protected $productNumber;

    /**
     * The sales price of the product.
     *
     * @var integer
     * @ORM\Column(name="sales_price", type="integer", nullable=false)
     * @JMS\Expose
     * @JMS\Groups({"search"})
     * @JMS\Type("integer")
     */
    protected $salesPrice;

    /**
     * The stroke price of the product.
     *
     * @var integer
     * @ORM\Column(name="stroke_price", type="integer", nullable=true)
     */
    protected $strokePrice;

    /**
     * The product description.
     *
     * @var string
     * @ORM\Column(name="description", type="text", nullable=false)
     * @JMS\Expose
     * @JMS\Groups({"search"})
     * @JMS\Type("string")
     */
    protected $description;

    /**
     * The short product description.
     *
     * @var string
     * @ORM\Column(name="short_description", type="text", nullable=true)
     * @JMS\Expose
     * @JMS\Groups({"search"})
     * @JMS\Type("string")
     */
    protected $shortDescription;

    /**
     * The UNIX timestamp with the start date the product is rated as new.
     *
     * @var integer $newFrom
     * @ORM\Column(name="new_from", type="integer", nullable=true)
     * @JMS\Expose
     * @JMS\Groups({"search"})
     * @JMS\Type("DateTime")
     */
    protected $newFrom;

    /**
     * The UNIX timestamp with the end date the product is rated as new.
     *
     * @var integer
     * @ORM\Column(name="new_to", type="integer", nullable=true)
     * @JMS\Expose
     * @JMS\Groups({"search"})
     * @JMS\Type("DateTime")
     */
    protected $newTo;

    /**
     * The ID of the parent product.
     *
     * @var integer
     * @ORM\Column(name="parent_id", type="integer", nullable=true)
     */
    protected $parentId;

    /**
     * The product's product images.
     *
     * @var \Doctrine\Common\Collections\ArrayCollection<\AppserverIo\Apps\Example\Entities\Impl\ProductImages> $images
     * @ORM\OneToMany(targetEntity="AppserverIo\Apps\Example\Entities\Impl\ProductImage", mappedBy="product")
     * @JMS\Expose
     * @JMS\MaxDepth(2)
     * @JMS\Groups({"search"})
     * @JMS\Type("ArrayCollection<AppserverIo\Apps\Example\Entities\Impl\ProductImage>")
     */
    protected $images;

    /**
     * The product variants.
     *
     * @var \Doctrine\Common\Collections\ArrayCollection<\AppserverIo\Apps\Example\Entities\Impl\ProductImages> $productVariants
     * @ORM\OneToMany(targetEntity="AppserverIo\Apps\Example\Entities\Impl\Product", mappedBy="parentProduct")
     * @JMS\Expose
     * @JMS\MaxDepth(2)
     * @JMS\Groups({"search"})
     * @JMS\Type("ArrayCollection<AppserverIo\Apps\Example\Entities\Impl\Product>")
     */
    protected $productVariants;

    /**
     * The parent product.
     *
     * @var \AppserverIo\Apps\Example\Entities\Impl\Product $parentProduct
     * @ORM\ManyToOne(targetEntity="AppserverIo\Apps\Example\Entities\Impl\Product", inversedBy="productVariants")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    protected $parentProduct;

    /**
     * Initialize the product instance.
     */
    public function __construct()
    {

        // update the creation and update date
        $this->updateCreatedUpdatedDate();

        // initialize the array with the status
        $this->statusArray = [
            self::STATUS_ACTIVE,
            self::STATUS_PROCESSING,
            self::STATUS_INACTIVE
        ];

        // initialize the product's collections
        $this->variants = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->productVariants = new ArrayCollection();
    }

    /**
     * Return's the unique product ID.
     *
     * @return integer The unique product ID
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Return's the product status.
     *
     * @return integer The product status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set's the product status.
     *
     * @param integer $status The product status
     *
     * @return void
     * @throws \InvalidArgumentException Is thrown if the passed status is invalid
     */
    public function setStatus($status)
    {

        // query whether the passed status is valid
        if (!in_array($status, $this->statusArray)) {
            throw new \InvalidArgumentException("Invalid status");
        }

        // set the status
        $this->status = $status;
    }

    /**
     * Return's the product name.
     *
     * @return string The product name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set's the product name.
     *
     * @param string $name The product name
     *
     * @return void
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Return's the product's URL key.
     *
     * @return string The product's URL key
     */
    public function getUrlKey()
    {
        return $this->urlKey;
    }

    /**
     * Set's the product's URL key.
     *
     * @param string $urlKey The product's URL key
     *
     * @return void
     */
    public function setUrlKey($urlKey)
    {
        $this->urlKey = $urlKey;
    }

    /**
     * Return's the unique product number.
     *
     * @return string The unique product number
     */
    public function getProductNumber()
    {
        return $this->productNumber;
    }

    /**
     * Set's the unique product number.
     *
     * @param string $productNumber The unique product number
     *
     * @return void
     */
    public function setProductNumber($productNumber)
    {
        $this->productNumber = $productNumber;
    }

    /**
     * Return's the product's sales price.
     *
     * @return integer The product's sales price
     */
    public function getSalesPrice()
    {
        return $this->salesPrice;
    }

    /**
     * Set's the product's sales price.
     *
     * @param integer $salesPrice The product's sales price
     *
     * @return void
     */
    public function setSalesPrice($salesPrice)
    {
        $this->salesPrice = $salesPrice;
    }

    /**
     * Return's the product's stroke price.
     *
     * @return integer The product's stroke price
     */
    public function getStrokePrice()
    {
        return $this->strokePrice;
    }

    /**
     * Set's the product's stroke price.
     *
     * @param integer $strokePrice The product's stroke price
     *
     * @return void
     */
    public function setStrokePrice($strokePrice)
    {
        $this->strokePrice = $strokePrice;
    }

    /**
     * Return's the product description.
     *
     * @return string The product description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set's the product description.
     *
     * @param string $description The product description
     *
     * @return void
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Return's the product's short description.
     *
     * @return string The product's short description
     */
    public function getShortDescription()
    {
        return $this->shortDescription;
    }

    /**
     * Set's the product's short description.
     *
     * @param string $shortDescription The product's short description
     *
     * @return void
     */
    public function setShortDescription($shortDescription)
    {
        $this->shortDescription = $shortDescription;
    }

    /**
     * Set's the UNIX timestamp with the start date the product is rated as new.
     *
     * @return integer The UNIX timestamp with the start date
     */
    public function getNewFrom()
    {
        return $this->newFrom;
    }

    /**
     * Return's the UNIX timestamp with the start date the product is rated as new.
     *
     * @param integer $newFrom The UNIX timestamp with the start date
     *
     * @return void
     */
    public function setNewFrom($newFrom)
    {
        $this->newFrom = $newFrom;
    }

    /**
     * Return's the UNIX timestamp with the end date the product is rated as new.
     *
     * @return integer The UNIX timestamp with the end date
     */
    public function getNewTo()
    {
        return $this->newTo;
    }

    /**
     * Set's the UNIX timestamp with the end date the product is rated as new.
     *
     * @param integer $newTo The UNIX timestamp with the end date
     *
     * @return void
     */
    public function setNewTo($newTo)
    {
        $this->newTo = $newTo;
    }

    /**
     * Return's the product's variants.
     *
     * @return \Doctrine\Common\Collections\ArrayCollection<\AppserverIo\Apps\Example\Entities\Impl\Product> The product's variants
     */
    public function getVariants()
    {
        return $this->variants;
    }

    /**
     * Add's the passed product to the variants.
     *
     * @param \AppserverIo\Apps\Example\Entities\Impl\Product $product The product to add to the variants
     *
     * @return void
     */
    public function addVariant($product)
    {
        $this->variants->add($product);
    }

    /**
     * Return's the product images.
     *
     * @return \Doctrine\Common\Collections\ArrayCollection<\AppserverIo\Apps\Example\Entities\Impl\ProductImage> The product images
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * Set's the product images.
     *
     * @param \Doctrine\Common\Collections\ArrayCollection<\AppserverIo\Apps\Example\Entities\Impl\ProductImage> $images The product images
     *
     * @return void
     */
    public function setImages($images)
    {
        $this->images = $images;
    }

    /**
     * Add's the passed product image to the images.
     *
     * @param \AppserverIo\Apps\Example\Entities\Impl\ProductImage $image The product image to add
     *
     * @return void
     */
    public function addImage($image)
    {
        $this->images->add($image);
    }

    /**
     * Remove's the passed product image from the product.
     *
     * @param \AppserverIo\Apps\Example\Entities\Impl\ProductImage $image The product image to remove
     *
     * @return void
     */
    public function removeImage($image)
    {
        $this->images->removeElement($image);
    }

    /**
     * Set's the passed product as parent product.
     *
     * @param \AppserverIo\Apps\Example\Entities\Impl\Product $parentProduct The parent product to set
     *
     * @return void
     */
    public function setParentProduct($parentProduct)
    {
        $this->parentProduct = $parentProduct;
    }
}

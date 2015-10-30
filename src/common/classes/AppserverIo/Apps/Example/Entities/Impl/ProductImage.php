<?php

/**
 * AppserverIo\Apps\Example\Entities\Impl\ProductImage
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
 * @ORM\Table(name="product_image")
 */
class ProductImage extends AbstractEntity
{

    /**
     * The unique ID of this entity.
     *
     * @var integer
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(name="id", type="integer", nullable=false)
     */
    protected $id;

    /**
     * The product ID the product image is bound to.
     *
     * @var integer
     * @ORM\Column(name="product_id", type="integer", nullable=false)
     */
    protected $productId;

    /**
     * The product image title.
     *
     * @var string
     * @ORM\Column(name="title", type="string", nullable=false)
     */
    protected $title;

    /**
     * The product image filename.
     *
     * @var string
     * @ORM\Column(name="filename", type="string", nullable=false)
     */
    protected $filename;

    /**
     * The product this product image is bound to.
     *
     * @var \AppserverIo\Apps\Example\Entities\Impl\Product
     * @ORM\ManyToOne(targetEntity="AppserverIo\Apps\Example\Entities\Impl\Product", inversedBy="images")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    protected $product;

    /**
     * Initializes the product image instance.
     */
    public function __construct()
    {
        $this->updateCreatedUpdatedDate();
    }

    /**
     * Return's the unique cart item ID.
     *
     * @return integer The unique cart item ID
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set's the unique cart item ID.
     *
     * @param integer $id The unique cart item ID
     *
     * @return void
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Return's the product ID the cart item is bound to.
     *
     * @return integer The product ID the cart item is bound to
     */
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * Set's the product ID the cart item is bound to.
     *
     * @param integer $productId The product ID the cart item is bound to
     *
     * @return void
     */
    public function setProductId($productId)
    {
        $this->productId = $productId;
    }

    /**
     * Returns the product image title.
     *
     * @return string The product image title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set's the product image title.
     *
     * @param string $title The product image title
     *
     * @return void
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Returns the product image filename.
     *
     * @return string The product image filename
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set's the product image filename.
     *
     * @param string $filename The product image filename
     *
     * @return void
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
    }

    /**
     * Return's the product this cart item is bound to.
     *
     * @return \AppserverIo\Apps\Example\Entities\Impl\Product The product the cart item is bound to
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set's the product this cart item is bound to.
     *
     * @param \AppserverIo\Apps\Example\Entities\Impl\Product $product The product the cart item is bound to
     *
     * @return void
     */
    public function setProduct($product)
    {
        $this->product = $product;
    }
}

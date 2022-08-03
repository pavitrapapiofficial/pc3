<?php
namespace PurpleCommerce\AdvancedWishlist\Plugin;

class ProductList
{

    protected $layout;

    public function __construct(
        \Magento\Framework\View\LayoutInterface $layout
    ) {
        $this->layout = $layout;
    }

    public function aroundGetProductDetailsHtml(
        \Magento\Catalog\Block\Product\ListProduct $subject,
        \Closure $proceed,
        \Magento\Catalog\Model\Product $product
    ) {
        // @codingStandardsIgnoreStart
        return $this->layout->createBlock('PurpleCommerce\AdvancedWishlist\Block\Index')
            ->setProduct($product)
            ->setTemplate('PurpleCommerce_AdvancedWishlist::catalog/list.phtml')
            ->toHtml();
        // @codingStandardsIgnoreEnd
    }
}

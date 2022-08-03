<?php
namespace PurpleCommerce\ProductEnquiry\Block;

class Index extends \Magento\Framework\View\Element\Template
{
    protected $helper;
    protected $imgHelper;
    protected $_productloader;
    protected $_registry;
    public function __construct(
        \PurpleCommerce\ProductEnquiry\Helper\Data $helperData,
        \Magento\Framework\Registry $registry,
        \Magento\Catalog\Helper\Image $imgData,
        \Magento\Catalog\Model\ProductFactory $_productloader,
        \Magento\Framework\View\Element\Template\Context $context
    ) {
        $this->helper = $helperData;
        $this->imgHelper = $imgData;
        $this->_registry = $registry;
        $this->_productloader = $_productloader;
        parent::__construct($context);
    }

    public function sayHello()
    {
        return __('Hello World');
    }
    public function isModuleEnabled()
    {
        return $this->helper->isModuleEnabled();
    }

    public function getCurrentCategory()
    {
        return $this->_registry->registry('current_category');
    }
    
    public function getCurrentProduct()
    {
        return $this->_registry->registry('current_product');
    }

    public function getProductData()
    {
        $currentProd=$this->_registry->registry('current_product');
        $product=$this->_productloader->create()->load($currentProd->getId());
        $imageUrl = $this->imgHelper->init($product, 'product_page_image_small')
                ->setImageFile($product->getSmallImage()) // image,small_image,thumbnail
                ->resize(100)
                ->getUrl();
        $result=[
            "name"=>$currentProd->getName(),
            "sku"=>$currentProd->getSku(),
            "img"=>$imageUrl
        ];
        return $result;
    }

    // @codingStandardsIgnoreStart
    public function getPopup()
    {
        $_product=$this->getCurrentProduct();
        $html="<div>
                <a href='#' id='click-me' class='action primary'>".$this->getButtonText()."</a>
                </div>

                <div id='popup-modal' style='display:none;'>
                    <div class='workwithus-popup-form'>
                        <div class='form-loader' id='form-loader' style='display:none'>
                            <img src='/mgdev/pub/static/frontend/Magento/luma/en_US/images/loader-1.gif'>
                        </div>
                        <form class='form-horizontal' method='post' enctype='multipart/form-data' action='cform/index/index'>
                        <div class='cus-pop-field'>
                        <p style='font-size: 13px;opacity: 0.6;'>".$this->getFromDesc()."</p>
                        </div>";
                        
        if ($this->showProdData()) {
            $prodData=$this->getProductData();
            $html.="<div class='cus-pop-img'>
                        <img src='".$prodData['img']."'>
                    </div>
                    <div class='cus-pop-para'>
                    <div>
                        Product Name:".$prodData['name']."
                    </div>
                    <div>
                        Product Sku:".$prodData['sku']."
                    </div></div>";
        }
        $customfileds='';
        if ($this->getCustomOne()!='') {
            $customfileds.="<div class='cus-pop-field'>
                                <input type='text' required name='customone' placeholder='".$this->getCustomOne()."' />
                            </div>";
        }
        if ($this->getCustomTwo()!='') {
            $customfileds.="<div class='cus-pop-field'>
                                <input type='text' required name='customtwo' placeholder='".$this->getCustomTwo()."' />
                            </div>";
        }
        if ($this->getCustomThree()!='') {
            $customfileds.="<div class='cus-pop-field'>
                                <input type='text' required name='customthree' placeholder='".$this->getCustomThree()."' />
                            </div>";
        }
        if ($this->getCustomFour()!='') {
            $customfileds.="<div class='cus-pop-field'>
                                <input type='text' required name='customfour' placeholder='".$this->getCustomFour()."' />
                            </div>";
        }
        
        $html.="<input type='hidden' name='pname' id='pname' value='".$prodData['name']."'>
        <input type='hidden' name='psku' id='psku' value='".$prodData['sku']."'>
                            <div class='cus-pop-field'>
                                <input type='text' id='pfullname' required name='fullname' placeholder='Full Name' />
                            </div>
                            <div class='cus-pop-field'>
                                <input type='tel' id='pphone' required name='phone' placeholder='Contact Number' />
                            </div>
                            <div class='cus-pop-field'>
                                <input type='email' id='pemail' required name='email' placeholder='Email' />
                            </div>
                            <div class='cus-pop-field'>
                                <input type='text' required id='psub' name='subject' placeholder='Subject' />
                            </div>
                            <div class='cus-pop-field'>
                                <input type='text' required id='pmsg' name='message' placeholder='Message' />
                            </div>".$customfileds."
                            <p id='error-m' style='color:red;display:none'>All fields are required.</p>
                            
                        </form>
                        <div class='cus-pop-field'>
                            <button class='cus-pop-field cus-pop-btn' id='final'>Submit</button>
                        </div>
                    </div>
                </div>
                <div class='pi-success-message' style='display:none'>
                    <div class='pi-success-message-main'>
                        <a class='cus-pop-clos'>X</a>
                        <div class='pi-success-iiner'>
                        </div>
                    </div> 
                </div>

                <script>
                    require(
                        [
                            'jquery',
                            'Magento_Ui/js/modal/modal'
                        ],
                        function(
                            $,
                            modal
                        ) {
                            var options = {
                                type: 'popup',
                                responsive: true,
                                innerScroll: true,
                                modalClass: 'pc-listing-modal',
                                title: '".$this->getFormTitle()."',
                                buttons: [{
                                    
                                }]
                            };

                            var popup = modal(options, $('#popup-modal'));
                            $('#click-me').on('click',function(){ 
                                $('#popup-modal').modal('openModal');
                            });

                            $('.cus-pop-btn').on('click',function(e){ 
                                $('#form-loader').show();
                                var vals=[];
                                vals.push(
                                    $('#pfullname').val(),
                                    $('#pphone').val(),
                                    $('#pemail').val(),
                                    $('#psub').val(),
                                    $('#pmsg').val(),
                                    $('#cusone').val(),
                                    $('#custwo').val(),
                                    $('#custhree').val(),
                                    $('#cusfour').val()
                                );
                                
                                
                                if(vals.includes('')){
                                    // alert('if');
                                    $('#error-m').show();
                                }else{
                                    // alert('else');
                                    $('#error-m').hide();
                                    e.preventDefault();
                                    // console.log(vals);
                                    $.ajax({
                                        type: 'post',
                                        url: '/mgdev/pub/submitenquiry/index/index',
                                        data: $('form').serialize(),
                                        success: function (result) {
                                            $('#form-loader').hide();
                                            $('#popup-modal').modal('closeModal');
                                            // $('.pi-success-iiner').html('<h2>'+result+'</h2>');
                                            // $('.pi-success-message').show();
                                            $('.workwithus-popup-form').html(result);
                                            // this.closeModal();
                                        }
                                    });
                                }
                                
                            });
                            $('.cus-pop-clos').on('click',function(e){ 
                                $('.pi-success-message').hide();
                            });
                        }
                    );
                </script>";
        return $html;
    }
    // @codingStandardsIgnoreEnd

    public function isForAll()
    {
        return $this->helper->isForAll();
    }
    public function getFromDesc()
    {
        return $this->helper->getFromDesc();
    }

    public function isOnListing()
    {
        return $this->helper->isOnListing();
    }
    public function isOnDetail()
    {
        return $this->helper->isOnDetail();
    }
    public function visibleCategories()
    {
        return $this->helper->visibleCategories();
    }
    public function getButtonText()
    {
        return $this->helper->getButtonText();
    }
    public function getFormTitle()
    {
        if ($this->helper->getFormTitle()=='') {
            return 'Enquiry Form';
        } else {
            return $this->helper->getFormTitle();
        }
    }
    public function showProdData()
    {
        return $this->helper->showProdData();
    }
    public function getSuccessMsg()
    {
        return $this->helper->getSuccessMsg();
    }
    public function getCustomOne()
    {
        return $this->helper->getCustomOne();
    }
    public function getCustomTwo()
    {
        return $this->helper->getCustomTwo();
    }
    public function getCustomThree()
    {
        return $this->helper->getCustomThree();
    }
    public function getCustomFour()
    {
        return $this->helper->getCustomFour();
    }
    public function sendEmailToAdmin()
    {
        return $this->helper->sendEmailToAdmin();
    }
    public function getAdminEmail()
    {
        return $this->helper->getAdminEmail();
    }

    public function getCC()
    {
        return $this->helper->getCC();
    }
    public function isAutoRespEnable()
    {
        return $this->helper->isAutoRespEnable();
    }
    public function getAutoRespEmail()
    {
        return $this->helper->getAutoRespEmail();
    }
    public function autoRespMessage()
    {
        return $this->helper->autoRespMessage();
    }
}

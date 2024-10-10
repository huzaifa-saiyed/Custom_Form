<?php
namespace Kitchen365\CustomForm\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Json\Helper\Data as JsonHelper;
use Magento\Framework\Controller\Result\JsonFactory;

class Save extends Action
{
    protected $resultJsonFactory;
    protected $checkoutSession;
    protected $jsonHelper;

    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        CheckoutSession $checkoutSession,
        JsonHelper $jsonHelper
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->checkoutSession = $checkoutSession;
        $this->jsonHelper = $jsonHelper;
    }

    public function execute()
    {
        $result = $this->resultJsonFactory->create();

        $jsonData = $this->getRequest()->getContent();
        $data = json_decode($jsonData, true);
        
        $quote = $this->checkoutSession->getQuote(); 
        // echo '<pre>';
        // print_r($data);die;

        $quote->setHowManyYearsBusiness($data['how_many_years_business']);
        $quote->setHowDidYouHearAboutUs($data['how_did_you_hear_about_us']);
        $quote->setHasShowroom($data['has_showroom']);
        $quote->setHasSalesRep($data['has_sales_rep']);
        $quote->setSalesRepName($data['sales_rep_name']);
        $quote->save();
 
        $result->setData([
            'success' => true,
            'message' => 'Data saved successfully.'
        ]);

        return $result;
    }
}

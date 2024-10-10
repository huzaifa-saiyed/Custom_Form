<?php

namespace Kitchen365\CustomForm\Block\Adminhtml\Order;

use Magento\Backend\Block\Template;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Framework\Registry;

class View extends Template
{
    protected $orderRepository;
    protected $registry;

    public function __construct(
        Template\Context $context,
        OrderRepositoryInterface $orderRepository,
        Registry $registry, 
        array $data = []
    ) {
        $this->orderRepository = $orderRepository;
        $this->registry = $registry; 
        parent::__construct($context, $data);
    }

    public function getOrder()
    {
        return $this->registry->registry('sales_order');
    }

    public function getCustomData()
    {
        $order = $this->getOrder();
        return [
            'how_many_years_business' => $order->getHowManyYearsBusiness(),
            'how_did_you_hear_about_us' => $order->getHowDidYouHearAboutUs(),
            'sales_rep_name' => $order->getSalesRepName(),
            'has_showroom' => $order->getHasShowroom(),
            'has_sales_rep' => $order->getHasSalesRep()
        ];
    }
}

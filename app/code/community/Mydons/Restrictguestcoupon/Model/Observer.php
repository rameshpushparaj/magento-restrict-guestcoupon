<?php

class Mydons_Restrictguestcoupon_Model_Observer 
{

    public function limitGuestCouponUsage($observer) 
    {
        $rule = $observer['rule'];
        $quote = $observer['quote'];		
	#Mage::log("Limit free gift usage rule coupon code".$rule->getCode(),null,'guestcoupon.log');
        if ($rule->getId() && ($rule->getCode()!='')) {
            $userEmail = $quote->getCustomerEmail();
	    $previousOrdersWithCoupon = Mage::getModel('sales/order')->getCollection()
						->addAttributeToFilter('customer_email', array('eq'=> $userEmail))
						->addAttributeToFilter('applied_rule_ids', array('finset' => array($rule->getId())));
            if (sizeof($previousOrdersWithCoupon) >= 1) {
                $quote->setCouponCode('');
                $quote->collectTotals()->save();
                return false;
            }
        }
    }

}

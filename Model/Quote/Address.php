<?php

namespace JustShout\Gfs\Model\Quote;

use Magento\Quote\Model\Quote;

/**
 * Shipping Total rewrite
 *
 * @package   JustShout\Gfs
 * @author    JustShout <http://developer.justshoutgfs.com/>
 * @copyright JustShout - 2019
 */
class Address extends Quote\Address
{
    /**
     * Rewrite request to force base rates
     * 
     * @param Quote\Item\AbstractItem|null $item
     *
     * @return bool
     */
    public function requestShippingRates(\Magento\Quote\Model\Quote\Item\AbstractItem $item = null)
    {
        $found = parent::requestShippingRates($item);

        if ($this->getBaseShippingAmount()) {
            $this->setShippingAmount((float) $this->getBaseShippingAmount());
        }

        return $found;
    }
}

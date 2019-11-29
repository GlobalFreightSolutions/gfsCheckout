<?php

namespace JustShout\Gfs\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Delivery Methods Source
 *
 * @package   JustShout\Gfs
 * @author    JustShout <http://developer.justshoutgfs.com/>
 * @copyright JustShout - 2019
 */
class DeliveryMethods implements OptionSourceInterface
{
    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => 0,
                'label' => __('Standard'),
            ],
            [
                'value' => 1,
                'label' => __('Calendar'),
            ],
            [
                'value' => 2,
                'label' => __('Click & Collect'),
            ],
        ];
    }
}

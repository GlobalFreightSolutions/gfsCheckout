<?php

namespace JustShout\Gfs\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Delivery Types Source
 *
 * @package   JustShout\Gfs
 * @author    JustShout <http://developer.justshoutgfs.com/>
 * @copyright JustShout - 2019
 */
class DeliveryTypes implements OptionSourceInterface
{
    /**
     * Standard Delivery Alias
     */
    const METHOD_STANDARD = 'dmStandard';

    /**
     * Click And Collect alias
     */
    const METHOD_DROP_POINT = 'dmDropPoint';

    /**
     * Store
     */
    const METHOD_STORE = 'dmStore';

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => self::METHOD_STANDARD,
                'label' => __('Standard Delivery'),
            ],
            [
                'value' => self::METHOD_DROP_POINT,
                'label' => __('Collect Your Order'),
            ],
            [
                'value' => self::METHOD_STORE,
                'label' => __('Store'),
            ],
        ];
    }
}

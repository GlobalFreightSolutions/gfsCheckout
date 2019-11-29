<?php

namespace JustShout\Gfs\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Droppoint Sort Source
 *
 * @package   JustShout\Gfs
 * @author    JustShout <http://developer.justshoutgfs.com/>
 * @copyright JustShout - 2019
 */
class DroppointSort implements OptionSourceInterface
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
                'value' => 'carrier',
                'label' => __('Carrier'),
            ],
            [
                'value' => 'distance',
                'label' => __('Distance'),
            ],
        ];
    }
}

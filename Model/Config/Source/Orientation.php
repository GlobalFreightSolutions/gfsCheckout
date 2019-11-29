<?php

namespace JustShout\Gfs\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Orientation Source
 *
 * @package   JustShout\Gfs
 * @author    JustShout <http://developer.justshoutgfs.com/>
 * @copyright JustShout - 2019
 */
class Orientation implements OptionSourceInterface
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
                'value' => 'horizontal',
                'label' => __('Horizontal'),
            ],
            [
                'value' => 'vertical',
                'label' => __('Vertical'),
            ],
        ];
    }
}

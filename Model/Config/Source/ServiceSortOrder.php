<?php

namespace JustShout\Gfs\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Service Sort Order Source
 *
 * @package   JustShout\Gfs
 * @author    JustShout <http://developer.justshoutgfs.com/>
 * @copyright JustShout - 2019
 */
class ServiceSortOrder implements OptionSourceInterface
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
                'value' => 'cheapestFirst',
                'label' => __('Cheapest First'),
            ],
            [
                'value' => 'fastestFirst',
                'label' => __('Fastest First'),
            ],[

                'value' => 'expensiveFirst',
                'label' => __('Expensive First'),
            ],
            [
                'value' => 'slowestFirst',
                'label' => __('Slowest First'),
            ],
        ];
    }
}

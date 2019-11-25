<?php

namespace JustShout\Gfs\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Dimension Source
 *
 * @package   JustShout\Gfs
 * @author    JustShout <http://developer.justshoutgfs.com/>
 * @copyright JustShout - 2019
 */
class DimensionUnits implements OptionSourceInterface
{
    /**
     * Centimeters
     */
    const CENTIMETERS = 'diCentimeters';

    /**
     * Meters
     */
    const METERS = 'diMeters';

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => self::CENTIMETERS,
                'label' => __('Centimeters (cm)'),
            ],
            [
                'value' => self::METERS,
                'label' => __('Meters (m)'),
            ]
        ];
    }
}

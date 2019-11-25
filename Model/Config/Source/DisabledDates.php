<?php

namespace JustShout\Gfs\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Disabled Dates Source
 *
 * @package   JustShout\Gfs
 * @author    JustShout <http://developer.justshoutgfs.com/>
 * @copyright JustShout - 2019
 */
class DisabledDates implements OptionSourceInterface
{
    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function toOptionArray()
    {
        $options = [];
        for ($i = 1; $i <= 31; $i++) {
            $options[] = [
                'value' => $i,
                'label' => $i,
            ];
        }

        return $options;
    }
}

<?php

namespace JustShout\Gfs\Block\Adminhtml\System\Config\CustomFields;

use Magento\Framework\View\Element\Html\Select;

/**
 * Day Labels Dropdown Block
 *
 * @package   JustShout\Gfs
 * @author    JustShout <http://developer.justshoutgfs.com/>
 * @copyright JustShout - 2019
 */
class DayLabels extends Select
{
    /**
     * Set Input Name
     *
     * @param string $value
     *
     * @return $this
     */
    public function setInputName($value)
    {
        return $this->setName($value);
    }

    /**
     * Set dropdown options before render
     *
     * @return string
     */
    protected function _toHtml()
    {
        if (!$this->getOptions()) {
            $this->addOption(null, null);
            foreach ($this->_getDayLabelOptions() as $option) {
                $this->addOption($option, $option);
            }
        }

        return parent::_toHtml();
    }

    /**
     * Get Options
     *
     * @return array
     */
    protected function _getDayLabelOptions()
    {
        return [
            'Su',
            'Mo',
            'Tu',
            'We',
            'Th',
            'Fr',
            'Sa',
        ];
    }
}

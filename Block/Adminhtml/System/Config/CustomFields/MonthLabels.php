<?php

namespace JustShout\Gfs\Block\Adminhtml\System\Config\CustomFields;

use Magento\Framework\View\Element\Html\Select;

/**
 * Month Labels Dropdown Block
 *
 * @package   JustShout\Gfs
 * @author    JustShout <http://developer.justshoutgfs.com/>
 * @copyright JustShout - 2019
 */
class MonthLabels extends Select
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
            foreach ($this->_getMonthLabelOptions() as $option) {
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
    protected function _getMonthLabelOptions()
    {
        return [
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            'July',
            'August',
            'September',
            'October',
            'November',
            'December',
        ];
    }
}

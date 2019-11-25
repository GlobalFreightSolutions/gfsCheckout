<?php

namespace JustShout\Gfs\Block\Adminhtml\System\Config\CustomFields;

use Magento\Framework\View\Element\Html\Select;

/**
 * Data Types Dropdown Block
 *
 * @package   JustShout\Gfs
 * @author    JustShout <http://developer.justshoutgfs.com/>
 * @copyright JustShout - 2019
 */
class Type extends Select
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
            foreach ($this->_getTypeOptions() as $value => $label) {
                $this->addOption($value, $label);
            }
        }

        return parent::_toHtml();
    }

    /**
     * Get Data Type Options
     *
     * @return array
     */
    protected function _getTypeOptions()
    {
        return [
            'Integer' => __('Integer (Number)'),
            'double'  => __('Double (Decimal)'),
            'String'  => __('String (Text)'),
        ];
    }
}

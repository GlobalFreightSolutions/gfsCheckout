<?php

namespace JustShout\Gfs\Block\Adminhtml\System\Config;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

/**
 * Header Title Block
 *
 * @package   JustShout\Gfs
 * @author    JustShout <http://developer.justshoutgfs.com/>
 * @copyright JustShout - 2019
 */
class Header extends Field
{
    /**
     * {@inheritdoc}
     *
     * @var string
     */
    protected $_template = 'JustShout_Gfs::system/config/header.phtml';

    /**
     * Element Label
     *
     * @var string
     */
    protected $_label;

    /**
     * This method will render the gfs info block in the system config
     *
     * @param AbstractElement $element
     *
     * @return string
     */
    public function render(AbstractElement $element)
    {
        $columns = $this->getRequest()->getParam('website') || $this->getRequest()->getParam('store') ? 5 : 4;
        $this->setLabel($element->getLabel());
        $html = $this->toHtml();

        return $this->_decorateRowHtml($element, sprintf('<td colspan="%d">%s</td>',
            $columns,
            $html
        ));
    }

    /**
     * Set label in header
     *
     * @param string $label
     *
     * @return $this
     */
    public function setLabel($label)
    {
        $this->_label = $label;

        return $this;
    }

    /**
     * Get the label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->_label;
    }
}

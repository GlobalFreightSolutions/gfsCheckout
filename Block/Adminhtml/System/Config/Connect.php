<?php

namespace JustShout\Gfs\Block\Adminhtml\System\Config;

use Magento\Backend\Block\Widget\Button;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

/**
 * System Config Connect Block
 *
 * @package   JustShout\Gfs
 * @author    JustShout <http://developer.justshoutgfs.com/>
 * @copyright JustShout - 2019
 */
class Connect extends Field
{
    /**
     * {@inheritdoc}
     *
     * @var string
     */
    protected $_template = 'JustShout_Gfs::system/config/connect.phtml';

    /**
     * Remove scope label
     *
     * @param  AbstractElement $element
     *
     * @return string
     */
    public function render(AbstractElement $element)
    {
        $element->unsScope()->unsCanUseWebsiteValue()->unsCanUseDefaultValue();

        $columns = $this->getRequest()->getParam('website') || $this->getRequest()->getParam('store') ? 5 : 4;

        $html = sprintf('<tr id="row_%s_message"><td colspan="%d"><div id="test_gfs_connection_result" class="message" style="display:none;"></div></td></tr>',
            $element->getHtmlId(),
            $columns
        );

        $html .= parent::render($element);

        return $html;
    }

    /**
     * Return element html
     *
     * @param  AbstractElement $element
     *
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        return $this->_toHtml();
    }

    /**
     * Return ajax url for connection button
     *
     * @return string
     */
    public function getAjaxUrl()
    {
        return $this->getUrl('gfs/system/connection');
    }

    /**
     * Generate collect button html
     *
     * @return string
     */
    public function getButtonHtml()
    {
        /** @var Button $button */
        $button = $this->getLayout()->createBlock(Button::class);
        $button->setData([
            'id'    => $this->getButtonIdAttr(),
            'label' => __('Test Connection'),
        ]);

        return $button->toHtml();
    }

    /**
     * Get Button Id Attribute
     *
     * @return string
     */
    public function getButtonIdAttr()
    {
        return 'test_gfs_connection';
    }
}

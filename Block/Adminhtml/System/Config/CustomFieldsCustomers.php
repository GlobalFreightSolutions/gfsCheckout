<?php

namespace JustShout\Gfs\Block\Adminhtml\System\Config;

/**
 * Customer Custom Fields Block
 *
 * @package   JustShout\Gfs
 * @author    JustShout <http://developer.justshoutgfs.com/>
 * @copyright JustShout - 2019
 */
class CustomFieldsCustomers extends AbstractCustomFields
{
    /**
     * {@inheritdoc}
     */
    protected function _getValueRenderer()
    {
        if (!$this->_valueRenderer) {
            $this->_valueRenderer = $this->getLayout()->createBlock(CustomFields\CustomerAttributes::class, null, [
                'data' => [
                    'is_render_to_js_template' => true
                ]
            ]);
        }

        return $this->_valueRenderer;
    }
}

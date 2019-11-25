<?php

namespace JustShout\Gfs\Block\Adminhtml\System\Config;

/**
 * Order Item/Product Custom Fields Block
 *
 * @package   JustShout\Gfs
 * @author    JustShout <http://developer.justshoutgfs.com/>
 * @copyright JustShout - 2019
 */
class CustomFieldsItems extends AbstractCustomFields
{
    /**
     * {@inheritdoc}
     */
    protected function _getValueRenderer()
    {
        if (!$this->_valueRenderer) {
            $this->_valueRenderer = $this->getLayout()->createBlock(CustomFields\ProductAttributes::class, null, [
                'data' => [
                    'is_render_to_js_template' => true
                ]
            ]);
        }

        return $this->_valueRenderer;
    }
}

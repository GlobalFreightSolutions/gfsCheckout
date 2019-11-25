<?php

namespace JustShout\Gfs\Block\Adminhtml\System\Config;

use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use Magento\Framework\DataObject;
use Magento\Framework\View\Element\BlockInterface;

/**
 * Month Labels Custom Fields Block
 *
 * @package   JustShout\Gfs
 * @author    JustShout <http://developer.justshoutgfs.com/>
 * @copyright JustShout - 2019
 */
class MonthLabels extends AbstractFieldArray
{
    /**
     * Indication whether block is prepared to render or no
     *
     * @var bool
     */
    protected $_isPreparedToRender = false;

    /**
     * Month Dropdown Block
     *
     * @var CustomFields\MonthLabels|BlockInterface
     */
    protected $_monthRenderer;

    /**
     * {@inheritdoc}
     */
    protected function _prepareToRender()
    {
        $this->addColumn('month', [
            'label'    => __('Month'),
            'renderer' => $this->_getMonthRenderer()
        ]);

        $this->addColumn('label', [
            'label'    => __('Label')
        ]);

        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add Month Label');
    }

    /**
     * {@inheritdoc}
     */
    protected function _getMonthRenderer()
    {
        if (!$this->_monthRenderer) {
            $this->_monthRenderer = $this->getLayout()->createBlock(CustomFields\MonthLabels::class, null, [
                'data' => [
                    'is_render_to_js_template' => true
                ]
            ]);
        }

        return $this->_monthRenderer;
    }

    /**
     * Set row data
     *
     * @param DataObject $row
     *
     * @return $this
     */
    protected function _prepareArrayRow(DataObject $row)
    {
        $options = [];
        $typeKey = 'option_' . $this->_getMonthRenderer()->calcOptionHash($row->getData('month'));
        $options[$typeKey] = 'selected="selected"';
        $row->setData('option_extra_attrs', $options);

        return $this;
    }
}

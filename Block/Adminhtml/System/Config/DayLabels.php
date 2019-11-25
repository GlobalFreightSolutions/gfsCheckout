<?php

namespace JustShout\Gfs\Block\Adminhtml\System\Config;

use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use Magento\Framework\DataObject;
use Magento\Framework\View\Element\BlockInterface;

/**
 * Day Labels Custom Fields Block
 *
 * @package   JustShout\Gfs
 * @author    JustShout <http://developer.justshoutgfs.com/>
 * @copyright JustShout - 2019
 */
class DayLabels extends AbstractFieldArray
{
    /**
     * Indication whether block is prepared to render or no
     *
     * @var bool
     */
    protected $_isPreparedToRender = false;

    /**
     * Day Dropdown Block
     *
     * @var CustomFields\DayLabels|BlockInterface
     */
    protected $_dayRenderer;

    /**
     * {@inheritdoc}
     */
    protected function _prepareToRender()
    {
        $this->addColumn('day', [
            'label'    => __('Day'),
            'renderer' => $this->_getDayRenderer()
        ]);

        $this->addColumn('label', [
            'label'    => __('Label')
        ]);

        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add Day Label');
    }

    /**
     * {@inheritdoc}
     */
    protected function _getDayRenderer()
    {
        if (!$this->_dayRenderer) {
            $this->_dayRenderer = $this->getLayout()->createBlock(CustomFields\DayLabels::class, null, [
                'data' => [
                    'is_render_to_js_template' => true
                ]
            ]);
        }

        return $this->_dayRenderer;
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
        $typeKey = 'option_' . $this->_getDayRenderer()->calcOptionHash($row->getData('day'));
        $options[$typeKey] = 'selected="selected"';
        $row->setData('option_extra_attrs', $options);

        return $this;
    }
}

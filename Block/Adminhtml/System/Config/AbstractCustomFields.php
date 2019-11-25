<?php

namespace JustShout\Gfs\Block\Adminhtml\System\Config;

use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use Magento\Framework\DataObject;
use Magento\Framework\View\Element\BlockInterface;

/**
 * Abstract Custom Field Block
 *
 * @package   JustShout\Gfs
 * @author    JustShout <http://developer.justshoutgfs.com/>
 * @copyright JustShout - 2019
 */
abstract class AbstractCustomFields extends AbstractFieldArray
{
    /**
     * {@inheritdoc}
     *
     * @var string
     */
    protected $_template = 'JustShout_Gfs::system/config/custom-fields.phtml';

    /**
     * Type Dropdown Block
     *
     * @var CustomFields\Type|BlockInterface
     */
    protected $_typeRenderer;

    /**
     * Value Dropdown Block
     *
     * @var CustomFields\Type|BlockInterface
     */
    protected $_valueRenderer;

    /**
     * Custom Fields Available
     *
     * @var int
     */
    protected $_maxRows = 3;

    /**
     * Get Custom Fields Available
     *
     * @return int
     */
    public function getMaxRows()
    {
        return $this->_maxRows;
    }

    /**
     * {@inheritdoc}
     */
    protected function _prepareToRender()
    {
        $this->addColumn('type', [
            'label'    => __('Type'),
            'renderer' => $this->_getTypeRenderer()
        ]);

        $this->addColumn('value', [
            'label'    => __('Value'),
            'renderer' => $this->_getValueRenderer()
        ]);

        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add Custom Field');
    }

    /**
     * Get Custom Field Type Dropdown
     *
     * @return CustomFields\Type|BlockInterface
     */
    protected function _getTypeRenderer()
    {
        if (!$this->_typeRenderer) {
            $this->_typeRenderer = $this->getLayout()->createBlock(CustomFields\Type::class, null, [
                'data' => [
                    'is_render_to_js_template' => true
                ]
            ]);
        }

        return $this->_typeRenderer;
    }

    /**
     * Get the value dropdown
     *
     * @return BlockInterface
     */
    abstract protected function _getValueRenderer();

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
        $typeKey = 'option_' . $this->_getTypeRenderer()->calcOptionHash($row->getData('type'));
        $options[$typeKey] = 'selected="selected"';
        $valueKey = 'option_' . $this->_getValueRenderer()->calcOptionHash($row->getData('value'));
        $options[$valueKey] = 'selected="selected"';
        $row->setData('option_extra_attrs', $options);

        return $this;
    }
}

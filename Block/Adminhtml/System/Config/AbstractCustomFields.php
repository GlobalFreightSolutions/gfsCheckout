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
    protected $_maxRows = 10;

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
        ]);

        $this->addColumn('value', [
            'label'    => __('Value'),
            'renderer' => $this->_getValueRenderer()
        ]);

        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add Custom Field');
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
//        $typeKey = 'option_' . $this->_getTypeRenderer()->calcOptionHash($row->getData('type'));
//        $options[$typeKey] = 'selected="selected"';
        $valueKey = 'option_' . $this->_getValueRenderer()->calcOptionHash($row->getData('value'));
        $options[$valueKey] = 'selected="selected"';
        $row->setData('option_extra_attrs', $options);

        return $this;
    }
}

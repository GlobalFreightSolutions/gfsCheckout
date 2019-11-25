<?php

namespace JustShout\Gfs\Block\Adminhtml\System\Config;

use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\Module\ModuleListInterface;

/**
 * System Config Information Block
 *
 * @package   JustShout\Gfs
 * @author    JustShout <http://developer.justshoutgfs.com/>
 * @copyright JustShout - 2019
 */
class Information extends Field
{
    /**
     * Checkout Configuration Login Link
     */
    const GFS_CHECKOUT_LOGIN = 'https://checkout.justshoutgfs.com/';

    /**
     * Checkout Configuration Register Link
     */
    const GFS_CHECKOUT_REGISTER = 'https://register.gfsdeliver.com/';

    /**
     * Module List
     *
     * @var ModuleListInterface
     */
    protected $_moduleList;

    /**
     * {@inheritdoc}
     *
     * @var string
     */
    protected $_template = 'JustShout_Gfs::system/config/information.phtml';

    /**
     * Information constructor
     *
     * @param Context             $context
     * @param ModuleListInterface $moduleList
     * @param array               $data
     */
    public function __construct(
        Context             $context,
        ModuleListInterface $moduleList,
        array               $data = []
    ) {
        parent::__construct($context, $data);
        $this->_moduleList = $moduleList;
    }

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
        $html = $this->toHtml();

        return $this->_decorateRowHtml($element, sprintf('<td colspan="%d">%s</td>',
            $columns,
            $html
        ));
    }

    /**
     * Gfs Logo
     *
     * @return string
     */
    public function getLogoSrc()
    {
        return $this->getViewFileUrl('JustShout_Gfs::images/logo.png');
    }

    /**
     * Get Current Module Version
     *
     * @return string
     */
    public function getModuleVersion()
    {
        $moduleName = parent::getModuleName();

        return $this->_moduleList->getOne($moduleName)['setup_version'];
    }

    /**
     * Gfs Checkout Login Portal
     *
     * @return string
     */
    public function getCheckoutLoginUrl()
    {
        return self::GFS_CHECKOUT_LOGIN;
    }

    /**
     * Gfs Checkout Register
     *
     * @return string
     */
    public function getCheckoutRegisterUrl()
    {
        return self::GFS_CHECKOUT_REGISTER;
    }
}

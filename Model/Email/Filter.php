<?php

namespace JustShout\Gfs\Model\Email;

use Magento\Email\Model\Template;

/**
 * Email Filter Shipping Description
 *
 * @package   JustShout\Gfs
 * @author    JustShout <http://developer.justshoutgfs.com/>
 * @copyright JustShout - 2019
 */
class Filter extends Template\Filter
{
    /**
     * Override the varDirective method so that when the shipping description is being rendered on the email, the
     * modifier `raw` is added so that html can be included on the email.
     *
     * @param string[] $construction
     *
     * @return string
     */
    public function varDirective($construction)
    {
        // just return the escaped value if no template vars exist to process
        if (count($this->templateVars) == 0) {
            return $construction[0];
        }
        list($directive, $modifiers) = $this->explodeModifiers($construction[2], 'escape');
        if (trim($directive) === 'order.getShippingDescription()') {
            $modifiers = 'raw';
        }

        return $this->applyModifiers($this->getVariable($directive, ''), $modifiers);
    }
}

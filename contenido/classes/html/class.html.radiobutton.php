<?php
/**
 * This file contains the cHTMLRadiobutton class.
 *
 * @package Core
 * @subpackage HTML
 * @version SVN Revision $Rev:$
 *
 * @author Simon Sprankel
 * @copyright four for business AG <www.4fb.de>
 * @license http://www.contenido.org/license/LIZENZ.txt
 * @link http://www.4fb.de
 * @link http://www.contenido.org
 */

if (!defined('CON_FRAMEWORK')) {
    die('Illegal call');
}

/**
 * cHTMLRadiobutton class represents a radio button.
 *
 * @package Core
 * @subpackage Frontend
 */
class cHTMLRadiobutton extends cHTMLFormElement {

    /**
     * Values for the check box
     *
     * @var string
     */
    protected $_value;

    /**
     * The text for the corresponding label
     *
     * @var string
     */
    protected $_labelText;

    /**
     * Constructor.
     * Creates an HTML radio button element.
     *
     * @param string $name Name of the element
     * @param string $value Value of the radio button
     * @param string $id ID of the element
     * @param bool $checked Is element checked?
     * @param string $disabled Item disabled flag (non-empty to set disabled)
     * @param string $tabindex Tab index for form elements
     * @param string $accesskey Key to access the field
     * @param string $class the class of this element
     * @return void
     */
    public function __construct($name, $value, $id = '', $checked = false, $disabled = false, $tabindex = null, $accesskey = '', $class = '') {
        parent::__construct($name, $id, $disabled, $tabindex, $accesskey);
        $this->_tag = 'input';
        $this->_value = $value;
        $this->_contentlessTag = true;

        $this->setChecked($checked);
        $this->updateAttribute('type', 'radio');
        $this->updateAttribute('value', $value);
        $this->setClass($class);
    }

    /**
     *
     * @deprecated [2012-01-19] use __construct instead
     */
    public function cHTMLRadiobutton($name, $value, $id = '', $checked = false, $disabled = false, $tabindex = null, $accesskey = '') {
        cDeprecated('Use __construct() instead');
        $this->__construct($name, $value, $id, $checked, $disabled, $tabindex, $accesskey);
    }

    /**
     * Sets the checked flag.
     *
     * @param bool $checked If true, the "checked" attribute will be assigned.
     * @return cHTMLRadiobutton $this
     */
    public function setChecked($checked) {
        if ($checked == true) {
            return $this->updateAttribute('checked', 'checked');
        } else {
            return $this->removeAttribute('checked');
        }
    }

    /**
     * Sets a custom label text
     *
     * @param string $text Text to display
     * @return cHTMLRadiobutton $this
     */
    public function setLabelText($text) {
        $this->_labelText = $text;

        return $this;
    }

    /**
     * Renders the option element.
     * Note:
     *
     * If this element has an ID, the value (which equals the text displayed)
     * will be rendered as seperate HTML label, if not, it will be displayed
     * as regular text. Displaying the value can be turned off via the
     * parameter.
     *
     * @param bool $renderlabel If true, renders a label
     * @return string Rendered HTML
     */
    public function toHtml($renderLabel = true) {
        $attributes = $this->getAttributes(true);

        if ($renderLabel == false) {
            return $this->fillSkeleton($attributes);
        }

        $id = $this->getAttribute('id');

        $renderedLabel = '';

        if ($id != '') {
            $label = new cHTMLLabel($this->_value, $this->getAttribute('id'));

            if ($this->_labelText != '') {
                $label->text = $this->_labelText;
            }

            $renderedLabel = $label->toHtml();
        } else {
            $renderedLabel = $this->_value;
        }

        return $this->fillSkeleton($attributes) . $renderedLabel;
    }

}
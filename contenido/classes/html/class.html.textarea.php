<?php
/**
 * This file contains the cHTMLTextarea class.
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
 * cHTMLTextarea class represents a textarea.
 *
 * @package Core
 * @subpackage Frontend
 */
class cHTMLTextarea extends cHTMLFormElement {

    protected $_value;

    /**
     * Constructor.
     * Creates an HTML text area.
     *
     * If no additional parameters are specified, the
     * default width is 60 chars, and the height is 5 chars.
     *
     * @param string $name Name of the element
     * @param string $initvalue Initial value of the textarea
     * @param int $width width of the textarea
     * @param int $height height of the textarea
     * @param string $id ID of the element
     * @param string $disabled Item disabled flag (non-empty to set disabled)
     * @param string $tabindex Tab index for form elements
     * @param string $accesskey Key to access the field
     * @param string $class the class of this element
     * @return void
     */
    public function __construct($name, $initvalue = '', $width = '', $height = '', $id = '', $disabled = false, $tabindex = null, $accesskey = '', $class = '') {
        parent::__construct($name, $id, $disabled, $tabindex, $accesskey);
        $this->_tag = 'textarea';
        $this->setValue($initvalue);
        $this->_contentlessTag = false;
        $this->setWidth($width);
        $this->setHeight($height);
        $this->setClass($class);
    }

    /**
     *
     * @deprecated [2012-01-19] use __construct instead
     */
    public function cHTMLTextarea($name, $initvalue = '', $width = '', $height = '', $id = '', $disabled = false, $tabindex = null, $accesskey = '') {
        cDeprecated('Use __construct() instead');
        $this->__construct($name, $initvalue, $width, $height, $id, $disabled, $tabindex, $accesskey);
    }

    /**
     * Sets the width of the text box.
     *
     * @param int $width width of the text box
     * @return cHTMLTextarea $this
     */
    public function setWidth($width) {
        $width = intval($width);

        if ($width <= 0) {
            $width = 50;
        }

        return $this->updateAttribute('cols', $width);
    }

    /**
     * Sets the maximum input length of the text box.
     *
     * @param int $maxlen maximum input length
     * @return cHTMLTextarea $this
     */
    public function setHeight($height) {
        $height = intval($height);

        if ($height <= 0) {
            $height = 5;
        }

        return $this->updateAttribute('rows', $height);
    }

    /**
     * Sets the initial value of the text box.
     *
     * @param string $value Initial value
     * @return cHTMLTextarea $this
     */
    public function setValue($value) {
        $this->_value = $value;

        return $this;
    }

    /**
     * Renders the textarea
     *
     * @return string Rendered HTML
     */
    public function toHtml() {
        $this->_setContent($this->_value);

        return parent::toHTML();
    }

}
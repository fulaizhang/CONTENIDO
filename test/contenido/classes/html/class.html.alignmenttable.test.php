<?php

/**
 *
 * @author claus.schunk@4fb.de
 * @author marcus.gnass@4fb.de
 * @copyright four for business AG <www.4fb.de>
 * @license http://www.contenido.org/license/LIZENZ.txt
 * @link http://www.4fb.de
 * @link http://www.contenido.org
 */

/**
 *
 * @author claus.schunk@4fb.de
 * @author marcus.gnass@4fb.de
 */
class cHTMLAlignmentTableTest extends cTestingTestCase {

    /**
     *
     * @var cHTMLAlignmentTable
     */
    private $_tableEmpty;

    /**
     *
     * @var cHTMLAlignmentTable
     */
    private $_tableInt;

    /**
     *
     * @var cHTMLAlignmentTable
     */
    private $_tableFloat;

    /**
     *
     * @var cHTMLAlignmentTable
     */
    private $_tableEmptyString;

    /**
     *
     * @var cHTMLAlignmentTable
     */
    private $_tableString;

    /**
     *
     * @var cHTMLAlignmentTable
     */
    private $_tableBool;

    /**
     *
     * @var cHTMLAlignmentTable
     */
    private $_tableNull;

    /**
     *
     * @var cHTMLAlignmentTable
     */
    private $_tableObject;

    /**
     *
     * @var cHTMLAlignmentTable
     */
    private $_tableData;

    /**
     * Creates tables with values of different datatypes.
     */
    protected function setUp(): void {
        ini_set('display_errors', true);
        error_reporting(E_ALL);

        $this->_tableEmpty = new cHTMLAlignmentTable();
        $this->_tableInt = new cHTMLAlignmentTable(0);
        $this->_tableFloat = new cHTMLAlignmentTable(1.0);
        $this->_tableEmptyString = new cHTMLAlignmentTable('');
        $this->_tableString = new cHTMLAlignmentTable(' foo ');
        $this->_tableBool = new cHTMLAlignmentTable(true);
        $this->_tableNull = new cHTMLAlignmentTable(null);
        $this->_tableObject = new cHTMLAlignmentTable(new stdClass());
        $this->_tableData = new cHTMLAlignmentTable(0, 1.0, '', ' foo ', true, null, new stdClass());
    }

    /**
     * Test constructor which sets the member $_tag.
     * Is already tested by test of parent class!
     */
    public function testConstructTag() {
        $act = $this->_readAttribute($this->_tableEmpty, '_tag');
        $exp = 'table';
        $this->assertSame($exp, $act);
    }

    /**
     * Test constructor which sets the member $_contentlessTag.
     */
    public function testConstructContentlessTag() {
        $act = $this->_readAttribute($this->_tableEmpty, '_contentlessTag');
        $exp = false;
        $this->assertSame($exp, $act);
    }

    /**
     * Test constructor which sets the member $_data.
     */
    public function testConstructData() {
        $act = $this->_readAttribute($this->_tableEmpty, '_data');
        $this->assertSame(true, is_array($act));
        $this->assertEmpty(array_diff([], $act));

        $act = $this->_readAttribute($this->_tableInt, '_data');
        $this->assertSame(true, is_array($act));
        $this->assertEmpty(array_diff([0], $act));

        $act = $this->_readAttribute($this->_tableFloat, '_data');
        $this->assertSame(true, is_array($act));
        $this->assertEmpty(array_diff([1.0], $act));

        $act = $this->_readAttribute($this->_tableEmptyString, '_data');
        $this->assertSame(true, is_array($act));
        $this->assertEmpty(array_diff([''], $act));

        $act = $this->_readAttribute($this->_tableString, '_data');
        $this->assertSame(true, is_array($act));
        $this->assertEmpty(array_diff([' foo '], $act));

        $act = $this->_readAttribute($this->_tableBool, '_data');
        $this->assertSame(true, is_array($act));
        $this->assertEmpty(array_diff([true], $act));

        $act = $this->_readAttribute($this->_tableNull, '_data');
        $this->assertSame(true, is_array($act));
        $this->assertEmpty(array_diff([null], $act));

        $act = $this->_readAttribute($this->_tableObject, '_data');
        $this->assertSame(true, is_array($act));
        $this->assertTrue([new stdClass()] == $act);

        $act = $this->_readAttribute($this->_tableData, '_data');
        $this->assertSame(true, is_array($act));
        $this->assertTrue([0, 1.0, '', ' foo ', true, null, new stdClass()] == $act);
    }

    /**
     * Tests rendering of empty table.
     */
    public function testRenderEmpty() {
        // $table = new cHTMLAlignmentTable();
        // $this->assertSame($table->render(), $table->toHtml());
        $act = $this->_tableEmpty->render();
        $exp = '<table cellpadding="0" cellspacing="0"><tr></tr></table>';
        $this->assertSame($exp, $act);
    }

    /**
     * Tests rendering of table w/ int value.
     */
    public function testRenderInt() {
        $act = $this->_tableInt->render();
        $exp = '<table cellpadding="0" cellspacing="0"><tr><td>0</td></tr></table>';
        $this->assertSame($exp, $act);
    }

    /**
     * Tests rendering of table w/ float value.
     */
    public function testRenderFloat() {
        $act = $this->_tableFloat->render();
        $exp = '<table cellpadding="0" cellspacing="0"><tr><td>1.0</td></tr></table>';
        $this->assertSame($exp, $act);
    }

    /**
     * Tests rendering of table w/ empty string.
     */
    public function testRenderEmptyString() {
        $act = $this->_tableEmptyString->render();
        $exp = '<table cellpadding="0" cellspacing="0"><tr><td></td></tr></table>';
        $this->assertSame($exp, $act);
    }

    /**
     * Tests rendering of table w/ string value.
     */
    public function testRenderString() {
        $act = $this->_tableString->render();
        $exp = '<table cellpadding="0" cellspacing="0"><tr><td> foo </td></tr></table>';
        $this->assertSame($exp, $act);
    }

    /**
     * Tests rendering of table w/ bool value.
     */
    public function testRenderBool() {
        $act = $this->_tableBool->render();
        $exp = '<table cellpadding="0" cellspacing="0"><tr><td>1</td></tr></table>';
        $this->assertSame($exp, $act);
    }

    /**
     * Tests rendering of table w/ NULL value.
     */
    public function testRenderNull() {
        $act = $this->_tableNull->render();
        $exp = '<table cellpadding="0" cellspacing="0"><tr><td></td></tr></table>';
        $this->assertSame($exp, $act);
    }

    /**
     * Tests rendering of table w/ object.
     */
    public function testRenderObject() {
        $act = $this->_tableObject->render();
        $exp = '';
        $this->assertSame($exp, $act);
    }

    /**
     * Tests rendering of table w/ all values.
     */
    public function testRenderData() {
        $act = $this->_tableData->render();
        $exp = ''; // TODO this is not the expected value!
        $this->assertSame($exp, $act);
    }
}

?>
<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Validate
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */

/**
 * Validates that a value is greater than or equal to another value
 *
 * @category   Zend
 * @package    Zend_Validate
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Validate_GreaterThanOrEqualTo extends Zend_Validate_GreaterThan
{

    /**
     * @var array
     */
    protected $_messageTemplates = array(
        self::NOT_GREATER => "'%value%' is not greater than or equal to '%min%'",
    );

    /**
     * @var array
     */
    protected $_messageVariables = array(
        'min' => '_min'
    );

    /**
     * Defined by Zend_Validate_Interface
     *
     * Returns true if and only if $value is greater than or equal to min option
     *
     * @param  mixed $value
     * @return boolean
     */
    public function isValid($value)
    {
        $this->_setValue($value);

        if ($this->_min > $value) {
            $this->_error(self::NOT_GREATER);
            return false;
        }
        return true;
    }

}

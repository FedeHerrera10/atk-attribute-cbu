<?php

namespace Sintattica\Atk\Attributes;

use Sintattica\Atk\Core\Tools;
use Sintattica\Atk\Core\Language;
use Sintattica\Atk\Utils\IpUtils;

/**
 * The CUIT  attribute provides a widget to edit CUIT's in atk9
 *
 * @author Santiago Ottonello <sanotto@gmail.com>
 */
class CBUAttribute extends Attribute
{

    /**
     * Constructor.
     *
     * @param string $name attribute name
     * @param int $flags attribute flags.
     */
    public function __construct($name, $flags = 0)
    {
        parent::__construct($name, $flags);
        $this->setAttribSize(22);
    }

    /**
     * Fetch value.
     *
     * @param array $postvars post vars
     *
     * @return string fetched value
     */
    public function fetchValue($postvars)
    {
        $value = parent::fetchValue($postvars);
        return $value;
    }

    public function edit($record, $fieldprefix, $mode)
    {
        return parent::edit($record, $fieldprefix, $mode);
    }

    /**
     * Checks if the value is a valid YearMonth value.
     *
     * @param array $record The record that holds the value for this
     *                       attribute. If an error occurs, the error will
     *                       be stored in the 'atkerror' field of the record.
     * @param string $mode The mode for which should be validated ("add" or
     *                       "update")
     */
    public function validate(&$record, $mode)
    {
        // Check for valid ip string
        $strvalue = Tools::atkArrayNvl($record, $this->fieldName(), '');
        if ($strvalue != '' && $strvalue != '...') {
            if (!$this::isValidCBU($strvalue)) {
                Tools::triggerError($record, $this->fieldName(), 'CUIT No v&aacute;lido');
            }
        }
        parent::validate($record, $mode);
    }

    public static function isValidCBU($cuit)
    {
        //Tarea para la casa
        return true;
    }

    /**
     * Converts the internal attribute value to one that is understood by the
     * database.
     *
     * @param array $rec The record that holds this attribute's value.
     *
     * @return string The database compatible value
     */
    public function value2db($rec)
    {
        $value = Tools::atkArrayNvl($rec, $this->fieldName());
        return $value;
    }

}

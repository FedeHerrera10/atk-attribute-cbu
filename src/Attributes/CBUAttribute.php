<?php

namespace Sintattica\Atk\Attributes;

use Sintattica\Atk\Core\Tools;
use Sintattica\Atk\Core\Language;
use Sintattica\Atk\Utils\IpUtils;

/**
 * The CUIT  attribute provides a widget to edit CBU's in atk9
 *
 * @author Federico Herrera <herrera3299@gmail.com>
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
                Tools::triggerError($record, $this->fieldName(), 'CBU No v&aacute;lido');
            }
        }
        parent::validate($record, $mode);
    }

    /**
     * Obtiene si el cbu que se pasa como parametro es valido.
     *
     * @param $cbu Cbu.
     *
     * @return bool
     */
    public static function isValidCBU($cbu)
    {
            if ($cbu !=''){
            $banco_suc=substr($cbu,0,7);
            $nt_cuenta=substr($cbu,8,13);
            $div1=substr($cbu,7,1);
            $div2=substr($cbu,21,1);
            
            $digv_bloque1= self::bloque1($banco_suc);
            $digv_bloque2= self::bloque2($nt_cuenta);
	
            if($div1==$digv_bloque1 and $div2==$digv_bloque2){
            return true;
            }
          }
            return false;
     }
    
     /**
     * Calcula el primer digito verificador que corresponde a Banco,Sucursal.
     *
     * @param $banco_sucursal Banco,Suc.
     *
     * @return int digito verificador
     */
        private static function bloque1($banco_sucursal)
        {
	  $constantes[0]=7;
          $constantes[1]=1;
	  $constantes[2]=3;
	  $constantes[3]=9;
	  $constantes[4]=7;
	  $constantes[5]=1;
	  $constantes[6]=3;
	  
          $sum_bloque=0;

          for($i=0;$i<7;$i++){
		
          $digito=substr($banco_sucursal,$i,1);
          $digito2=$digito*$constantes[$i];
          $leng=strlen($digito2)-1;
          $sum_bloque=$sum_bloque+(substr($digito2,$leng,1));
	
	}
	
	$dv_bloque1= self::prox_decena($sum_bloque);
	return $dv_bloque1;
        
        
          }

          /**
     * Calcula el primer segundo verificador que corresponde a nt,Cuenta.
     *
     * @param $nt_cta nt, Numero de cuenta.
     *
     * @return int digito verificador
     */
        private static function bloque2($nt_cta)
        {
	   $const[0]=3;
	   $const[1]=9;
      	   $const[2]=7;
	   $const[3]=1;
	   $const[4]=3;
	   $const[5]=9;
	   $const[6]=7;
	   $const[7]=1;
	   $const[8]=3;
	   $const[9]=9;
	   $const[10]=7;
	   $const[11]=1;
	   $const[12]=3;
	
	   $sum_bloque=0;

           for($i=0;$i<13;$i++){
		
           $digito=substr($nt_cta,$i,1);
	   $digito2=$digito*$const[$i];
	   $leng=strlen($digito2)-1;
	   $sum_bloque=$sum_bloque+(substr($digito2,$leng,1));
	
           }
	
	   $dv_bloque2= self::prox_decena($sum_bloque);
	   return $dv_bloque2;
        }

             /**
     * Obtiene la proxima decena de un numero entero.
     *
     * @param $bloque Numero obtenido en la sumatoria de bloques.
     *
     * @return int proxima decena
     */
        private static function prox_decena($bloque)
        {
            $decena=( intdiv(($bloque-1),10)+1)*10;
            $proxima_decena=$decena-$bloque;
            return $proxima_decena;

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

<?php

/**
 * Debugging functions
 *
 * @author     spekkionu
 * @license New BSD http://www.opensource.org/licenses/bsd-license.php
 */
class Debug extends Zend_Debug
{

  /**
     * Debug helper function.  This is a wrapper for var_dump() that adds
     * the <pre /> tags, cleans up newlines and indents, and runs
     * htmlentities() before output.
     *
     * @param  mixed  $var   The variable to dump.
     * @param  string $label OPTIONAL Label to prepend to output.
     * @param  bool   $echo  OPTIONAL Echo output if true.
     * @return string
     */
    public static function dump($var, $label=null, $echo=true)
    {
        // format the label
        $label = ($label===null) ? '' : rtrim($label) . ' ';

        // var_dump the variable into a buffer and keep the output
        ob_start();
        var_dump($var);
        $output = ob_get_clean();

        // neaten the newlines and indents
        $output = preg_replace("/\]\=\>\n(\s+)/m", "] => ", $output);
        if (self::getSapi() == 'cli') {
            $output = PHP_EOL . $label
                    . PHP_EOL . $output
                    . PHP_EOL;
        } else {
            if(!extension_loaded('xdebug')) {
                $output = htmlspecialchars($output, ENT_QUOTES);
            }

            $output = '<pre style="text-align:left;background-color:#FFFFFF;color:#000000;padding:3px;margin:0;">'
                    . $label
                    . $output
                    . '</pre>';
        }

        if ($echo) {
            echo($output);
        }
        return $output;
    }
  

}
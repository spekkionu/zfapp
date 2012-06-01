<?php
/*
 *  $Id: config.php 2753 2007-10-07 20:58:08Z Jonathan.Wage $
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the LGPL. For more information, see
 * <http://www.doctrine-project.org>.
 */

/**
 * Doctrine Configuration File
 *
 * This is a sample implementation of Doctrine
 *
 * @package     Doctrine
 * @subpackage  Config
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision: 2753 $
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @author      Jonathan H. Wage <jwage@mac.com>
 */

define('SANDBOX_PATH', dirname(__FILE__));
define('PROJECT_PATH', dirname(dirname(SANDBOX_PATH)));
define('SYSTEM', PROJECT_PATH . '/src/system');
define('DOCTRINE_PATH', SYSTEM . '/library/vendor/doctrine-orm/lib');
define('DATA_FIXTURES_PATH', PROJECT_PATH . '/data/fixtures');
define('MODELS_PATH', PROJECT_PATH . '/data/models');
define('MIGRATIONS_PATH', PROJECT_PATH . '/data/migrations');
define('SQL_PATH', PROJECT_PATH . '/data/sql');
define('YAML_SCHEMA_PATH', PROJECT_PATH . '/data/schema');

// Load Application Config
$config = require_once(SYSTEM.'/configs/config.php');


require_once(DOCTRINE_PATH . DIRECTORY_SEPARATOR . 'Doctrine.php');
spl_autoload_register(array('Doctrine', 'autoload'));
spl_autoload_register(array('Doctrine', 'modelsAutoload'));
spl_autoload_register(array('Doctrine', 'extensionsAutoload'));

Doctrine_Core::setExtensionsPath(SYSTEM.'/library/vendor/DoctrineExtensions');

$manager = Doctrine_Manager::getInstance();
$manager->setAttribute(Doctrine_Core::ATTR_QUOTE_IDENTIFIER, true);
$manager->setAttribute(Doctrine_Core::ATTR_VALIDATE, Doctrine_Core::VALIDATE_ALL);
$manager->setAttribute( Doctrine_Core::ATTR_USE_NATIVE_ENUM, true );
$manager->setAttribute(Doctrine_Core::ATTR_MODEL_LOADING, Doctrine_Core::MODEL_LOADING_PEAR);
$manager->setAttribute(Doctrine_Core::ATTR_TABLE_CLASS_FORMAT, 'Table_%s');
$manager->setAttribute( Doctrine_Core::ATTR_AUTOLOAD_TABLE_CLASSES,true);
$manager->setAttribute(Doctrine_Core::ATTR_AUTO_ACCESSOR_OVERRIDE, true);
$manager->setCharset( 'utf8' );
$manager->setCollate( 'utf8_unicode_ci' );
switch($config['database']['adapter']){
  case "Pdo_Mysql":
  case "Mysqli":
    $config['database']['dsn'] = "mysql" .
                        '://' . $config['database']['params']['username'] .
                        ':' . $config['database']['params']['password'].
                        '@' . $config['database']['params']['host'] .
                        '/' . $config['database']['params']['dbname'];
    break;
  case "Pdo_Sqlite":
    $config['database']['dsn'] = "sqlite:///".SYSTEM."/cache/".$config['database']['dbname'].".sqlite?mode=666";
    break;
  case "Pdo_Pgsql":
    $config['database']['dsn'] = "mysql" .
                        '://' . $config['database']['params']['username'] .
                        ':' . $config['database']['params']['password'].
                        '@' . $config['database']['params']['host'] .
                        '/' . $config['database']['params']['dbname'];
    break;
  default:
    exit("Unsuported database adapter {$config['database']['adapter']}");
    break;
}
// Connect to database
$conn = Doctrine_Manager::connection($config['database']['dsn']);

Doctrine_Core::setModelsDirectory(MODELS_PATH);
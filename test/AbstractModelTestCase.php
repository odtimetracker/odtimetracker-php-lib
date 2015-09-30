<?php
/**
 * odtimetracker-php-lib
 *
 * @author Ondřej Doněk, <ondrejd@gmail.com>
 * @license Mozilla Public License 2.0 https://www.mozilla.org/MPL/2.0/
 * @link https://github.com/odtimetracker/odtimetracker-php-gtk
 */

namespace odTimeTrackerTest;

use \PHPUnit_Framework_TestCase;

/**
 * Abstract class for our test cases for mappers.
 */
abstract class AbstractModelTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * @static
     * @var \PDO $pdo
     */
    static protected $pdo = null;

    /**
     * @static
     * @return void
     */
    public static function setUpBeforeClass()
    {
        self::$pdo = new \PDO(
            $GLOBALS['DB_DSN'],
            $GLOBALS['DB_USER'],
            $GLOBALS['DB_PASSWD']
        );

        if ($GLOBALS['DB_TRUNCATE'] === true) {
            $tables = array(
                \odTimeTracker\Model\ActivityMapper::TABLE_NAME,
                \odTimeTracker\Model\ProjectMapper::TABLE_NAME
            );

            foreach ($tables as $table) {
                self::truncateTable($table);
            }
        }
    }

    /**
     * @internal
     * @static
     * @param string $tableName
     * @return void
     */
    protected static function truncateTable($tableName)
    {
        if (strpos($GLOBALS['DB_DSN'], 'sqlite:') === 0) {
            self::$pdo->exec("DELETE FROM `{$tableName}` ");
            self::$pdo->exec("DELETE FROM `sqlite_sequence` WHERE `name` = '{$tableName}' ");
        } else {
            self::$pdo->exec("TRUNCATE `{$tableName}` ");
            self::$pdo->exec("ALTER TABLE `{$tableName}` AUTO_INCREMENT = 1 ");
        }
    }

    /**
     * @static
     * @return void
     */
    public static function tearDownAfterClass()
    {
        self::$pdo = null;
    }

    /**
     * @var PHPUnit_Extensions_Database_DB_IDatabaseConnection $conn
     */
//    private $conn = null;

    /**
     * @final
     * @return PHPUnit_Extensions_Database_DB_IDatabaseConnection
     */
/*    final public function getConnection()
    {
        if ($this->conn !== null) {
            return $this->conn;
        }

        if (self::$pdo === null) {
            self::$pdo = new \PDO(
                $GLOBALS['DB_DSN'],
                $GLOBALS['DB_USER'],
                $GLOBALS['DB_PASSWD']
            );
        }

        if ($GLOBALS['DB_TRUNCATE'] === true) {
            $tables = array(
                \odTimeTracker\Model\ActivityMapper::TABLE_NAME,
                \odTimeTracker\Model\ProjectMapper::TABLE_NAME
            );

            foreach ($tables as $table) {
                self::$pdo->exec(sprintf('TRUNCATE `%s`;', $table));
                self::$pdo->exec(sprintf('ALTER TABLE `%s` AUTO_INCREMENT = 1;', $table));
            }
        }

        $this->conn = $this->createDefaultDBConnection(
            self::$pdo, 
            $GLOBALS['DB_DBNAME']
        );

        return $this->conn;
    } // end getConnection()
*/
    /**
     * @returns PHPUnit_Extensions_Database_DataSet_ArrayDataSet
     */
/*    final public function getDataSet()
    {
        return new PHPUnit_Extensions_Database_DataSet_ArrayDataSet(array(
            'Activities' => self::getDataActivities(),
            'Projects'   => self::getDataProjects()
        ));
    } // end getDataSet()
*/
    /**
     * @static
     * @return array
     */
    final public static function getDataActivities()
    {
        return array(
            array(
                'ActivityId' => 1,
                'ProjectId' => 1,
                'Name' => 'Activity #1',
                'Description' => 'The first test activity.',
                'Tags' => 'tag1,tag2,tag3',
                'Started' => new \DateTime('2011-10-05 10:00:00+1:00'),
                'Stopped' => new \DateTime('2011-10-05 13:00:00+1:00')
            ),
            array(
                'ActivityId' => 2,
                'ProjectId' => 1,
                'Name' => 'Activity #2',
                'Description' => 'The second test activity.',
                'Tags' => 'tag2,tag3',
                'Started' => new \DateTime('2011-10-05 14:10:00+1:00'),
                'Stopped' => new \DateTime('2011-10-05 21:15:30+1:00')
            ),
            array(
                'ActivityId' => 3,
                'ProjectId' => 1,
                'Name' => 'Activity #3',
                'Description' => 'The third test activity.',
                'Tags' => 'tag3,tag5',
                'Started' => new \DateTime('2011-10-06 07:15:00+1:00'),
                'Stopped' => new \DateTime('2011-10-06 19:45:00+1:00')
            ),
            array(
                'ActivityId' => 4,
                'ProjectId' => 1,
                'Name' => 'Activity #4',
                'Description' => 'The fourth test activity.',
                'Tags' => null,
                'Started' => new \DateTime('2011-10-07 09:15:00+1:00'),
                'Stopped' => new \DateTime('2011-10-07 09:19:00+1:00')
            ),
            array(
                'ActivityId' => 5,
                'ProjectId' => 2,
                'Name' => 'Activity #5',
                'Description' => null,
                'Tags' => '',
                'Started' => new \DateTime('2011-10-07 10:01:10+1:00'),
                'Stopped' => new \DateTime('2011-10-07 10:31:06+1:00')
            ),
            array(
                'ActivityId' => 6,
                'ProjectId' => 3,
                'Name' => 'Activity #6',
                'Description' => 'The sixth test activity.',
                'Tags' => 'tag1,tag4',
                'Started' => new \DateTime('2011-10-07 12:01:10+1:00'),
                'Stopped' => new \DateTime('2011-10-07 14:11:24+1:00')
            ),
            array(
                'ActivityId' => 7,
                'ProjectId' => 4,
                'Name' => 'Activity #7',
                'Description' => 'The seventh test activity.',
                'Tags' => 'tag4',
                'Started' => new \DateTime('2011-10-08 09:04:00+1:00'),
                'Stopped' => null
            )
        );
    } // end getDataActivities()

    /**
     * @static
     * @return array
     */
    final public static function getDataProjects()
    {
        return array(
            array(
                'ProjectId' => 1,
                'Name' => 'Project #1',
                'Description' => 'The first test project.',
                'Created' => new \DateTime('2011-10-05 09:00:00+1:00')
            ),
            array(
                'ProjectId' => 2,
                'Name' => 'Project #2',
                'Description' => 'The second test project.',
                'Created' => new \DateTime('2011-10-05 09:21:00+1:00')
            ),
            array(
                'ProjectId' => 3,
                'Name' => 'Project #3',
                'Description' => 'The third test project.',
                'Created' => new \DateTime('2011-10-05 09:35:00+1:00')
            ),
            array(
                'ProjectId' => 4,
                'Name' => 'Project #4',
                'Description' => 'The fourth test project.',
                'Created' => new \DateTime('2011-10-05 10:40:00+1:00')
            ),
            array(
                'ProjectId' => 5,
                'Name' => 'Project #5',
                'Description' => 'The fifth test project.',
                'Created' => new \DateTime('2011-10-10 10:00:00+1:00')
            ),
            array(
                'ProjectId' => 6,
                'Name' => 'Project #6',
                'Description' => 'The sixth test project.',
                'Created' => new \DateTime('2011-10-10 13:00:00+1:00')
            ),
            array(
                'ProjectId' => 7,
                'Name' => 'Project #7',
                'Description' => 'The seventh test project.',
                'Created' => new \DateTime('2011-10-10 13:30:00+1:00')
            )
        );
    } // end getDataProjects()
} // End of AbstractModelTestCase
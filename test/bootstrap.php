<?php
/**
 * odtimetracker-php-lib
 *
 * @author Ondřej Doněk, <ondrejd@gmail.com>
 * @license Mozilla Public License 2.0 https://www.mozilla.org/MPL/2.0/
 * @link https://github.com/odtimetracker/odtimetracker-php-gtk
 */

// Load PHP files
chdir(dirname(__DIR__));

require_once 'vendor/autoload.php';

/**
 * Contains test data.
 */
class TestData {
    /**
     * @static
     * @return array
     */
    public static function getActivities() {
        return array(
            array(
                'ActivityId' => 1,
                'ProjectId' => 1,
                'Name' => 'Activity #1',
                'Description' => 'The first test activity.',
                'Tags' => 'tag1,tag2,tag3',
                'Started' => '2011-10-05 10:00:00+1:00',
                'Stopped' => '2011-10-05 13:00:00+1:00'
            ),
            array(
                'ActivityId' => 2,
                'ProjectId' => 1,
                'Name' => 'Activity #2',
                'Description' => 'The second test activity.',
                'Tags' => 'tag2,tag3',
                'Started' => '2011-10-05 14:10:00+1:00',
                'Stopped' => '2011-10-05 21:15:30+1:00'
            ),
            array(
                'ActivityId' => 3,
                'ProjectId' => 1,
                'Name' => 'Activity #3',
                'Description' => 'The third test activity.',
                'Tags' => 'tag3,tag5',
                'Started' => '2011-10-06 07:15:00+1:00',
                'Stopped' => '2011-10-06 19:45:00+1:00'
            ),
            array(
                'ActivityId' => 4,
                'ProjectId' => 1,
                'Name' => 'Activity #4',
                'Description' => 'The fourth test activity.',
                'Tags' => null,
                'Started' => '2011-10-07 09:15:00+1:00',
                'Stopped' => '2011-10-07 09:19:00+1:00'
            ),
            array(
                'ActivityId' => 5,
                'ProjectId' => 2,
                'Name' => 'Activity #5',
                'Description' => null,
                'Tags' => '',
                'Started' => '2011-10-07 10:01:10+1:00',
                'Stopped' => null
            )
        );
    } // end getActivities()

    /**
     * @static
     * @return array
     */
    public static function getProjects() {
        return array(
            array(
                'ProjectId' => 1,
                'Name' => 'Project #1',
                'Description' => 'The first test project.',
                'Created' => null
            ),
            array(
                'ProjectId' => 2,
                'Name' => 'Project #2',
                'Description' => 'The second test project.',
                'Created' => null
            ),
            array(
                'ProjectId' => 3,
                'Name' => 'Project #3',
                'Description' => 'The third test project.',
                'Created' => null
            ),
            array(
                'ProjectId' => 4,
                'Name' => 'Project #4',
                'Description' => 'The fourth test project.',
                'Created' => '2011-10-05 10:00:00.0000+1:00'
            ),
            array(
                'ProjectId' => 5,
                'Name' => 'Project #5',
                'Description' => 'The fifth test project.',
                'Created' => new \DateTime('2011-10-10 10:00:00.0000+1:00')
            )
        );
    } // end getProjects()
} // End of TestData

<?php
/**
 * odtimetracker-php-cgi
 *
 * @license Mozilla Public License 2.0 https://www.mozilla.org/MPL/2.0/
 * @author Ondřej Doněk, <ondrejd@gmail.com>
 */

namespace odTimeTrackerTest\Model;

/**
 * @covers \odTimeTracker\Model\ActivityEntity
 */
class ActivityEntityTest extends \PHPUnit_Framework_TestCase {
	/**
	 * @var array $testActivities
	 */
	protected $testActivities;

	protected function setUp() {
		$this->testActivities = \TestData::getActivities();
	}

	protected function tearDown() {
		parent::tearDown();
	}

	/**
	 * @covers \odTimeTracker\Model\ActivityEntity::__construct
	 */
	public function testConstructWithNulls() {
		$entity = new \odTimeTracker\Model\ActivityEntity();
		$this->assertNull($entity->getId());
		$this->assertNull($entity->getActivityId(), '"ActivityId" should initially be null');
		$this->assertNull($entity->getProjectId(), '"ProjectId" should initially be null');
		$this->assertNull($entity->getName(), '"Name" should initially be null');
		$this->assertNull($entity->getDescription(), '"Description" should initially be null');
		$this->assertNull($entity->getTags(), '"Tags" should initially be null');
		$this->assertNull($entity->getStarted(), '"Started" should initially be null');
		$this->assertNull($entity->getStopped(), '"Stopped" should initially be null');
	}

	/**
	 * @covers \odTimeTracker\Model\ActivityEntity::__construct
	 */
	public function testConstructWithValues() {
		foreach ($this->testActivities as $data) {
			$entity = new \odTimeTracker\Model\ActivityEntity($data);
			$this->assertEquals($data['ActivityId'], $entity->getId());
			$this->assertEquals($data['ActivityId'], $entity->getActivityId(), '"ActivityId" was not set correctly');
			$this->assertEquals($data['ProjectId'], $entity->getProjectId(), '"ProjectId" was not set correctly');
			$this->assertEquals($data['Name'], $entity->getName(), '"Name" was not set correctly');
			$this->assertEquals($data['Description'], $entity->getDescription(), '"Description" was not set correctly');
			$this->assertEquals($data['Tags'], $entity->getTags(), '"Tags" was not set correctly');
			$this->assertEquals(new \DateTime($data['Started']), $entity->getStarted(), '"Started" was not set correctly');

			if (is_null($data['Stopped'])) {
				$this->assertNull($entity->getStopped());
			} else {
				$this->assertEquals(new \DateTime($data['Stopped']), $entity->getStopped(), '"Stopped" was not set correctly');
			}
		}
	}

	/**
	 * @covers \odTimeTracker\Model\ActivityEntity::exchangeArray
	 */
	public function testExchangeArrayWithNulls() {
		foreach ($this->testActivities as $data) {
			$entity = new \odTimeTracker\Model\ActivityEntity();
			$entity->exchangeArray($data);
			$entity->exchangeArray(array());
			$this->assertNull($entity->getId());
			$this->assertNull($entity->getActivityId(), '"ActivityId" should be null');
			$this->assertNull($entity->getProjectId(), '"ProjectId" should be null');
			$this->assertNull($entity->getName(), '"Name" should be null');
			$this->assertNull($entity->getDescription(), '"Description" should be null');
			$this->assertNull($entity->getTags(), '"Tags" should be null');
			$this->assertNull($entity->getStarted(), '"Started" should be null');
			$this->assertNull($entity->getStopped(), '"Stopped" should be null');
		}
	}

	/**
	 * @covers \odTimeTracker\Model\ActivityEntity::exchangeArray
	 */
	public function testExchangeArrayWithValues() {
		foreach ($this->testActivities as $data) {
			$entity = new \odTimeTracker\Model\ActivityEntity();
			$entity->exchangeArray($data);
			$this->assertEquals($data['ActivityId'], $entity->getId());
			$this->assertEquals($data['ActivityId'], $entity->getActivityId(), '"ActivityId" was not set correctly');
			$this->assertEquals($data['ProjectId'], $entity->getProjectId(), '"ProjectId" was not set correctly');
			$this->assertEquals($data['Name'], $entity->getName(), '"Name" was not set correctly');
			$this->assertEquals($data['Description'], $entity->getDescription(), '"Description" was not set correctly');
			$this->assertEquals($data['Tags'], $entity->getTags(), '"Tags" was not set correctly');
			$this->assertEquals(new \DateTime($data['Started']), $entity->getStarted(), '"Started" was not set correctly');

			if (is_null($data['Stopped'])) {
				$this->assertNull($entity->getStopped());
			} else {
				$this->assertEquals(new \DateTime($data['Stopped']), $entity->getStopped(), '"Stopped" was not set correctly');
			}
		}
	}

	/**
	 * @covers \odTimeTracker\Model\ActivityEntity::getArrayCopy
	 */
	public function testGetArrayCopyWithNulls() {
		$entity = new \odTimeTracker\Model\ActivityEntity();
		$copy = $entity->getArrayCopy();
		$this->assertNull($copy['ActivityId'], '"ActivityId" should initially be null');
		$this->assertNull($copy['ProjectId'], '"ProjectId" should initially be null');
		$this->assertNull($copy['Name'], '"Name" should initially be null');
		$this->assertNull($copy['Description'], '"Description" should initially be null');
		$this->assertNull($copy['Tags'], '"Tags" should initially be null');
		$this->assertNull($copy['Started'], '"Started" should initially be null');
		$this->assertNull($copy['Stopped'], '"Stopped" should initially be null');
	}

	/**
	 * @covers \odTimeTracker\Model\ActivityEntity::getArrayCopy
	 */
	public function testGetArrayCopyWithValues() {
		foreach ($this->testActivities as $data) {
			$entity = new \odTimeTracker\Model\ActivityEntity($data);
			$copy = $entity->getArrayCopy();
			$this->assertEquals($data['ActivityId'], $copy['ActivityId'], '"ActivityId" was not set correctly');
			$this->assertEquals($data['ProjectId'], $copy['ProjectId'], '"ProjectId" was not set correctly');
			$this->assertEquals($data['Name'], $copy['Name'], '"Name" was not set correctly');
			$this->assertEquals($data['Description'], $copy['Description'], '"Description" was not set correctly');
			$this->assertEquals($data['Tags'], $copy['Tags'], '"Tags" was not set correctly');
			$this->assertEquals(new \DateTime($data['Started']), $copy['Started'], '"Started" was not set correctly');

			if (is_null($data['Stopped'])) {
				$this->assertNull($entity->getStopped());
			} else {
				$this->assertEquals(new \DateTime($data['Stopped']), $copy['Stopped'], '"Stopped" was not set correctly');
			}
		}
	}

	/**
	 * @covers \odTimeTracker\Model\ActivityEntity::getArrayCopy
	 */
	public function testGetArrayCopyExtraValues() {
		$activity = new \odTimeTracker\Model\ActivityEntity($this->testActivities[0]);
		$data = $activity->getArrayCopy();

		$this->assertEquals('5.10.2011 10:00', $data['StartedFormatted']);
		$this->assertEquals('5.10.2011 13:00', $data['StoppedFormatted']);
		$this->assertEquals(3, $data['Duration']->h);
		$this->assertEquals(0, $data['Duration']->i);
		$this->assertEquals(0, $data['Duration']->s);
		$this->assertEquals('3 hours', $data['DurationFormatted']);
		$this->assertEquals(true, $data['IsWithinOneDay']);
	}

	/**
	 * @covers \odTimeTracker\Model\ActivityEntity::getDuration
	 */
	public function testGetDuration() {
		$activity1 = new \odTimeTracker\Model\ActivityEntity($this->testActivities[0]);
		$duration1 = $activity1->getDuration();
		$this->assertEquals(3, $duration1->h);
		$this->assertEquals(0, $duration1->i);
		$this->assertEquals(0, $duration1->s);

		$activity2 = new \odTimeTracker\Model\ActivityEntity($this->testActivities[1]);
		$duration2 = $activity2->getDuration();
		$this->assertEquals(7, $duration2->h);
		$this->assertEquals(5, $duration2->i);
		$this->assertEquals(30, $duration2->s);

		$activity3 = new \odTimeTracker\Model\ActivityEntity($this->testActivities[2]);
		$duration3 = $activity3->getDuration();
		$this->assertEquals(12, $duration3->h);
		$this->assertEquals(30, $duration3->i);
		$this->assertEquals(0, $duration3->s);

		$activity3 = new \odTimeTracker\Model\ActivityEntity($this->testActivities[3]);
		$duration3 = $activity3->getDuration();
		$this->assertEquals(0, $duration3->h);
		$this->assertEquals(4, $duration3->i);
		$this->assertEquals(0, $duration3->s);
	}

	/**
	 * @covers \odTimeTracker\Model\ActivityEntity::getDurationFormatted
	 */
	public function testGetDurationFormatted() {
		$activity1 = new \odTimeTracker\Model\ActivityEntity($this->testActivities[0]);
		$this->assertEquals('3 hours', $activity1->getDurationFormatted());

		$activity2 = new \odTimeTracker\Model\ActivityEntity($this->testActivities[1]);
		$this->assertEquals('7 hours, 5 minutes', $activity2->getDurationFormatted());

		$activity3 = new \odTimeTracker\Model\ActivityEntity($this->testActivities[2]);
		$this->assertEquals('12 hours, 30 minutes', $activity3->getDurationFormatted());

		$activity3 = new \odTimeTracker\Model\ActivityEntity($this->testActivities[3]);
		$this->assertEquals('4 minutes', $activity3->getDurationFormatted());
	}

	/**
	 * @covers \odTimeTracker\Model\ActivityEntity::getTagsAsArray
	 */
	public function testGetTagsAsArray() {
		$activity1 = new \odTimeTracker\Model\ActivityEntity($this->testActivities[0]);
		$this->assertEquals(array('tag1', 'tag2', 'tag3'), $activity1->getTagsAsArray());

		$activity2 = new \odTimeTracker\Model\ActivityEntity($this->testActivities[1]);
		$this->assertEquals(array('tag2', 'tag3'), $activity2->getTagsAsArray());

		$activity3 = new \odTimeTracker\Model\ActivityEntity($this->testActivities[2]);
		$this->assertEquals(array('tag3', 'tag5'), $activity3->getTagsAsArray());

		$activity4 = new \odTimeTracker\Model\ActivityEntity($this->testActivities[3]);
		$this->assertEquals(array(), $activity4->getTagsAsArray());
	}

	/**
	 * @covers \odTimeTracker\Model\ActivityEntity::isWithinOneDay
	 */
	public function testIsWithinOneDay() {
		$activity1 = new \odTimeTracker\Model\ActivityEntity($this->testActivities[0]);
		$this->assertEquals(true, $activity1->isWithinOneDay());

		$activity2 = new \odTimeTracker\Model\ActivityEntity($this->testActivities[1]);
		$this->assertEquals(true, $activity2->isWithinOneDay());

		$activity3 = new \odTimeTracker\Model\ActivityEntity($this->testActivities[2]);
		$this->assertEquals(true, $activity3->isWithinOneDay());

		// TODO Check false!
	}

	/**
	 * @covers \odTimeTracker\Model\ActivityEntity::getStartedFormatted
	 */
	public function testGetStartedFormatted() {
		$activity1 = new \odTimeTracker\Model\ActivityEntity($this->testActivities[0]);
		$this->assertEquals('5.10.2011 10:00', $activity1->getStartedFormatted());

		$activity2 = new \odTimeTracker\Model\ActivityEntity($this->testActivities[1]);
		$this->assertEquals('5.10.2011 14:10', $activity2->getStartedFormatted());

		$activity3 = new \odTimeTracker\Model\ActivityEntity($this->testActivities[2]);
		$this->assertEquals('6.10.2011 7:15', $activity3->getStartedFormatted());
	}

	/**
	 * @covers \odTimeTracker\Model\ActivityEntity::getStartedFormatted
	 */
	public function testGetStartedRfc3339() {
		$activity1 = new \odTimeTracker\Model\ActivityEntity($this->testActivities[0]);
		$this->assertEquals('2011-10-05T10:00:00+01:00', $activity1->getStartedRfc3339());

		$activity2 = new \odTimeTracker\Model\ActivityEntity($this->testActivities[1]);
		$this->assertEquals('2011-10-05T14:10:00+01:00', $activity2->getStartedRfc3339());

		$activity3 = new \odTimeTracker\Model\ActivityEntity($this->testActivities[2]);
		$this->assertEquals('2011-10-06T07:15:00+01:00', $activity3->getStartedRfc3339());
	}

	/**
	 * @covers \odTimeTracker\Model\ActivityEntity::getStoppedFormatted
	 */
	public function testGetStoppedFormatted() {
		$activity1 = new \odTimeTracker\Model\ActivityEntity($this->testActivities[0]);
		$this->assertEquals('5.10.2011 13:00', $activity1->getStoppedFormatted());

		$activity2 = new \odTimeTracker\Model\ActivityEntity($this->testActivities[1]);
		$this->assertEquals('5.10.2011 21:15', $activity2->getStoppedFormatted());

		$activity3 = new \odTimeTracker\Model\ActivityEntity($this->testActivities[2]);
		$this->assertEquals('6.10.2011 19:45', $activity3->getStoppedFormatted());
	}

	/**
	 * @covers \odTimeTracker\Model\ActivityEntity::getStoppedRfc3339
	 */
	public function testGetStoppedRfc3339() {
		$activity1 = new \odTimeTracker\Model\ActivityEntity($this->testActivities[0]);
		$this->assertEquals('2011-10-05T13:00:00+01:00', $activity1->getStoppedRfc3339());

		$activity2 = new \odTimeTracker\Model\ActivityEntity($this->testActivities[1]);
		$this->assertEquals('2011-10-05T21:15:30+01:00', $activity2->getStoppedRfc3339());

		$activity3 = new \odTimeTracker\Model\ActivityEntity($this->testActivities[2]);
		$this->assertEquals('2011-10-06T19:45:00+01:00', $activity3->getStoppedRfc3339());
	}
}
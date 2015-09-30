<?php
/**
 * odtimetracker-php-cgi
 *
 * @license Mozilla Public License 2.0 https://www.mozilla.org/MPL/2.0/
 * @author Ondřej Doněk, <ondrejd@gmail.com>
 */

namespace odTimeTrackerTest\Model;

/**
 * @covers \odTimeTracker\Model\ProjectMapper
 */
class ProjectMapperTest extends \odTimeTrackerTest\AbstractModelTestCase
{
	/**
	 * @covers \odTimeTracker\Model\ProjectMapper::insert
	 */
	public function testInsert()
	{
		$mapper = new \odTimeTracker\Model\ProjectMapper(self::$pdo);

		foreach (self::getDataProjects() as $data) {
			$project = $mapper->insert(new \odTimeTracker\Model\ProjectEntity($data));
			$this->assertInstanceOf('\odTimeTracker\Model\ProjectEntity', $project);
		}

		$results = $mapper->selectAll();
		$this->assertEquals(7, count($results));

		// Test failure
		$res = $mapper->insert(new \odTimeTracker\Model\ProjectEntity(array('Name' => 'Test name')));
		$this->assertFalse($res);

		$results = $mapper->selectAll();
		$this->assertEquals(7, count($results));
	}

	/**
	 * @covers \odTimeTracker\Model\ProjectMapper::update
	 */
	public function testUpdate()
	{
		$mapper = new \odTimeTracker\Model\ProjectMapper(self::$pdo);
		$projects = self::getDataProjects();

		$project1 = new \odTimeTracker\Model\ProjectEntity($projects[0]);
		$project1->setName('(Updated) '.$project1->getName());
		$project1->setDescription('(Updated) '.$project1->getDescription());

		$res1 = $mapper->update($project1);
		$this->assertInstanceOf('\odTimeTracker\Model\ProjectEntity', $res1);
		$this->assertEquals('(Updated) '.$projects[0]['Name'], $res1->getName());
		$this->assertEquals('(Updated) '.$projects[0]['Description'], $res1->getDescription());

		$project2 = new \odTimeTracker\Model\ProjectEntity();
		$project2->setName('Test project');

		$res2 = $mapper->update($project2);
		$this->assertFalse($res2);
	}

	/**
	 * @covers \odTimeTracker\Model\ProjectMapper::delete
	 */
	public function testDelete()
	{
		$projects = self::getDataProjects();
		$project = new \odTimeTracker\Model\ProjectEntity($projects[6]);
		$mapper = new \odTimeTracker\Model\ProjectMapper(self::$pdo);

		$res = $mapper->delete($project);
		$this->assertTrue($res);

		$results = $mapper->selectAll();
		$this->assertEquals(6, count($results));
	}

	/**
	 * @covers \odTimeTracker\Model\ProjectMapper::deleteById
	 */
	public function testDeleteById()
	{
		$mapper = new \odTimeTracker\Model\ProjectMapper(self::$pdo);

		$res = $mapper->deleteById(6);
		$this->assertTrue($res);

		$results = $mapper->selectAll();
		$this->assertEquals(5, count($results));
	}

	/**
	 * @covers \odTimeTracker\Model\ProjectMapper::selectAll
	 */
	public function testSelectAll()
	{
		$mapper = new \odTimeTracker\Model\ProjectMapper(self::$pdo);

		$results = $mapper->selectAll();
		$this->assertEquals(5, count($results));

		// Test "random" project
		$project = $results[4];
		$this->assertInstanceOf('\odTimeTracker\Model\ProjectEntity', $project);
		$this->assertGreaterThanOrEqual(5, $project->getId());
		$this->assertGreaterThanOrEqual(5, $project->getProjectId());
		$this->assertEquals('Project #5', $project->getName());
		$this->assertEquals('The fifth test project.', $project->getDescription());
		$this->assertEquals(new \DateTime('2011-10-10 10:00:00.0000+1:00'), $project->getCreated());
		$this->assertEquals('10.10.2011 10:00', $project->getCreatedFormatted());
 		$this->assertEquals('2011-10-10T10:00:00+01:00', $project->getCreatedRfc3339());
	}

	/**
	 * @covers \odTimeTracker\Model\ProjectMapper::selectById
	 */
	public function testSelectById()
	{
		$mapper = new \odTimeTracker\Model\ProjectMapper(self::$pdo);

		$project1 = $mapper->selectById(5);
		$this->assertInstanceOf('\odTimeTracker\Model\ProjectEntity', $project1);
		$this->assertGreaterThanOrEqual(5, $project1->getId());
		$this->assertGreaterThanOrEqual(5, $project1->getProjectId());
		$this->assertEquals('Project #5', $project1->getName());
		$this->assertEquals('The fifth test project.', $project1->getDescription());
		$this->assertEquals(new \DateTime('2011-10-10 10:00:00.0000+1:00'), $project1->getCreated());
		$this->assertEquals('10.10.2011 10:00', $project1->getCreatedFormatted());
 		$this->assertEquals('2011-10-10T10:00:00+01:00', $project1->getCreatedRfc3339());

		$project2 = $mapper->selectById(25);
		$this->assertNull($project2);
	}
}

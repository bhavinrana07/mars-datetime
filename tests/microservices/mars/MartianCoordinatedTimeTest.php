<?php

use App\Controller\Domain\MartianCoordinatedTimeController;
use PHPUnit\Framework\TestCase;

class MartianCoordinatedTimeTest extends TestCase
{
    private const TIMESTAMP = 1581144317;

    private const RESPONSE = [
        'mars_sol_date' => 51937.94038,
        'martian_coordinated_time' => '22:34:09',
    ];
    /**
     * testMartianCoordinatedTime function
     * Testing the main functionality of the Class
     * @return void
     */
    public function testMartianCoordinatedTime()
    {
        $martianCoordinatedTimeController = new MartianCoordinatedTimeController;
        $result = $martianCoordinatedTimeController->getMarsDateTime(self::TIMESTAMP);
        $this->assertEquals($result, self::RESPONSE);
    }

    /**
     * testIsValidTimeStamp function
     * Test provided date and time is valid or not.
     * @return void
     */
    public function testIsValidTimeStamp()
    {
        $martianCoordinatedTimeController = new MartianCoordinatedTimeController;
        $result = $martianCoordinatedTimeController->isValidTimeStamp(self::TIMESTAMP);
        $this->assertTrue($result);
    }
}

<?php

use\Raisins\{Permission, EqualsNameException};
use PHPUnit\Framework\TestCase;

class PermissionTest extends TestCase
{
    /**
     * setup
     *
     * @return void
     */
    protected function setUp(): void
    {
    }

    public function testGetName()
    {
        $name = "read";
        $permission = new Permission($name);

        $this->assertEquals($name, $permission->getName());
    }

    /**
     * Undocumented function
     *
     * @dataProvider permissionProvider
     */
    public function testGetState($state)
    {
        $name = "read";
        $permission = new Permission($name, $state);

        $this->assertEquals($state, $permission->getState());
    }

    public function testEncodeJson()
    {
        $name = "read";
        $permission = new Permission($name, 1);
        $this->assertJson(json_encode($permission));
    }

    public function testPermissionJson()
    {
        $name = "read";
        $permission = new Permission($name, 1);
        $json = json_encode($permission);
        $object = json_decode($json, false);
        $this->assertEquals("read", $object->name);
        $this->assertEquals(1, $object->state);
    }

    public function testToString()
    {
        $name = "read";
        $permission = new Permission($name, 1);
        $this->assertEquals("read", $permission);
    }

    /**
     * Test Permission Merge
     *
     * @dataProvider mergeProvider
     */
    public function testMerge($grState, $uState, $expected)
    {
        $name = "read";
        $permissionForGroup = new Permission($name, $grState);
        $permissionForUser = new Permission($name, $uState);

        $permission = $permissionForGroup->merge($permissionForUser);
        $this->assertEquals($expected, $permission->getState());
    }

    public function testMergeThrowEqualsNameException()
    {
        $this->expectException(EqualsNameException::class);

        $permissionForGroup = new Permission("read");
        $permissionForUser = new Permission("create");

        $permissionForGroup->merge($permissionForUser);
    }

    public function mergeProvider()
    {
        return [
            // grState, uState, expected
            [0, 1, 1],
            [1, 0, 1],
            [0, 0, 0],
            [-1, 0, -1],
            [0, -1, -1],
        ];
    }

    public function permissionProvider()
    {
        return [
            [0],
            [1],
            [-1]
        ];
    }
}

<?php

use\Raisins\{PermissionValidation, Permission};
use PHPUnit\Framework\TestCase;

class PermissionValidationTest extends TestCase
{
    /**
     * setup
     *
     * @return void
     */
    protected function setUp(): void
    {
    }

    /**
     * Test Email
     *
     * @dataProvider permissionProvider
     */
    public function testValidate($availablePermission, $requiredPermission, $expected)
    {
        $permission = new PermissionValidation;
        $permission->available = $availablePermission;
        $permission->required = $requiredPermission;

        $result = $permission->validate();

        $this->assertEquals($expected, $result);
    }

    public function testGetIngexByName()
    {
        $permission = [
            new Permission("read"),
            new Permission("edit"),
            new Permission("delete")
        ];

        $pv = new PermissionValidation();

        $this->assertEquals(0, $pv->getIndexByName("read", $permission));
        $this->assertEquals(1, $pv->getIndexByName("edit", $permission));
        $this->assertEquals(2, $pv->getIndexByName("delete", $permission));
        $this->assertEquals(-1, $pv->getIndexByName("name", $permission));
    }

    public function testMerge()
    {
        $permission = [
            new Permission("read"),
            new Permission("edit"),
            new Permission("delete")
        ];

        $permissionMerge = [
            new Permission("read", 1),
            new Permission("edit"),
            new Permission("create"),
            new Permission("delete", -1)
        ];

        $pv = new PermissionValidation();
        $pv->available = $permission;
        $pv->merge($permissionMerge);

        $this->assertEquals(1, $pv->available[0]->getState());

        $this->assertEquals("create", $pv->available[3]->getName());
    }

    public function testPermissionMergedValidate()
    {
        $pv = new PermissionValidation();

        // set required permissions
        $pv->required = [
            new Permission("read", -1),
            new Permission("edit"),
            new Permission("delete", 1)
        ];

        // set available permission
        $pv->available = [
            new Permission("read"),
            new Permission("edit"),
            new Permission("delete", -1)
        ];

        $result = $pv->validate();
        
        $this->assertEquals(false, $result);

        // merge overridden permissions (option)
        $pv->merge([
            new Permission("delete", 1)
        ]);

        $result = $pv->validate();

        $this->assertEquals(true, $result);
    }

    public function permissionProvider()
    {
        $read = new Permission("read");
        $create = new Permission("create");
        $delete = new Permission("delete");
        $update = new Permission("update");
        return [
            // [availablePermission, requiredPermission, expected]
            [[], [], true],
            [[], [$read, $create, $delete], false],
            [[$read, $create], [$read, $create, $delete], false],
            [[$read, $create, $delete], [$read, $create, $delete], true],
            [[$read, $create, $delete, $update], [$read, $create, $delete], true],
            [[$read, $create, $delete], [$read, $create, $delete], true],
        ];
    }
}

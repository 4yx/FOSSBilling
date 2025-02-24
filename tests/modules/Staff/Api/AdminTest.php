<?php


namespace Box\Mod\Staff\Api;


class AdminTest extends \BBTestCase {
    /**
     * @var \Box\Mod\Staff\Api\Admin
     */
    protected $api = null;

    public function setup(): void
    {
        $this->api= new \Box\Mod\Staff\Api\Admin();
    }

    public function testgetDi()
    {
        $di = new \Pimple\Container();
        $this->api->setDi($di);
        $getDi = $this->api->getDi();
        $this->assertEquals($di, $getDi);
    }

    public function testget_list()
    {
        $data = array();

        $serviceMock = $this->getMockBuilder('\\' . \Box\Mod\Staff\Service::class)->getMock();
        $serviceMock->expects($this->atLeastOnce())
            ->method('getSearchQuery')
            ->will($this->returnValue(array('sqlString', array())));
        $serviceMock->expects($this->atLeastOnce())
            ->method('toModel_AdminApiArray')
            ->will($this->returnValue(array()));

        $resultSet = array(
            'list' => array('id' => 1),
        );
        $pagerMock = $this->getMockBuilder('\Box_Pagination')->getMock();
        $pagerMock->expects($this->atLeastOnce())
            ->method('getSimpleResultSet')
            ->will($this->returnValue($resultSet));

        $adminModel = new \Model_Admin();
        $adminModel->loadBean(new \DummyBean());
        $dbMock = $this->getMockBuilder('\Box_Database')->getMock();
        $dbMock->expects($this->atLeastOnce())
            ->method('getExistingModelById')
            ->will($this->returnValue($adminModel));

        $di = new \Pimple\Container();
        $di['pager'] = $pagerMock;
        $di['db'] = $dbMock;


        $this->api->setDi($di);
        $this->api->setService($serviceMock);

        $result = $this->api->get_list($data);
        $this->assertIsArray($result);
    }

    public function testget()
    {
        $data['id'] = 1;

        $serviceMock = $this->getMockBuilder('\\' . \Box\Mod\Staff\Service::class)->getMock();
        $serviceMock->expects($this->atLeastOnce())
            ->method('toModel_AdminApiArray')
            ->will($this->returnValue(array()));

        $validatorMock = $this->getMockBuilder('\\' . \FOSSBilling\Validate::class)->getMock();
        $validatorMock->expects($this->atLeastOnce())
            ->method('checkRequiredParamsForArray');
        $dbMock = $this->getMockBuilder('\Box_Database')->getMock();
        $dbMock->expects($this->atLeastOnce())
            ->method('getExistingModelById')
            ->will($this->returnValue(new \Model_Admin()));

        $di = new \Pimple\Container();
        $di['validator'] = $validatorMock;
        $di['db'] = $dbMock;

        $this->api->setService($serviceMock);
        $this->api->setDi($di);

        $result = $this->api->get($data);
        $this->assertIsArray($result);
    }

    public function testupdate()
    {
        $data['id'] = 1;

        $serviceMock = $this->getMockBuilder('\\' . \Box\Mod\Staff\Service::class)->getMock();
        $serviceMock->expects($this->atLeastOnce())
            ->method('update')
            ->will($this->returnValue(true));

        $validatorMock = $this->getMockBuilder('\\' . \FOSSBilling\Validate::class)->getMock();
        $validatorMock->expects($this->atLeastOnce())
            ->method('checkRequiredParamsForArray');
        $dbMock = $this->getMockBuilder('\Box_Database')->getMock();
        $dbMock->expects($this->atLeastOnce())
            ->method('getExistingModelById')
            ->will($this->returnValue(new \Model_Admin()));

        $di = new \Pimple\Container();
        $di['validator'] = $validatorMock;
        $di['db'] = $dbMock;

        $this->api->setDi($di);
        $this->api->setService($serviceMock);

        $result = $this->api->update($data);
        $this->assertIsBool($result);
        $this->assertTrue($result);
    }

    public function testdelete()
    {
        $data['id'] = 1;

        $serviceMock = $this->getMockBuilder('\\' . \Box\Mod\Staff\Service::class)->getMock();
        $serviceMock->expects($this->atLeastOnce())
            ->method('delete')
            ->will($this->returnValue(true));

        $validatorMock = $this->getMockBuilder('\\' . \FOSSBilling\Validate::class)->getMock();
        $validatorMock->expects($this->atLeastOnce())
            ->method('checkRequiredParamsForArray');
        $dbMock = $this->getMockBuilder('\Box_Database')->getMock();
        $dbMock->expects($this->atLeastOnce())
            ->method('getExistingModelById')
            ->will($this->returnValue(new \Model_Admin()));

        $di = new \Pimple\Container();
        $di['validator'] = $validatorMock;
        $di['db'] = $dbMock;

        $this->api->setDi($di);
        $this->api->setService($serviceMock);

        $result = $this->api->delete($data);
        $this->assertIsBool($result);
        $this->assertTrue($result);
    }


    public function testchange_password()
    {
        $data = array(
            'id' => '1',
            'password' => 'test!23A',
            'password_confirm' => 'test!23A',
        );

        $validatorMock = $this->getMockBuilder('\\' . \FOSSBilling\Validate::class)->getMock();
        $validatorMock->expects($this->atLeastOnce())
            ->method('isPasswordStrong');
        $validatorMock->expects($this->atLeastOnce())
            ->method('checkRequiredParamsForArray');

        $serviceMock = $this->getMockBuilder('\\' . \Box\Mod\Staff\Service::class)->getMock();
        $serviceMock->expects($this->atLeastOnce())
            ->method('changePassword')
            ->will($this->returnValue(true));

        $dbMock = $this->getMockBuilder('\Box_Database')->getMock();
        $dbMock->expects($this->atLeastOnce())
            ->method('getExistingModelById')
            ->will($this->returnValue(new \Model_Admin()));

        $di = new \Pimple\Container();
        $di['validator'] = $validatorMock;
        $di['db'] = $dbMock;

        $this->api->setDi($di);
        $this->api->setService($serviceMock);
        $result = $this->api->change_password($data);

        $this->assertTrue(true);
    }

    public function testchange_passwordPasswordDonotMatch()
    {
        $data = array(
            'id' => '1',
            'password' => 'test!23A',
            'password_confirm' => 'test',
        );

        $validatorMock = $this->getMockBuilder('\\' . \FOSSBilling\Validate::class)->getMock();
        $validatorMock->expects($this->atLeastOnce())
            ->method('checkRequiredParamsForArray');

        $di = new \Pimple\Container();
        $di['validator'] = $validatorMock;

        $this->api->setDi($di);
        $this->expectException(\FOSSBilling\Exception::class);
        $this->expectExceptionMessage('Passwords do not match');
        $this->api->change_password($data);
    }

    public function testcreate()
    {
        $data = array(
            'admin_group_id' => '1',
            'password' => 'test!23A',
            'email' => 'okey@example.com',
            'name' => 'OkeyTest',
        );

        $newStaffId = 1;

        $serviceMock = $this->getMockBuilder('\\' . \Box\Mod\Staff\Service::class)->getMock();
        $serviceMock->expects($this->atLeastOnce())
            ->method('create')
            ->will($this->returnValue($newStaffId));

        $validatorMock = $this->getMockBuilder('\\' . \FOSSBilling\Validate::class)->getMock();
        $validatorMock->expects($this->atLeastOnce())
            ->method('checkRequiredParamsForArray');

        $di = new \Pimple\Container();
        $di['validator'] = $validatorMock;

        $toolsMock = $this->getMockBuilder('\\' . \FOSSBilling\Tools::class)->getMock();
        $toolsMock->expects($this->atLeastOnce())->method('validateAndSanitizeEmail');
        $di['tools'] = $toolsMock;

        $this->api->setDi($di);
        $this->api->setService($serviceMock);

        $result = $this->api->create($data);
        $this->assertIsInt($result);
        $this->assertEquals($newStaffId, $result);
    }

    public function testpermissions_get()
    {
        $data['id'] = 1;

        $staffModel = new \Model_Admin();
        $staffModel->loadBean(new \DummyBean());

        $serviceMock = $this->getMockBuilder('\\' . \Box\Mod\Staff\Service::class)->getMock();
        $serviceMock->expects($this->atLeastOnce())
            ->method('getPermissions')
            ->will($this->returnValue(array()));

        $validatorMock = $this->getMockBuilder('\\' . \FOSSBilling\Validate::class)->getMock();
        $validatorMock->expects($this->atLeastOnce())
            ->method('checkRequiredParamsForArray');
        $dbMock = $this->getMockBuilder('\Box_Database')->getMock();
        $dbMock->expects($this->atLeastOnce())
            ->method('getExistingModelById')
            ->will($this->returnValue($staffModel));

        $di = new \Pimple\Container();
        $di['validator'] = $validatorMock;
        $di['db'] = $dbMock;

        $this->api->setDi($di);
        $this->api->setService($serviceMock);

        $result = $this->api->permissions_get($data);
        $this->assertIsArray($result);
    }

    public function testpermissions_update()
    {
        $data = array(
            'id' => '1',
            'permissions' => 'default',
        );

        $serviceMock = $this->getMockBuilder('\\' . \Box\Mod\Staff\Service::class)->getMock();
        $serviceMock->expects($this->atLeastOnce())
            ->method('setPermissions')
            ->will($this->returnValue(true));


        $staffModel = new \Model_Admin();
        $staffModel->loadBean(new \DummyBean());

        $validatorMock = $this->getMockBuilder('\\' . \FOSSBilling\Validate::class)->getMock();
        $validatorMock->expects($this->atLeastOnce())
            ->method('checkRequiredParamsForArray');
        $dbMock = $this->getMockBuilder('\Box_Database')->getMock();
        $dbMock->expects($this->atLeastOnce())
            ->method('getExistingModelById')
            ->will($this->returnValue($staffModel));

        $di = new \Pimple\Container();
        $di['validator'] = $validatorMock;
        $di['db'] = $dbMock;
        $di['logger'] = new \Box_Log();

        $this->api->setDi($di);
        $this->api->setService($serviceMock);

        $result = $this->api->permissions_update($data);
        $this->assertIsBool($result);
        $this->assertTrue($result);
    }

    public function testgroup_get_pairs()
    {
        $serviceMock = $this->getMockBuilder('\\' . \Box\Mod\Staff\Service::class)->getMock();
        $serviceMock->expects($this->atLeastOnce())
            ->method('getAdminGroupPair')
            ->will($this->returnValue(array()));

        $this->api->setService($serviceMock);
        $result = $this->api->group_get_pairs(array());
        $this->assertIsArray($result);
    }

    public function testgroup_get_list()
    {
        $data = array();

        $serviceMock = $this->getMockBuilder('\\' . \Box\Mod\Staff\Service::class)->getMock();
        $serviceMock->expects($this->atLeastOnce())
            ->method('getAdminGroupSearchQuery')
            ->will($this->returnValue(array('sqlString', array())));

        $pagerMock = $this->getMockBuilder('\Box_Pagination')->getMock();
        $pagerMock->expects($this->atLeastOnce())
            ->method('getSimpleResultSet')
            ->will($this->returnValue(array('list' => array())));

        $di = new \Pimple\Container();
        $di['pager'] = $pagerMock;


        $this->api->setDi($di);
        $this->api->setService($serviceMock);

        $result = $this->api->group_get_list($data);
        $this->assertIsArray($result);
    }

    public function testgroup_create()
    {
        $data['name'] = 'Prime Group';
        $newGroupId = 1;

        $serviceMock = $this->getMockBuilder('\\' . \Box\Mod\Staff\Service::class)->getMock();
        $serviceMock->expects($this->atLeastOnce())
            ->method('createGroup')
            ->will($this->returnValue($newGroupId));

        $validatorMock = $this->getMockBuilder('\\' . \FOSSBilling\Validate::class)->getMock();
        $validatorMock->expects($this->atLeastOnce())
            ->method('checkRequiredParamsForArray');

        $di = new \Pimple\Container();
        $di['validator'] = $validatorMock;

        $this->api->setDi($di);
        $this->api->setService($serviceMock);

        $result = $this->api->group_create($data);
        $this->assertIsInt($result);
        $this->assertEquals($newGroupId, $result);
    }

    public function testgroup_get()
    {
        $data['id'] = '1';

        $serviceMock = $this->getMockBuilder('\\' . \Box\Mod\Staff\Service::class)->getMock();
        $serviceMock->expects($this->atLeastOnce())
            ->method('toAdminGroupApiArray')
            ->will($this->returnValue(array()));


        $validatorMock = $this->getMockBuilder('\\' . \FOSSBilling\Validate::class)->getMock();
        $validatorMock->expects($this->atLeastOnce())
            ->method('checkRequiredParamsForArray');
        $dbMock = $this->getMockBuilder('\Box_Database')->getMock();
        $dbMock->expects($this->atLeastOnce())
            ->method('getExistingModelById')
            ->will($this->returnValue(new \Model_AdminGroup()));

        $di = new \Pimple\Container();
        $di['validator'] = $validatorMock;
        $di['db'] = $dbMock;

        $this->api->setIdentity(new \Model_Admin());
        $this->api->setDi($di);
        $this->api->setService($serviceMock);

        $result = $this->api->group_get($data);
        $this->assertIsArray($result);
    }

    public function testgroup_delete()
    {
        $data['id'] = '1';

        $serviceMock = $this->getMockBuilder('\\' . \Box\Mod\Staff\Service::class)->getMock();
        $serviceMock->expects($this->atLeastOnce())
            ->method('deleteGroup')
            ->will($this->returnValue(true));

        $validatorMock = $this->getMockBuilder('\\' . \FOSSBilling\Validate::class)->getMock();
        $validatorMock->expects($this->atLeastOnce())
            ->method('checkRequiredParamsForArray');
        $dbMock = $this->getMockBuilder('\Box_Database')->getMock();
        $dbMock->expects($this->atLeastOnce())
            ->method('getExistingModelById')
            ->will($this->returnValue(new \Model_AdminGroup()));

        $di = new \Pimple\Container();
        $di['validator'] = $validatorMock;
        $di['db'] = $dbMock;

        $this->api->setIdentity(new \Model_Admin());
        $this->api->setDi($di);
        $this->api->setService($serviceMock);

        $result = $this->api->group_delete($data);
        $this->assertIsBool($result);
        $this->assertTrue($result);
    }

    public function testgroup_update()
    {
        $data['id'] = '1';

        $serviceMock = $this->getMockBuilder('\\' . \Box\Mod\Staff\Service::class)->getMock();
        $serviceMock->expects($this->atLeastOnce())
            ->method('updateGroup')
            ->will($this->returnValue(true));

        $validatorMock = $this->getMockBuilder('\\' . \FOSSBilling\Validate::class)->getMock();
        $validatorMock->expects($this->atLeastOnce())
            ->method('checkRequiredParamsForArray');
        $dbMock = $this->getMockBuilder('\Box_Database')->getMock();
        $dbMock->expects($this->atLeastOnce())
            ->method('getExistingModelById')
            ->will($this->returnValue(new \Model_AdminGroup()));

        $di = new \Pimple\Container();
        $di['validator'] = $validatorMock;
        $di['db'] = $dbMock;

        $this->api->setIdentity(new \Model_Admin());
        $this->api->setDi($di);
        $this->api->setService($serviceMock);

        $result = $this->api->group_update($data);
        $this->assertIsBool($result);
        $this->assertTrue($result);
    }

    public function testlogin_history_get_list()
    {
        $data = array();

        $serviceMock = $this->getMockBuilder('\\' . \Box\Mod\Staff\Service::class)->getMock();
        $serviceMock->expects($this->atLeastOnce())
            ->method('getActivityAdminHistorySearchQuery')
            ->will($this->returnValue(array('sqlString', array())));
        $serviceMock->expects($this->atLeastOnce())
            ->method('toActivityAdminHistoryApiArray')
            ->will($this->returnValue(array()));

        $resultSet = array(
            'list' => array('id' => 1),
        );
        $pagerMock = $this->getMockBuilder('\Box_Pagination')->getMock();
        $pagerMock->expects($this->atLeastOnce())
            ->method('getSimpleResultSet')
            ->will($this->returnValue($resultSet));

        $model = new \Model_ActivityAdminHistory();
        $model->loadBean(new \DummyBean());
        $dbMock = $this->getMockBuilder('\Box_Database')->getMock();
        $dbMock->expects($this->atLeastOnce())
            ->method('getExistingModelById')
            ->will($this->returnValue($model));

        $di = new \Pimple\Container();
        $di['pager'] = $pagerMock;
        $di['db'] = $dbMock;


        $this->api->setDi($di);
        $this->api->setService($serviceMock);

        $result = $this->api->login_history_get_list($data);
        $this->assertIsArray($result);
    }

    public function testlogin_history_get()
    {
        $data['id'] = '1';

        $serviceMock = $this->getMockBuilder('\\' . \Box\Mod\Staff\Service::class)->getMock();
        $serviceMock->expects($this->atLeastOnce())
            ->method('toActivityAdminHistoryApiArray')
            ->will($this->returnValue(array()));

        $validatorMock = $this->getMockBuilder('\\' . \FOSSBilling\Validate::class)->getMock();
        $validatorMock->expects($this->atLeastOnce())
            ->method('checkRequiredParamsForArray');
        $dbMock = $this->getMockBuilder('\Box_Database')->getMock();
        $dbMock->expects($this->atLeastOnce())
            ->method('getExistingModelById')
            ->will($this->returnValue(new \Model_ActivityAdminHistory()));

        $di = new \Pimple\Container();
        $di['validator'] = $validatorMock;
        $di['db'] = $dbMock;

        $this->api->setIdentity(new \Model_Admin());
        $this->api->setDi($di);
        $this->api->setService($serviceMock);

        $result = $this->api->login_history_get($data);
        $this->assertIsArray($result);
    }

    public function testlogin_history_delete()
    {
        $data['id'] = '1';

        $serviceMock = $this->getMockBuilder('\\' . \Box\Mod\Staff\Service::class)->getMock();
        $serviceMock->expects($this->atLeastOnce())
            ->method('deleteLoginHistory')
            ->will($this->returnValue(true));

        $validatorMock = $this->getMockBuilder('\\' . \FOSSBilling\Validate::class)->getMock();
        $validatorMock->expects($this->atLeastOnce())
            ->method('checkRequiredParamsForArray');
        $dbMock = $this->getMockBuilder('\Box_Database')->getMock();
        $dbMock->expects($this->atLeastOnce())
            ->method('getExistingModelById')
            ->will($this->returnValue(new \Model_ActivityAdminHistory()));

        $di = new \Pimple\Container();
        $di['validator'] = $validatorMock;
        $di['db'] = $dbMock;

        $this->api->setIdentity(new \Model_Admin());
        $this->api->setDi($di);
        $this->api->setService($serviceMock);

        $result = $this->api->login_history_delete($data);
        $this->assertIsBool($result);
        $this->assertTrue($result);
    }

    public function testBatch_delete()
    {
        $activityMock = $this->getMockBuilder('\\' . \Box\Mod\Staff\Api\Admin::class)->onlyMethods(array('login_history_delete'))->getMock();
        $activityMock->expects($this->atLeastOnce())->method('login_history_delete')->will($this->returnValue(true));

        $validatorMock = $this->getMockBuilder('\\' . \FOSSBilling\Validate::class)->disableOriginalConstructor()->getMock();
        $validatorMock->expects($this->atLeastOnce())
            ->method('checkRequiredParamsForArray')
            ->will($this->returnValue(null));

        $di              = new \Pimple\Container();
        $di['validator'] = $validatorMock;
        $activityMock->setDi($di);

        $result = $activityMock->batch_delete_logs(array('ids' => array(1, 2, 3)));
        $this->assertEquals(true, $result);
    }
}

<?php


namespace Box\Mod\Servicehosting\Api;


class AdminTest extends \BBTestCase
{

    /**
     * @var \Box\Mod\Servicehosting\Api\Admin
     */
    protected $api = null;

    public function setup(): void
    {
        $this->api = new \Box\Mod\Servicehosting\Api\Admin();
    }

    public function testgetDi()
    {
        $di = new \Pimple\Container();
        $this->api->setDi($di);
        $getDi = $this->api->getDi();
        $this->assertEquals($di, $getDi);
    }

    public function testchange_plan()
    {
        $data = array(
            'plan_id' => 1,
        );

        $getServiceReturnValue = array(new \Model_ClientOrder(), new \Model_ServiceHosting);
        $apiMock               = $this->getMockBuilder('\\' . \Box\Mod\Servicehosting\Api\Admin::class)
            ->onlyMethods(array('_getService'))
            ->getMock();

        $apiMock->expects($this->atLeastOnce())
            ->method('_getService')
            ->will($this->returnValue($getServiceReturnValue));

        $serviceMock = $this->getMockBuilder('\\' . \Box\Mod\Servicehosting\Service::class)->getMock();
        $serviceMock->expects($this->atLeastOnce())
            ->method('changeAccountPlan')
            ->will($this->returnValue(true));

        $dbMock = $this->getMockBuilder('\Box_Database')->getMock();
        $dbMock->expects($this->atLeastOnce())
            ->method('getExistingModelById')
            ->will($this->returnValue(new \Model_ServiceHostingHp));

        $di       = new \Pimple\Container();
        $di['db'] = $dbMock;

        $apiMock->setDi($di);
        $apiMock->setService($serviceMock);

        $result = $apiMock->change_plan($data);
        $this->assertIsBool($result);
        $this->assertTrue($result);
    }

    public function testchange_planMissingPlanId()
    {
        $data = array();

        $this->expectException(\FOSSBilling\Exception::class);
        $this->expectExceptionMessage('plan_id is missing');
        $this->api->change_plan($data);
    }

    public function testchange_username()
    {
        $getServiceReturnValue = array(new \Model_ClientOrder(), new \Model_ServiceHosting);
        $apiMock               = $this->getMockBuilder('\\' . \Box\Mod\Servicehosting\Api\Admin::class)
            ->onlyMethods(array('_getService'))
            ->getMock();

        $apiMock->expects($this->atLeastOnce())
            ->method('_getService')
            ->will($this->returnValue($getServiceReturnValue));

        $serviceMock = $this->getMockBuilder('\\' . \Box\Mod\Servicehosting\Service::class)->getMock();
        $serviceMock->expects($this->atLeastOnce())
            ->method('changeAccountUsername')
            ->will($this->returnValue(true));

        $apiMock->setService($serviceMock);

        $result = $apiMock->change_username(array());
        $this->assertIsBool($result);
        $this->assertTrue($result);
    }

    public function testchange_ip()
    {
        $getServiceReturnValue = array(new \Model_ClientOrder(), new \Model_ServiceHosting);
        $apiMock               = $this->getMockBuilder('\\' . \Box\Mod\Servicehosting\Api\Admin::class)
            ->onlyMethods(array('_getService'))
            ->getMock();

        $apiMock->expects($this->atLeastOnce())
            ->method('_getService')
            ->will($this->returnValue($getServiceReturnValue));

        $serviceMock = $this->getMockBuilder('\\' . \Box\Mod\Servicehosting\Service::class)->getMock();
        $serviceMock->expects($this->atLeastOnce())
            ->method('changeAccountIp')
            ->will($this->returnValue(true));

        $apiMock->setService($serviceMock);

        $result = $apiMock->change_ip(array());
        $this->assertIsBool($result);
        $this->assertTrue($result);
    }

    public function testchange_domain()
    {
        $getServiceReturnValue = array(new \Model_ClientOrder(), new \Model_ServiceHosting);
        $apiMock               = $this->getMockBuilder('\\' . \Box\Mod\Servicehosting\Api\Admin::class)
            ->onlyMethods(array('_getService'))
            ->getMock();

        $apiMock->expects($this->atLeastOnce())
            ->method('_getService')
            ->will($this->returnValue($getServiceReturnValue));

        $serviceMock = $this->getMockBuilder('\\' . \Box\Mod\Servicehosting\Service::class)->getMock();
        $serviceMock->expects($this->atLeastOnce())
            ->method('changeAccountDomain')
            ->will($this->returnValue(true));

        $apiMock->setService($serviceMock);

        $result = $apiMock->change_domain(array());
        $this->assertIsBool($result);
        $this->assertTrue($result);
    }

    public function testchange_password()
    {
        $getServiceReturnValue = array(new \Model_ClientOrder(), new \Model_ServiceHosting);
        $apiMock               = $this->getMockBuilder('\\' . \Box\Mod\Servicehosting\Api\Admin::class)
            ->onlyMethods(array('_getService'))
            ->getMock();

        $apiMock->expects($this->atLeastOnce())
            ->method('_getService')
            ->will($this->returnValue($getServiceReturnValue));

        $serviceMock = $this->getMockBuilder('\\' . \Box\Mod\Servicehosting\Service::class)->getMock();
        $serviceMock->expects($this->atLeastOnce())
            ->method('changeAccountPassword')
            ->will($this->returnValue(true));

        $apiMock->setService($serviceMock);

        $result = $apiMock->change_password(array());
        $this->assertIsBool($result);
        $this->assertTrue($result);
    }

    public function testsync()
    {
        $getServiceReturnValue = array(new \Model_ClientOrder(), new \Model_ServiceHosting);
        $apiMock               = $this->getMockBuilder('\\' . \Box\Mod\Servicehosting\Api\Admin::class)
            ->onlyMethods(array('_getService'))
            ->getMock();

        $apiMock->expects($this->atLeastOnce())
            ->method('_getService')
            ->will($this->returnValue($getServiceReturnValue));

        $serviceMock = $this->getMockBuilder('\\' . \Box\Mod\Servicehosting\Service::class)->getMock();
        $serviceMock->expects($this->atLeastOnce())
            ->method('sync')
            ->will($this->returnValue(true));

        $apiMock->setService($serviceMock);

        $result = $apiMock->sync(array());
        $this->assertIsBool($result);
        $this->assertTrue($result);
    }

    public function testupdate()
    {
        $getServiceReturnValue = array(new \Model_ClientOrder(), new \Model_ServiceHosting);
        $apiMock               = $this->getMockBuilder('\\' . \Box\Mod\Servicehosting\Api\Admin::class)
            ->onlyMethods(array('_getService'))
            ->getMock();

        $apiMock->expects($this->atLeastOnce())
            ->method('_getService')
            ->will($this->returnValue($getServiceReturnValue));

        $serviceMock = $this->getMockBuilder('\\' . \Box\Mod\Servicehosting\Service::class)->getMock();
        $serviceMock->expects($this->atLeastOnce())
            ->method('update')
            ->will($this->returnValue(true));

        $apiMock->setService($serviceMock);

        $result = $apiMock->update(array());
        $this->assertIsBool($result);
        $this->assertTrue($result);
    }

    public function testmanager_get_pairs()
    {
        $serviceMock = $this->getMockBuilder('\\' . \Box\Mod\Servicehosting\Service::class)->getMock();
        $serviceMock->expects($this->atLeastOnce())
            ->method('getServerManagers')
            ->will($this->returnValue(array()));

        $this->api->setService($serviceMock);

        $result = $this->api->manager_get_pairs(array());
        $this->assertIsArray($result);
    }

    public function testserver_get_pairs()
    {
        $serviceMock = $this->getMockBuilder('\\' . \Box\Mod\Servicehosting\Service::class)->getMock();
        $serviceMock->expects($this->atLeastOnce())
            ->method('getServerPairs')
            ->will($this->returnValue(array()));

        $this->api->setService($serviceMock);

        $result = $this->api->server_get_pairs(array());
        $this->assertIsArray($result);
    }

    public function testserver_get_list()
    {
        $serviceMock = $this->getMockBuilder('\\' . \Box\Mod\Servicehosting\Service::class)->getMock();
        $serviceMock->expects($this->atLeastOnce())
            ->method('getServersSearchQuery')
            ->will($this->returnValue(array('SQLstring', array())));

        $pagerMock = $this->getMockBuilder('\Box_Pagination')->getMock();
        $pagerMock->expects($this->atLeastOnce())
            ->method('getSimpleResultSet')
            ->will($this->returnValue(array('list' => array())));

        $dbMock = $this->getMockBuilder('\Box_Database')->getMock();
        $dbMock->expects($this->atLeastOnce())
            ->method('find')
            ->will($this->returnValue(array()));

        $di              = new \Pimple\Container();
        $di['pager']     = $pagerMock;
        $di['db']        = $dbMock;


        $this->api->setDi($di);
        $this->api->setService($serviceMock);

        $result = $this->api->server_get_list(array());
        $this->assertIsArray($result);
    }

    public function testserver_create()
    {
        $data = array(
            'name'    => 'test',
            'ip'      => '1.1.1.1',
            'manager' => 'ServerManagerCode',
        );

        $newServerId = 1;

        $serviceMock = $this->getMockBuilder('\\' . \Box\Mod\Servicehosting\Service::class)->getMock();
        $serviceMock->expects($this->atLeastOnce())
            ->method('createServer')
            ->will($this->returnValue($newServerId));

        $validatorMock = $this->getMockBuilder('\\' . \FOSSBilling\Validate::class)->disableOriginalConstructor()->getMock();
        $validatorMock->expects($this->atLeastOnce())
            ->method('checkRequiredParamsForArray')
            ->will($this->returnValue(null));

        $di              = new \Pimple\Container();
        $di['validator'] = $validatorMock;
        $this->api->setDi($di);

        $this->api->setService($serviceMock);

        $result = $this->api->server_create($data);
        $this->assertIsInt($result);
        $this->assertEquals($newServerId, $result);
    }

    public function testserver_get()
    {
        $data['id'] = 1;

        $serviceMock = $this->getMockBuilder('\\' . \Box\Mod\Servicehosting\Service::class)->getMock();
        $serviceMock->expects($this->atLeastOnce())
            ->method('toHostingServerApiArray')
            ->will($this->returnValue(array()));

        $dbMock = $this->getMockBuilder('\Box_Database')->getMock();
        $dbMock->expects($this->atLeastOnce())
            ->method('getExistingModelById')
            ->will($this->returnValue(new \Model_ServiceHostingServer));

        $validatorMock = $this->getMockBuilder('\\' . \FOSSBilling\Validate::class)->disableOriginalConstructor()->getMock();
        $validatorMock->expects($this->atLeastOnce())
            ->method('checkRequiredParamsForArray')
            ->will($this->returnValue(null));

        $di              = new \Pimple\Container();
        $di['db']        = $dbMock;
        $di['validator'] = $validatorMock;

        $this->api->setDi($di);
        $this->api->setService($serviceMock);

        $result = $this->api->server_get($data);
        $this->assertIsArray($result);
    }

    public function testServerDelete()
    {
        // Test case 1: Server can be deleted
        $data['id'] = 1;

        $serviceMock = $this->getMockBuilder('\\' . \Box\Mod\Servicehosting\Service::class)->getMock();
        $serviceMock->expects($this->atLeastOnce())
            ->method('deleteServer')
            ->will($this->returnValue(true));

        $dbMock = $this->getMockBuilder('\Box_Database')->getMock();
        $dbMock->expects($this->atLeastOnce())
            ->method('getExistingModelById')
            ->will($this->returnValue(new \Model_ServiceHostingServer));

        $validatorMock = $this->getMockBuilder('\\' . \FOSSBilling\Validate::class)->disableOriginalConstructor()->getMock();
        $validatorMock->expects($this->atLeastOnce())
            ->method('checkRequiredParamsForArray')
            ->will($this->returnValue(null));

        $di              = new \Pimple\Container();
        $di['db']        = $dbMock;
        $di['validator'] = $validatorMock;

        $this->api->setDi($di);
        $this->api->setService($serviceMock);

        $result = $this->api->server_delete($data);
        $this->assertTrue($result);

        // Test case 2: Server is used by service_hostings and cannot be deleted
        $data['id'] = 2;

        $dbMock = $this->getMockBuilder('\Box_Database')->getMock();
        $dbMock->expects($this->atLeastOnce())
            ->method('getExistingModelById')
            ->will($this->returnValue(new \Model_ServiceHostingServer));

        $validatorMock = $this->getMockBuilder('\\' . \FOSSBilling\Validate::class)->disableOriginalConstructor()->getMock();
        $validatorMock->expects($this->atLeastOnce())
            ->method('checkRequiredParamsForArray')
            ->will($this->returnValue(null));

        // Mock the 'find' method to return a non-empty array, simulating the server being used by service hostings
        $dbMock->expects($this->atLeastOnce())
            ->method('find')
            ->will($this->returnValue(['dummy_data']));

        $di              = new \Pimple\Container();
        $di['db']        = $dbMock;
        $di['validator'] = $validatorMock;

        $this->api->setDi($di);

        // Now, we expect an exception to be thrown because the server is used by service_hostings
        $this->expectException(\FOSSBilling\Exception::class);
        $this->expectExceptionCode(704);

        $this->api->server_delete($data);
    }


    public function testserver_update()
    {
        $data['id'] = 1;

        $serviceMock = $this->getMockBuilder('\\' . \Box\Mod\Servicehosting\Service::class)->getMock();
        $serviceMock->expects($this->atLeastOnce())
            ->method('updateServer')
            ->will($this->returnValue(true));

        $dbMock = $this->getMockBuilder('\Box_Database')->getMock();
        $dbMock->expects($this->atLeastOnce())
            ->method('getExistingModelById')
            ->will($this->returnValue(new \Model_ServiceHostingServer));
        $validatorMock = $this->getMockBuilder('\\' . \FOSSBilling\Validate::class)->disableOriginalConstructor()->getMock();
        $validatorMock->expects($this->atLeastOnce())
            ->method('checkRequiredParamsForArray')
            ->will($this->returnValue(null));

        $di              = new \Pimple\Container();
        $di['db']        = $dbMock;
        $di['validator'] = $validatorMock;

        $this->api->setDi($di);
        $this->api->setService($serviceMock);

        $result = $this->api->server_update($data);
        $this->assertIsBool($result);
        $this->assertTrue($result);
    }

    public function testserver_test_connection()
    {
        $data['id'] = 1;

        $serviceMock = $this->getMockBuilder('\\' . \Box\Mod\Servicehosting\Service::class)->getMock();
        $serviceMock->expects($this->atLeastOnce())
            ->method('testConnection')
            ->will($this->returnValue(true));

        $dbMock = $this->getMockBuilder('\Box_Database')->getMock();
        $dbMock->expects($this->atLeastOnce())
            ->method('getExistingModelById')
            ->will($this->returnValue(new \Model_ServiceHostingServer));
        $validatorMock = $this->getMockBuilder('\\' . \FOSSBilling\Validate::class)->disableOriginalConstructor()->getMock();
        $validatorMock->expects($this->atLeastOnce())
            ->method('checkRequiredParamsForArray')
            ->will($this->returnValue(null));

        $di              = new \Pimple\Container();
        $di['db']        = $dbMock;
        $di['validator'] = $validatorMock;

        $this->api->setDi($di);
        $this->api->setService($serviceMock);

        $result = $this->api->server_test_connection($data);
        $this->assertIsBool($result);
        $this->assertTrue($result);
    }

    public function testhp_get_pairs()
    {
        $serviceMock = $this->getMockBuilder('\\' . \Box\Mod\Servicehosting\Service::class)->getMock();
        $serviceMock->expects($this->atLeastOnce())
            ->method('getHpPairs')
            ->will($this->returnValue(array()));

        $this->api->setService($serviceMock);
        $result = $this->api->hp_get_pairs(array());
        $this->assertIsArray($result);
    }

    public function testhp_get_list()
    {
        $serviceMock = $this->getMockBuilder('\\' . \Box\Mod\Servicehosting\Service::class)->getMock();
        $serviceMock->expects($this->atLeastOnce())
            ->method('getHpSearchQuery')
            ->will($this->returnValue(array('SQLstring', array())));

        $pagerMock = $this->getMockBuilder('\Box_Pagination')->getMock();
        $pagerMock->expects($this->atLeastOnce())
            ->method('getSimpleResultSet')
            ->will($this->returnValue(array('list' => array())));


        $di              = new \Pimple\Container();
        $di['pager']     = $pagerMock;


        $this->api->setDi($di);
        $this->api->setService($serviceMock);

        $result = $this->api->hp_get_list(array());
        $this->assertIsArray($result);
    }

    public function testhp_delete()
    {
        $data = array(
            'id' => 1,
        );

        $model = new \Model_ServiceHostingHp();

        $serviceMock = $this->getMockBuilder('\\' . \Box\Mod\Servicehosting\Service::class)->getMock();
        $serviceMock->expects($this->atLeastOnce())
            ->method('deleteHp')
            ->will($this->returnValue(true));

        $dbMock = $this->getMockBuilder('\Box_Database')->getMock();
        $dbMock->expects($this->atLeastOnce())
            ->method('getExistingModelById')
            ->will($this->returnValue($model));
        $validatorMock = $this->getMockBuilder('\\' . \FOSSBilling\Validate::class)->disableOriginalConstructor()->getMock();
        $validatorMock->expects($this->atLeastOnce())
            ->method('checkRequiredParamsForArray')
            ->will($this->returnValue(null));

        $di              = new \Pimple\Container();
        $di['db']        = $dbMock;
        $di['validator'] = $validatorMock;

        $this->api->setDi($di);
        $this->api->setService($serviceMock);

        // Add a try-catch block to handle the exception thrown in the hp_delete function
        try {
            $result = $this->api->hp_delete($data);

            // If the function doesn't throw an exception, then the test should assert the result
            $this->assertIsBool($result);
            $this->assertTrue($result);
        } catch (\FOSSBilling\Exception $e) {
            // If the function throws an exception, the test should fail
            $this->fail("Exception thrown: " . $e->getMessage());
        }
    }


    public function testhp_get()
    {
        $data = array(
            'id' => 1,
        );

        $model = new \Model_ServiceHostingHp();

        $serviceMock = $this->getMockBuilder('\\' . \Box\Mod\Servicehosting\Service::class)->getMock();
        $serviceMock->expects($this->atLeastOnce())
            ->method('toHostingHpApiArray')
            ->will($this->returnValue(array()));

        $dbMock = $this->getMockBuilder('\Box_Database')->getMock();
        $dbMock->expects($this->atLeastOnce())
            ->method('getExistingModelById')
            ->will($this->returnValue($model));

        $validatorMock = $this->getMockBuilder('\\' . \FOSSBilling\Validate::class)->disableOriginalConstructor()->getMock();
        $validatorMock->expects($this->atLeastOnce())
            ->method('checkRequiredParamsForArray')
            ->will($this->returnValue(null));

        $di              = new \Pimple\Container();
        $di['db']        = $dbMock;
        $di['validator'] = $validatorMock;

        $this->api->setDi($di);
        $this->api->setService($serviceMock);

        $result = $this->api->hp_get($data);
        $this->assertIsArray($result);
    }

    public function testhp_update()
    {
        $data = array(
            'id' => 1,
        );

        $model = new \Model_ServiceHostingHp();

        $serviceMock = $this->getMockBuilder('\\' . \Box\Mod\Servicehosting\Service::class)->getMock();
        $serviceMock->expects($this->atLeastOnce())
            ->method('updateHp')
            ->will($this->returnValue(true));

        $dbMock = $this->getMockBuilder('\Box_Database')->getMock();
        $dbMock->expects($this->atLeastOnce())
            ->method('getExistingModelById')
            ->will($this->returnValue($model));

        $validatorMock = $this->getMockBuilder('\\' . \FOSSBilling\Validate::class)->disableOriginalConstructor()->getMock();
        $validatorMock->expects($this->atLeastOnce())
            ->method('checkRequiredParamsForArray')
            ->will($this->returnValue(null));

        $di              = new \Pimple\Container();
        $di['db']        = $dbMock;
        $di['validator'] = $validatorMock;

        $this->api->setDi($di);
        $this->api->setService($serviceMock);

        $result = $this->api->hp_update($data);
        $this->assertIsBool($result);
        $this->assertTrue($result);
    }


    public function testhp_create()
    {
        $data = array(
            'name' => 'test',
        );

        $newHpId = 2;

        $serviceMock = $this->getMockBuilder('\\' . \Box\Mod\Servicehosting\Service::class)->getMock();
        $serviceMock->expects($this->atLeastOnce())
            ->method('createHp')
            ->will($this->returnValue($newHpId));

        $validatorMock = $this->getMockBuilder('\\' . \FOSSBilling\Validate::class)->disableOriginalConstructor()->getMock();
        $validatorMock->expects($this->atLeastOnce())
            ->method('checkRequiredParamsForArray')
            ->will($this->returnValue(null));

        $di              = new \Pimple\Container();
        $di['validator'] = $validatorMock;
        $this->api->setDi($di);

        $this->api->setService($serviceMock);

        $result = $this->api->hp_create($data);
        $this->assertIsInt($result);
        $this->assertEquals($newHpId, $result);
    }

    public function test_getService()
    {
        $data = array(
            'order_id' => 1,
        );

        $clientOrderModel = new \Model_ClientOrder();
        $dbMock           = $this->getMockBuilder('\Box_Database')->getMock();
        $dbMock->expects($this->atLeastOnce())
            ->method('getExistingModelById')
            ->will($this->returnValue($clientOrderModel));


        $model            = new \Model_ServiceHosting();
        $orderServiceMock = $this->getMockBuilder('\\' . \Box\Mod\Order\Service::class)->getMock();
        $orderServiceMock->expects($this->atLeastOnce())
            ->method('getOrderService')
            ->will($this->returnValue($model));
        $validatorMock = $this->getMockBuilder('\\' . \FOSSBilling\Validate::class)->disableOriginalConstructor()->getMock();
        $validatorMock->expects($this->atLeastOnce())
            ->method('checkRequiredParamsForArray')
            ->will($this->returnValue(null));

        $di                = new \Pimple\Container();
        $di['mod_service'] = $di->protect(fn() => $orderServiceMock);
        $di['db']          = $dbMock;
        $di['validator']   = $validatorMock;

        $this->api->setDi($di);

        $result = $this->api->_getService($data);
        $this->assertIsArray($result);
        $this->assertInstanceOf('\Model_ClientOrder', $result[0]);
        $this->assertInstanceOf('\Model_ServiceHosting', $result[1]);
    }

    public function test_getServiceOrderNotActivated()
    {
        $data = array(
            'order_id' => 1,
        );

        $clientOrderModel = new \Model_ClientOrder();
        $dbMock           = $this->getMockBuilder('\Box_Database')->getMock();
        $dbMock->expects($this->atLeastOnce())
            ->method('getExistingModelById')
            ->will($this->returnValue($clientOrderModel));


        $model            = null;
        $orderServiceMock = $this->getMockBuilder('\\' . \Box\Mod\Order\Service::class)->getMock();
        $orderServiceMock->expects($this->atLeastOnce())
            ->method('getOrderService')
            ->will($this->returnValue($model));

        $validatorMock = $this->getMockBuilder('\\' . \FOSSBilling\Validate::class)->disableOriginalConstructor()->getMock();
        $validatorMock->expects($this->atLeastOnce())
            ->method('checkRequiredParamsForArray')
            ->will($this->returnValue(null));

        $di                = new \Pimple\Container();
        $di['mod_service'] = $di->protect(fn() => $orderServiceMock);
        $di['db']          = $dbMock;
        $di['validator']   = $validatorMock;

        $this->api->setDi($di);

        $this->expectException(\FOSSBilling\Exception::class);
        $this->expectExceptionMessage('Order is not activated');
        $this->api->_getService($data);
    }
}

<?php


namespace Box\Mod\Paidsupport;


class ServiceTest extends \BBTestCase {
    /**
     * @var \Box\Mod\Paidsupport\Service
     */
    protected $service = null;

    public function setup(): void
    {
        $this->service= new \Box\Mod\Paidsupport\Service();
    }

    public function testgetDi()
    {
        $di = new \Pimple\Container();
        $this->service->setDi($di);
        $getDi = $this->service->getDi();
        $this->assertEquals($di, $getDi);
    }

    public function testenoughInBalanceToOpenTicket()
    {
        $clientModel = new \Model_Client();
        $clientModel->loadBean(new \DummyBean());

        $clientTotalAmount = 25;

        $clientBalanceMock = $this->getMockBuilder('\\' . \Box\Mod\Client\ServiceBalance::class)->getMock();
        $clientBalanceMock->expects($this->atLeastOnce())
            ->method('getClientBalance')
            ->with($clientModel)
            ->willReturn($clientTotalAmount);

        $paidSupportConfig = array(
            'ticket_price' => 2,
            'error_msg' => 'Insufficient funds to open ticket',
        );

        $di = new \Pimple\Container();
        $di['mod_service'] = $di->protect(function($serviceName, $subService) use ($clientBalanceMock){
            if ($serviceName == 'Client' && $subService == 'Balance') {
                return $clientBalanceMock;
            }
        });
        $di['mod_config'] = $di->protect(function($serviceName) use ($paidSupportConfig){
            if ($serviceName== 'Paidsupport'){
                return $paidSupportConfig;
            }
        });
        $this->service->setDi($di);

        $result = $this->service->enoughInBalanceToOpenTicket($clientModel);
        $this->assertTrue($result);
    }

    public function test_NotenoughInBalanceToOpenTicket()
    {
        $clientModel = new \Model_Client();
        $clientModel->loadBean(new \DummyBean());

        $clientTotalAmount = 0;

        $clientBalanceMock = $this->getMockBuilder('\\' . \Box\Mod\Client\ServiceBalance::class)->getMock();
        $clientBalanceMock->expects($this->atLeastOnce())
            ->method('getClientBalance')
            ->with($clientModel)
            ->willReturn($clientTotalAmount);

        $paidSupportConfig = array(
            'ticket_price' => 2,
            'error_msg' => 'Insufficient funds to open ticket',
        );

        $di = new \Pimple\Container();
        $di['mod_service'] = $di->protect(function($serviceName, $subService) use ($clientBalanceMock){
            if ($serviceName == 'Client' && $subService == 'Balance') {
                return $clientBalanceMock;
            }
        });
        $di['mod_config'] = $di->protect(function($serviceName) use ($paidSupportConfig){
            if ($serviceName== 'Paidsupport'){
                return $paidSupportConfig;
            }
        });

        $this->service->setDi($di);

        $this->expectException(\FOSSBilling\Exception::class);
        $this->expectExceptionMessage($paidSupportConfig['error_msg']);
        $this->service->enoughInBalanceToOpenTicket($clientModel);
    }

    public function test_enoughInBalanceToOpenTicket_TicketPriceEqualsTotalAmount()
    {
        $clientModel = new \Model_Client();
        $clientModel->loadBean(new \DummyBean());

        $clientTotalAmount = 4;

        $clientBalanceMock = $this->getMockBuilder('\\' . \Box\Mod\Client\ServiceBalance::class)->getMock();
        $clientBalanceMock->expects($this->atLeastOnce())
            ->method('getClientBalance')
            ->with($clientModel)
            ->willReturn($clientTotalAmount);

        $paidSupportConfig = array(
            'ticket_price' => $clientTotalAmount,
            'error_msg' => 'Insufficient funds to open ticket',
        );

        $di = new \Pimple\Container();
        $di['mod_service'] = $di->protect(function($serviceName, $subService) use ($clientBalanceMock){
            if ($serviceName == 'Client' && $subService == 'Balance') {
                return $clientBalanceMock;
            }
        });
        $di['mod_config'] = $di->protect(function($serviceName) use ($paidSupportConfig){
            if ($serviceName== 'Paidsupport'){
                return $paidSupportConfig;
            }
        });
        $this->service->setDi($di);

        $result = $this->service->enoughInBalanceToOpenTicket($clientModel);
        $this->assertTrue($result);
    }

    public function test_enoughInBalanceToOpenTicket_TicketPriceIsNotSet()
    {
        $clientModel = new \Model_Client();
        $clientModel->loadBean(new \DummyBean());

        $clientTotalAmount = 4;

        $clientBalanceMock = $this->getMockBuilder('\\' . \Box\Mod\Client\ServiceBalance::class)->getMock();
        $clientBalanceMock->expects($this->atLeastOnce())
            ->method('getClientBalance')
            ->with($clientModel)
            ->willReturn($clientTotalAmount);

        $paidSupportConfig = array();

        $di = new \Pimple\Container();
        $di['mod_service'] = $di->protect(function($serviceName, $subService) use ($clientBalanceMock){
            if ($serviceName == 'Client' && $subService == 'Balance') {
                return $clientBalanceMock;
            }
        });
        $di['mod_config'] = $di->protect(function($serviceName) use ($paidSupportConfig){
            if ($serviceName== 'Paidsupport'){
                return $paidSupportConfig;
            }
        });
        $this->service->setDi($di);

        $result = $this->service->enoughInBalanceToOpenTicket($clientModel);
        $this->assertTrue($result);
    }

    public function testonBeforeClientOpenTicket_PaidSupportForHelpdeskEnabled()
    {
        $di = new \Pimple\Container();

        $clientModel = new \Model_Client();
        $clientModel->loadBean(new \DummyBean());

        $dbMock = $this->getMockBuilder('\Box_Database')->getMock();
        $dbMock->expects($this->atLeastOnce())
            ->method('load')
            ->with('Client')
            ->willReturn($clientModel);
        $di['db'] = $dbMock;

        $paidSupportMock = $this->getMockBuilder('\\' . \Box\Mod\Paidsupport\Service::class)->getMock();
        $paidSupportMock->expects($this->atLeastOnce())
            ->method('hasHelpdeskPaidSupport')
            ->willReturn(true);
        $paidSupportMock->expects($this->atLeastOnce())
            ->method('enoughInBalanceToOpenTicket')
            ->with($clientModel);
        $di['mod_service'] = $di->protect(function ($serviceName) use($paidSupportMock){
            if ($serviceName == 'Paidsupport'){
                return $paidSupportMock;
            }
        });

        $params = array(
            'client_id' => 1,
            'support_helpdesk_id' => 1,
        );

        $boxEventMock = $this->getMockBuilder('\Box_Event')->disableOriginalConstructor()->getMock();
        $boxEventMock->expects($this->atLeastOnce())
            ->method('getDi')
            ->willReturn($di);
        $boxEventMock->expects($this->atLeastOnce())
            ->method('getParameters')
            ->willReturn($params);

        $result = $this->service->onBeforeClientOpenTicket($boxEventMock);
        $this->assertTrue($result);
    }

    public function testonBeforeClientOpenTicket_PaidSupportForHelpdeskDisabled()
    {
        $di = new \Pimple\Container();

        $clientModel = new \Model_Client();
        $clientModel->loadBean(new \DummyBean());

        $dbMock = $this->getMockBuilder('\Box_Database')->getMock();
        $dbMock->expects($this->atLeastOnce())
            ->method('load')
            ->with('Client')
            ->willReturn($clientModel);
        $di['db'] = $dbMock;

        $paidSupportMock = $this->getMockBuilder('\\' . \Box\Mod\Paidsupport\Service::class)->getMock();
        $paidSupportMock->expects($this->atLeastOnce())
            ->method('hasHelpdeskPaidSupport')
            ->willReturn(false);
        $paidSupportMock->expects($this->never())
            ->method('enoughInBalanceToOpenTicket')
            ->with($clientModel);
        $di['mod_service'] = $di->protect(function ($serviceName) use($paidSupportMock){
            if ($serviceName == 'Paidsupport'){
                return $paidSupportMock;
            }
        });

        $params = array(
            'client_id' => 1,
            'support_helpdesk_id' => 1,
        );

        $boxEventMock = $this->getMockBuilder('\Box_Event')->disableOriginalConstructor()->getMock();
        $boxEventMock->expects($this->atLeastOnce())
            ->method('getDi')
            ->willReturn($di);
        $boxEventMock->expects($this->atLeastOnce())
            ->method('getParameters')
            ->willReturn($params);

        $result = $this->service->onBeforeClientOpenTicket($boxEventMock);
        $this->assertTrue($result);
    }

    public function testgetTicketPrice()
    {
        $di = new \Pimple\Container();
        $paidSupportConfig = array(
            'ticket_price' => 1,
        );

        $di['mod_config'] = $di->protect(function($serviceName) use ($paidSupportConfig){
            if ($serviceName== 'Paidsupport'){
                return $paidSupportConfig;
            }
        });

        $this->service->setDi($di);
        $result = $this->service->getTicketPrice();
        $this->assertEquals($paidSupportConfig['ticket_price'], $result);
    }

    public function testgetTicketPrice_NotSet()
    {
        $di = new \Pimple\Container();
        $paidSupportConfig = array();

        $di['mod_config'] = $di->protect(function($serviceName) use ($paidSupportConfig){
            if ($serviceName== 'Paidsupport'){
                return $paidSupportConfig;
            }
        });

        $this->service->setDi($di);
        $result = $this->service->getTicketPrice();
        $this->assertEquals(0, $result);
    }

    public function testgetErrorMessage()
    {
        $di = new \Pimple\Container();
        $errorMessage = 'Not enough funds';
        $paidSupportConfig = array(
            'error_msg' => $errorMessage,
        );

        $di['mod_config'] = $di->protect(function($serviceName) use ($paidSupportConfig){
            if ($serviceName== 'Paidsupport'){
                return $paidSupportConfig;
            }
        });


        $this->service->setDi($di);
        $result = $this->service->getErrorMessage();
        $this->assertEquals($errorMessage, $result);
    }

    public function testgetErrorMessage_NotSet()
    {
        $di = new \Pimple\Container();
        $errorMessage = 'Configure paid support module!';
        $paidSupportConfig = array();

        $di['mod_config'] = $di->protect(function($serviceName) use ($paidSupportConfig){
            if ($serviceName== 'Paidsupport'){
                return $paidSupportConfig;
            }
        });


        $this->service->setDi($di);
        $result = $this->service->getErrorMessage();
        $this->assertEquals($errorMessage, $result);
    }

    public function testonAfterClientOpenTicket()
    {
        $di = new \Pimple\Container();

        $supportTicketModel = new \Model_SupportTicket();
        $supportTicketModel->loadBean(new \DummyBean());

        $clientModel = new \Model_Client();
        $clientModel->loadBean(new \DummyBean());

        $dbMock = $this->getMockBuilder('\Box_Database')->getMock();
        $dbMock->expects($this->atLeastOnce())
            ->method('load')
            ->willReturnCallback(function (...$args) use ($supportTicketModel, $clientModel) {
                $value = match($args[0]) {
                    'SupportTicket' => $supportTicketModel,
                    'Client' => $clientModel
                };

                return $value;
            });
        $di['db'] = $dbMock;

        $paidSupportMock = $this->getMockBuilder('\\' . \Box\Mod\Paidsupport\Service::class)->getMock();
        $paidSupportMock->expects($this->atLeastOnce())
            ->method('hasHelpdeskPaidSupport')
            ->willReturn(true);
        $paidSupportMock->expects($this->atLeastOnce())
            ->method('enoughInBalanceToOpenTicket')
            ->with($clientModel);

        $clientBalanceMock = $this->getMockBuilder('\\' . \Box\Mod\Client\ServiceBalance::class)->getMock();
        $clientBalanceMock->expects($this->atLeastOnce())
            ->method('deductFunds');

        $di['mod_service'] = $di->protect(function ($serviceName, $sub ='') use($paidSupportMock, $clientBalanceMock){
            if ($serviceName == 'Paidsupport'){
                return $paidSupportMock;
            }
            if ($serviceName == 'Client' && $sub == 'Balance'){
                return $clientBalanceMock;
            }
        });

        $params = array(
            'id' => 1,
            'support_helpdesk_id' => 1,
        );

        $boxEventMock = $this->getMockBuilder('\Box_Event')->disableOriginalConstructor()->getMock();
        $boxEventMock->expects($this->atLeastOnce())
            ->method('getDi')
            ->willReturn($di);
        $boxEventMock->expects($this->atLeastOnce())
            ->method('getParameters')
            ->willReturn($params);

        $result = $this->service->onAfterClientOpenTicket($boxEventMock);
        $this->assertTrue($result);
    }

    public function testonAfterClientOpenTicket_PaidSupportDisabledForHelpdesk()
    {
        $di = new \Pimple\Container();

        $supportTicketModel = new \Model_SupportTicket();
        $supportTicketModel->loadBean(new \DummyBean());

        $clientModel = new \Model_Client();
        $clientModel->loadBean(new \DummyBean());

        $dbMock = $this->getMockBuilder('\Box_Database')->getMock();
        $dbMock->expects($this->atLeastOnce())
            ->method('load')
            ->willReturnCallback(function (...$args) use ($supportTicketModel, $clientModel) {
                $value = match($args[0]) {
                    'SupportTicket' => $supportTicketModel,
                    'Client' => $clientModel
                };

                return $value;
            });
        $di['db'] = $dbMock;

        $paidSupportMock = $this->getMockBuilder('\\' . \Box\Mod\Paidsupport\Service::class)->getMock();
        $paidSupportMock->expects($this->atLeastOnce())
            ->method('hasHelpdeskPaidSupport')
            ->willReturn(false);
        $paidSupportMock->expects($this->never())
            ->method('enoughInBalanceToOpenTicket')
            ->with($clientModel);

        $di['mod_service'] = $di->protect(function ($serviceName, $sub ='') use($paidSupportMock){
            if ($serviceName == 'Paidsupport'){
                return $paidSupportMock;
            }
        });

        $params = array(
            'id' => 1,
            'support_helpdesk_id' => 1,
        );

        $boxEventMock = $this->getMockBuilder('\Box_Event')->disableOriginalConstructor()->getMock();
        $boxEventMock->expects($this->atLeastOnce())
            ->method('getDi')
            ->willReturn($di);
        $boxEventMock->expects($this->atLeastOnce())
            ->method('getParameters')
            ->willReturn($params);

        $result = $this->service->onAfterClientOpenTicket($boxEventMock);
        $this->assertTrue($result);
    }

    public function testgetPaidHelpdeskConfig()
    {
        $di = new \Pimple\Container();
        $helpdeskId = 2;
        $helpdeskConfig = array(
            $helpdeskId => 0
        );
        $paidSupportConfig = array(
            'helpdesk' => $helpdeskConfig,
        );

        $di['mod_config'] = $di->protect(function($serviceName) use ($paidSupportConfig){
            if ($serviceName== 'Paidsupport'){
                return $paidSupportConfig;
            }
        });

        $this->service->setDi($di);
        $result = $this->service->getPaidHelpdeskConfig();
        $this->assertIsArray($result);
        $this->assertNotEmpty($result);
        $this->assertEquals($helpdeskConfig, $result);

    }

    public function testgetPaidHelpdeskConfig_IsNotSet()
    {
        $di = new \Pimple\Container();
        $paidSupportConfig = array();

        $di['mod_config'] = $di->protect(function($serviceName) use ($paidSupportConfig){
            if ($serviceName== 'Paidsupport'){
                return $paidSupportConfig;
            }
        });

        $this->service->setDi($di);
        $result = $this->service->getPaidHelpdeskConfig();
        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    public function testhasHelpdeskPaidSupport_turnedOff()
    {
        $helpdeskId = 1;
        $helpdeskConfig = array(
            $helpdeskId => 0
        );
        $paidSupportServiceMock = $this->getMockBuilder('\\' . \Box\Mod\Paidsupport\Service::class)
            ->onlyMethods(array('getPaidHelpdeskConfig'))
            ->getMock();
        $paidSupportServiceMock->expects($this->atLeastOnce())
            ->method('getPaidHelpdeskConfig')
            ->willReturn($helpdeskConfig);

        $result = $paidSupportServiceMock->hasHelpdeskPaidSupport($helpdeskId);
        $this->assertFalse($result);
    }

    public function testhasHelpdeskPaidSupport_turnedOn()
    {
        $helpdeskId = 1;
        $helpdeskConfig = array(
            $helpdeskId => 1
        );
        $paidSupportServiceMock = $this->getMockBuilder('\\' . \Box\Mod\Paidsupport\Service::class)
            ->onlyMethods(array('getPaidHelpdeskConfig'))
            ->getMock();
        $paidSupportServiceMock->expects($this->atLeastOnce())
            ->method('getPaidHelpdeskConfig')
            ->willReturn($helpdeskConfig);

        $result = $paidSupportServiceMock->hasHelpdeskPaidSupport($helpdeskId);
        $this->assertTrue($result);
    }

    public function testhasHelpdeskPaidSupport_ConfigNotConfigured()
    {
        $helpdeskId = 1;
        $helpdeskConfig = array();

        $paidSupportServiceMock = $this->getMockBuilder('\\' . \Box\Mod\Paidsupport\Service::class)
            ->onlyMethods(array('getPaidHelpdeskConfig'))
            ->getMock();
        $paidSupportServiceMock->expects($this->atLeastOnce())
            ->method('getPaidHelpdeskConfig')
            ->willReturn($helpdeskConfig);

        $result = $paidSupportServiceMock->hasHelpdeskPaidSupport($helpdeskId);
        $this->assertFalse($result);
    }

    public function testPaidSupportAppliedForAllHelpdesks_AllHelpdesksAreNotChecked()
    {
        $di = new \Pimple\Container();
        $helpdeskId = 2;
        $helpdeskId1 = 3;
        $helpdeskConfig = array(
            $helpdeskId => 0,
            $helpdeskId1 => 0
        );
        $paidSupportConfig = array(
            'helpdesk' => $helpdeskConfig,
        );

        $di['mod_config'] = $di->protect(function($serviceName) use ($paidSupportConfig){
            if ($serviceName == 'Paidsupport'){
                return $paidSupportConfig;
            }
        });

        $this->service->setDi($di);
        $result = $this->service->hasHelpdeskPaidSupport($helpdeskId);
        $this->assertFalse($result);
    }

    public function testUninstall()
    {
        $di = new \Pimple\Container();

        $dbMock = $this->getMockBuilder('\Box_Database')->getMock();
        $model = new \Model_ExtensionMeta();
        $dbMock->expects($this->atLeastOnce())
            ->method('findOne')
            ->with('ExtensionMeta')
            ->willReturn($model);
        $dbMock->expects($this->atLeastOnce())
            ->method('trash')
            ->with($model);

        $di['db'] = $dbMock;

        $this->service->setDi($di);
        $result = $this->service->uninstall();
        $this->assertTrue($result);
    }

    public function testUninstall_ConfigNotFound()
    {
        $di = new \Pimple\Container();

        $dbMock = $this->getMockBuilder('\Box_Database')->getMock();
        $model = new \Model_ExtensionMeta();
        $dbMock->expects($this->atLeastOnce())
            ->method('findOne')
            ->with('ExtensionMeta');
        $dbMock->expects($this->never())
            ->method('trash');

        $di['db'] = $dbMock;

        $this->service->setDi($di);
        $result = $this->service->uninstall();
        $this->assertTrue($result);
    }

    public function testInstall()
    {
        $di = new \Pimple\Container();

        $extensionServiceMock = $this->getMockBuilder('\\' . \Box\Mod\Extension\Service::class)->getMock();
        $extensionServiceMock->expects($this->atLeastOnce())
            ->method('setConfig')
            ->willReturn(true);

        $di['mod_service'] = $di->protect(function ($serviceName) use ($extensionServiceMock){
            if ($serviceName == 'Extension'){
                return $extensionServiceMock;
            }
        });

        $this->service->setDi($di);
        $result = $this->service->install();
        $this->assertTrue($result);
    }
}

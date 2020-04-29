<?php

declare(strict_types=1);

namespace Ingenico\ConnectGraphQl\Test\Unit\Model\Resolver;

use Ingenico\Connect\Api\SessionManagerInterface;
use Ingenico\Connect\Model\Ingenico\Session\Session as IngenicoSession;
use Ingenico\ConnectGraphQl\Model\Resolver\IngenicoClientSession;
use Magento\Customer\Model\Session;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class IngenicoClientSessionTest extends TestCase
{
    /**
     * @var IngenicoClientSession|MockObject
     */
    private $subject;

    /**
     * @var SessionManagerInterface|MockObject
     */
    private $mockedSessionManager;

    /**
     * @var Session|MockObject
     */
    private $mockedCustomerSession;

    protected function setUp()
    {
        $this->mockSessionManager();
        $this->mockedCustomerSession = $this
            ->getMockBuilder(Session::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->subject = $this->getObjectManager()->getObject(
            IngenicoClientSession::class,
            [
                'sessionManager' => $this->mockedSessionManager,
                'customerSession' => $this->mockedCustomerSession,
            ]
        );
    }

    public function testAnonymousCustomerWillGetAnonymousSession()
    {
        // Setup:
        $this->mockedCustomerSession
            ->method('isLoggedIn')
            ->willReturn(false);

        // Set expectations:
        $this->mockedSessionManager
            ->expects($this->once())
            ->method('createAnonymousSession');
        $this->mockedSessionManager
            ->expects($this->never())
            ->method('createCustomerSession');

        // Exercise:
        $this->subject->resolve(
            $this->mockField(),
            $this->mockContext(),
            $this->mockResolveInfo()
        );
    }

    public function testRegisteredCustomerWillGetCustomerSession()
    {
        // Setup:
        $this->mockedCustomerSession
            ->method('isLoggedIn')
            ->willReturn(true);

        // Set expectations:
        $this->mockedSessionManager
            ->expects($this->never())
            ->method('createAnonymousSession');
        $this->mockedSessionManager
            ->expects($this->once())
            ->method('createCustomerSession');

        // Exercise:
        $this->subject->resolve(
            $this->mockField(),
            $this->mockContext(),
            $this->mockResolveInfo()
        );
    }

    private function getObjectManager(): ObjectManager
    {
        return new ObjectManager($this);
    }

    /**
     * @return MockObject|Field
     */
    private function mockField()
    {
        return $this
            ->getMockBuilder(Field::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @return MockObject|ContextInterface
     */
    private function mockContext()
    {
        return $this
            ->getMockBuilder(ContextInterface::class)
            ->getMock();
    }

    /**
     * @return MockObject|ResolveInfo
     */
    private function mockResolveInfo()
    {
        return $this
            ->getMockBuilder(ResolveInfo::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    private function mockSessionManager()
    {
        $this->mockedSessionManager = $this
            ->getMockBuilder(SessionManagerInterface::class)
            ->getMock();
        /** @var IngenicoSession|MockObject $mockedSession */
        $mockedSession = $this
            ->getMockBuilder(IngenicoSession::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockedSession->method('toJson')->willReturn('{}');
        $this->mockedSessionManager
            ->method('createAnonymousSession')
            ->willReturn($mockedSession);
        $this->mockedSessionManager
            ->method('createCustomerSession')
            ->willReturn($mockedSession);
    }
}

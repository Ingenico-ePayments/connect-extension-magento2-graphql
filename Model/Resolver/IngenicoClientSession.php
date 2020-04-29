<?php

declare(strict_types=1);

namespace Ingenico\ConnectGraphQl\Model\Resolver;

use Ingenico\Connect\Api\SessionManagerInterface;
use Ingenico\Connect\Sdk\DataObject;
use Magento\Customer\Model\Session;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;
use Magento\Framework\GraphQl\Query\Resolver\Value;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

use function __;
use function json_decode;

class IngenicoClientSession implements ResolverInterface
{
    /**
     * @var SessionManagerInterface
     */
    private $sessionManager;

    /**
     * @var Session
     */
    private $customerSession;

    public function __construct(
        SessionManagerInterface $sessionManager,
        Session $customerSession
    ) {
        $this->sessionManager = $sessionManager;
        $this->customerSession = $customerSession;
    }

    /**
     * @param Field $field
     * @param ContextInterface $context
     * @param ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     * @return Value|mixed
     * @throws LocalizedException
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        $session = $this->customerSession->isLoggedIn() ?
            $this->sessionManager->createCustomerSession((int) $this->customerSession->getCustomerId()) :
            $this->sessionManager->createAnonymousSession();

        if (!$session instanceof DataObject) {
            throw new LocalizedException(__('Invalid session type'));
        }

        return json_decode($session->toJson(), true);
    }
}

<?php

declare(strict_types=1);

namespace Ingenico\ConnectGraphQl\Model\Resolver;

use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\QuoteGraphQl\Model\Cart\Payment\AdditionalDataProviderInterface;

use function __;
use function array_key_exists;

class IngenicoDataProvider implements AdditionalDataProviderInterface
{
    const PATH_ADDITIONAL_DATA = 'ingenico';

    /**
     * @param array $args
     * @return array
     * @throws GraphQlInputException
     */
    public function getData(array $args): array
    {
        if (!array_key_exists(self::PATH_ADDITIONAL_DATA, $args)) {
            throw new GraphQlInputException(
                __('Required parameter "ingenico" for "payment_method" is missing.')
            );
        }
        return $args[self::PATH_ADDITIONAL_DATA];
    }
}

# Ingenico Connect GraphQL Module for Magento 2

This module adds GraphQl support to the [Ingenico Connect Module for Magento 2](https://github.com/Ingenico-ePayments/connect-extension-magento2).

**Please note that this module is currently in `0.x`-release and should
therefor be considered 'unstable'. This does not mean that the module will
not work as expected but that it's public API is not definitive yet.**

## Usage

This module adds an option to GraphQl to generate a [consumer session](https://epayments-api.developer-ingenico.com/s2sapi/v1/en_US/java/sessions/create.html) 
that you can use with the [Ingenico mobile and browser SDK's](https://epayments.developer-ingenico.com/documentation/sdk/mobile/).

## Installation instructions

This module can be installed using Composer:

    composer require ingenico-epayments/connect-extension-magento2-graphql

## Example

The following GraphQl query will create a consumer session with Ingenico:

```graphql
{
  ingenicoClientSession {
    assetUrl
    clientApiUrl
    clientSessionId
    customerId
    invalidTokens
    region
  }
}
```

Example response:

```json
{
  "data": {
    "ingenicoClientSession": {
      "assetUrl": "https://assets.pay1.preprod.secured-by-ingenico.com/",
      "clientApiUrl": "https://ams1.preprod.api-ingenico.com/client",
      "clientSessionId": "ccf8ee1015944ab09e053411e683b43f",
      "customerId": "11492-214bf4b4d0db4321a5e006e0ec6f080b",
      "invalidTokens": null,
      "region": "EU"
    }
  }
}
```

### Registered Customers in Magento

If you're making a request for a registered customer in Magento (a customer
that is currently logged in), please make sure that you've 
[generated a customer token](https://devdocs.magento.com/guides/v2.3/graphql/get-customer-authorization-token.html)
and included it in the HTTP Headers.

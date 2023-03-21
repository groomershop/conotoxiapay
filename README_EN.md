# Conotoxia Pay - payment module for Magento 2
The module adds Conotoxia Pay payment gateway to Magento 2.

## Changelog
- 1.12.0 Payment action select option has been hidden.
- 1.10.0 Added a possibility to hide the icon on the payment selection list.
- 1.9.0 Added the ability to select additional payment method icons.
- 1.8.0 Plugin updated to be compatible with Magento 2.4.4 and PHP 8.1.
- 1.4.0 Added option to select visible payment method icons on the payment method selection screen.
- 1.2.0 Added support for refund CANCELLED status.

## Table of contents

* [Requirements](#requirements)
* [Installation](#installation)
* [Configuration](#configuration)
    * [Module configuration in Magento 2](#module-configuration-in-magento-2)
    * [Point of sale configuration in the Merchant's Panel](#point-of-sale-configuration-in-the-merchants-panel)
* [Refunds](#refunds)

## Requirements
* Magento 2.4.4
* PHP 8.1
* PHP extensions:
    * curl
    * json
    * openssl
    * readline

## Installation
1. The module should be downloaded from the [conotoxia.com](https://conotoxia.com/payments/for-developers) website.
2. Extract the archive and copy the `Conotoxia` directory to `app/code/` in your Magento 2 instance.
3. Sign in to your server via SSH.
4. Navigate to your Magento installation directory.
5. Enable the module:
    - `php bin/magento module:enable Conotoxia_Pay`.
6. Update the database:
    - `php bin/magento setup:upgrade`.
7. Compile code and dependency injection:
    - `php bin/magento setup:di:compile`.
8. Deploy static view files (production mode only):
    - `php bin/magento setup:static-content:deploy`.
9. Check permissions on directories and files and set them correctly if needed.

## Configuration
The following guide implies that the Partner has completed the configuration of the account in the [Merchant's Panel](https://fx.conotoxia.com/merchant).

### Module configuration in Magento 2
1. In `Stores -> Configuration`, select `Sales -> Payment Methods`.
2. In `OTHER PAYMENT METHODS` section, find `Conotoxia Pay` and then click `Configure`.
3. Enter the necessary data in the module configuration:
    - `API Client identifier*` and `API Client secret*` - access data can be generated
     in [Merchant's Panel](https://fx.conotoxia.com/merchant/configuration) (`Access Data` section).
    - `Point of Sale ID` - identifier of the created point of sale from the [Merchant's Panel](https://fx.conotoxia.com/merchant).
    - `Private key` - it is possible to generate a private key on the module configuration page. A public key is 
     generated from the private key entered on the module configuration page. This key is automatically transferred to
     Conotoxia Pay upon saving the configuration. It is not necessary to enter the key in the Merchant's Panel. 
     Additional instructions on how to generate keys can be found in the [documentation](https://docs.conotoxia.com/payments/online-shops#generating-a-public-key).
    - `Sort Order` - sort order in payment methods list on the checkout page.
4. Select visible payment method icons on the payment selection screen.
5. Enable Conotoxia Pay.
6. Save configuration.

`*` Data can be obtained by going through the wizard in [Merchant's Panel](https://fx.conotoxia.com/merchant).

### Point of sale configuration in the [Merchant's Panel](https://fx.conotoxia.com/merchant)
The point of sale should be set up according to the configuration below:  

- `URL address for payment creation notification`  
 e.g. https://magento.store.pl/conotoxia_pay/receive/notifications
  
- `URL address for refund creation notification`  
 e.g. https://magento.store.pl/conotoxia_pay/receive/notifications
  
- `URL address for successfully executed payment`  
 e.g. https://magento.store.pl/checkout/onepage/success
  
- `URL address for unsuccessful payment`  
 e.g. https://magento.store.pl/checkout/onepage/success

The `magento.store.pl` should be replaced with your Magento shop domain.

## Refunds
Refunds can be ordered from within the module and from the [Merchant's Panel](https://fx.conotoxia.com/merchant).

## Payment actions
This plugin operates in `Authorize` mode by default.  
More information in [the documentation](https://docs.magento.com/user-guide/configuration/sales/payment-methods.html#payment-actions).

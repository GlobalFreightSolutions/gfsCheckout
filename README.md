# GFS (Global Freight Solutions) - Magento 2 Module

This module provides and integration for the Global Freight Solutions Checkout Widget that will provide a variety of shipping options that will be shown in the Magento 2 Checkout

## Version:
2.0.0

## Developers:
Global Freight Solutions - https://github.com/GlobalFreightSolutions

## Installation

To use this module in your Magento 2 store instance, you will need to run the composer command `composer require justshout/magento2-gfs`. When this has been done, go to the `bin` folder in the magento 2 project and then to the following

1. Run the module enabling command `php magento module:enable JustShout_Gfs`
2. Run the upgrade command with `php magento setup:upgrade`
3. Run the static content command with the stores language so if its uk english it would be `php magento setup:static-content:deploy en_GB`

For a more in-depth guide of how to install the module from the Magento 2 Marketplace please visit https://devdocs.magento.com/guides/v2.2/comp-mgr/install-extensions.html

## User Guide

To setup the shipping module, log into your admin panel and navigate to your configuration by going to STORES > Settings > Configuration and then going to Sales > Shipping Methods, and after doing this, you will be able to see the GFS tab.

To enable the GFS module, you will either need to login or register with GFS to retrieve your Retailer ID and Retailer Secret keys.

After you have received these details, set the Enabled dropdown to `Yes` and then copy your details to the `Retailer ID` and `Retailer Secret` Fields and the click Save Config. You will then be able to test your connection to GFS by clicking the Test Connection button.

After setting these options, you will have a variety of options you can change to personalise the GFS widgets in your magento checkout
Please note that by installing and enabling the GFS module, other shipping methods will not be available on the store.


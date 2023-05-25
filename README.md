# ![shurjoPay](https://shurjopay.com.bd/dev/images/shurjoPay.png) Magento 2 plugin package

[![Test Status](https://github.com/rust-random/rand/workflows/Tests/badge.svg?event=push)]()
![NPM](https://img.shields.io/npm/l/sp-plugin)


Official shurjoPay Magento 2 plugin for merchants or service providers to connect with [**_shurjoPay_**](https://shurjopay.com.bd) Payment Gateway v2.1 developed and maintained by [_**ShurjoMukhi Limited**_](https://shurjomukhi.com.bd).
### The steps in this installation guide will allow you to easily install the Shurjopay Magento 2 plugin.To see shurjopay in action, check out our [Magento sample project](https://github.com/shurjopay-plugins/sp-plugin-usage-magento) as well.
### ➡ Note : shurjoPay Magento 2 plugin will work on magento 2.3.x | 2.4.x | 2.4.5-p1 

# ⚙️Installation and Configuration:

### ➡ Step 1
* Install **Magento 2**
    *(standard installation process)*

### ➡ Step 2
* Download the plugin contents from the github repo as zip and extract them.

* Then copy the **shurjomukhi**  folder and paste the folder to your Magento directory **“app/code”**

### ➡ Step 3
* Run these command in your terminal to build shurjopay module in Magento 2.
####    Note: Remember to open terminal in Magento root directory.

```PHP
php bin/magento indexer:reindex
php bin/magento setup:upgrade
php bin/magento setup:static-content:deploy -f
php bin/magento cache:flush
```
### ➡ Step 4
* Login to Magento Panel as Admin.
* Go to Stors->Configuration->SALES, select **Payment Methods** option in “SALES”.

![filter_payments](./Readme%20Pictures/Magento-1.png)

* Find **SHURJOPAY PAYMENT GATEWAY** there and to active shurjopay select Enabled to “Yes”.
* Save the shurjopay merchant credentials in Admin settings of the plugin.\
    _(select Test Mode checkbox to YES if you want to test in sandbox with sandbox credentials)_\
     _(select Test Mode checkbox to No if you want to go Live with Live credentials)_

    * **Merchant ID**, **Merchant Password**, and **Merchant Prefix** after merchant onboarding, be sent through email.
    * To onboard please query in [shurjoPay](https://shurjopay.com.bd) contact.
    
![install_edit_plugin](./Readme%20Pictures/Magento-2.png)

### ➡ Step 5

* Click on “Save Config” button.
* Then there will be a link for “Cache Management”,Click on “Cache Management”.

![install_edit_plugin](./Readme%20Pictures/Magento-3.png)

* In Cache Management click on “Flush Magento Cache” button.

![cache_clear](./Readme%20Pictures/Magento-4.png)

### shurjoPay will be successfully integrated with Magento 2.

## References

1. [Magento 2 sample project](https://github.com/shurjopay-plugins/sp-plugin-usage-magento) showing usage of the Magento plugin.
2. [Sample applications and projects](https://github.com/shurjopay-plugins/sp-plugin-usage-examples) in many different languages and frameworks showing shurjopay integration.
3. [shurjoPay Postman site](https://documenter.getpostman.com/view/6335853/U16dS8ig) illustrating the request and response flow using the sandbox system.
4. [shurjopay Plugins](https://github.com/shurjopay-plugins) home page on github

## License

This code is under the [MIT open source License](http://www.opensource.org/licenses/mit-license.php).

#### Please [contact](https://shurjopay.com.bd/#contacts) with shurjoPay team for more detail.

Copyright ©️2023 [ShurjoMukhi Limited](https://shurjomukhi.com.bd).

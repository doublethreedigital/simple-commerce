# Changelog

## Unreleased

## v2.3.7 (2021-06-26)

### What's fixed

* Updated the gateway stub so it's up-to-date
* `_request` will no longer be saved to the order entry when saving.

### What's new

* New Country fieldtype
* When creating an entry, we'll now save any default fields from your blueprint. #433
* Both `StockRunningLow` and `StockRunOut` events are now available triggers for notifications. #423

## v2.3.6 (2021-06-11)

## What's fixed

* When updating a line item, the metadata is no longer overwritten #431

## What's improved

* You can now use a custom request on the `{{ sc:cart:addItem }}` and `{{ sc:cart:updateItem }}` tags #432

## v2.3.5 (2021-06-10)

## What's fixed

* You can now set line item metadata when adding a new line item.

## v2.3.4 (2021-06-09)

### What's fixed

* Fixed tax calculations if result is a rounded number, with no decimals #429

### What's improved

* 'United States' and 'Canada' aren't at the top of the countries list anymore
* Removed `sc:info` command - it's never been used

## v2.3.3 (2021-06-05)

## What's new

* You can now specify a Form Request on some form tags (for custom validation rules) #425

## v2.3.2 (2021-06-03)

### What's improved

* Refactored usage of `findBySlug`, it's being deprecated soon and will likely be removed in Statamic 3.2 #424
* When using `Something::create`, you can now provide `slug` or `published` to manually set the slug/published status.
* Checkout: Payment is now done at the very end of the checkout request, rather than just before the coupon. Orders will also be recalculated before payment in case of any last-minute changes.
* Checkout: You can now set the Customer's ID in the checkout request
* Checkout: You can now redeem coupons as part of the checkout request
* Checkout: Refactored some stuff around product stock
* Checkout: Added a job lot of automated tests to cover the checkout flow

### What's fixed

* Fixed IDE completion on the `Coupon` facade (was suggesting Order methods, rather than Coupon methods)
* Checkout: Free orders will now be marked as paid again after checkout
* Fixed issue where coupons limited to certain products could be valid/non-valid accidentally.

## v2.3.1 (2021-05-17)

### What's new?

* You can now grab 'raw' data through the Cart Tag, rather than augmented data. (`{{ sc:cart:rawGrandTotal }}`)

## v2.3.0 (2021-05-10)

While there's been quite a few breaking changes between v2.2 and v2.3, most of them have been addressed by [Update Scripts](https://statamic.dev/knowledge-base/configuring-update-scripts#what-are-update-scripts), which will be run automatically when updating Simple Commerce.

Simple Commerce v2.3 requires your site to be running Statamic 3.1 and configured correctly for update scripts.

Please review the [Upgrade Guide](https://sc-docs.doublethree.digital/v2.3/update-guide) before updating.

### What's new

* It's now easier to swap out the driver being used for products, orders, coupons or customers.
* You can now mark any unpaid orders as paid from inside the Control Panel.
* Events have been renamed and parameters have been switched about.
* Notifications have been refactored! (Again...)
* The `Address` DTO now contains some more helpful methods.
* Product Variants now have their very own DTO
* You can now filter by Order Status in the CP

### Breaking changes

* Translations have been simplified. All translations live in the `messages.php` file. If you override the translations, please review.
* Built-in gateways have been moved from `Gateways\GatewayName` to `Gateways\Builtin\GatewayName`
* Gateway DTOs are now called `Response`, `Purchase` and `Prepare` (Gateway is no longer in the name)
* Updates have been made to Data Contracts, please review if you are overriding any of them.
* If you're overriding any of the Data Classes, please register it inside the updated config file, rather than manually via the Service Container.
* `Cart` facade has been removed (it was deprecated in v2.2). Please replace with the `Order` facade.
* Event parameters & event names have been changed. Please review if you are listening for any Simple Commerce events.
* Notifications have been refactored - they now use Laravel Notifications, rather than Mailables. If you were overriding the notifications previously, you will need to refactor into Notifications and update inside the Simple Commerce config.

## v2.3.0-beta.5 (2021-05-03)

### What's fixed

* 500 error on Mollie Webhook with successful payment #422

## v2.3.0-beta.4 (2021-05-03)

### What's fixed

* `Trailing data` error would sometimes appear when viewing paid orders in the CP

## v2.3.0-beta.3 (2021-04-30)

### What's fixed

* Updated parameters of `CouponRedeemed` event.
* Fixed issue where upgrade scripts would error if you're configuration is cached. #421

## v2.3.0-beta.2 (2021-04-23)

### What's new

* The cart update request will respect any validation rules you have in your order blueprint #417

### What's fixed

* Fixed an issue where update scripts wouldn't be run when upgrading to a beta release.

## v2.3.0-beta.1 (2021-04-22)

I've not got a solid date for v2.3 release yet but here's the first of a couple beta releases. Feel free to test it if you've got any spare time.

### Updating to the beta

* Set `minimum-stability` to `"dev"` or `"alpha"`
* Change `"doublethreedigital/simple-commerce"` to `"2.3.*"`

```json
// composer.json

"minimum-stability": "alpha",
"require": {
    "doublethreedigital/simple-commerce": "2.3.*",
    // all the other stuff...
},
```

* Then run: `composer update doublethreedigital/simple-commerce --with-all-dependencies`

FYI: You'll need to be running Statamic 3.1 to install the beta.

### More info

For more information on what's new and any breaking changes, review the [`CHANGELOG.md`](https://github.com/doublethreedigital/simple-commerce/blob/2.3/CHANGELOG.md).

## v2.2.21 (2021-04-20)

* [fix] Don't fail if user's cart has been deleted. Create a new one instead. #416

## v2.2.20 (2021-04-16)

* [fix] Fixed exception when running Refund action on Order entry.
* [fix] Tidied up the orders CP listing for new sites.

## v2.2.19 (2021-04-02)

* [fix] Fix issues with coupon calculations #405

## v2.2.18 (2021-03-30)

* [fix] Fixed issue where shipping & billing addresses would not be output on PDF Receipts #404

## v2.2.17 (2021-03-29)

* [new] Statamic 3.1 support
* [new] Product Specific Coupons #390
* [new] Added docblocks to Facades #400
* [new] Added country validation when submitting addresses #398 #402
* [fix] Allow for calculator to be run with any `Order` class

## v2.2.16 (2021-03-13)

* [fix] Fixed issue with `GatewayDoesNotExist` exception
* [fix] Ensure we don't have two slashes in Gateway Webhook URL #387
* [fix] Order Confirmation emails will now be sent for off-site gateways #395

## v2.2.15 (2021-03-10)

* [new] Ability to bind your own `Calculator` class
* [fix] Fixed bug where `items_total` would be a string when using coupons.
* Refactored the `Calculator`

## v2.2.14 (2021-03-08)

* [new] A new [`ReceiveGatewayWebhook`](https://github.com/doublethreedigital/simple-commerce/blob/master/src/Events/ReceiveGatewayWebhook.php) event is dispatched when a gateway webhook is received.
* [new] You can now specify a different redirect URL for errors. - `error_redirect`.
* [fix] Improved handling of Mollie webhooks, we now detect if an order has been paid and redirect correctly. #384
* [fix] Fixed issue where cookie cart driver wasn't forgetting cart after checkout #383
* [fix] An exception will be thrown when a gateway errors, instead of a die dump.
* [fix] Fixed webhook and callback URLs sometimes not being formed correctly.
* [fix] Fixed an occasionaly exception with the Cookie Driver.
* Deprecated 'order item' methods, and replaced them with 'line item' methods.


## v2.2.13 (2021-03-04)

* [new] Added [Ignition Solutions](https://flareapp.io/docs/solutions/introduction) to some exceptions to help with debugging.
* [fix] Fixed the ability to update an existing cart item with a negative quantity #375
* [fix] Fixed an incorrect method signature in the shipping method stub #380
* Tidied up [the `README`](https://github.com/doublethreedigital/simple-commerce) (but it's not really code related)

## v2.2.12 (2021-02-22)

* [new] Orders will now be added to Customer entries, so there's now a two-way relationship #369
* [new] You can also now use `{{ sc:customer:orders }}` with Orders on the Customer entries, using the `from` parameter.
* [fix] Fixed issue where email's would not be sent if email was set but no customer on order. #372

## v2.2.11 (2021-02-19)

* [new] Added some helper methods to the `Address` object.
* Added tests to the Order Calculator (not sure how we got this far without them)
* And some general cleanup 🧹

## v2.2.10 (2021-02-19)

* [fix] Tax amounts should no longer be off. Was previously using the wrong formula.

## v2.2.9 (2021-02-18)

* Cookie Driver is now the default for new installs.
* [fix] Fixed `Call to undefined method make()` when using cookie cart driver. #365

## v2.2.8 (2021-02-16)

* [fix] Validate against the possibility of having negative line item quantities. #354
* [fix] Fixed bug with `{{ sc:cart:{key} }}` usage.
* [fix] Fixed bug when Order calculator is called on paid order.

## v2.2.7 (2021-02-12)

* [fix] Fixed issue when adding more than a single item to your cart #353
* [fix] When gateway's response isn't success, throw validation errors #352

## v2.2.6 (2021-02-10)

* [new] Allow adding product to cart multiple times and up the quantity. #351
* [fix] Now throws `EntryNotFound` exception when no entry can be found, instead of `OrderNotFound`. #349

## v2.2.5 (2021-02-09)

* [fix] Fixed accidental bug introduced with cart driver fix in v2.2.4.

## v2.2.4 (2021-02-09)

* [fix] Don't throw an exception on `cart` endpoint if no cart exists
* [fix] Don't attempt to augment variant fieldtype if value is `null`
* [fix] When customer's active cart is deleted, a new one will be created, instead of throwing an exception. #348

## v2.2.3 (2021-02-06)

* [new] Added a command to automatically remove old cart entries. `php please sc:cart-cleanup`
* [fix] Coupon total should be calculated from items total, not the grand total.
* [fix] If grand total of cart is `0`, then don't do any gateway work
* [fix] Strip out any decimals from product prices when added to cart
* [fix] On the variant fieldtype: if there is no value, display an error message instead of spinner.

## v2.2.2 (2021-02-02)

* Fixed bug when removing an item from your cart #346

## v2.2.1 (2021-01-31)

It didn't take me very long to find a bunch of bugs...

* Fixed exception within upgrade tool when `stillat/proteus` isn't installed
* Upgrader will no longer continue if `stillat/proteus` isn't installed
* Stripe Gateway should pull key & secret from gateway config, not hard coded `.env` values
* When processing checkout, don't attempt to save data if we don't have any.

## v2.2.0 (2021-01-31)

Before upgrading, please review the [upgrade guide](https://sc-docs.doublethree.digital/v2.2/update-guide) in case there's any changes you need to make.

### What's new

* [Cart Drivers](https://sc-docs.doublethree.digital/v2.2/cart-drivers)
* Under the hood codebase improvements

### What's fixed

* Various bugs

## v2.1.35 (2021-01-30)

* [fix] Actually use the new format when adding items to the cart
* [fix] Fixed issue when clearing the cart

## v2.1.34 (2021-01-30)

* [new] Updated the default order blueprint
* [new] Added a new 'Product Variant' fieldtype to select a single variant

## v2.1.33 (2021-01-27)

* [fix] Fixed some naming inconsistencies with postal/zip codes #343

## v2.1.32 (2021-01-21)

* [fix] Fix situations where the tax totals would be wrong with certain tax rates #340

## v2.1.31 (2021-01-21)

* [fix] ~~Fix situations where the tax totals would be wrong with certain tax rates #340~~

## v2.1.30 (2021-01-17)

* [new] Improved action responses (including propper support for AJAX usage)

## v2.1.29 (2021-01-14)

* [fix] Fixed issue with customer entry being overwritten by new carts. #337
* [fix] Fixed situation where exception would be thrown if the 'Maximum Uses' field isn't set #338

## v2.1.28 (2021-01-11)

* [new] Currency formatting now depends on current site locale, instead of always being `en_US`.
* [fix] Fixed issue with tax calculations #331
* [fix] Fixed Mollie Gateway issues and off-site gateway issues #334

## v2.1.27 (2021-01-11)

* [fix] Fixed `->get()` parameter issue when using Mollie webhook. #332

## v2.1.26 (2021-01-09)

* [fix] Sometimes tags were being called twice. Now it should just be once!
* [fix] Fixed exception sometimes if attempting variant augmentation on a product with no variants.
* [fix] Fixed issue where Gateway Webhook URL's were returned as `null`.

## v2.1.25 (2021-01-05)

* [fix] Fixed the way we handle fallback URLs for off-site gateways #329

## v2.1.24 (2021-01-04)

* [fix] Fixed exception thrown by Off-site gateway callback. #327
* [fix] If a redirect is not provided for off-site gateway, user should be redirected to the homepage.

## v2.1.23 (2020-12-28)

* [new] PHP 8 Support! #318
* [fix] Product entries with variants should not have a standard `price` field.
* [fix] The `has` method on Repositories will now return `null`, instead of throwing an exception about undefined indexes.

## v2.1.22 (2020-12-23)

* [fix] Fix issues parsing `null` Money fields. Addresses issue from #323.

## v2.1.21 (2020-12-23)

* [fix] Just get site with `Site::current()` inside Money Fieldtype, instead of getting the entries' locale.

## v2.1.20 (2020-12-21)

* [fix] Fixed issue when passing `receipt_email` to Stripe

## v2.1.19 (2020-12-21)

* [fix] Simplified the site detecting logic in the Money Fieldtype #319

## v2.1.18 (2020-12-18)

* [fix] Fixed issue with locales in Money Fieldtype again.

## v2.1.17 (2020-12-18)

* Re-tag of v2.1.16 (the release workflow broke)

## v2.1.16 (2020-12-18)

* [fix] Fix issue where `locale()` is called on undefined, in Money Fieldtype.

## v2.1.15 (2020-12-12)

* Remove Woodland

## v2.1.14 (2020-12-12)

* [new] You can now enable automatic receipt emails from Stripe.
* [new] You can now use a single address for an order, instead of different shipping and billing ones.
* [new] You can now set display names for gateways. Useful for when you give the customer an option between them.
* [fix] Fixed a bug causing type exceptions.
* [fix] Ensure customer can't add more of a product than you actually have in stock.

## v2.1.13 (2020-12-05)

* [new] Added some better error handling for form tags.
* [fix] Issue where it couldn't find an 'index' gateway using the `{{ sc:gateways }}` tag? Now sorted!

## v2.1.12 (2020-11-29)

A whole lot of API related changes this release...

* [new] It's now easier to get fields from your cart. How's `{{ sc:cart:delivery_note }}`?
* [new] The Order Calculator has been moved out of the `CartRepository` and into it's own class. However, the `recalculateTotals` method on the `CartRepository` will continue to work for the time being.
* [new] Introduced a new `OrderRepository` which will eventually replace the `CartRepository` (it's a breaking change so it won't happen until at least v2.2)
* [new] Added `customer` method to `CartRepository`
* [fix] Default order blueprint no longer has a SKU field on it.
* [fix] `php please sc:install` command will now only publish blueprints and configuration file.

## v2.1.11 (2020-11-27)

* [new] Add an `exempt_from_tax` field to products that you want to be exempt from tax.
* [fix] Carts & Customers will be created in the correct site.
* [fix] When created, customers will now be published instead of a draft.
* [fix] Money Fieldtype will respect the site of the entry and display the correct currency.
* [fix] Fixed issue where you could add the same product/variant to the cart twice.

## v2.1.10 (2020-11-22)

* [fix] Fixed bug with blueprint field checking

## v2.1.9 (2020-11-22)

* [new] Ensure fields are included in product & order blueprints.

## v2.1.8 (2020-11-21)

* [fix] Fix `vendor:publish` bug when running `php please sc:install` (I should really double check this stuff before I release it)

## v2.1.7 (2020-11-21)

* Re-tag of v2.1.6

## v2.1.6 (2020-11-21)

* [update] Improved the install process - combine `vendor:publish` step and `setup-content`.

## v2.1.5 (2020-11-13)

* [fix] A more descriptive message will now be shown if currency formatting fails due to the `php-intl` extension not being installed or enabled.'
* [fix] Fixed issue where gateways weren't being booted at all...

## v2.1.4 (2020-11-12)

* [fix] Fixed issue with [Woodland](https://github.com/doublethreedigital/simple-commerce/blob/master/src/Woodland.php) when installing Simple Commerce for the first time. #313
* [fix] Fixed issue with product variants fieldtype on new product entries. #314
* [fix] Fixed issue when adding a new variation in product variants fieldtype
* [fix] Localize and use plural/signular stuff with index text for product variants fields.

## v2.1.3 (2020-10-30)

* [fix] Fix issues with installing via Composer, from last release. Whoops.

## v2.1.2 (2020-10-30)

* [new] Licensing statistics - so we can check how many sites are using Simple Commerce, what versions are in use and if they have valid licenses.

## v2.1.1 (2020-10-28)

* [new] Simple Commerce fieldtypes will now display nicely in entry listings
* [fix] Fixed issue when using an off-site gateway without specifing a `redirect`
* Added a bunch of tests behind the scenes

## v2.1.0 (2020-10-18)

**v2.1 contains various breaking changes, we recommend reviewing [the update guide](https://sc-docs.doublethree.digital/v2.1/update-guide) to ensure your site will work with the update.**

* [new] [Product Variants](https://sc-docs.doublethree.digital/v2.1/product-variants)
* [new] [Built-in Mollie Gateway](https://sc-docs.doublethree.digital/v2.1/gateways#builtin-gateways)
* [new] Product Stock
* [new] Sales Widget - dashboard widget for reviewing sales over a week, 14 days and a month.
* [new] Support for Off-site gateways and a bunch of changes to the way gateways work overall.
* [new] Minimum Order Numbers
* [fix] Various bug fixes.

## v2.0.23 (2020-10-05)

* [new] Licensing statistics - so we can check how many sites are using Simple Commerce, what versions are in use and if they have valid licenses. (also introduced in [v2.1.2](https://github.com/doublethreedigital/simple-commerce/releases/tag/v2.1.2))

## v2.0.22 (2020-10-04)

* [new] You can now update the format of the customer titles.
* [fix] When updating a customer, sometimes the title would be removed... #311
* [fix] If updating a customer's name using `{{ sc:cart:update }}` tag, it wouldn't work because of some copy and pasting
* And added a bunch of customer related tests...

## v2.0.21 (2020-10-04)

* [fix] Fixed a bug that would happen if you try and get a customer that doesn't have a title or slug set.

## v2.0.20 (2020-10-04)

* [fix] Fixed issue where SC would presume a customer entry had a name on it, but if it didn't then stuff would break.

## v2.0.19 (2020-10-04)

* [fix] Fixed issue with customer data when being passed into Stripe Gateway, from #307

## v2.0.18 (2020-10-03)

* [fix] Fixed issues when creating/updating customers via the `{{ sc:cart:update }}` tag. #307

## v2.0.17 (2020-09-30)

* [fix] Fixed issue with decimals causing incorrect totals, when using coupons on an order #304

## v2.0.16 (2020-09-28)

* [new] Payment Method's are now saved for later, with Stripe Gateway #306

## v2.0.15 (2020-09-05)

* [fix] The money fieldtype will now default to 0 when nothing is entered instead of throwing an exception.
* [fix] Fixed issue where you couldn't remove the last item from the cart

## v2.0.14 (2020-08-29)

* [new] You can now register gateways on-demand with `SimpleCommerce::registerGateway(PayPalGateway::class, [])`
* [fix] Fixed issue where deleting a single cart item would clear the cart instead (again) #293
* [fix] Fixed issue when trying to submit checkout form without any customer information.

## v2.0.13 (2020-08-27)

* [fix] Fixes issue when adding to the cart when you've already deleted items from the cart. #293
* [fix] Generate a title and slug for customer if they don't already have one - fixes a type error #296
* [fix] Fixed issue when the `CheckoutController` tries to call the `PreCheckout` event on case sensitive systems. #294

## v2.0.12 (2020-08-26)

* [new] Brought back Order Statuses, you may want to run `php please simple-commerce:setup-command` to create the relevant taxonomies and terms.
* [new] You can now send customer metadata when using `{{ sc:cart:update }}` and `{{ sc:checkout }}` tags. #289
* [new] You can now toggle if Simple Commerce sends an `Order Confirmation` email to your customers after checkout. It's enabled by default. #288
* [new] Customer & Order information is now passed to Stripe when preparing payment intent #292
* [new] Brand new `php please simple-commerce:setup-command` command for setting up collections & taxonomies when installing Simple Commerce
* [fix] Fixed issue where adding items to cart would overwrite what is already there. #290

## v2.0.11 (2020-08-24)

* [fix] Fixed issue with ProductRepository not being bound properly because of a spelling mistake 🤦‍♂️ #287

## v2.0.10 (2020-08-22)

* [new] You can now specify the version of the Stripe API you want to use.
* [fix] Fixed issue caused when using a gateway that doesn't return anything from the prepare method.
* [fix] Fixed `checkAvailability` failing for shipping methods
* [fix] Fixed issue with completing cart without a customer being attached to the order.
* Changed version constraint of `statamic/cms` due to v3 release

## v2.0.9 (2020-08-18)

* [fix] Simple Commerce releases should now include built assets.
* [fix] Issue when entering value inside Money fieldtype without separator and it converts it to cents/penies
* [fix] Percentage coupons #281

## v2.0.8 (2020-08-17)

* [fix] Simple Commerce tags were broken after beta 44


## v2.0.7 (2020-08-17)

* [fix] Config, blueprint etc should no longer be overwritten on composer update
* Simple Commerce only supports PHP 7.4

## v2.0.6 (2020-08-14)

* [new] Refunds - somehow managed to ship without refunds but they're here now!
* [new] The output from prepare methods in gateways is now saved in the order so it can be used again in the gateway
* [break] Really small breaking change, inside the `{{ sc:gateways }}` loop, change `{{ config:* }}` to `{{ gateway-config:* }}` to grab gateway configuration values.
* [fix] Fixed initial state for the money fieldtype, should no longer show .
* [fix] Exceptions should now be thrown for when gateways don't exist or none is required at checkout
* [fix] `_redirect` should no longer be passed into cart when updating cart
* [fix] Fixed issue with coupon validation, where an error was being thrown as we were validation the wrong thing 🤦‍♂️ #276

## v2.0.5 (2020-08-11)

* [fix] Blueprints being re-published after every Simple Commerce update

## v2.0.4 (2020-08-10)

* [fix] Simple Commerce no longer relies on calebporzio/sushi for Currency and Country models
* [fix] Incorrect typehint causing issues when getting cart items
*[fix] Typos

## v2.0.3 (2020-08-06)

* [new] Introduced a `Product` facade, repository and related things...
* [fix] Fixed bug where you'd run into an error if you visit the cart/checkout when you have no cart in the session. Fixes #275
* [break] The `$request` variable is now passed in as a second parameter of the `purchase` method to a gateway.

## v2.0.2 (2020-08-01)

* [new] Introduced two new commands: `make:gateway` and `make:shipping-method`
* [fix] Use FormRequests for validating action endpoints
* [fix] Officially only supports PHP 7.4
* [break] Removed `/shipping-options` endpoints.
* [break] Removed need for SKUs, you can have them if you want them but they won't be saved in orders anymore.

## v2.0.1 (2020-07-31)

* [new] Added `PreCheckout` and `PostCheckout` events, triggered by the Checkout controller.

## 2.0.0 (2020-07-25)

* **Simple Commerce v2.0 has launched!**

# Changelog

All notable changes to this project will be documented in this file.

## [2.1.4] - Unreleased
[2.1.4]: https://github.com/Smile-SA/magento2-module-map/compare/2.1.3...2.1.4

- Supported platforms: Magento 2.4.4 and 2.4.5 only, PHP 8.1
- `composer.json` now states it: `php ^8.1` and `magento/framework >=103.0.4 <103.0.6` (previously `php ^8.1` and `magento/framework >=103.0.4`)
- Magento 2.4.6 and later and Mage-OS are supported by the 2.2.x line
- Fix `setup:di:compile` on Magento 2.4.4 and 2.4.5: native `mixed` and union types are removed from method signatures and kept in docblocks
- CI: static analysis on Magento 2.4.5, `setup:di:compile` on Magento 2.4.4 and 2.4.5

## [2.1.3] - 2026-06-03
[2.1.3]: https://github.com/Smile-SA/magento2-module-map/compare/2.1.2...2.1.3

- Remove dependency to unused jQuery UI components

## [2.1.2] - 2024-08-29
[2.1.2]: https://github.com/Smile-SA/magento2-module-map/compare/2.1.1...2.1.2

- API model docblocks

## [2.1.1] - 2024-05-03
[2.1.1]: https://github.com/Smile-SA/magento2-module-map/compare/2.1.0...2.1.1

- Fix geolocalize through header button
- Add CSP whitelist

## [2.1.x] - 2023-04-24
[2.1.x]: https://github.com/Smile-SA/magento2-module-map/compare/2.0.4...2.1.0

Dataset compatibility ES 2.11.x and PHP 8.2

- fix Dynamic type declaration
- fix Type hinting
- Replace `Zend_Date` by `DateTime`
- Remove `MutationObserver` support
- fix UI Component Retailer Offer editing
- Replace `Zend_Validate` by `Laminas\Validator`
- fix Retailer Grid Column Action
- fix Retailer Grid Mass Actions
- fix some translations
- remove Temando/Shipping Plugin

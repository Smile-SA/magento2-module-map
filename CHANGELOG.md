# Changelog

All notable changes to this project will be documented in this file.

## [2.2.0] - Unreleased
[2.2.0]: https://github.com/Smile-SA/magento2-module-map/compare/2.1.3...2.2.0

- Supported platforms: Magento 2.4.6 and later, Mage-OS 1.0.0 and later (tested up to Magento 2.4.9 and Mage-OS 4.0), PHP 8.1 to 8.5
- `composer.json` now states it: `php ^8.1` and `magento/framework >=103.0.6` (previously `php ^8.1` and `magento/framework >=103.0.4`)
- Magento 2.4.4 and 2.4.5 are supported by the 2.1.x line, from 2.1.4
- CI: static analysis and `setup:di:compile` run on Mage-OS, without Magento credentials

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

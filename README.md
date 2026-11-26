## ElasticSuite Map

It allows to add map on the website.

### Compatibility

| Module version | Magento | Mage-OS | PHP |
| --- | --- | --- | --- |
| 2.2.x | 2.4.6 and later (tested up to 2.4.9) | 1.0.0 and later (tested up to 4.0) | 8.1 to 8.5, as supported by the platform |
| 2.1.x, from 2.1.4 | 2.4.4 and 2.4.5 | not available | 8.1 |

Releases up to 2.1.3 are not covered by this table: they declared `php ^8.1` and `magento/framework >=103.0.4`, and their method signatures use native `mixed` or union types, which the code generator of Magento 2.4.4 and 2.4.5 rejects (`setup:di:compile` fails).

### How to use

1. Install the module via Composer:

``` composer require smile/module-map ```

2. Enable it:

``` bin/magento module:enable Smile_Map ```

3. Install the module and rebuild the DI cache:

``` bin/magento setup:upgrade ```

### How to configure

> Stores > Configuration > Services > Smile Map > Map Settings

Field                            | Type
---------------------------------|--------------------------------------
Provider                         | Select (OpenStreetMap/Google Maps)
Google API Key                   | Text
Ipstack API Key                  | Text
Google API Libraries             | Text
Google Map Styles                | Text
Directions Url                   | Text
Marker Icon                      | Image
Disable zoom out when no results | Select (Yes/No)
Enable Marker Cluster            | Select (Yes/No)

> Stores > Configuration > Services > Smile Map > Address templates

Field         | Type
--------------|--------
Text          | Text
Text One Line | Text
HTML          | Text
PDF           | Text

## ElasticSuite Map

It allows to add map on the website.

### How to use

1. Install the module via Composer :

``` composer require smile/module-map ```

2. Enable it

``` bin/magento module:enable Smile_Map ```

3. Install the module and rebuild the DI cache

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

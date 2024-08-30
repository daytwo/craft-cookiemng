# daytwo/craft-cookiemng

Interface to manage cookies preferences

## Requirements

This plugin requires Craft CMS 4.5.0 or later, and PHP 8.0.2 or later.

## Installation

You can install this plugin from the Plugin Store or with Composer.

#### From the Plugin Store

Go to the Plugin Store in your project’s Control Panel and search for “daytwo/craft-cookiemng”. Then press “Install”.

#### With Composer

Open your terminal and run the following commands:

```bash
# go to the project directory
cd /path/to/my-project.test

# tell Composer to load the plugin
composer require daytwo/craft-cookiemng

# tell Craft to install the plugin
./craft plugin/install cookiemng
```


## Configuration
To configure the `cookiemng.php` file, follow these steps:

1. Copy the `cookiemng.php` file from the `config` directory of the plugin to your Craft CMS project's `config` directory.

2. Open the `cookiemng.php` file in a text editor.

3. Modify the configuration settings according to your preferences. Here is an example configuration:

```php
<?php

<?php
//supports multiple environments and multiple sites.
//to configure multiple sites, add a new key to the array with the site handle as the key
return [    
    '*' => [
        'cookieName' => ['default' => 'your_cookie_name'],
        'cookieDomain' => ['default' => 'your_domain_here'],
        'cookiePath' => ['default' => '/'],
        'cookieExpiry' => ['default' => 365],
        'cookieSecure' => ['default' => true],

        'cookieEnabled' => ['default' => true], //enables the cookie consent banner (can be turned on/off per environment)
        'cookieGoogleEnabled' => ['default' => true], //enables triggering the gtag consent (GTM). Compliant with google consent v2(can be turned on/off per environment)

        'theme' => ['default' => 'black'],//allowed values: blue, green, red, yellow, purple, black
        'inverted' => ['default' => false], // when inverted is true, the panel background will use the theme color
        'roundedCorners' => ['default' => true], // when roundedCorners is true, buttons will have rounded corners
        'cornerPanel' => ['default' => false], // when cornerPanel is true, the panel will be in the bottom right corner, otherwise it will be a bottom bar
        'zIndex' => ['default' => 99999999], // z-index of the panel
        'blockBackground' => ['default' => true], // when blockBackground is true, the background will be blocked when the panel is open (only for the bottom bar version)

        'saveButton' => ['default' => 'Save'],
        'acceptAllButton' => ['default' => 'Accept all'],
        'acceptFunctionalButton' => ['default' => 'Accept necessary'],
        'customizeButton' => ['default' => 'Customize'],

        'cookiesHeader' => ['default' => 'About cookies'],
        'cookiesDescription' => ['default' => 'This website uses cookies. We use cookies primarily to improve and analyze the experience of our website and for marketing purposes. Because we respect your privacy rights, you can choose not to accept certain types of cookies. Click on the different category headings to find out more and change the default settings. Blocking certain types of cookies may have a negative impact on the experience of the website and may limit the services we can offer you.'],
        'cookiesReadMore' => ['default' => 'Read more'],
        'cookiesReadMoreLink' => ['default' => '/privacy-policy'],

        'functionalCookies' => ['default' => true],//this will always be true even if you set to false. This is the 'mandatory' category
        'functionalTitle' => ['default' => 'Necessary'],
        'functionalDescription' => ['default' => 'Enables basic functionality to remember user, location and preferences. Also supports security, network management and availability.'],

        'analyticsCookies' => ['default' => true],//disabled the request for consent for this category
        'analyticsDescription' => ['default' => 'Allows the use of behavioral data to optimize performance, see how you interact with our websites and apps, and to improve the Second Space experience.'],
        'analyticsTitle' => ['default' => 'Analytics'],
        
        'advertisingCookies' => ['default' => true],//disabled the request for consent for this category
        'advertisingDescription' => ['default' => "We use cookies to make our ads more engaging and valuable to people who visit our site. Some common uses for cookies are: selection of advertising based on what is relevant to the user; improving the reporting of advertising campaign results; avoid showing ads that the user has already seen."],
        'advertisingTitle' => ['default' => 'Marketing'],
        
        'personalizationCookies' => ['default' => true],//disabled the request for consent for this category
        'personalizationDescription' => ['default' => 'Allows sharing of behavioral data with advertising partners. This data is used to improve and report on the experience of personalized ads on the websites of partners.'],
        'personalizationTitle' => ['default' => 'Personalised ads'],
    ],
    'dev' => [
        // Configuration settings for the dev environment (override default settings here)
    ],
    'staging' => [
        // Configuration settings for the staging environment (override default settings here)
    ],
    'production' => [
        // Configuration settings for the production environment (override default settings here)
    ]
];
```

4. Save the `cookiemng.php` file.

5. Clear the Craft CMS cache by running the following command in your terminal:

```bash
./craft clear-caches/all
```

6. The configuration changes will now take effect.

## OUTPUT

In order to do add the cookie manager panel to your website you must add this to your templates:

Right after the opening the `<head>` tag
```
{{craft.cookiemng.consentTemplate(currentSite.handle)|raw}}
````

Right before closing the `<body/>` tag
```
{{ craft.cookiemng.render(currentSite.handle,true|false)|raw }}
```

THe second parameter defines if the popup should be hidden when the user access the "Read More" link. When 'true' the plugin will check if the current requested url matches the url pattern from the "read more" page, which is usually a privacy policy page, and hide the popup automatically (so users can properly read it).

7. Trigger the cookie panel to be displayed after the user has dismissed it

This is a mandatory feature and should be available for users to change their preferences at any time.
You can trigger it via javascript by calling:
```
cm_displaySettings();
```

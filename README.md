# Overview

Welcome to the negative(-11) PHP MVC Framework. You are using version 2 (Chocolate).

## Support

This project is currently unmaintained other than for security purposes. If you have discovered a
security issue, please submit an issue or pull request. The repository is preserved here for 
documentation purposes.

## Features

- Lightweight MVC PHP Framework.
- Namespace autoloading
- Straightforward and easy to use. No complex setup.
- Free and Open Source.
- Includes core MVC architecture.
- Includes basic MySQL package.
- Includes basic setup for PHPUnit testing.

## Requirements

PHP Version 5.3+ Apache Web Server with mod_rewrite enabled (if using included .htaccess file).

## Installation

1. Set application directory as your website root. It is recommended that you keep all other folders
   outside of the public web directory.
2. Open parameters.php and modify any desired configuration settings. Follow instructions for
   specifying environment paths and packages. You may need to change the `ENVIRONMENT_ROOT`
   directory in index.php if you placed the application directory in a different location than the
   rest of the framework.
3. Open your browser and point to website. If you have everything configured correctly, you should
   see the framework information page.

#### Sample Apache Virtual Host

```Apache
<VirtualHost *:80>
  DocumentRoot /var/www/example/application
  ServerName example.com
  <Directory /var/www/example/application>
    AllowOverride All
    allow from all
    Options +Indexes
  </Directory>
</VirtualHost>
```
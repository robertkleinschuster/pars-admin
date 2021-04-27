# pars-admin

[![Build Status](https://travis-ci.com/pars-framework/pars-admin.svg?branch=master)](https://travis-ci.com/pars-framework/pars-admin)
[![Coverage Status](https://coveralls.io/repos/github/pars-framework/pars-admin/badge.svg?branch=master)](https://coveralls.io/github/pars-framework/pars-admin?branch=master)

Admin module for PARS CMS

## Installation

Clone this repository:
```bash
$ git clone https://github.com/pars-framework/pars-admin.git
$ cd pars-master
$ git submodule init
$ git submodule update
$ git submodule foreach --recursive git checkout develop
```

Install PHP 7.4 and composer

Ubuntu 20.04:
```bash
$ sudo apt update
$ sudo apt install php php-cli php-fpm php-json php-common php-mysql php-pdo-mysql php-zip php-gd php-mbstring php-curl php-xml php-pear php-bcmath
$ curl -sS https://getcomposer.org/installer -o composer-setup.php
$ sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer
```

macOS:
```bash
$ brew install php@7.4
$ brew install composer
```

Moreover you need to setup MariaDB.`

Install project dependencies
```bash
#navigate to project folder
$ composer install
$ composer update
$ composer run-script serve --timeout=0
```

Setup a MariaDB database and user and then open admin.localhost:8080 in a browser and enter db config.
To setup the database you can use MySQL workbench https://www.mysql.com/products/workbench/, command line interface or any other MySQL client.
Done. Now open the project in your favourite editor and start developemnt.

Admin should be available at [localhost:8080](localhost:8080)

## Documentation
Package Dependency Guid
This defines what package can use classes from what other packages.

[Diagram](https://github.com/PARS-Framework/pars-admin/blob/develop/PARS-Package-Dependencies.png)

work in progress

Browse the documentation online at https://docs.parsphp.org/pars-admin/

## Contributing

Pull requests are welcome. For major changes, please open an issue first to discuss what you
would like to change.

In general, we follow the "fork-and-pull" Git workflow:
1. Fork the repo on GitHub
2. Clone the project to your own machine
3. Commit changes to your own branch
4. Push your work back up to your fork
5. Submit a Pull request so that we can review your changes

Please refer to the project's guidelines for submitting patches and additions and make sure
to update tests as appropriate.

NOTE: Be sure to merge the latest from "upstream" before making a pull request!

## Version Bump
Any commit message that includes major, minor, patch will trigger the respective version bump.

## Support

* [Issues](https://github.com/pars/pars-admin/issues/)
* [Forum](https://discourse.parsphp.org/)

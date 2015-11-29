# yaes
Yet Another Ecommerce Scanner

A CLI and web tool to scan e-commerce sites for known vulnerabilities.

# Installation instructions

Required dependencies

php5 interpreter, curl, and php5 extension for curl

After cloning the repository, download composer

```bash
curl -sS https://getcomposer.org/installer | php
```

And install the dependencies

```bash
php composer.phar install
```

At that point, the CLI interface is available by running

```bash
./yaes.php scan <website>
```

The web interface resides inside the directory "frontend". Perhaps the easiest way to try it is by using the integrated PHP webserver

```bash
php -S localhost:9000 -t .
```

The web interface should be available at http://localhost:9000/frontend


# NMAP

You can run YAES from nmap command line if you prefer:

- Symbolic link yaes must be create in path first:

ie: ln -s /home/user/bin/yaes /location/of/yaes.php

- Copy yaes/nmap/http-yaes.nse to /usr/share/nmap/scripts/

- To run:

nmap --script=http-yaes.nse URL [-p port]

ie: nmap --script=http-yaes.nse demo.opencart.com -p 80

# TODO

Check out TODO list and missing features at: https://github.com/onticsoluciones/yaes/issues?q=is%3Aopen+is%3Aissue+label%3Aenhancement

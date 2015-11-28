# yaes
Yet Another Ecommerce Scanner

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

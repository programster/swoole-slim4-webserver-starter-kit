A starter kit for deploying a webserver running [Slim 4](https://www.slimframework.com/) framework with 
[Swoole PHP](https://www.swoole.co.uk/).

### Packages
This codebase makes use of:

* [ilexn/swoole-convent-psr7](https://github.com/iLexN/swoole-psr7) in order to create a convert that will convert the 
swool request to a psr7 http request, and then the psr7 response back to a swoole response.

* [nyholm/psr7](https://github.com/Nyholm/psr7) - for a [PSR-17](https://www.php-fig.org/psr/psr-17/) HTTP factory that 
we feed into the converter, but you could easily swap this out with any Psr17 compliant object.


### Swoole Version
This codebase was built/tested against Swoole 4.6.7 with PHP 8.0 on Ubuntu 20.04.


## Run / Deploy
Navigate to the `/src` directory and run:

```bash
php main.php
```

This will start the webserver on your local host listening to port 9000. You can then navigate to 
[http://127.0.0.1:9000](http://127.0.0.1:9000) in your browser and see the response.

My FlixTrip
===========

## Install the Application

Run this command from the directory in which you want to install your new Slim Framework application. You will require PHP 7.3 or newer.

```bash
composer create-project slim/slim-skeleton [my-app-name]
```

```bash
cd [my-app-name]
composer start
```

Or you can use `docker-compose` to run the app with `docker`, so you can run these commands:
```bash
cd [my-app-name]
docker-compose up -d
```
After that, open `http://localhost:8080` in your browser.

Run this command in the application directory to run the test suite

```bash
composer test
```

That's it! Now go build something cool.

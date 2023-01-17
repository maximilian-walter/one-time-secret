# One time secret

Web app to securely share secrets. I created this app for my own needs, but published it as open source in case somebody
else finds it useful.

## How does it work?

The secrets are encrypted inside the browser and then stored on the server. The web app generates a share link,
containing a unique ID and encryption key for the secret.

The ID is used to store and load the secret from the server, but the key never leaves the browsers. Therefore, it is
never possible to decrypt the secrets on the server.

If a user opens the share link, the encrypted secret is loaded from the server and decrypted inside the browser, using
the encryption key embedded in the link. A secret can only be loaded once. This is important, so it's possible to
recognize if the secret was accessed by someone else and is therefore compromised.

Use the tool only to transfer single secrets (e.g. only a password without a username). Always assume the link could be
opened by somebody else.

## System requirements

The web app is based on Symfony 6 and requires PHP 8.1 or newer. No database is required. A web server with SSL is
highly recommended. For building the frontend-assets (CSS, JavaScript), node.js and npm is required (a current LTS should
work fine).

## Installation

```bash
# Clone app
git clone https://github.com/maximilian-walter/one-time-secret
cd one-time-secret

# Install dependencies using Composer
composer install --no-dev
composer dump-env prod

# Build frontend assets
npm ci
npm run-script build
```

Now configure your web-server, using `public` as document root. Now you can create new secrets accessing the URI:

https://www.example.com/create

## License

Copyright © 2023

Licensed under the [MIT License](LICENSE).
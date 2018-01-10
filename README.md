## Local Setup

If you are missing any of these things, install them:

- **homebrew** - `/usr/bin/ruby -e "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/master/install)"`
- **nginx**:
  - `brew install nginx`
  - `mv /usr/local/etc/nginx/nginx.conf /usr/local/etc/nginx/nginx.bak.conf`
  - `echo "worker_processes  1; events {worker_connections  1024;}" > /usr/local/etc/nginx/nginx.conf`
- **composer** - `curl -sS https://getcomposer.org/installer | php && sudo mv composer.phar /usr/local/bin/composer`
- **npm** - https://nodejs.org/en/download/
- **heroku toolbelt** - `brew install heroku`
- **php7** - `curl -s http://php-osx.liip.ch/install.sh | bash -s 7.1` and possibly other things

Install dependencies:

- `npm install`
- `composer install`



Use docker to run mariadb: (You will need to run a database for your backend.  Here's how to set one up easily...)

- https://github.com/agencyenterprise/publisher-desk/wiki


Setup your .env file:

- `cp .env.example .env`
- edit your new `.env` file to reflect your docker containerized mariadb installed above by replacing all the `DB_<etc>` lines
with
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=33062
DB_DATABASE=publisher_desk
DB_USERNAME=root
DB_PASSWORD=root
```
(Note that you will use the `mysql` entry even though you are in fact using mariadb.  They are very similar, and laravel has
no mariadb driver, so we use the one for mysql.)

Quick Start:

- Run `php artisan migrate --seed`


To authenticate a user, make a `POST` request to `/api/auth/login` with parameter as mentioned below:

```
email: lucas@youbeo.com
password: lucas
```

Request:

```sh
curl -X POST -F "email=lucas@youbeo.com" -F "password=lucas" "http://localhost:8000/api/auth/login"
```

Response:

```
{
  "success": {
    "message": "token_generated",
    "token": "a_long_token_appears_here"
  }
}
```

- With token provided by above request, you can check authenticated user by sending a `GET` request to: `/api/auth/user`.

Request:

```sh
curl -X GET -H "Authorization: Bearer a_long_token_appears_here" "http://localhost:8000/api/auth/user"
```

Response:

```
{
  "success": {
    "user": {
      "id": 1,
      "name": "Lucas Henrique",
      "email": "lucas@youbeo.com",
      "created_at": null,
      "updated_at": null
    }
  }
}
```


Start server:

- `heroku local`






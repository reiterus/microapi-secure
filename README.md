# MicroApi Secure
MicroApi Secure is a small engine for creating **application APIs** 
based on Symfony packages, which is a development of 
[MicroApi Core](https://github.com/reiterus/microapi-core) and contains security tools.
Since MicroApi is based on the Symfony architecture,
it can be developed to **any level of complexity** if desired.
Forming your application based on this engine,
you will always be sure that there is
**nothing superfluous** in your code.

### Features of MicroApi Secure out of the box
- ability to 
  - create secure endpoints
  - use JWT-tokens
- authentication examples `base`, `json` and `token`
- example of using a provider 
  - **in-memory**
  - **in-json**
- users list in a JSON-file
- PhpStorm [http requests](http/admin.http) files
- custom logger with named channels
- 3 configuration files
- examples of **testing REST API** endpoints

See also [core features](https://github.com/reiterus/microapi-core#microapi-core).

#### Join the development of MicroApi!

## Usage
It's very simple! Just run these two commands:
- `composer create-project reiterus/microapi-secure folder && cd folder`
- `make docker-start`

That's all!  
Now your API app is available at http://localhost:8009

## JWT
To get JWT-token just send http-request to endpoint. For example, `/admin`.

To check/decode this token send request to `jwt/decode` endpoint.
```shell
GET http://localhost:8009/jwt/decode
Authorization: Bearer some.jwt-token
```

JWT-token structure:
```json
{
  "iat": 1681036036,
  "exp": 1681036336,
  "sub": "admin.email@yandex.ru",
  "user": {
    "username": "admin",
    "email": "admin.email@yandex.ru",
    "roles": [
      "ROLE_ADMIN"
    ]
  }
}
```

## Logger
```shell
# run command
docker-compose logs -ft api | grep MICROAPI
# get log info
MICROAPI: TOKEN_ACCESS: Invalid Access Token {"token":"wrong.token.manager"}
```

## Makefile commands
For the convenience of working with the project, there are several [make-commands](commands.md): local and for Docker.

## Examples
Default response at `/admin` endpoint
```json
{
  "page": "Admin Account",
  "identifier": "admin",
  "roles": [
    "ROLE_ADMIN"
  ]
}
```

## Installation
You can install the project in two ways

From packagist.org
```shell
composer create-project reiterus/microapi-secure
```

From GitHub repository
```json
{
 "repositories": [
  {
   "type": "vcs",
   "url": "https://github.com/reiterus/microapi-secure.git"
  }
 ]
}
```

## License

This library is released under the [MIT license](LICENSE).

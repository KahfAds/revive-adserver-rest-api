# Revive Adserver Rest APIs

### Requirements
- Php v8
- Composer
- Slim packages
    - slim/slim : ^4.8
    - slim/http : ^1.2
    - slim/psr7 : ^1.4

> Assuming your adserver is place at `/var/www/html/adserver` location. Below instructions are based on the adserver location.
It can be different but make sure to configure adserver according to your requirements.

### Installation Instructions
`Step 1:` Dowload the rest api source code and copy `rest` dir inside the adserver's `<adserver-dir-name>/www/api/v2/rest` directory

`Step 2:` Go to the adserver's directory and install below packages
- `composer require slim/slim`
- `composer require slim/http`
- `composer require slim/psr7`

`Step 3:` Configure nginx server
```
location ~ ^/var/www/html/<adserver-dir-name>/(?!$|www/) {
	return 403;
}

//to access rest apis    
location /<adserver-dir-name>/www/api/v2/rest/ {
	try_files $uri $uri/ /<adserver-dir-name>/www/api/v2/rest/index.php?$args;
}
```

### API documentation
[Postman Collection](https://github.com/jatinderaujla/revive-adserver-rest-api/)

### API Requests

#### Heartbeat 
Make this request to verify installation

`Heartbeat Request`

```
curl --location --request GET 'http://<host-name>/adserver/www/api/v2/rest'
```

`Heartbeat Response`
```
{
    "status": "UP"
}
```
#### Login
This API is required before calling and other API request. This API will return `sessionID` and use header `sessionID: <session-id>`

`Login Request`
```
curl --location --request POST 'http://<host-name>/adserver/www/api/v2/rest/login' \
--header 'Content-Type: application/json' \
--data-raw '{
    "username": "<admin-username>",
    "password": "<admin-password>"
}'

```

`Login Response`
```
{
    "message": "Login",
    "data": {
        "username": "<admin-username>",
        "sessionID": "<session-id>",
        "created_at": 1719471784
    },
    "timestamp": 1719471784
}
```


## FAQs

### How to access Rest API?
Base URL: `http|https://<host-name>/<adserver-dir-name>/www/api/v2/rest`

Default Base Path `/adserver/www/api/v2/rest`


### How to change base path of Rest API?

Configure any once approach either 1 or 2

##### 1. Through conf file of adserver
- Edit conf file located at path `<adserver-dir-name>/var/<host-name>.conf.php`
- Under `[webpath]` add conf property `restAPI="<path-for-rest-api>"` and save it.

### `or`

##### 2. Change source file of Rest API.

This approach override the above if conf property is not present
- Edit file `<adserver-dir-name>/www/api/v2/rest/index.php`
- Update path here `define('API_BASE_PATH', '/adserver/www/api/v2/rest')` and save it.


##
### TODO List:
- Create plugin for Rest API for easy of use and configuration.
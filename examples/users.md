# Table of Contents
* [GET /users](#get-users)

* [POST /users/new](#post-usersnew)
## GET /users
Get All Users

### Parameters
#### required
* `name` **string** (except: `[bob, john]`) - user name

#### option
* `status` **numric** (default: `10`, only: `[10, 20, 30]`) - user status

#### :memo: note
* Here is note1
* Here is note2

### Example
#### 200 Success
#### Request
```
GET /users
Content-Type: application/json

{
    "status": 10,
    "name": "tarou"
}
```
#### Response
```
200
Content-Type: application/json; charset=utf-8

{
    "id": 1,
    "name": "tarou",
    "status": 10,
    "created_at": "2015-04-21T14:55:09.351Z",
    "updated_at": "2015-04-21T14:55:09.351Z"
}
```
#### 404 Not Found
#### Request
```
GET /users
Content-Type: application/json

{
    "status": 20,
    "name": "tarou"
}
```
#### Response
```
404
Content-Type: application/json; charset=utf-8

{
    "message": "not found"
}
```

## POST /users/new
Post New User

### Parameters
#### required
* `name` **string** (except: `[bob, john]`) - user name

#### option
* `status` **numric** (default: `10`, only: `[10, 20, 30]`) - user status

#### :memo: note
* Here is note1
* Here is note2

### Example
#### 200 Success
#### Request
```
POST /users/new
Content-Type: application/json

{
    "status": 10,
    "name": "ichiro"
}
```
#### Response
```
200
Content-Type: application/json; charset=utf-8

{
    "id": 1,
    "created_at": "2015-04-21T14:55:09.351Z"
}
```

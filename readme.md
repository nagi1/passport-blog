<!-- Title and slug-->

# Laravel passport blog

> A simple blog with front-end and API using laravel passport.

<br>

**_Screenshots of the admin panel_**

![Screenshot1](/screenshots/1.png)

<br>

![Screenshot1](/screenshots/2.png)

<br>

![Screenshot1](/screenshots/4.png)

**_Screenshots of the front page_**

![Screenshot1](/screenshots/1.png)





---

## Table of Contents

- [Features](#features)
- [Installation](#installation)
- [Usage](#usage)
- [Documentation](#documentation)
  - [API](#api)

---

## Features

- Frontend admin panel
- Frontend page
- Fully exposed API
- Laravel 7
- Passport
- Simple

---

## Installation

### Clone

> Clone this repo to your local machine using

```Bash
git clone https://github.com/nagi1/passport-blog.git
```

### Setup


```Bash
composer install
composer dump-autoload
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan passport:keys
php artisan db:seed
#if you want dummy data
php artisan db:seed --class=DummyDataSeeder
```

## Usage

- Login as
  - email: admin@admin.com
  - password: admin
- Follow The [documentation](#documentation)

## Documentation

First things first you need to request an **Bearer Access Token** for the client.

`POST http://localhost:8000/oauth/token`

Request Body

**grant_type**: password

**client_id**: 2

**client_secret**: use this command to generate a new client secret `php artisan passport:keys`

**username**: admin@admin.com

**password**: admin

## API

Get the token bearer to be authenticated.

using laravel awesome [Route::resource()](https://laravel.com/docs/7.x/controllers) you can get all possible routes using this command `php artisan route:list`

---

### Overall stats

Get overall status of the blog

`GET http://localhost:8000/api/stats`

**Response example**

```json
{
    "data": {
        "stats": {
            "posts": 37,
            "comments": 40,
            "tags": 12,
            "categories": 10
        }
    }
}
```


---

## Posts

### Response body example

```json
    "data": [
        {
            "id": 38,
            "title": "Post example",
            "body": "Body example",
            "is_published": false,
            "created_at": "31-10-2020 09:55",
            "updated_at": "31-10-2020 10:02"
        }
    ]
```

### Fetch Posts

`GET http://localhost:8000/api/posts`

Fetch all posts, you can customize the result set by adding one of these parameters to request url.

**?title=example**: fetch posts that its title start with `example`

**?search=example**: search for posts that matches keyword `example`

**?order=oldest**: order results by oldest date

**?order=latest**: (default order), order results latest

**?status=published**: fetch only published posts

**?status=drafted**: (default) fetch published and drafted posts

**?limit=10**: (default is 10) limit results to 10 results pre page, use **?page=2** to navigate to other pages.


### Create new Post

`POST http://localhost:8000/api/posts`

**Request body**

**title**: Post title

**body**: Post body

**category_id**: valid category id

**tags**: tags separated by comma ex "tag1, tag2, tag3"

### Update Post

`PUT/PATCH http://localhost:8000/api/posts/{post_id}`

**Parameter:** post_id

request body as create

### Delete Post

`DELETE http://localhost:8000/api/posts/{post_id}`

**Parameter:** post_id

### Show Post

`GET http://localhost:8000/api/posts/{post_id}`

**Parameter:** post_id

**Full response**

```json
{
    "data": {
        "id": 38,
        "title": "Post example",
        "body": "Body example",
        "is_published": false,
        "created_at": "31-10-2020 09:55",
        "updated_at": "31-10-2020 10:02",
        "comments": [
            {
                "id": 41,
                "body": "comment example",
                "user": {
                    "name": "Ahmed Gaber",
                    "email": "ahmed@tailors.com",
                    "created_at": "30-10-2020 23:38",
                    "is_admin": true
                },
                "created_at": "31-10-2020 22:00",
                "updated_at": "31-10-2020 22:00"
            }
        ],
        "category": {
            "id": 1,
            "name": "et",
            "created_at": "30-10-2020 23:38",
            "updated_at": "30-10-2020 23:38"
        },
        "user": {
            "name": "Ahmed Nagi",
            "email": "nagi@tailors.com",
            "created_at": "30-10-2020 23:38",
            "is_admin": true
        },
        "tags": [
            {
                "id": 12,
                "name": "tag1",
                "created_at": "31-10-2020 10:02",
                "updated_at": "31-10-2020 10:02"
            }
        ]
    }
}
```

### Publish Posts (admin)

`POST http://localhost:8000/api/posts/{post}/publish`

**This action only allowed for admin**

**Parameter:** post_id

### Comment on posts

`POST http://localhost:8000/api/posts/{post}/comment`

**Parameter:** post_id

**Request body**

**body**: Comment body

**(optional) user_id**: if not provided current authenticated user id will be used.


---

## Comment

### Response body example

```json
    "data": [
        {
                "id": 41,
                "body": "comment Example",
                "user": {
                    "name": "Ahmed Nagi",
                    "email": "ahmed@tailors.com",
                    "created_at": "30-10-2020 23:38",
                    "is_admin": true
                },
                "created_at": "31-10-2020 22:00",
                "updated_at": "31-10-2020 22:00"
            }
        ],
    ]
```

### Fetch all comments

`GET http://localhost:8000/api/comments`


### Show comment

`GET http://localhost:8000/api/comments/{category_id}`

**Parameter:** comment_id

### Delete comment (requires OC or admin)

`DELETE http://localhost:8000/api/comments/{comment_id}`

**Parameter:** comment_id

---

## Users

### Response body example

```json
    "data": [
        {
            "name": "Ahmed Nagi",
            "email": "nagi@tailors.com",
            "created_at": "30-10-2020 23:38",
            "is_admin": true
        }
    ]
```

### Register new user

`POST http://localhost:8000/api/users`

**Request body**

**name**: user full name
**email**: unique email
**password**: valid 8 char password
**(optional) is_admin**: bool (true/false) to assign registered user to be admin


### Reset password

`POST http://localhost:8000/api/auth/reset-password`

**Request body**

**email**: user email (will send email with rest code)


### Change password

`POST http://localhost:8000/api/auth/change-password`

**Request body**

**email**: user email
**reset_key**: reset key sent to the user by email
**password**: new changed password


### Fetch all users (require admin)

`GET http://localhost:8000/api/users`


### Delete Category (require admin)

`DELETE http://localhost:8000/api/users/{user_id}`

**Parameter:** user_id


---
## Category

### Response body example

```json
    "data": [
        {
            "id": 1,
            "name": "et",
            "created_at": "30-10-2020 23:38",
            "updated_at": "30-10-2020 23:38"
        }
    ]
```


### Fetch all categories

`GET http://localhost:8000/api/categories`

### Show category

`GET http://localhost:8000/api/categories/{category_id}`

**Parameter:** category_id

### Create new Category

`POST http://localhost:8000/api/categories`

**name**: category name

### Update Category

`PUT/PATCH http://localhost:8000/api/categories/{category_id}`

**Parameter:** category_id

*request body as create category*

### Delete Category

`DELETE http://localhost:8000/api/categories/{category_id}`

**Parameter:** category_id

---

## Tag

### Response body example

```json
    "data": [
        {
            "id": 1,
            "name": "tag",
            "created_at": "30-10-2020 23:38",
            "updated_at": "30-10-2020 23:38"
        }
    ]
```

### Fetch all Tags

`GET http://localhost:8000/api/tags`

### Show tag

`GET http://localhost:8000/api/tags/{tag_id}`

**Parameter:** tag_id

### Create new tag

`POST http://localhost:8000/api/tags`

**name**: tag name

### Update tag

`PUT/PATCH http://localhost:8000/api/tags/{tag_id}`

**Parameter:** tag_id

*request body as create tag*

### Delete tag

`DELETE http://localhost:8000/api/tags/{tag_id}`

**Parameter:** tag_id

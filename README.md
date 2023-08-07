# Web POS API Documentation

This document provides details about the Web POS API endpoints along with their request methods and descriptions.

## Authentication

All endpoints require authentication using a bearer token. The token must be included in the request headers with the key `Authorization`.

### Token

-   Key: `Authorization`
-   Value: `Bearer {{token}}`

## Endpoints

### 1. Login

```
 http://127.0.0.1:8000/api/v1/login

```

**Description**: This endpoint is used for user login. It requires an email and password as form data and returns a bearer token upon successful authentication.

#### Request

-   Method: `POST`

-   form data

| Arguments  | Type     | Description                  |
| :--------- | :------- | :--------------------------- |
| `name`     | `string` | **Required** admin@gmail.com |
| `password` | `string` | **Required** asdffdsa        |

#### Response

The response will contain the bearer token used for subsequent authenticated requests.

### 2. Profile

#### 2.1 Logout

```
http://127.0.0.1:8000/api/v1/logout

```

**Description**: This endpoint is used to log out the currently authenticated user.

#### Request

-   Method: `GET`

#### 2.2 Register for staff

```
http://127.0.0.1:8000/api/v1/register-staff

```

**Description**: This endpoint is used for user registration. Only admin users can register new users.

#### Request

-   Method: `POST`

### form data

| Arguments               | Type     | Description                  |
| :---------------------- | :------- | :--------------------------- |
| `name`                  | `string` | **Required** stuff@gmail.com |
| `email`                 | `string` | **Required** asdffdsa        |
| `password`              | `string` | **Required** asdffdsa        |
| `password_confirmation` | `string` | **Required** asdffdsa        |

### 3. Sale Processing

#### 3.1 Voucher

##### 3.1.1 Store

```
POST http://127.0.0.1:8000/api/v1/voucher
```

**Description**: This endpoint is used to process a sale and generate a voucher for the purchased products.

#### Request

-   Method: `POST`

-   Body:
    ```json
    {
        "products": [
            {
                "product_id": 1,
                "quantity": 5
            },
            {
                "product_id": 2,
                "quantity": 2
            }
        ],
        "customer_name": "myo min naing",
        "phone_number": "0912345678"
    }
    ```

#### 3.2 Voucher Record

##### 3.2.1 Store

**Endpoint**: `POST http://127.0.0.1:8000/api/v1/voucher-record`

**Description**: This endpoint is used to store voucher records.

#### Request

-   Method: `POST`

### form data

| Arguments    | Type     | Description                         |
| :----------- | :------- | :---------------------------------- |
| `voucher_id` | `number` | **Required** 1                      |
| `product_id` | `number` | **Required** 3                      |
| `quantity`   | `number` | **Required** 3                      |
| `cost`       | `number` | **Required** quantity \* sale_price |

### 4. Inventory Management

#### 4.1 Products

##### 4.1.1 Store

```
http://127.0.0.1:8000/api/v1/product

```

**Description**: This endpoint is used to add a new product to the inventory.

#### Request

-   Method: `POST`

### form data

| Arguments          | Type     | Description             |
| :----------------- | :------- | :---------------------- |
| `name`             | `string` | **Required** toothbrush |
| `brand_id`         | `number` | **Required** 3          |
| `actual_price`     | `number` | **Required** 100        |
| `sale_price`       | `number` | **Required** 1200       |
| `unit`             | `string` | **Required** dozen      |
| `more_information` | `string` | it's a toothbrush       |

##### 4.1.2 Index

```
http://127.0.0.1:8000/api/v1/product

```

**Description**: This endpoint is used to retrieve a list of all products in the inventory.

#### Request

-   Method: `GET`

#### Response

The response will contain a list of products.

##### 4.1.3 Update

```
http://127.0.0.1:8000/api/v1/product/32

```

**Description**: This endpoint is used to update information about a specific product.

#### Request

-   Method: `PUT`

### form data

| Arguments          | Type     | Description             |
| :----------------- | :------- | :---------------------- |
| `name`             | `string` | **Required** toothpaste |
| `brand_id`         | `number` | **Required** 3          |
| `actual_price`     | `number` | **Required** 100        |
| `sale_price`       | `number` | **Required** 1200       |
| `unit`             | `string` | **Required** dozen      |
| `more_information` | `string` | it's a toothpaste       |

##### 4.1.4 Delete

```
http://127.0.0.1:8000/api/v1/product/1
```

**Description**: This endpoint is used to delete a specific product from the inventory.

#### Request

-   Method: `DELETE`

##### 4.1.5 Show

```

 http://127.0.0.1:8000/api/v1/product/32

```

**Description**: This endpoint is used to retrieve information about a specific product.

#### Request

-   Method: `GET`

#### Response

The response will contain information about the specified product.

#### 4.2 Brand

##### 4.2.1 Store

```
 http://127.0.0.1:8000/api/v1/brand

```

**Description**: This endpoint is used to add a new brand to the inventory.

#### Request

-   Method: `POST`

### form data

| Arguments     | Type     | Description              |
| :------------ | :------- | :----------------------- |
| `name`        | `string` | **Required** cocala      |
| `company`     | `string` | **Required** max         |
| `information` | `text`   | **Required** lorem ispum |

##### 4.2.2 Index

```
http://127.0.0.1:8000/api/v1/brand

```

**Description**: This endpoint is used to retrieve a list of all brands in the inventory.

#### Request

-   Method: `GET`

#### Response

The response will contain a list of brands.

##### 4.2.3 Show

```
 http://127.0.0.1:8000/api/v1/brand/16

```

**Description**: This endpoint is used to retrieve information about a specific brand.

#### Request

-   Method: `GET`

#### Response

The response will contain information about the specified brand.

##### 4.2.4 Update

```
http://127.0.0.1:8000/api/v1/brand/16
```

**Description**: This endpoint is used to update information about a specific brand.

#### Request

-   Method: `PUT`

### form data

| Arguments     | Type     | Description              |
| :------------ | :------- | :----------------------- |
| `name`        | `string` | **Required** cocala      |
| `company`     | `string` | **Required** max         |
| `information` | `text`   | **Required** lorem ispum |

##### 4.2.5 Delete

```
http://127.0.0.1:8000/api/v1/brand/1

```

**Description**: This endpoint is used to delete a specific brand from the inventory.

#### Request

-   Method: `DELETE`

#### 4.3 Stock

##### 4.3.1 Store

```
http://127.0.0.1:8000/api/v1/stock
```

**Description**: This endpoint is used to store stock information.

#### Request

-   Method: `POST`

### form data

| Arguments          | Type     | Description              |
| :----------------- | :------- | :----------------------- |
| `product_id`       | `number` | **Required** 2           |
| `quantity`         | `number` | **Required** 50          |
| `more_information` | `text`   | **Required** lorem ispum |

##### 4.3.2 Index

```
http://127.0.0.1:8000/api/v1/stock

```

**Description**: This endpoint is used to retrieve a list of all stock items.

#### Request

-   Method: `GET`

#### Response

The response will contain a list of stock items.

##### 4.3.3 Show

```
http://127.0.0.1:8000/api/v1/stock/65

```

**Description**: This endpoint is used to retrieve information about a specific stock item.

#### Request

-   Method: `GET`

#### Response

The response will contain information about the specified stock item.

##### 4.3.4 Update

```
http://127.0.0.1:8000/api/v1/stock/68

```

**Description**: This endpoint is used to update information about a specific stock item.

#### Request

-   Method: `PUT`

### form data

| Arguments          | Type     | Description              |
| :----------------- | :------- | :----------------------- |
| `product_id`       | `number` | **Required** 2           |
| `quantity`         | `number` | **Required** 500         |
| `more_information` | `text`   | **Required** lorem ispum |

##### 4.3.5 Delete

**Endpoint**: `DELETE http://127.0.0.1:8000/api/v1/stock/1`

**Description**: This endpoint is used to delete a specific stock item.

#### Request

-   Method: `DELETE`

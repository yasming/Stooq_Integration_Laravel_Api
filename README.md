# Stooq Integration

This project is an API that integrates with stooq services: https://stooq.com/q/l/?s=AAPL.US&f=sd2t2ohlcvn&h&e=csv, the parameter "s" changes and according to this parameter results from the market are gotten.
One of the API's endpoints returns the market results, we also have, jwt authentication, user creation, and history of user's calls to market results endpoint.

## Prerequisites

```
Docker
```

### How to run project's locally

```
docker-compose up
```

```
After all containers be built the project will be able to access on localhost:80
```

```
To run project's test is need to do:

1. docker exec -it stooq_integration_nginx /bin/bash
2. php artisan test
```


## How to consume the project routes: 


- Authentication endpoint


```
POST http://localhost:80/api/login
```

```
Body: 
```

```
{
    email: "yasmin@hotmail.com",
    password: "1234"
} 
```

```
Response: 
```

```
{
    "data": {
        "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9zdG9vcS1pbnRlZ3JhdGlvbi1hcHAudGVzdFwvYXBpXC9sb2dpbiIsImlhdCI6MTY0NjUxNDQwMiwiZXhwIjoxNjQ2NTE4MDAyLCJuYmYiOjE2NDY1MTQ0MDIsImp0aSI6IjRXMWwxRXJQOVBlY3VaVk0iLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.NYVjA7TX9dLxqOXDTIdri60PW7u1RvzqxMvriouOfcw",
        "user": {
            "id": 1,
            "name": "Mr. Uriel Pollich MD",
            "email": "jo.wiza@example.net",
            "email_verified_at": "2022-03-05T21:06:23.000000Z",
            "created_at": "2022-03-05T21:06:24.000000Z",
            "updated_at": "2022-03-05T21:06:24.000000Z"
        }
    }
}
```


- Create user endpoint


```
POST http://localhost:80/api/users/store
```

```
Body: 
```

```
{
    name: "jane"
    email: "jane@doe.com",
    password: "1234"
} 
```

```
Response:
```

```
{
   "message" => "User created successfully !"
}
```


- Get informations from makert to a specific quot


```
GET localhost:80/api/stocks?q=AAPL.US
```

```
Response:
```

```
{
    "symbol": "AAPL.US",
    "date": "2022-03-04",
    "time": "22:00:07",
    "open": "164.49",
    "high": "165.55",
    "low": "162.1",
    "close": "163.17",
    "volume": "83819592",
    "name": "APPLE\r"
}
```


- Get history from users market successfully requests


```
GET localhost:8000/api/histories
```


```
Response:
```

```
{
   "current_page": 1,
    "data": [
        {
            "id": 1,
            "date": "2022-03-04 22:00:07",
            "name": "APPLE\r",
            "symbol": "AAPL.US",
            "open": 164.49,
            "high": 165.55,
            "low": 162.1,
            "close": 163.17,
            "user_id": 1,
            "created_at": "2022-03-05T21:15:02.000000Z",
            "updated_at": "2022-03-05T21:15:02.000000Z"
        }
    ],
    "first_page_url": "http://stooq-integration-app.test/api/histories?page=1",
    "from": 1,
    "last_page": 1,
    "last_page_url": "http://stooq-integration-app.test/api/histories?page=1",
    "links": [
        {
            "url": null,
            "label": "&laquo; Previous",
            "active": false
        },
        {
            "url": "http://stooq-integration-app.test/api/histories?page=1",
            "label": "1",
            "active": true
        },
        {
            "url": null,
            "label": "Next &raquo;",
            "active": false
        }
    ],
    "next_page_url": null,
    "path": "http://stooq-integration-app.test/api/histories",
    "per_page": 20,
    "prev_page_url": null,
    "to": 1,
    "total": 1
}
```

# POST *localhost/api/board*
## **Headers:**
#### Authorization: JWT
#### Accept: "application/json"

## **Params-example:**
``` json
    {
        "title": "test title4",
        "description": "test description4",
        || statuses not required, option
        "statuses": [ 
            "to do",
            "in progress",
            "reviews",
            "done"
        ]
    }
```

## **Response:**
``` json
    {
        "status": "success" OR "error"
        "result": true
    }
```
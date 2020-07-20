# GET *localhost/api/statistics/progressInPercentage*
## **Headers:**
#### Authorization: JWT
#### Accept: "application/json"

## **GET-Params-example:**
``` json
    {
        "board_id": [int]
    }
```

## **Response-example:**
``` json
    {
        "status": "success",
        "result": {
            "backlog": "25%",
            "development": "25%",
            "done": "50%",
            "review": "0%"
        }
    }
```
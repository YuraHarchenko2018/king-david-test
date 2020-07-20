# GET *localhost/api/statistics/bestUserByLastWeek*
## **Headers:**
#### Authorization: JWT
#### Accept: "application/json"

## **GET-Params-example:**
``` json
    {
        "board_id": [int]
    }
```

## **Response:**
``` json
    {
        "status": "success",
        "best_user": 2
    }
```
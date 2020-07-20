# GET *localhost/api/statistics/totalDoneTask*
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
        "tasks": [{...}, {...}, ...]
    }
```
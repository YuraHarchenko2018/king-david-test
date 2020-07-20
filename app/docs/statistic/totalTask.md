# GET *localhost/api/statistics/totalTask*
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
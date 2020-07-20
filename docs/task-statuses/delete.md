# PATCH *localhost/api/task-statuses/{id}*
## **Headers:**
#### Authorization: JWT
#### Accept: "application/json"

## **Params-example:**
``` json
    {
        "title": [string],
        "board_id": [int]
    }
```

## **Response:**
``` json
    {
        "status": "success" OR "error"
        "result": true
    }
```
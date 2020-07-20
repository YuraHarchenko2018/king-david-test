# POST *localhost/api/task-statuses*
## **Headers:**
#### Authorization: JWT
#### Accept: "application/json"

## **Params:**
``` json
    {
        "title": [string],
        "board_id": [int]
    }

## **Response:**
``` json
    {
        "status": "success" OR "error"
        "result": {...}
    }
```
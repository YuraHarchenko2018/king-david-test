# POST *localhost/api/task*
## **Headers:**
#### Authorization: JWT
#### Accept: "application/json"

## **Params-example:**
##### title [string]
##### status_id [int]
##### board_id [int]
##### image_link [int]

## **Response:**
``` json
    {
    "status": "success" OR "error"
    "result": {...}
```
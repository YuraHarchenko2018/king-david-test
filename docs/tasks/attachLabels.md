# POST *localhost/api/attachLabels/{id}* ["id" it's a id of task]
## **Headers:**
#### Authorization: JWT
#### Accept: "application/json"

## **Params-example:**
``` json
    {
        "label_id": [
            1,2
        ]
    }
```

## **Response:**
``` json
    {
        "status": "success" OR "error"
        "result": {...}
    }
```
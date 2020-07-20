# PATCH *localhost/api/board/{id}*
## **Headers:**
#### Authorization: JWT
#### Accept: "application/json"

## **Params-example:**
``` json
    {
        "title": [string],
        "description": [string]
    }
```

## **Response:**
``` json
    {
        "status": "success" OR "error"
        "result": true
    }
```
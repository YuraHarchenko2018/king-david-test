# POST *localhost/api/task/uploadPhoto*
## **Headers:**
#### Authorization: JWT
#### Accept: "application/json"

## **Params-example:**
##### uid [int] -- image_link in task
##### image [file]

## **Response:**
``` json
    {
        "status": "success" OR "error"
    }
```
# POST *localhost/api/login*
## **Headers:**
#### Accept: "application/json"

## **Params:**
##### name [string]
##### password [string]

## **Response:**
``` json
    {
        "status": success OR error,
        "jwt": __exactly_jwt__
    }
```

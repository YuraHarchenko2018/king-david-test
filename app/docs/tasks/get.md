# GET *localhost/api/task*
## **Headers:**
#### Authorization: JWT
#### Accept: "application/json"

## **GET-Params-example:**
? order_by       = id
& order_type     = desc
& page           = 1
& board_id       = 3
& statuses_ids[] = 23 || not required
& label_ids[]    = 12 || not required

## **Response:**
### response as paginator
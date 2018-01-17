# users-microservice

Is an API Services that used for maintain user.

User microservices.

This is a collection of API Services that used for maintain user.
This api has features:
- Browse users:
`GET /users`
- Read User by id:
`GET /users/{id}`
- Edit User by id:
`PATCH /users/{id}`
- Add User:
`POST /users`
- Delete User by ID
`DELETE /users/{id}`
- Delete Mulitple User
`DELETE /users`


Framework using latest production lumen version.
- Use Service Layer for Domain Business Logic

User microservice has entities:

- User
    - id (uuid.v4)
    - first_name
    - last_name
    - password
    - email
    - phone
    - country
    - created_at
    - updated_at
    - deleted_at

- LoginHistory
    - user_id (uuid.v4)
    - login_date

# Technical Docs

## This is the process of technical development of requested tests : 
- Install [Laravel 8](https://laravel.com/docs/8.x/installation)
- Install and setup [Laravel Passport](https://laravel.com/docs/8.x/passport) for the authentication, i;m using version 10.*
- Create migration, models and controllers of Category, Book, and Loan
- Create migration of Book Category
- Create all method/function in the controller of Category, Book, and Loan
- Create all routes for the Category, Book, and Loan
- Create CategoryFactory, BookFactory, and LoanFactory for Unit Testing
- Create CategoryControllerTest, AuthControllerTest, BookControllerTest and LoanControllerTest for unit testing in each method of the controller

## How to run in locally
- Make sure the laravel version is 8
- Clone this repo
- Run composer update
- In the env, you must have a empty db to connect this project
- Run php artisan migrate
- Run php artisan passport:install
- Run php artisan key:generate
- Run php artisan serv

## List of Endpoint Api
### Endpoint Login/Register
- Register : http://127.0.0.1:8000/api/register **(POST)**, in this endpoint have some parameters.

    **name**, **email**, **password** and **password_confirmation**

    Example :
    {
        "name": "thomas",
        "email": "thomas@gmail.com",
        "password": "12345678",
        "password_confirmation": "12345678"
    }

    Result : 
    {
    "user": {
        "name": "thomas",
        "email": "thomas@gmail.com",
        "updated_at": "2024-07-17T07:47:29.000000Z",
        "created_at": "2024-07-17T07:47:29.000000Z"
    },
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiZGM0MzE1ZjJjZjZhY2M2ZGUwZGMwMDAyMzYxMDM3YWI2NWFlM2Y2ZGZhYzA5YjIwMGFhYWRhYTQ2ODM1ZWY5MDMzMmI3ZjcwNzI4MzU5NWQiLCJpYXQiOjE3MjEyMDI0NDkuNDM4ODE4LCJuYmYiOjE3MjEyMDI0NDkuNDM4ODE4LCJleHAiOjE3NTI3Mzg0NDkuNDM0MzIxLCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.LZIat50mReRA2CXmnqjaho6oiwaI72CyeC99KAYYPmKyWNpoJ5QCJ0Y4Z2bn9L31cLDoCc8LAqlBYyKIBCFzzU3Kfe_Bv6ks3a0JGskd5_VisX99dchna8feZ11KtLpgrV0DBNYhs_SNgdukfYI4PGZotG5irGJk6qlSK-Z74U8Ii-aUOnHuQmKhgDk8P8CRoSwDBAkj5lJxOOmKyMI6VOIxnfnSjddx5bUMGgNGtzFSf_Ue5CapHp1ZnQinPA7zAv5CA49f762ynT0askUUvdpgZDZh781qmON5lygyn5kZauZaxrH-r3gPcPTc8racOHnOTrVuwM36CGRwVb34G2DvrVkLw0FZDb9INkLpWXBn2OAapr6tMhqclQjkg2-M19q4UsL7hnVo-jspmna_RPglV3GNuKA5torGF2NEWfROuVNp1eMcFpD5llU6rILdJMJVz4fsjKTXCJSigC7G7z94rC-4o_2H56kGquA8x3OqYM6LDCiDiqKVDmdBL3zWUns2j4bpGcYQ1G8p_iPUOKV0oFWF3hs6OusFenKjLYYNt8-bKi4xQnSjYI6eyGfOup_xqeDURYvq8nO7lefJnllz0QMyflhOYPQCafEYmryGWtz8p86n7hDgBnwJU8sEE55vHHlx7vkDMcRuT3QOJ0UtSQIAkXDxotdVyXfnjlc"
    }
- Login : http://127.0.0.1:8000/api/login **(POST)**, in this endpoint have two parameters :

    **email** and **password**
    Example :
    {
        "email": "thomas@gmail.com",
        "password": "12345678",
    }

    Result :
    {
    "user": {
        "name": "thomas",
        "email": "thomas@gmail.com",
        "email_verified_at": null,
        "created_at": "2024-07-17T07:47:29.000000Z",
        "updated_at": "2024-07-17T07:47:29.000000Z"
    },
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiMDIwZWNkMzIxYTMyNDE5Zjc2OTY3MjE1ZWVmOTRlYTI1Y2RiNTgzMDc1NzU1YjdiOTRmZWM2ODU3NDMyN2VhNzcxNTg3MDJhNmQwYjdkZjQiLCJpYXQiOjE3MjEyMDI0NTMuNzAzNzA0LCJuYmYiOjE3MjEyMDI0NTMuNzAzNzA1LCJleHAiOjE3NTI3Mzg0NTMuNzAwOTI5LCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.IAOJZAcNxDeUfr4vWc_HV2yA-Vi54kZVkI6NFB5YOAlxd6NSogS_IseIX1_J7OYDPPFPciL28RHiOY7lWvSNsMR43Hq9RRxG7KgBZD2TZsuKX2O7MEMiWhb6I6C_Ze4yfX0S4ltOJsBhwQiS7a2UeUMXBftPH3zmBj9BzuO9UdyClL-LZ-c0rE62yxqugaDXdIEE_iiv_blqgpJk0Oupx2mdnj99vUos-1Ocfcl4atHBplPo4rCkRmzzP3Q13mRPW94wCX7D5iSjEKLrhLnV-FrviERBGZPZ3LeQtcFGC08qKnP141g0An-4xiwz9OvPG4J2CasY2lN4OWJwh__Ja1y9SDCo88ndzlW_9T7grJTJjNOGGr3-tZCMnsMpcK38In3RF1LeVA7LEo9rQAZ0Y3KAmR6X0BYTDe9aGDhjjidxnloq5vCzRblOP3uaf8-iRKSJTViArar9_8CnlJPJ9MpPugRTnwGH9yiBCG82dCVecBThbSfcrbvIEJZugsBx2YK0Z5CEZ715b5KJGOQxzwmIiCdUZr8BRpWRO4nUNNaQ81hGq832VhwHIuxUcYozCOlQkDXaLUKh0NCqMfKQtg7zfeu9rFjUbX7VKRv4_ZTBWZ2nqTRhyDG23c5JS1BIt1qaJh-S0zX_m97fZfSr_gkJ9qH4dj713xC7nXF58RA"
    }

### Endpoint Category
- **Show All Data Category :** http://127.0.0.1:8000/api/category **(GET)**, this is endpoint to showing all the data of category.
- **Show Each Data (by id) Category :** http://127.0.0.1:8000/api/category/:id **(GET)**, this is endpoint to showing each data **(by id)** of category.
- **Create a Data Category :** http://127.0.0.1:8000/api/category **(POST)**, in this endpoint have one paramater :

    **name**
- **Update a Data Category :** http://127.0.0.1:8000/api/category/:id **(PATCH)**, in this endpoint have some parameter like endpoint before **(POST)**
- **Delete a Data Category :** http://127.0.0.1:8000/api/category/:id **(DELETE)**

### Endpoint Book
- **Show All Data Book :** http://127.0.0.1:8000/api/book **(GET)**, this is endpoint to showing all the data of book.
- **Show Each Data (by id) Book :** http://127.0.0.1:8000/api/book/:id **(GET)**, this is endpoint to showing each data **(by id)** of book.
- **Create a Data Book :** http://127.0.0.1:8000/api/book **(POST)**, in this endpoint have some parameters :

  **title**, **author**, **category_id**
  Note : the **category_id** is array, so you have to send using array data
- **Update a Data Book :** http://127.0.0.1:8000/api/book/:id **(PATCH**), in this have some parameter like endpoint before **(POST)**
- **Delete a Data Book :** http://127.0.0.1:8000/api/book/:id **(DELETE)**

### Endpoint Loan
- **Show All Data Loan :** http://127.0.0.1:8000/api/loan **(GET)**, this is endpoint to showing all the data of loan.
- **Show Each Data (by id) Loan :** http://127.0.0.1:8000/api/loan/:id **(GET)**, this is endpoint to showing each data **(by id)** of loan.
- **Create a Data Loan :** http://127.0.0.1:8000/api/loan **(POST)**, in this endpoint have some paramaters :

    **book_id**, **borrower**, **borrowed_at**, **returned_at**(this is nullable)
- **Update a Data Loan :** http://127.0.0.1:8000/api/loan/:id **(PATCH)**, in this endpoint have some parameter like endpoint before **(POST)**
- **Delete a Data Loan :** http://127.0.0.1:8000/api/loan/:id **(DELETE)**

- **Show Data Borrowers :** http://127.0.0.1:8000/api/borrowed-books **(GET)**, borrowers data when the user has not returned the book.
- **Update Data Book Return :** http://127.0.0.1:8000/api/return-book/:id **(PATCH)**, in this endpoint have one paramater : 

    **returned_at**




Endpints

http://localhost/api-restful-slim-test-php/public/api/v1/customers
http://localhost/api-restful-slim-test-php/public/api/v1/customer/{Rfrnc}
http://localhost/api-restful-slim-test-php/public/api/v1/customers/new

curl --location --request POST 'http://localhost/api-restful-slim-test-php/public/api/v1/customers/new \
--header 'Content-Type: application/json' \
--data-raw '{
        "dni":"2274959899",
        "id_reg": 1,
        "id_com":1,
        "email":"aaa@b.com",
        "name":"AAA",
        "last_name":"BBB",
        "address": "Ass",
        "status": "A"
    }'

http://localhost/api-restful-slim-test-php/public/api/v1/customers/delete/{Rfrnc}

curl --location --request POST 'http://localhost/api-restful-slim-test-php/public/api/v1/customers/new' \
--header 'Content-Type: application/json' \
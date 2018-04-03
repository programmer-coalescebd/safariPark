##1. API Endpoint
API Endpoint for the app is `DOMAIN_NAME/api/v2/`

API Engine require authorization using header `X-Auth-Ibn`, `X-Auth-Ibn` captures user IP address and access/visit time and valid time (upon validity duration). If the IP address changes or validity expires, request will return Error.

In order to call the encrypted value of `X-Auth-Ibn`, you have to include `encryption_lite.php` file on your script. Then initialize the token generator like bellow :-

```
require_once 'encryption_lite.php';
$encryption = new encryption;

if (strpos($_SERVER['REMOTE_ADDR'], '192.168') !== false) {
    $ip = file_get_contents("http://ipecho.net/plain");
} elseif (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}

$headerToken = $encryption->enc_token($ip, 10);
```
`enc_token` function has two parameters. First part is the user IP address and the second is validity duration in minutes. You can use your own IP capture function in order to avoid `Proxy` or `VPN` access.

Now `$headerToken` has a long encrypted value of user access IP and token validate time. Value is encrypted using `OpenSSL` and `base64`, which only can be decrypt using same SSL certificate. Simply push this header with your HTTP request, for example :-

#####`PHP file_get_contents`
```
$opts = [
    "http" => [
        "method" => "GET",
        "header" => "X-Auth-Ibn: ".$headerToken."\r\n"
    ]
];

$context = stream_context_create($opts);
$request = file_get_contents('DOMAIN_NAME/api/v2/tickets', false, $context);
```

#####`PHP Curl`
```
curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-Auth-Ibn:'.$headerToken));
```

#####`PHP Guzzle`
```
$client = new GuzzleHttp\Client(['headers' => ['X-Auth-Ibn' => $headerToken]]);
```

#####`jQuery/Ajax`
```
$.ajax({
    url: 'DOMAIN_NAME/api/v2/tickets',
    type: "GET",
    headers: { 'X-Auth-Ibn': '<?php echo $headerToken; ?>' }
});
```

#####`AngularJS`
```
$http.get('DOMAIN_NAME/api/v2/tickets', {headers: {'X-Auth-Ibn': '<?php echo $headerToken; ?>'}}).then(function (data) {

});
```

##2. Ticket List Endpoint
API Endpoint for ticketing is `DOMAIN_NAME/api/v2/tickets`, header `X-Auth-Ibn` is required.

This End point supports `GET` and `POST` method but `GET` is preferable because Authorization is sending by header.

On a successful request, End Point response will be : 
```
->entry_tickets[Object]
-->[Array]
--->id[String]
--->sort_by[String]
--->slug[String]
--->name[String]
--->price[String]
--->description[String]
--->require[String]

->safari_tickets[Object]
-->[Array]
--->id[String]
--->sort_by[String]
--->slug[String]
--->name[String]
--->price[String]
--->description[String]

->schedules[Object]
-->entry_tickets[Object]
--->dates[Object]
---->[Array]
----->open[String]
----->break[String]
----->reopen[String]
----->close[String]
----->tickets[String]
-->safari_tickets[Object]
--->dates[Object]
---->[Array]
----->open[String]
----->break[String]
----->reopen[String]
----->close[String]
----->tickets[String]

->advance_date[String]

->valid[String]

->limit[String]

->fee[String]

->payment[String]
``` 

`.entry_tickets` has tickets from Entry Ticket Category. `id` is numeric id for the ticket, `sort_by` is the position of this ticket, `slug` is unique alphanumeric id, `name` is ticket title (only Bangla available now), `price` is ticket price in floating value, `description` is ticket description in HTML format and `require` is if this ticket require an adult (boolean).

`.safari_tickets` has tickets from Entry Ticket Category. `id` is numeric id for the ticket, `sort_by` is the position of this ticket, `slug` is unique alphanumeric id, `name` is ticket title (only Bangla available now), `price` is ticket price in floating value and `description` is ticket description in HTML format.

`.schedules` has two child objects `.schedules.entry_tickets` and `.schedules.safari_tickets`. These are the park time schedule with open, break, reopen and closing time based of date (DD/MM/YYYY). This dates also has how many tickets are available to purchase.

`advance_date` refer to how many days in advance tickets are purchasable in number eg.`10`.

`valid` refer to how many days tickets will be valid for, it's also numeric value eg.`10`.

`limit` is per person limit for purchasing tickets, it's also numeric value eg.`10`.

`fee` this is the payment gateway processing fee, it's floating value.

`payment` this is payment URL of the server eg.`DOMAIN_NAME/payment`

##3. Placing Order
After you verify your ticketing form you can place your order to `.payment[URL]` of ticketing API. **You need user login token** as well, check the login section for how to get `Token`.

Payment End Point supports json format ticketing information, so you have to prepare your json before you place an order. Ticketing json format as bellow :-

```
[
  {
    "entry_tickets": [
      {
        "ticketID": "48",
        "ticketQuantity": "2"
      },
      {
        "ticketID": "49",
        "ticketQuantity": "1"
      }
    ],
    "safari_tickets": [
      
    ],
    "entry_travel_date": "13/09/2017",
    "entry_valid_date": "23/09/2017",
    "entry_timeID": "1",
    "safari_travel_date": "",
    "safari_valid_date": "",
    "safari_timeID": "",
    "platform": "web"
  }
]
``` 

On the above example two Entry tickets has been picked,  `ticketID`:`48` Quantity `2` and `ticketID`:`49` Quantity `1`. When Entry Ticket is chosen `entry_travel_date`, `entry_valid_date` and `entry_timeID` is required. Same as above when Safari ticket will be selected, you need dates and `timeID` for Safari Ticket. You can't place multi category ticket together now, if you want to use this you have to mention how you want to use 2 payment URL.

When your json is ready you can make a HTTP `GET` request to `.payment[URL]` from ticketing API along with user access `Token`. Request example :-

```
PAYMENT_URL/USER_TOKEN/TICKET_JSON
```

On successful request Payment Endpoint will return Invoice Number and payment gateway URL as bellow :-
```
{
  "Status": "408",
  "Message": "Order has been placed",
  "Invoice": "HBWV-85614032",
  "URL": "https:\/\/sandbox.sslcommerz.com\/gwprocess\/v3\/gw.php?Q=PAY&SESSIONKEY=567E402E6BD96D2BA4B0DA52CD4FB3EE"
}
```
Now you can redirect user to `URL`, where he can pay for his invoice.

Once payment is made ticket information will be available at user account endpoint.

##4. User Register
API Endpoint for user registration is `DOMAIN_NAME/api/v2/register`, header `X-Auth-Ibn` is required.

This End point only support `POST` method with form data format `json`.

Once you validate your form you can make a HTTP `POST` request to `DOMAIN_NAME/api/v2/register` with bellow json post data :-

```
{
  "name": "USER_NAME",
  "email": "USER_EMAIL",
  "phone": "USER_PHONE,
  "password": "USER_PASSWORD",
  "password_re": "USER_PASSWORD_AGAIN",
  "language": "LANGUAGE_CODE"
}
```
You can use `language` `en` or `bn` to customize server response message. 

On successful request user will receive a confirmation email. Once he validate his email address he can login and purchase tickets or access his account.

##5. User Login
API Endpoint for user login is `DOMAIN_NAME/api/v2/login`, header `X-Auth-Ibn` is required.

This End point only support `POST` method with form data format `json`.

Once you validate your form you can make a HTTP `POST` request to `DOMAIN_NAME/api/v2/login` with bellow json post data :-

```
{
  "username": "USER_EMAIL OR USER_PHONE",
  "password": "USER_PASSWORD,
  "platform": "Web",
  "language": "LANGUAGE_CODE"
}
```
You can use `language` `en` or `bn` to customize server response message.

**On successful request server response will have an unique user access `Token`. This token is required for purchasing tickets or accessing user account.**

##6. Forget Password
API Endpoint for forget password is `DOMAIN_NAME/api/v2/forget`, header `X-Auth-Ibn` is required.

This End point only support `POST` method with form data format `json`.

Once you validate your form you can make a HTTP `POST` request to `DOMAIN_NAME/api/v2/forget` with bellow json post data :-

```
{
  "email": "USER_EMAIL OR USER_PHONE",
  "language": "LANGUAGE_CODE"
}
```
You can use `language` `en` or `bn` to customize server response message.

On successful request user will receive an email with password reset link. This request will not change user's current password until user click that link and set new password.

##7. Change Password
API Endpoint for changing password is `DOMAIN_NAME/api/v2/password/USER_TOKEN`, header `X-Auth-Ibn` is required. And you need user access token from login.

This End point only support `POST` method with form data format `json`.

Once you validate your form you can make a HTTP `POST` request to `DOMAIN_NAME/api/v2/password/USER_TOKEN` with bellow json post data :-

```
{
  "old_password": "USER CURRENT PASSWORD",
  "password": "USER NEW PASSWORD",
  "re_password": "USER NEW PASSWORD CONFIRM",
  "language": "LANGUAGE_CODE"
}
```
You can use `language` `en` or `bn` to customize server response message.

**On successful request user password will be reset, you require to reset user token with new user token sent by server.**

##8. User Account
API Endpoint for user account is `DOMAIN_NAME/api/v2/account/USER_TOKEN`, header `X-Auth-Ibn` is required. And you need user access token from login.

This End point supports `GET` and `POST` method but `GET` is preferable because Authorization is sending by header.

On a successful request, End Point response will be : 

```
->account[Object]
-->id[String]
-->name[String]
-->email[String]
-->phone[String]
```

`.account` has user account information. `id` is numeric id of the user, `name` is user full name, `email` is user email address and `phone` is user phone number.


##9. User Bookings
API Endpoint for user account is `DOMAIN_NAME/api/v2/myTicket/USER_TOKEN`, header `X-Auth-Ibn` is required. And you need user access token from login.

This End point supports `GET` and `POST` method but `GET` is preferable because Authorization is sending by header.

On a successful request, End Point response will be : 


```
->bookings[Object]
-->[Array]
--->id[String]
--->invoice_id[String]
--->qr_code[String]
--->PNR[String]
--->phone[String]
--->status[String]
--->total_amount[String]
--->type[String]
--->visit_date[String]
--->visit_time[String]
--->valid_date[String]
--->visited_date[String]
--->created_at[String]
--->tickets[Object]
---->[Array]
----->name[String]
----->total[String]
```


`.bookings` has all order placed by user with `id` = order id, `invoice_id` = invoice Number, `qr_code` = base64 format image of unique order id, `PNR` is a secret code for this order, `phone` is user phone number, `status` is order status (see bellow for status type), `total_amount` is total amount user paid for this order, `type` is ticket category or type eg. `entryTicket` or `safariTicket`, `visit_date` date user selected for travel `UNIX time`, `visit_time` is time user selected for travel, `valid_date` is ticket validity time `UNIX time`, `visited_date` is if user already used these tickets the date he visited will be here `UNIX time`, `created_at` order placed time `UNIX time` and `tickets` has ticket user selected from that category.


`status` = `0` this order has no activity.

`status` = `1` order has been cancelled from payment gateway.

`status` = `2` order has been failed from payment gateway.

`status` = `3` order has been expired.

`status` = `4` order has been used by user.

`status` = `5` order has been paid and usable.
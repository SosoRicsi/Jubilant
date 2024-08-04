<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{subject}}</title>
    </head>
    <body>
        <h1>Tisztelt {{username}}!</h1>
        <h1>Ezen email formájában értesítjük önt, hogy fiókjába új eszközről jelentkeztek be!</h1>
        <h2>Az eszköz adata:</h2>
        <ul>
            <li><u>Böngésző:</u> {{browser}}</li>
            <li><u>IP-cím:</u> {{ip}}</li>
            <li><u>Dátum:</u> {{date}}</li>
        </ul>

        <p>Amennyiben nem ön volt, megváltoztathatja a jelszavát <a href='http://localhost:9231/dashboard/account/changePassword'>itt!</a></p>
    </body>
</html>
<!DOCTYPE html>
<html lang="hu">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{subject}}</title>
    </head>
    <body>
        <h1>Tisztelt {{username}}!</h1>
        <p>Ezen e-mail formájában értesítjük önt, hogy ekkor sikeresen regisztrált az oldalunkra: {{registerDate}}</p>
        <p>A fiókja feltételezen bejegyzett álopotba került. Kérjük erősítse meg a regisztrációját ezen a linken keresztül: <a href="http://localhost:9231/execute/register/authenticate/{{customID}}">Megerősítés</a></p>
    </body>
</html>
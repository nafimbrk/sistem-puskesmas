<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite('resources/js/app.js')
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    @inertiaHead
</head>

<body>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    @routes
    @inertia
</body>

</html>



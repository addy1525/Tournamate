<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="refresh" content="0;url={{ route('login') }}?role=referee">
    <title>Redirect — Tournamate Referee</title>
</head>
<body>
    <script>window.location.replace("{{ route('login') }}?role=referee");</script>
</body>
</html>
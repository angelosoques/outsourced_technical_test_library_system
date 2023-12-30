<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Home</title>
</head>
<body>
    <h1>Library View</h1>

    @if(isset($data))
        <?php dd($data) ?>
        <ul>
            @foreach($data as $item)
                <li>{{ $item }}</li>
            @endforeach
        </ul>
    @else
        <p>No data available.</p>
    @endif
</body>
</html>
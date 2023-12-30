<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Profile</title>
</head>
<body>
    <h1>Member Profile</h1>

    @if(isset($borrowedBooks))
        <?php dd($borrowedBooks) ?>
        <ul>
            @foreach($borrowedBooks as $item)
                <li>{{ $item }}</li>
            @endforeach
        </ul>
    @else
        <p>No data available.</p>
    @endif
</body>
</html>

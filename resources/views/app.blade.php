<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Shopping List')</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
</head>
<body>
    @extends('layouts.app')

@section('content')
    <div>
        <ul>
            @foreach ($shoppingList as $item)
                <li id="item-{{ $item->id }}" 
                    class="{{ $item->is_purchased ? 'text-decoration-line-through' : '' }}">
                    {{ $item->name }}
                    <button class="toggle-purchased" data-id="{{ $item->id }}">Toggle Purchased</button>
                </li>
            @endforeach
        </ul>
    </div>
    <form action="/add" method="POST">
        @csrf
        <input type="text" name="item_name" class="form-control" placeholder="Enter item" required>
        <input type="text" name="category" class="form-control" placeholder="Enter category">
        <button type="submit" class="btn btn-primary">Add</button>
    </form>

    @foreach ($items->groupBy('category') as $category => $groupedItems)
    <h4>{{ $category ?? 'Uncategorized' }}</h4>
    <ul class="list-group">
        @foreach ($groupedItems as $item)
            <li class="list-group-item">{{ $item->item_name }}</li>
        @endforeach
    </ul>
@endforeach

<form action="/" method="GET">
    <select name="filter" onchange="this.form.submit()">
        <option value="">All</option>
        <option value="purchased">Purchased</option>
        <option value="not_purchased">Not Purchased</option>
    </select>
</form>

    <script>
        document.querySelectorAll('.toggle-purchased').forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const itemId = this.dataset.id;
                fetch(`/toggle-purchased/${itemId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const listItem = document.getElementById(`item-${itemId}`);
                        listItem.classList.toggle('text-decoration-line-through', data.is_purchased);
                    }
                });
            });
        });
    </script>
@endsection

    <div class="container mt-4">
        @yield('content')
    </div>
    
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

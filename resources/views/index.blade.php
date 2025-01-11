<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Shopping List')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    @extends('layouts.app')

    @section('title', 'Shopping List')

    @section('content')
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Shopping List</a>
        </div>
    </nav>

    <div class="container mt-5">
        <h1 class="text-center">Manage Your Shopping List</h1>

        <form id="add-item-form" class="mt-4" action="/add" method="POST">
            @csrf
            <div class="mb-3">
                <label for="item-name" class="form-label">Add New Item</label>
                <input type="text" name="item_name" class="form-control" id="item-name" placeholder="Enter item name" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Item</button>
        </form>

        <h2 class="mt-5">Items</h2>
        <ul class="list-group" id="shopping-list">
            @foreach ($shoppingList as $item)
                <li id="item-{{ $item->id }}" class="list-group-item {{ $item->is_purchased ? 'text-decoration-line-through' : '' }}">
                    {{ $item->name }}
                    <button class="btn btn-sm btn-warning toggle-purchased" data-id="{{ $item->id }}">Toggle Purchased</button>
                </li>
            @endforeach
        </ul>

        <div class="mt-3">
            {{ $shoppingList->links() }}
        </div>
    </div>
    @endsection

    @section('scripts')
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

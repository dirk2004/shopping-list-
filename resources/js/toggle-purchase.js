document.querySelectorAll('.toggle-purchased').forEach(button => {
    button.addEventListener('click', function (e) {
        e.preventDefault();
        const itemId = this.dataset.id;
        fetch(`/toggle-purchased/${itemId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
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

// Seleccionar/deseleccionar todos los checkboxes
document.getElementById('selectAll').addEventListener('click', function (e) {
    const checkboxes = document.querySelectorAll('input[name="ids[]"]');
    checkboxes.forEach(checkbox => checkbox.checked = e.target.checked);
});

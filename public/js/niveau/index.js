document.addEventListener('DOMContentLoaded', () => {
    const tableBody = document.getElementById('niveaux-table-body');

    // Fetch niveaux data
    fetch('/api/niveaux')
        .then(response => response.json())
        .then(data => {
            // Clear any existing rows
            tableBody.innerHTML = '';

            // Loop through the data and create table rows
            data.forEach(niveau => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="px-6 py-4">${niveau.id}</td>
                    <td class="px-6 py-4">${niveau.nomNiveau}</td>
                `;
                tableBody.appendChild(row);
            });
        })
        .catch(error => {
            console.error('Error fetching niveaux data:', error);
        });
});

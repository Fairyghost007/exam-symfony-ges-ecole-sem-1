document.addEventListener('DOMContentLoaded', () => {
    // Fetch the cours data from the API
    fetch('/api/cours')
        .then(response => response.json())
        .then(data => {
            // Get the table body element
            const tableBody = document.getElementById('cours-table-body');

            // Clear any existing rows
            tableBody.innerHTML = '';

            // Loop through the data and create table rows
            data.forEach(cour => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="px-6 py-4">${cour.id}</td>
                    <td class="px-6 py-4">${cour.nomCours}</td>
                    <td class="px-6 py-4">${cour.module}</td>
                    <td class="px-6 py-4">${cour.professeur}</td>
                    <td class="px-6 py-4">
                        <a href="/cours/edit/${cour.id}" class="text-blue-500 hover:text-blue-700">Edit</a>
                        <a href="/cours/delete/${cour.id}" class="text-red-500 hover:text-red-700 ml-2">Delete</a>
                    </td>
                `;
                tableBody.appendChild(row);
            });
        })
        .catch(error => {
            console.error('Error fetching cours data:', error);
        });
});

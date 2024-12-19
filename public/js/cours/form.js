document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('cours-form');

    // Fetch modules and professors
    fetch('/api/modules')
        .then(response => response.json())
        .then(data => {
            const moduleSelect = document.getElementById('module');
            data.modules.forEach(module => {
                const option = document.createElement('option');
                option.value = module.value;
                option.textContent = module.name;
                moduleSelect.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Error fetching modules:', error);
        });

    fetch('/api/professeurs')
        .then(response => response.json())
        .then(data => {
            const professeurSelect = document.getElementById('professeur');
            data.professeurs.forEach(professeur => {
                const option = document.createElement('option');
                option.value = professeur.id;
                option.textContent = professeur.nom;
                professeurSelect.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Error fetching professors:', error);
        });

    form.addEventListener('submit', (event) => {
        event.preventDefault();

        // Collect form values
        const nomCours = document.getElementById('nomCours').value;
        const module = document.getElementById('module').value;
        const professeur = document.getElementById('professeur').value;

        const data = {
            nomCours: nomCours,
            module: module,
            professeur: professeur
        };

        console.log('Sending data:', data); // Debugging statement

        fetch(form.action, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data),
        })
        .then(response => {
            console.log('Response:', response); // Debugging statement
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(result => {
            console.log('Result:', result); // Debugging statement
            if (result.message === 'Cour added successfully') {
                window.location.href = '/cours';
            } else {
                alert('Error adding cours: ' + result.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while adding the cours.');
        });
    });
});

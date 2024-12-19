document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('niveau-form');

    form.addEventListener('submit', (event) => {
        event.preventDefault();

        // Collect form values
        const nomNiveau = document.getElementById('nomNiveau').value;

        const data = {
            nomNiveau: nomNiveau,
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
            if (result.message === 'Niveau added successfully') {
                window.location.href = '/niveau';
            } else {
                alert('Error adding niveau: ' + result.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while adding the niveau.');
        });
    });
});

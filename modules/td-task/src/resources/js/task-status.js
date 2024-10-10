document.addEventListener('DOMContentLoaded', function () {
    // Finn status select-boksen
    const statusSelect = document.getElementById('status');

    if (statusSelect) {
        // Legg til en event listener for endring av status
        statusSelect.addEventListener('change', function () {
            // Hent oppgave-ID og ny status
            const taskId = this.getAttribute('data-task-id');
            const newStatusId = this.value;

            // Debugging: Sjekk om taskId og newStatusId er riktig
            console.log('Task ID:', taskId);
            console.log('Selected Status ID:', newStatusId);

            // Send AJAX-forespørsel for å oppdatere status
            fetch(`/tasks/${taskId}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    status_id: newStatusId
                })
            })
            .then(response => {
                // Debugging: Sjekk om vi får en respons
                console.log('Response received:', response);
                return response.json();
            })
            .then(data => {
                // Debugging: Sjekk dataen fra serveren
                console.log('Data from server:', data);

                if (data.success) {
                    alert('Status updated successfully!');
                } else {
                    alert('Failed to update status.');
                }
            })
            .catch(error => {
                // Debugging: Sjekk om det er noen feil i fetch-funksjonen
                console.error('Error occurred:', error);
            });
        });
    }
});

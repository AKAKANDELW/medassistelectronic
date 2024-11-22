async function loadPatients() {
    const response = await fetch('getPatients.php');
    const patients = await response.json();
    const patientsList = document.getElementById('patients-list');

    patients.forEach(patient => {
        const listItem = document.createElement('li');
        listItem.textContent = `${patient.name} - ${patient.email}`;
        patientsList.appendChild(listItem);
    });
}

// Call loadPatients when the page loads
document.addEventListener('DOMContentLoaded', loadPatients);

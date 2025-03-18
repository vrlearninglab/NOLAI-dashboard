document.addEventListener("DOMContentLoaded", fetchSessions);

let allSessions = []; // Hier slaan we alle sessies op

function fetchSessions() {
    axios.get("/api/sessions")
        .then(response => {
            allSessions = response.data; // Bewaar alle sessies
            renderSessions(allSessions); // Render de sessies
        })
        .catch(error => console.error("Fout bij laden sessies:", error));
}

function renderSessions(sessions) {
    let tableBody = document.getElementById("sessionTableBody");
    tableBody.innerHTML = ""; // Reset de tabel

    sessions.forEach(session => {
        let row = document.createElement("tr");
        row.onclick = function() {
            window.location.href = `/sessie-analyse/${session.id}`;
        };

        row.innerHTML = `
            <td>${session.id}</td>
            <td>${session.created_at}</td>
            <td>${session.researcher_name}</td>
            <td>${session.student_number}</td>
        `;

        tableBody.appendChild(row);
    });
}

function filterSessions() {
    let searchValue = document.getElementById("searchInput").value.toLowerCase();

    // Verkrijg de geselecteerde filters
    let filterId = document.getElementById("filterId").checked;
    let filterDate = document.getElementById("filterDate").checked;
    let filterResearcher = document.getElementById("filterResearcher").checked;
    let filterStudent = document.getElementById("filterStudent").checked;

    let filteredSessions = allSessions.filter(session => {
        let match = false; // Standaard geen match, pas aan als een filter klopt

        // Filteren op ID (indien ingeschakeld)
        if (filterId && session.id.toString().includes(searchValue)) {
            match = true;
        }

        // Filteren op datum (indien ingeschakeld)
        if (filterDate && session.created_at.toLowerCase().includes(searchValue)) {
            match = true;
        }

        // Filteren op naam onderzoeker (indien ingeschakeld)
        if (filterResearcher && session.researcher_name.toLowerCase().includes(searchValue)) {
            match = true;
        }

        // Filteren op studentnummer (indien ingeschakeld)
        if (filterStudent && session.student_number.toLowerCase().includes(searchValue)) {
            match = true;
        }

        return match; // Alleen sessies die aan ten minste één filter voldoen
    });

    renderSessions(filteredSessions); // Update de tabel met de gefilterde sessies
}

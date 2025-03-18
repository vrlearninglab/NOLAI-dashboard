document.addEventListener("DOMContentLoaded", fetchNotes);

function fetchNotes() {
    let sessionId = window.sessionId || null; // Haal de sessie-ID op uit de globale variabele

    let url = sessionId ? `/notes/${sessionId}` : "/notes"; // Dynamische URL

    axios.get(url)
        .then(response => {
            let notesList = document.getElementById("notesList");
            notesList.innerHTML = ""; // Reset lijst

            response.data.forEach(note => {
                let li = document.createElement("li");
                li.textContent = `${note.created_at}: ${note.message}`; // Voeg timestamp toe
                notesList.appendChild(li);
            });
        })
        .catch(error => console.error("Fout bij laden notities:", error));
}

function addNote() {
    let noteInput = document.getElementById("noteInput");
    let message = noteInput.value.trim();
    if (!message) return;

    let sessionId = window.sessionId || null; // Sessie-ID ophalen
    let url = sessionId ? `/notes/${sessionId}` : "/notes"; // Dynamische URL

    axios.post(url, { message })
        .then(response => {
            noteInput.value = ""; // Leeg inputveld
            fetchNotes(); // Lijst vernieuwen
        })
        .catch(error => console.error("Fout bij opslaan notitie:", error));
}
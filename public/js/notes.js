document.addEventListener("DOMContentLoaded", fetchNotes);

function fetchNotes() {
    axios.get("/notes")
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

    axios.post("/notes", { message })
        .then(response => {
            noteInput.value = ""; // Leeg inputveld
            fetchNotes(); // Lijst vernieuwen
        })
        .catch(error => console.error("Fout bij opslaan notitie:", error));
}

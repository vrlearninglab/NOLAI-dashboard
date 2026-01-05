document.addEventListener("DOMContentLoaded", () => {
    fetchNotes();
    setInterval(fetchNotes, 2000); // Check every 2 seconds
});

function fetchNotes() {
    console.log("Notities ophalen...");
    let sessionId = window.sessionId || null; // Haal de sessie-ID op uit de globale variabele

    let url = sessionId ? `/notes/${sessionId}` : "/notes"; // Dynamische URL

    axios.get(url)
        .then(response => {
            let notesList = document.getElementById("notesList");
            notesList.innerHTML = ""; // Reset lijst

            // Voeg alle notities toe aan de lijst
            response.data.forEach(note => {
                let li = document.createElement("li");
                li.innerHTML = `<b> ${note.sender} </b> - ${note.created_at} <br> `;
                let messageSpan = document.createElement("span");
                messageSpan.textContent = note.message;
                li.appendChild(messageSpan);

                notesList.appendChild(li);
            });

            // Scroll automatisch naar beneden na het laden van notities
            notesList.scrollTop = notesList.scrollHeight;
        })
        .catch(error => console.error("Fout bij laden notities:", error));
}


function addNote() {
    let noteInput = document.getElementById("noteInput");
    let message = noteInput.value.trim();
    if (!message) return;

    let sessionId = window.sessionId || null; // Sessie-ID ophalen
    let url = sessionId ? `/notes/${sessionId}` : "/notes"; // Dynamische URL

    axios.post(url, { 
        message,
        sender: 'Gebruiker'
    })
        .then(() => {
            noteInput.value = ""; // Leeg inputveld
            fetchNotes(); // Lijst vernieuwen
        })
        .catch(error => console.error("Fout bij opslaan notitie:", error));
}
async function sendToUnity(message) {
    try {
        const response = await fetch('/send-to-unity', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ message: message })
        });
        const data = await response.json();
        alert(data.message);
    } catch (error) {
        console.error('Fout bij het versturen van data:', error);
    }
}

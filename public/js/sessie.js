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

var ActionButtonsContainer = document.getElementById('sessie-buttons-content');

function CreateActionButtons() {
    console.log("Creating Unity Action Buttons");
    fetch('/show-trigger-buttons')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log('Trigger Buttons Data:', data);
            ActionButtonsContainer.innerHTML = ''; //Clear container

            data.data.forEach(element => {
                if (element.text && element.group) { // Ensure the element has text and group properties
                    // Find or create a subcontainer for the group
                    let groupContainer = document.querySelector(`.group-container[data-group="${element.group}"]`);
                    if (!groupContainer) {
                        groupContainer = document.createElement('div');
                        groupContainer.classList.add('group-container');
                        groupContainer.setAttribute('data-group', element.group);

                        // Add a title for the group
                        let groupTitle = document.createElement('h3');
                        groupTitle.textContent = element.group;
                        groupContainer.appendChild(groupTitle);

                        // Append the group container to the main container
                        ActionButtonsContainer.appendChild(groupContainer);
                    }

                    // Create the button
                    let button = document.createElement('button');
                    button.innerHTML = element.text;

                    // Set the button color based on the element's color property
                    if (element.color) {
                        const { r, g, b } = element.color;
                        button.style.backgroundColor = `rgb(${r * 255}, ${g * 255}, ${b * 255})`;
                    }

                    let UnitySendObject = { data: [{ id: element.id, text: element.text }] };

                    button.onclick = () => {
                        sendToUnity(UnitySendObject);
                    }

                    // Append the button to the group container
                    groupContainer.appendChild(button);
                }
            });
        })
        .catch(error => {
            console.error('Error fetching trigger buttons:', error);
        });
}
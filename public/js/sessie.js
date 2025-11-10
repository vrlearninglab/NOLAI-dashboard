var ActionButtonsContainer = document.getElementById("sessie-buttons-content");
let timerInterval;
let pollingInterval;
let secondsElapsed = 0;

// Stel het CSRF-token globaal in voor axios
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
axios.defaults.headers.common["X-CSRF-TOKEN"] = csrfToken;

function checkMessage(message) {
    const sceneSwitchMessages = [];
    sceneSwitchMessages.push(
        "Scene (A0_onboarding)",
        "Scene (A1_Markt)",
        "Scene (A2_Schipgereedmaken)",
        "Scene (A3_Varen)"
    );
    let messageText = message.data[0].text;

    console.log(message);
    sendToUnity(message);

    if (messageText == "Scene (MENU)") {
        setTimeout(() => {
            CreateActionButtons();
        }, 5000);
    } else if (sceneSwitchMessages.includes(messageText)) {
        setTimeout(() => {
            startStream();
            CreateActionButtons();
        }, 5000);
    }
}

async function sendToUnity(message) {
    try {
        const response = await fetch("/send-to-unity", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({ message: message }),
        });
        const data = await response.json();
    } catch (error) {
        console.error("Fout bij het versturen van data:", error);
    }
}

function openPopup() {
    document.getElementById("popupOverlay").style.display = "flex";
}

function closePopup() {
    document.getElementById("popupOverlay").style.display = "none";
}

function confirmAndSendToUnity() {
    document.getElementById("popupOverlayConfirm").style.display = "none";
    document.getElementById("popupOverlaySave").style.display = "block";

    sendToUnity("Stop stream");
    stopTimer();
    startResponsePolling();
}

function startResponsePolling() {
    pollingInterval = setInterval(async () => {
        try {
            const response = await fetch("/get-unity-status ");
            const data = await response.json();
            console.log(data.message);

            if (data.message === "Data opgeslagen") {
                enableHomeButton();
            }
        } catch (error) {
            console.error("Fout bij ophalen status:", error);
        }
    }, 3000);
}

function stopPolling() {
    clearInterval(pollingInterval);
}

function enableHomeButton() {
    const homeButton = document.querySelector("#popupOverlaySave .confirm-btn");
    if (homeButton) {
        homeButton.disabled = false;

        homeButton.addEventListener("click", function () {
            window.location.href = "/home/{name}";
        });
    }

    stopPolling();
}

function startStream() {
    sendToUnity("Start stream");
    startTimer();
}

function startTimer() {
    clearInterval(timerInterval); // Reset de timer als deze al loopt
    secondsElapsed = 0;
    updateTimerDisplay();

    timerInterval = setInterval(() => {
        secondsElapsed++;
        updateTimerDisplay();
    }, 1000);
}

function stopTimer() {
    clearInterval(timerInterval); // Stop de timer

    let timerValue = document.getElementById("timer").textContent;
    let sessionId = window.sessionId || null; // Zorg ervoor dat sessionId beschikbaar is

    if (!sessionId) {
        console.error("Geen sessie-ID gevonden.");
        return;
    }

    axios
        .post("/save-timer", {
            full_time: timerValue,
            session_id: sessionId,
        })
        .then((response) => {
            console.log("Timer opgeslagen:", response.data);
        })
        .catch((error) => {
            console.error("Fout bij opslaan van timer:", error);
        });
}

function updateTimerDisplay() {
    const minutes = Math.floor(secondsElapsed / 60)
        .toString()
        .padStart(2, "0");
    const seconds = (secondsElapsed % 60).toString().padStart(2, "0");
    document.getElementById("timer").textContent = `${minutes}:${seconds}`;
}

function CreateActionButtons() {
    console.log("Creating Unity Action Buttons");
    fetch("/show-trigger-buttons")
        .then((response) => {
            if (!response.ok) {
                throw new Error("Network response was not ok");
            }
            return response.json();
        })
        .then((data) => {
            console.log("Trigger Buttons Data:", data);
            ActionButtonsContainer.innerHTML = ""; //Clear container

            data.data.forEach((element) => {
                if (element.text && element.group) {
                    // Ensure the element has text and group properties
                    // Find or create a subcontainer for the group
                    let groupContainer = document.querySelector(
                        `.group-container[data-group="${element.group}"]`
                    );
                    if (!groupContainer) {
                        groupContainer = document.createElement("section");
                        groupContainer.classList.add("group-container");
                        groupContainer.setAttribute(
                            "data-group",
                            element.group
                        );

                        // Add a title for the group
                        let groupTitle = document.createElement("h3");
                        groupTitle.textContent = element.group;
                        groupContainer.appendChild(groupTitle);

                        // Append the group container to the main container
                        ActionButtonsContainer.appendChild(groupContainer);
                    }

                    // Create the button
                    let button = document.createElement("button");
                    button.innerHTML = element.text;

                    // Set the button color based on the element's color property
                    if (element.color) {
                        const { r, g, b } = element.color;
                        button.style.backgroundColor = `rgb(${r * 255}, ${
                            g * 255
                        }, ${b * 255})`;
                    }

                    // send the message to play the scene of the button to unity
                    let UnitySendObject = {
                        data: [
                            {
                                id: element.id,
                                text: element.text,
                                question: "",
                            },
                        ],
                    };

                    //Check if a question was asked, and if so set it as an attribute
                    if (element.Question) {
                        button.setAttribute("AskedQuestion", element.Question);
                        UnitySendObject = {
                            data: [
                                {
                                    id: element.id,
                                    text: element.text,
                                    question: element.Question,
                                },
                            ],
                        };
                    }

                    button.onclick = () => {
                        checkMessage(UnitySendObject);
                    };

                    // Append the button to the group container
                    groupContainer.appendChild(button);
                }
            });
        })
        .catch((error) => {
            console.error("Error fetching trigger buttons:", error);
        });
}

async function checkStreamStatus() {
    try {
        const response = await fetch("/check-stream");
        const data = await response.json();

        if (data.streamURL) {
            updateStreamImage(data.streamURL);
        }
    } catch (error) {
        console.error("Fout bij het ophalen van de stream status:", error);
    }
}

function updateStreamImage(url) {
    const streamContainer = document.querySelector(".livestream div");
    streamContainer.innerHTML = `<img src="${url}" alt="">`;
}

setInterval(checkStreamStatus, 5000); //check elke 5 seconde

// Poll voor AI-evaluatie
function pollForAIEvaluation() {
    fetch("/pull-ai-evaluation") // GET-request naar de route
        .then((response) => response.json())
        .then((data) => {
            if (data && data.evaluation != null) {
                // Als er een evaluatie is, verwerk deze
                handleAIEvaluation(data.evaluation, data.question);
            }
        })
        .catch((error) => {
            console.error("Error polling for AI evaluation:", error);
        });
}

// Start polling (elke seconde)
setInterval(pollForAIEvaluation, 1000);

// Functie om de AI-evaluatie te verwerken
function handleAIEvaluation(evaluation, OriginalQuestion) {
    console.log(evaluation);
    console.log(OriginalQuestion);
    const buttons = document.querySelectorAll("button");
    const questionButton = Array.from(buttons).find(
        (btn) => btn.getAttribute("AskedQuestion") === OriginalQuestion
    );
    let goodButton;
    let badButton;

    if (questionButton) {
        const OriginalButtonText = questionButton.textContent.trim();
        const match = OriginalButtonText.match(/^(\d+)\)/);
        const number = match ? match[1] : null;

        if (number) {
            goodButton = Array.from(buttons).find((b) =>
                b.textContent.trim().startsWith(`${number}a)`)
            );
            badButton = Array.from(buttons).find((b) =>
                b.textContent.trim().startsWith(`${number}b)`)
            );
        }
    }

    // fallback (if not found by number) - keep previous explicit searches
    if (!goodButton) {
        console.error("Could not find good answer button for " + number);
    }
    if (!badButton) {
        console.error("Could not find bad answer button for " + number);
    }

    if (evaluation == 1) {
        
        selectAndTriggerButton(goodButton);
    } else {
      
        selectAndTriggerButton(badButton);
    }
}

function selectAndTriggerButton(button) {
    if (!button) {
        console.error("No button to select or trigger");
        return;
    }

    console.log("Selecting button:", button);

    button.style.outline = "3px solid yellow";
    button.style.backgroundColor = "rgba(255, 255, 0, 0.3)";

    setTimeout(() => {
        console.log("Triggering button:", button);
        try {
            // Prefer built-in click() which invokes onclick and event listeners
            if (typeof button.click === "function") {
                button.click();
            } else {
                button.dispatchEvent(new MouseEvent("click", { bubbles: true, cancelable: true, view: window }));
            }
        } catch (e) {
            console.error("Error triggering button click:", e);
        }
    }, 1000);
}

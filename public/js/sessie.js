var ActionButtonsContainer = document.getElementById("sessie-buttons-content");
let timerInterval;
let pollingInterval;
let secondsElapsed = 0;
let currentFase = 'Fase 1'; // Standaard startwaarde
let currentElementId = 1;   // Standaard startwaarde

// Stel het CSRF-token globaal in voor axios
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
axios.defaults.headers.common["X-CSRF-TOKEN"] = csrfToken;

// Haal de huidige Fase en sub-fase op vanuit de backend
async function fetchCurrentGroup() {
    try {
        const response = await fetch("/get-current-element");
        const data = await response.json();
        if (data.element_id && data.fase !== undefined) {
            const newFase = `Fase ${data.fase}`;
            const newElementId = data.element_id;
            if (newFase !== currentFase || newElementId !== currentElementId) {
                currentFase = newFase;
                currentElementId = newElementId;
                console.log("Current Fase updated:", currentFase, "Current element ID:", currentElementId);
                CreateActionButtons();
            }
        }
    } catch (error) {
        console.error("Error fetching current Fase:", error);
    }
}

// Poll elke 5 seconden voor updates van de huidige groep
setInterval(fetchCurrentGroup, 5000);

// Functie om knoppen te maken en te updaten
function CreateActionButtons() {
    console.log("Creating Unity Action Buttons for Fase:", currentFase, "Active element:", currentElementId);
    fetch("/show-trigger-buttons")
        .then((response) => {
            if (!response.ok) throw new Error("Network response was not ok");
            return response.json();
        })
        .then((responseData) => {
            console.log("Full response data:", responseData);
            ActionButtonsContainer.innerHTML = ""; //clear container

            // Controleer of responseData.data bestaat en een array is
            if (responseData.data && Array.isArray(responseData.data)) {
                responseData.data.forEach((element) => {
                    if (element.text && element.group !== undefined) {
                        const elementFase = element.group;
                        // Toon alleen knoppen voor de huidige fase
                        if (elementFase !== currentFase) return;

                        let groupContainer = document.querySelector(`.group-container[data-group="${elementFase}"]`);
                        if (!groupContainer) {
                            groupContainer = document.createElement("section");
                            groupContainer.classList.add("group-container");
                            groupContainer.setAttribute("data-group", elementFase);
                            let groupTitle = document.createElement("h3");
                            groupTitle.textContent = elementFase;
                            groupContainer.appendChild(groupTitle);
                            ActionButtonsContainer.appendChild(groupContainer);
                        }

                        let button = document.createElement("button");
                        button.innerHTML = element.text;
                        button.style.backgroundColor = `rgb(46, 52, 64)`;

                        // Markeer actieve knop
                        if (currentElementId && element.id == currentElementId) {
                            button.style.backgroundColor = `rgba(62, 73, 97, 1)`;
                            button.style.border = "2px solid white";
                            
                        }

                        let UnitySendObject = {
                            data: [{
                                id: element.id,
                                text: element.text,
                                question: element.Question || "",
                            }],
                        };

                        if (element.Question) {
                            button.setAttribute("AskedQuestion", element.Question);
                        }

                        button.onclick = () => {
                            checkMessage(UnitySendObject);
                            currentFase = elementFase;
                            currentElementId = element.id;
                            console.log("Updated currentFase to:", currentFase, "currentElementId to:", currentElementId);
                            CreateActionButtons();
                        };

                        groupContainer.appendChild(button);
                    }
                });
            } else {
                console.error("Data is not in the expected format. Expected {data: Array}, got:", responseData);
            }
        })
        .catch((error) => {
            console.error("Error fetching trigger buttons:", error);
        });
}

// Check of een bericht een scene switch is
function checkMessage(message) {
    const sceneSwitchMessages = [
        "Scene (A0_onboarding)",
        "Scene (A1_Markt)",
        "Scene (A2_Schipgereedmaken)",
        "Scene (A3_Varen)",
        "Scene (A4_Eiland)"
    ];
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

// Stuur data naar Unity
async function sendToUnity(message) {
    const buttons = document.querySelectorAll("button");
    console.log("message: ", message);
    if (!message.data || !message.data.length) {
        console.warn("No data in message");
        return;
    }
    const questionText = message.data[0].question;
    const questionButton = Array.from(buttons).find(
        (btn) => btn.getAttribute("AskedQuestion") === questionText
    );
    console.log("Question Button: ", questionButton);
    let AllAnswerOptions = [];
    if (questionButton) {
        const OriginalButtonText = questionButton.textContent.trim();
        const match = OriginalButtonText.match(/^(\d+)\)/);
        const number = match ? match[1] : null;
        if (number) {
            const regex = new RegExp(`^${number}[a-z]?\\)`);
            AllAnswerOptions = Array.from(buttons)
                .filter((b) => {
                    const text = b.textContent.trim();
                    return regex.test(text) && text !== OriginalButtonText;
                })
                .map((b) => b.innerHTML.trim());
            console.log("AllAnswerOptions:", AllAnswerOptions);
        }
    } else {
        console.warn("No button found for question:", questionText);
    }
    try {
        const response = await fetch("/send-to-unity", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                message: message,
                answerOptions: AllAnswerOptions,
            }),
        });
        const data = await response.json();
        console.log("Response from Unity:", data);
    } catch (error) {
        console.error("Fout bij het versturen van data:", error);
    }
}

// Open popup
function openPopup() {
    document.getElementById("popupOverlay").style.display = "flex";
}

// Sluit popup
function closePopup() {
    document.getElementById("popupOverlay").style.display = "none";
}

// Bevestig en stuur naar Unity
function confirmAndSendToUnity() {
    document.getElementById("popupOverlayConfirm").style.display = "none";
    document.getElementById("popupOverlaySave").style.display = "block";
    sendToUnity("Stop stream");
    stopTimer();
    startResponsePolling();
}

// Start polling voor Unity status
function startResponsePolling() {
    pollingInterval = setInterval(async () => {
        try {
            const response = await fetch("/get-unity-status");
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

// Stop polling
function stopPolling() {
    clearInterval(pollingInterval);
}

// Activeer home button
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

// Start stream
function startStream() {
    sendToUnity("Start stream");
    startTimer();
}

// Start timer
function startTimer() {
    clearInterval(timerInterval);
    secondsElapsed = 0;
    updateTimerDisplay();
    timerInterval = setInterval(() => {
        secondsElapsed++;
        updateTimerDisplay();
    }, 1000);
}

// Stop timer
function stopTimer() {
    clearInterval(timerInterval);
    let timerValue = document.getElementById("timer").textContent;
    let sessionId = window.sessionId || null;
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

// Update timer display
function updateTimerDisplay() {
    const minutes = Math.floor(secondsElapsed / 60)
        .toString()
        .padStart(2, "0");
    const seconds = (secondsElapsed % 60).toString().padStart(2, "0");
    document.getElementById("timer").textContent = `${minutes}:${seconds}`;
}

// Check stream status
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

// Update stream image
function updateStreamImage(url) {
    const streamContainer = document.querySelector(".livestream div");
    streamContainer.innerHTML = `<img src="${url}" alt="">`;
}

// Poll elke 5 seconden voor stream status
setInterval(checkStreamStatus, 5000);

// Poll voor AI-evaluatie
function pollForAIEvaluation() {
    fetch("/pull-ai-evaluation")
        .then((response) => response.json())
        .then((data) => {
            if (data && data.evaluation != null) {
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
    console.log("AI Evaluation:", evaluation);
    console.log("Original Question:", OriginalQuestion);
    const buttons = document.querySelectorAll("button");
    const questionButton = Array.from(buttons).find(
        (btn) => btn.getAttribute("AskedQuestion") === OriginalQuestion
    );
    const evaluatedButton = Array.from(buttons).find((btn) => {
        return btn.textContent.trim().toLowerCase() === evaluation.trim().toLowerCase();
    });
    if (evaluatedButton) {
        console.log("Matched button:", evaluatedButton);
        selectAndTriggerButton(evaluatedButton);
    } else {
        console.warn("No button found matching:", evaluation);
    }
}

// Selecteer en trigger een knop
function selectAndTriggerButton(button) {
    if (!button) {
        console.error("No button to select or trigger");
        return;
    }
    console.log("Selecting button:", button);
    button.style.outline = "3px solid white";
    setTimeout(() => {
        console.log("Triggering button:", button);
        try {
            if (typeof button.click === "function") {
                button.click();
            } else {
                button.dispatchEvent(new MouseEvent("click", { bubbles: true, cancelable: true, view: window }));
            }
        } catch (e) {
            console.error("Error triggering button click:", e);
        }
        button.style.outline = "none";
        button.style.backgroundColor = "rgba(129, 129, 129, 0.3)";
    }, 1000);
}

// Start de knoppen direct bij het laden van de pagina
CreateActionButtons();

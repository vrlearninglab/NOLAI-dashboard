# NOLAI Dashboard — Woordenschat in VR

The control and data-collection backend for [**Woordenschat in VR**](https://github.com/vrlearninglab/NOLAI-woordenschat-in-VR), a VR vocabulary-learning game for young children built as part of the NOLAI research project with Radboud University. Read about the project here: [Virtuele havenstad helpt kleuters hun woordenschat te vergroten](https://www.ru.nl/over-ons/nieuws/virtuele-havenstad-helpt-kleuters-hun-woordenschat-te-vergroten).

This dashboard sends live commands to the Unity game, receives a live session preview, and records session/response data for research analysis. It also hosts the AI stack that powers in-game character conversations.

## Stack

The dashboard is a set of services orchestrated with Docker Compose:

| Service | Purpose |
|---|---|
| **App** | A Laravel application — serves the dashboard UI and the API endpoint the Unity game connects to |
| **Ollama** | Serves a language model over a local API. The model in use has a custom system prompt tailored to the in-game characters |
| **Speaches** | Speech-to-text, built on top of OpenAI's Whisper — used for fast transcription of player speech |
| **Database** | MySQL — stores all session/user data |

All four are installed and started automatically via the included Docker Compose file.

## Requirements

- [Docker](https://www.docker.com/) (on Windows, Docker Desktop is the simplest route)
- The companion Unity project, [`NOLAI-woordenschat-in-VR`](https://github.com/vrlearninglab/NOLAI-woordenschat-in-VR), configured to point at this dashboard's IP

## Getting started

1. **Clone this repository.** The latest version lives on the `main` branch.
2. **Install Docker** ([Docker Desktop](https://www.docker.com/products/docker-desktop/) on Windows).
3. From a terminal in the project folder, build and start everything:
```bash
   docker compose up
```
4. Wait for the build to finish and watch for errors — most commonly around database credentials. If needed, add the following to the project's `.env` file (replace the password with your own), then restart the containers:

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=root
DB_PASSWORD=your_password_here

5. Visit **`localhost:8000`** in your browser to open the dashboard. If the Unity project is already running and pointed at this machine's IP, you'll see a live preview of the game session.

## Connecting the Unity game

In the Unity project, open the `Dashboard Connector` component in the scene view and set its IP address to the IP of the machine running this dashboard. If both run on the same machine, use `localhost`.

## Status

Research prototype, developed alongside the Unity client as part of the NOLAI project. Development and data collection have concluded; this repository is published as-is for reference and reproducibility.

## Partner

The project was funded by NOLAI and build in collaboration with Radboud University


## License

Apache License 2.0 — see [LICENSE](LICENSE) for details.

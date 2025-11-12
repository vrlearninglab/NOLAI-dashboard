#!/bin/sh
# Start Ollama in the background
ollama serve &

# Wait for the server to start
echo "Waiting for Ollama to start..."
sleep 8

# Create the model if it doesn't exist
if ! ollama show StudentLLM >/dev/null 2>&1; then
  echo "Creating StudentLLM model from /Modelfile..."
  ollama create StudentLLM -f /Modelfile
else
  echo "Model StudentLLM already exists, skipping creation."
fi

# Keep the container alive
wait

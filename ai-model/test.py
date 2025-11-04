import os
import torch
from transformers import AutoTokenizer, AutoModelForCausalLM

# Gebruik het absolute pad
model_path = os.path.abspath("C:/Users/virtu/OneDrive/Documenten/Github/Nolai/nolai-dashboard/ai-model/model")

try:
    print(f"Loading tokenizer from {model_path}...")
    tokenizer = AutoTokenizer.from_pretrained(
        model_path,
        local_files_only=True,
        trust_remote_code=True
    )
    print("Tokenizer loaded.")

    print(f"Loading model from {model_path}...")
    model = AutoModelForCausalLM.from_pretrained(
        model_path,
        local_files_only=True,
        trust_remote_code=True,
    )
    model = model.to("cuda")  # Verplaats het model handmatig naar de GPU
    print("Model loaded.")

    # Controleer of het model op de GPU staat
    print(f"Model device: {next(model.parameters()).device}")

    # Test het model
    input_text = "Write me a poem about Machine Learning."
    input_ids = tokenizer(input_text, return_tensors="pt").to("cuda")
    outputs = model.generate(**input_ids, max_new_tokens=32)
    print(tokenizer.decode(outputs[0]))

except Exception as e:
    print(f"Error: {e}")

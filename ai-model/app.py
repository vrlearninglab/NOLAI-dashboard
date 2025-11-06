from fastapi import FastAPI, Request, HTTPException
from pydantic import BaseModel
from transformers import AutoModelForCausalLM, AutoTokenizer
import torch

app = FastAPI()

# Laad model en tokenizer
model_name = "C:/Users/virtu/OneDrive/Documenten/Github/Nolai/nolai-dashboard/ai-model/model"
tokenizer = AutoTokenizer.from_pretrained(model_name, trust_remote_code=True)
model = AutoModelForCausalLM.from_pretrained(model_name, torch_dtype=torch.float16, trust_remote_code=True).to("cuda")

# Pydantic-model voor JSON-input
class EvaluationRequest(BaseModel):
    prompt: str

@app.post("/evaluate")
async def evaluate(request: Request, prompt: str = None, evaluation_request: EvaluationRequest = None):
    # Kies de prompt uit query-parameter of JSON-body
    if evaluation_request:
        prompt = evaluation_request.prompt
    elif not prompt:
        raise HTTPException(status_code=422, detail=[{"type": "missing", "loc": ["query", "prompt"], "msg": "Field required"}])

    # Verwerk de prompt
    inputs = tokenizer(prompt, return_tensors="pt").to("cuda")
    outputs = model.generate(**inputs, max_new_tokens=8)
    result = tokenizer.decode(outputs[0], skip_special_tokens=True)

    # Haal alleen het nieuwe antwoord (na de prompt)
    answer = result[len(prompt):].strip()
    return {"result": answer}

FROM python:3.11-slim

WORKDIR /app

# Install dependencies
COPY fastapi-service/requirements.txt ./
RUN pip install --no-cache-dir -r requirements.txt

# Copy application code
COPY . .

CMD ["uvicorn", "main:app", "--host", "0.0.0.0", "--port", "8000"]

FROM node:18-alpine

WORKDIR /app

# Install dependencies
COPY vue-frontend/package*.json ./
RUN npm install

# Copy application code
COPY . .

EXPOSE 5173
CMD ["npm", "run", "dev"]

import uvicorn
import redis
import os
from fastapi import FastAPI, HTTPException
from pydantic import BaseModel
from typing import List, Dict
import pandas as pd
from sklearn.neighbors import NearestNeighbors

app = FastAPI(
    title="Restaurant Recommendation API",
    description="API for personalized menu recommendations.",
    version="1.0.0",
)

# Mock data for demonstration
MOCK_USER_HISTORY = {
    101: [1, 2, 5], # customer 101 frequently orders menu items 1, 2, 5
    102: [3, 4, 6], # customer 102 frequently orders menu items 3, 4, 6
}

MOCK_MENU_ITEMS = {
    1: {"name": "Burger", "category": "Main", "price": 12.0},
    2: {"name": "Fries", "category": "Side", "price": 4.0},
    3: {"name": "Salad", "category": "Main", "price": 10.0},
    4: {"name": "Coke", "category": "Drink", "price": 2.5},
    5: {"name": "Pizza", "category": "Main", "price": 15.0},
    6: {"name": "Juice", "category": "Drink", "price": 3.0},
}

# In-memory mock recommendation model
def get_recommendations_model():
    """Build a simple KNN model based on mock data."""
    all_items = list(MOCK_MENU_ITEMS.keys())
    user_item_matrix = pd.DataFrame(0, index=list(MOCK_USER_HISTORY.keys()), columns=all_items)
    for user_id, items in MOCK_USER_HISTORY.items():
        user_item_matrix.loc[user_id, items] = 1
    
    model = NearestNeighbors(metric='cosine', algorithm='brute')
    model.fit(user_item_matrix)
    return model, user_item_matrix

RECOMMENDATION_MODEL, USER_ITEM_MATRIX = get_recommendations_model()

# Redis connection
redis_client = redis.Redis(host=os.getenv('REDIS_HOST', 'localhost'), port=6379, db=0)

class RecommendationItem(BaseModel):
    id: int
    name: str
    score: float = 1.0

class RecommendationResponse(BaseModel):
    items: List[RecommendationItem]
    strategy: str

@app.get("/api/v1/recommendations/{customer_id}", response_model=RecommendationResponse)
def get_recommendations(customer_id: int):
    """
    Get personalized menu recommendations for a customer.
    """
    if customer_id not in MOCK_USER_HISTORY:
        raise HTTPException(status_code=404, detail="Customer not found")

    # This is a mock implementation.
    # In a real-world scenario, this would involve a sophisticated ML model.
    user_history_vector = USER_ITEM_MATRIX.loc[[customer_id]].values
    distances, indices = RECOMMENDATION_MODEL.kneighbors(user_history_vector, n_neighbors=len(MOCK_MENU_ITEMS))

    recommended_items = []
    for item_index in indices[0]:
        item_id = USER_ITEM_MATRIX.columns[item_index]
        if item_id in MOCK_MENU_ITEMS:
            recommended_items.append(RecommendationItem(
                id=item_id,
                name=MOCK_MENU_ITEMS[item_id]['name'],
                score=1 - distances[0][indices[0].tolist().index(item_index)] # Convert distance to a score
            ))

    return RecommendationResponse(items=recommended_items, strategy="Collaborative Filtering (KNN)")

@app.get("/api/v1/health")
def health_check():
    """
    Health check endpoint.
    """
    return {"status": "ok", "message": "FastAPI is running"}

if __name__ == "__main__":
    uvicorn.run(app, host="0.0.0.0", port=8000)

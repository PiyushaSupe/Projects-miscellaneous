import os
import pickle
import pandas as pd
import numpy as np
from sklearn.ensemble import RandomForestRegressor
from sklearn.model_selection import train_test_split

# Simulated dataset with Indian states
states = [
    "Andhra Pradesh", "Arunachal Pradesh", "Assam", "Bihar", "Chhattisgarh",
    "Goa", "Gujarat", "Haryana", "Himachal Pradesh", "Jharkhand", "Karnataka",
    "Kerala", "Madhya Pradesh", "Maharashtra", "Manipur", "Meghalaya",
    "Mizoram", "Nagaland", "Odisha", "Punjab", "Rajasthan", "Sikkim",
    "Tamil Nadu", "Telangana", "Tripura", "Uttar Pradesh", "Uttarakhand",
    "West Bengal", "Delhi", "Jammu and Kashmir"
]

# Generate sample data
np.random.seed(42)
n_samples = 500
X = pd.DataFrame({
    "area_sqft": np.random.uniform(500, 4000, size=n_samples),
    "bedrooms": np.random.randint(1, 6, size=n_samples),
    "bathrooms": np.random.randint(1, 4, size=n_samples),
    "floors": np.random.randint(1, 4, size=n_samples),
    "year_built": np.random.randint(1980, 2022, size=n_samples),
    "state": np.random.choice(states, size=n_samples)
})

# Simulate price as a function of area, rooms, etc.
y = (
    X["area_sqft"] * 50 +
    X["bedrooms"] * 200000 +
    X["bathrooms"] * 150000 +
    X["floors"] * 50000 +
    (2022 - X["year_built"]) * -1000 +
    np.random.normal(0, 100000, size=n_samples)
)

# One-hot encode the 'state' column
X = pd.get_dummies(X, columns=["state"])

# Train/test split
X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)

# Train model
model = RandomForestRegressor(n_estimators=100, random_state=42)
model.fit(X_train, y_train)

# Save model and column names
os.makedirs("indian_states_app", exist_ok=True)
pickle.dump(model, open("indian_states_app/model.pkl", "wb"))
pickle.dump(X.columns.tolist(), open("indian_states_app/columns.pkl", "wb"))
pickle.dump(states, open("indian_states_app/states.pkl", "wb"))

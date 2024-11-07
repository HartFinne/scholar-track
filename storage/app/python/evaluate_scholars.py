import pandas as pd
from sqlalchemy import create_engine
from sklearn.linear_model import LogisticRegression
from sklearn.model_selection import train_test_split
from sklearn.metrics import accuracy_score, classification_report, confusion_matrix
import json
import os

# Database connection details
DB_CONNECTION = 'mysql'
DB_HOST = '127.0.0.1'
DB_PORT = '3306'
DB_DATABASE = 'laravel'
DB_USERNAME = 'forge'
DB_PASSWORD = 'Xf2DrGKSxX02BJ1S5yxI'

# Create a connection string
connection_string = f'{DB_CONNECTION}+pymysql://{DB_USERNAME}:{DB_PASSWORD}@{DB_HOST}:{DB_PORT}/{DB_DATABASE}'

# Create a database connection
engine = create_engine(connection_string)

# Load data from the 'datasets' table in the database
data = pd.read_sql_table('datasets', engine)
criteria = pd.read_sql_table('criteria', engine)

# Ensure 'startcontract' and 'endcontract' columns are in date format
data['startcontract'] = pd.to_datetime(data['startcontract'])
data['endcontract'] = pd.to_datetime(data['endcontract'])

# Calculate academic year as "YYYY-YYYY" format
data['acadyear'] = data['startcontract'].dt.year.astype(str) + "-" + data['endcontract'].dt.year.astype(str)

# Define criteria thresholds
MIN_GWA = criteria['cgwa'].iloc[0]
REQUIRED_CS_HOURS = criteria['cshours'].iloc[0]

# Add columns to evaluate each criterion
data['meets_gwasem1'] = data['gwasem1'] <= MIN_GWA
data['meets_gwasem2'] = data['gwasem2'] <= MIN_GWA
data['meets_cshours'] = (data['cshours'] > 0) & (data['cshours'] >= REQUIRED_CS_HOURS)
data['meets_lte'] = data['ltecount'] == 0
data['meets_penalty'] = data['penaltycount'] == 0

# Assign weights to each criterion
weights = {
    'meets_penalty': 40,
    'meets_gwasem1': 15,
    'meets_gwasem2': 15,
    'meets_lte': 20,
    'meets_cshours': 10
}

# Calculate yearly evaluation score based on weighted criteria
data['evalscore'] = (
    (data['meets_penalty'] * weights['meets_penalty']) +
    (data['meets_gwasem1'] * weights['meets_gwasem1']) +
    (data['meets_gwasem2'] * weights['meets_gwasem2']) +
    (data['meets_lte'] * weights['meets_lte']) +
    (data['meets_cshours'] * weights['meets_cshours'])
)

# Scale the score to a total of 100
data['evalscore'] = data['evalscore'] / sum(weights.values()) * 100

# Aggregate by caseCode to calculate average evaluation over multiple years
scholar_evaluations = data.groupby(['caseCode', 'acadyear'], as_index=False)['evalscore'].mean()

# Define the hiring threshold and create the target column `isPassed`
scholar_evaluations['isPassed'] = (scholar_evaluations['evalscore'] >= 75).astype(int)

# Prepare features and target variable
X = scholar_evaluations[['evalscore']]  # Input features
y = scholar_evaluations['isPassed']     # Target variable

# Split data into training and testing sets
X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)

# Initialize and train the logistic regression model
model = LogisticRegression()
model.fit(X_train, y_train)

# Predict on test set
y_pred = model.predict(X_test)

# Calculate and print performance metrics
accuracy = accuracy_score(y_test, y_pred)
classification_rep = classification_report(y_test, y_pred)
confusion_mat = confusion_matrix(y_test, y_pred)

print(f"Accuracy: {accuracy * 100:.2f}%")
print("Classification Report:")
print(classification_rep)
print("Confusion Matrix:")
print(confusion_mat)

# Store the list of scholars with their evaluation score and remark (strong candidate for hiring or not)
scholar_evaluations[['caseCode', 'acadyear', 'evalscore', 'isPassed']].to_sql('evalresults', con=engine, if_exists='replace', index=False)

# Export performance metrics to a JSON file for client review
metrics = {
    "accuracy": accuracy,
    "classification_report": classification_rep,
    "confusion_matrix": confusion_mat.tolist()
}

# Absolute path for the JSON file
base_directory = '/home/forge/scholartrackph.online/'
file_path = os.path.join(base_directory, 'storage/app/python/performance_metrics.json')

# Ensure the directory exists
os.makedirs(os.path.dirname(file_path), exist_ok=True)

metrics = {}  # Ensure you have this initialized appropriately

try:
    with open(file_path, 'w') as f:
        json.dump(metrics, f)
    print("Performance metrics saved successfully.")
except Exception as e:
    print(f"Error saving performance metrics: {e}")

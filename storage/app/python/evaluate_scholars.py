import pandas as pd
from sqlalchemy import create_engine
from sklearn.linear_model import LogisticRegression
from sklearn.model_selection import train_test_split
from sklearn.metrics import accuracy_score, classification_report, confusion_matrix
import json

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

# Ensure 'startcontract' and 'endcontract' columns are in date format
data['startcontract'] = pd.to_datetime(data['startcontract'])
data['endcontract'] = pd.to_datetime(data['endcontract'])

# Calculate academic year as "YYYY-YYYY" format
data['acadyear'] = data['startcontract'].dt.year.astype(str) + "-" + data['endcontract'].dt.year.astype(str)

# Define criteria thresholds
MIN_GWA = 1.5
REQUIRED_CS_HOURS = 6

# Add columns to evaluate each criterion
data['meets_gwasem1'] = data['gwasem1'] <= MIN_GWA
data['meets_gwasem2'] = data['gwasem2'] <= MIN_GWA
data['meets_cshours'] = (data['cshours'] > 0) & (data['cshours'] >= REQUIRED_CS_HOURS)
data['meets_lte'] = data['ltecount'] == 0
data['meets_penalty'] = data['penaltycount'] == 0

# Assign weights to each criterion
weights = {
    'meets_penalty': 40,  # Penalty count takes 40% of the evaluation
    'meets_gwasem1': 15,
    'meets_gwasem2': 15,  # GWA takes 30% of the evaluation
    'meets_lte': 20,      # LTE count takes 20% of the evaluation
    'meets_cshours': 10   # CS hours take 10% of the evaluation
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

# Write the results to the database
scholar_evaluations[['caseCode', 'acadyear', 'evalscore', 'isPassed']].to_sql('evalresults', con=engine, if_exists='replace', index=False) 

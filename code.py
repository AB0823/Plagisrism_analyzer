from __future__ import print_function

import argparse
import os
import pandas as pd
import joblib
from sklearn.linear_model import LogisticRegression
from sklearn.model_selection import train_test_split
from sklearn.metrics import accuracy_score

# Provided model load function
def model_fn(model_dir):
    """Load model from the model_dir."""
    print("Loading model.")
    model = joblib.load(os.path.join(model_dir, "model.joblib"))
    print("Done loading model.")
    return model


if __name__ == '__main__':
    # Argument parser
    parser = argparse.ArgumentParser()
    
    # SageMaker parameters
    parser.add_argument('--output-data-dir', type=str, default=os.environ['SM_OUTPUT_DATA_DIR'])
    parser.add_argument('--model-dir', type=str, default=os.environ['SM_MODEL_DIR'])
    parser.add_argument('--data-dir', type=str, default=os.environ['SM_CHANNEL_TRAIN'])

    # Hyperparameters for the model
    parser.add_argument('--max_iter', type=int, default=100, help="Maximum iterations for Logistic Regression")
    parser.add_argument('--solver', type=str, default='lbfgs', help="Solver to use in Logistic Regression")

    # Parse arguments
    args = parser.parse_args()

    # Read training data
    training_dir = args.data_dir
    train_data = pd.read_csv(os.path.join(training_dir, "train.csv"), header=None)
    
    # Labels and features
    train_y = train_data.iloc[:, 0]
    train_x = train_data.iloc[:, 1:]

    # Split into train and validation sets
    train_x, val_x, train_y, val_y = train_test_split(train_x, train_y, test_size=0.2, random_state=42)
    
    # Define and train the model
    model = LogisticRegression(max_iter=args.max_iter, solver=args.solver)
    model.fit(train_x, train_y)

    # Evaluate the model
    val_predictions = model.predict(val_x)
    val_accuracy = accuracy_score(val_y, val_predictions)
    print(f"Validation Accuracy: {val_accuracy}")

    # Save the model
    joblib.dump(model, os.path.join(args.model_dir, "model.joblib"))

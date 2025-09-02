from flask import Flask

app = Flask(__name__)

@app.route("/")
def home():
    return "App is working!"

if __name__ == "__main__":
    import os
    port = int(os.environ.get("PORT", 5000))
    app.run(debug=True, host='0.0.0.0', port=port)


from flask import Flask, render_template, request
import pickle
import numpy as np
import pandas as pd
import matplotlib.pyplot as plt
import base64
from io import BytesIO

app = Flask(__name__)
model = pickle.load(open("model.pkl", "rb"))
columns = pickle.load(open("columns.pkl", "rb"))
states = pickle.load(open("states.pkl", "rb"))

@app.route("/", methods=["GET", "POST"])
def home():
    prediction = None
    plot_url = None

    if request.method == "POST":
        input_data = []

        for col in columns:
            if col.startswith("state_"):
                input_data.append(1 if col == f"state_{request.form['state']}" else 0)
            else:
                val = float(request.form.get(col, 0))
                input_data.append(val)

        input_df = pd.DataFrame([input_data], columns=columns)
        prediction = round(model.predict(input_df)[0], 2)

        # Plot
        plt.figure(figsize=(5, 3))
        plt.bar(["Predicted Price"], [prediction], color='mediumseagreen')
        plt.ylabel("Price (INR)")
        plt.title("House Price Estimate")
        buf = BytesIO()
        plt.tight_layout()
        plt.savefig(buf, format="png")
        plt.close()
        plot_url = base64.b64encode(buf.getvalue()).decode("utf-8")

    return render_template("index.html", input_columns=columns, states=states, prediction=prediction, plot_url=plot_url)

if __name__ == "__main__":
    app.run(debug=True, host='0.0.0.0', port=8080)

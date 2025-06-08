import pandas as pd
import numpy as np
from sklearn.feature_extraction.text import CountVectorizer
from sklearn.ensemble import RandomForestClassifier
import pymysql as mdb
from datetime import datetime
import smtplib
from email.mime.multipart import MIMEMultipart 
from email.mime.text import MIMEText 
from email.mime.base import MIMEBase 
from email import encoders
import os

# 1. Load and clean training data
train = pd.read_csv('GFG1.csv')
train = train.dropna()
train = train.head(500)

x = train['item']
y = train['day']

# 2. Feature extraction
countV = CountVectorizer()
train_count = countV.fit_transform(x)

# 3. Train model
clf = RandomForestClassifier(n_estimators=100)
clf.fit(train_count, y)

# 4. Function to send email with product info and inline image
def send_mail(to_email, product_name, category, image_path):
    fromaddr = "sanchitsrivasta64@gmail.com"  # Your email
    password = "grffnzjuutnkhskg"              # Your Gmail app password

    msg = MIMEMultipart('related')  # 'related' for embedding images
    msg['From'] = fromaddr
    msg['To'] = to_email
    msg['Subject'] = "Food Nearing Expiry Alert"

    # HTML content with embedded image reference cid:productimage
    html = f"""
    <html>
      <body>
        <h2 style="color: #2E86C1;">Expiry Alert for Product</h2>
        <p>The product <strong>{product_name}</strong> (Category: <em>{category}</em>) is nearing expiry. Please take necessary action.</p>
        """
    
    # Check if image exists and embed or show placeholder text
    if image_path and os.path.isfile(image_path):
        html += f'<img src="cid:productimage" alt="{product_name}" style="width:300px; border:1px solid #ddd; padding:5px;"/>'
    else:
        html += "<p><i>[Image not available]</i></p>"
    
    html += """
      </body>
    </html>
    """

    # Attach HTML body
    msg.attach(MIMEText(html, 'html'))

    # Attach image if exists
    if image_path and os.path.isfile(image_path):
        with open(image_path, 'rb') as img_file:
            mime_img = MIMEBase('image', 'png')  # Adjust type if needed (png/jpg)
            mime_img.set_payload(img_file.read())
            encoders.encode_base64(mime_img)
            mime_img.add_header('Content-ID', '<productimage>')
            mime_img.add_header('Content-Disposition', 'inline', filename=os.path.basename(image_path))
            msg.attach(mime_img)

    # Send email
    try:
        s = smtplib.SMTP('smtp.gmail.com', 587)
        s.starttls()
        s.login(fromaddr, password)
        s.sendmail(fromaddr, to_email, msg.as_string())
        s.quit()
        print(f"Mail sent to {to_email}")
    except Exception as e:
        print(f"Mail sending failed: {e}")

# 5. Check expiry and send mail
date_today = str(datetime.now()).split(" ")[0]
date_format = "%Y-%m-%d"

try:
    mydb = mdb.connect(
        host="127.0.0.1",
        user="root",
        passwd="",
        database="impulse101"
    )

    mycursor = mydb.cursor()
    # Fetch product_image in query
    mycursor.execute("SELECT product_id, product_title, product_expiry, product_cat, product_image FROM products")
    myresult = mycursor.fetchall()
    mydb.close()

    for item in myresult:
        product_id = str(item[0])
        product_name = str(item[1])
        expiry_date = str(item[2])
        category = str(item[3])
        product_image = str(item[4])  # image filename/path from DB

        # Predict shelf life
        org = countV.transform([product_name])
        predicted_day = int(clf.predict(org)[0])

        try:
            exp_date = datetime.strptime(expiry_date, date_format)
            today = datetime.strptime(date_today, date_format)
            days_diff = (today - exp_date).days

            print(f"Checking: {product_name} | Days diff: {days_diff} | Predicted: {predicted_day}")

            if days_diff > predicted_day:
                print("Product expired or near expiry. Sending email...")

                # Fetch NGO emails
                db2 = mdb.connect(
                    host="127.0.0.1",
                    user="root",
                    passwd="",
                    database="tms"
                )
                cursor2 = db2.cursor()
                cursor2.execute("SELECT comm FROM churn")
                emails = cursor2.fetchall()
                db2.close()

                for email in emails:
                    # Construct image path based on your folder structure, e.g., images/
                    image_path = f"images/{product_image}" if product_image else None
                    send_mail(str(email[0]), product_name, category, image_path)

        except Exception as e:
            print(f"Date parsing failed for {product_name}: {e}")

except Exception as e:
    print(f"Database error: {e}")

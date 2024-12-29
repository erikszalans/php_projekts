import requests
from bs4 import BeautifulSoup
import mysql.connector

# Database connection
db = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="kazu_projekts"
)
cursor = db.cursor()

# Target URL
url = "https://www.eminova.lv/lv/kazu-kleitas/kazu-kleitas-modern-look"
headers = {"User-Agent": "Mozilla/5.0"}

# Make a GET request
response = requests.get(url, headers=headers)
soup = BeautifulSoup(response.text, "html.parser")

# Extract product data
products = soup.find_all("div", class_="product-box")

for product in products:
    # Name
    name_tag = product.find("div", class_="Title")
    name = name_tag.a.text.strip() if name_tag and name_tag.a else "No name"

    # Description
    desc_tag = product.find("div", class_="desc1")
    description = desc_tag.text.strip() if desc_tag else "No description"

    # Price
    price_tag = product.find("span", class_="PricesalesPrice")
    price = price_tag.text.strip() if price_tag else "No price"

    # Image URL
    image_tag = product.find("div", class_="front").find("img", class_="browseProductImage")
    image_url = image_tag["data-original"] if image_tag and "data-original" in image_tag.attrs else "No image URL"

    # Print extracted data for debugging
    print(f"Name: {name}, Description: {description}, Price: {price}, Image URL: {image_url}")

    # Save to database
    cursor.execute(
        "INSERT INTO products (name, price, image_url, description) VALUES (%s, %s, %s, %s)",
        (name, price, image_url, description)
    )
    db.commit()

print("Products scraped and saved to database.")
db.close()

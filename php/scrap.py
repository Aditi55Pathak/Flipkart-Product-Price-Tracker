import requests
from bs4 import BeautifulSoup
import json

# URL for scraping
url = "https://www.flipkart.com/audio-video/headset/pr?sid=0pm%2Cfcn&p%5B%5D=facets.headphone_type%255B%255D%3DTrue%2BWireless&sort=popularity&p%5B%5D=facets.connectivity%255B%255D%3DBluetooth&p%5B%5D=facets.rating%255B%255D%3D3%25E2%2598%2585%2B%2526%2Babove&p%5B%5D=facets.rating%255B%5D%3D4%25E2%2598%2585%2B%2526%2Babove&p%5B%5D=facets.price_range.from%3DMin&p%5B%5D=facets.price_range.to%3D2000&param=088&page=1"

# Updated headers
headers = {
    "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/95.0.4638.69 Safari/537.36",
    "Accept": "text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8",
    "Accept-Language": "en-US,en;q=0.5",
    "Connection": "keep-alive"
}

# Make the request with headers
r = requests.get(url, headers=headers)

# Check the response status code
if r.status_code == 200:
    # Parse the page content
    soup = BeautifulSoup(r.text, "lxml")

    # Find the first product details container
    product = soup.find("a", class_="wjcEIp")

    if product:
        # Extract product details
        product_name = product.get("title").split(" with ")[0]
        description = soup.find("div", class_="NqpwHC")
        price = soup.find("div", class_="Nx9bqj")
        rating = soup.find("div", class_="XQDdHH")
        reviews = soup.find("span", class_="Wphh3N")

        # Create a dictionary to hold the product details
        product_details = {
            "title": product_name,
            "description": description.text if description else "",
            "price": price.text if price else "",
            "rating": rating.text if rating else "",
            "reviews": reviews.text if reviews else ""
        }

        # Print the product details as JSON
        print(json.dumps(product_details))
else:
    print(json.dumps({"error": f"Failed to retrieve the page. Status code: {r.status_code}"}))
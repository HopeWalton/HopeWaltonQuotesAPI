# HopeWaltonQuotesAPI# Quotes API

## Project Overview
This is a RESTful API built with PHP and PostgreSQL that allows users to manage a collection of quotes, authors, and categories.

## Features
✅ Retrieve all quotes, a specific quote, or quotes by author/category.  
✅ Add, update, and delete quotes.  
✅ Foreign key constraints to maintain database integrity.  

## 🌐 Live API on Render
[Quotes API - Live Deployment](https://hopewaltonquotesapi.onrender.com)

## Technologies Used
- **Backend:** PHP (with Apache)
- **Database:** PostgreSQL
- **Hosting:** Render.com
- **Version Control:** Git & GitHub

## API Endpoints

### Quotes Endpoints
| Method | Endpoint | Description |
|--------|---------|------------|
| GET | `/api/quotes/` | Get all quotes |
| GET | `/api/quotes/?id=10` | Get a specific quote |
| GET | `/api/quotes/?author_id=3` | Get all quotes from a specific author |
| POST | `/api/quotes/` | Create a new quote |
| PUT | `/api/quotes/` | Update an existing quote |
| DELETE | `/api/quotes/` | Delete a quote |

### Authors Endpoints
| Method | Endpoint | Description |
|--------|---------|------------|
| GET | `/api/authors/` | Get all authors |
| GET | `/api/authors/?id=5` | Get a specific author |
| POST | `/api/authors/` | Create a new author |
| PUT | `/api/authors/` | Update an author |
| DELETE | `/api/authors/` | Delete an author |

### Categories Endpoints
| Method | Endpoint | Description |
|--------|---------|------------|
| GET | `/api/categories/` | Get all categories |
| GET | `/api/categories/?id=4` | Get a specific category |
| POST | `/api/categories/` | Create a new category |
| PUT | `/api/categories/` | Update a category |
| DELETE | `/api/categories/` | Delete a category |

## Database Schema
- **Authors Table**
  - `id` (Primary Key, Auto-increment)
  - `author` (VARCHAR, Not Null)

- **Categories Table**
  - `id` (Primary Key, Auto-increment)
  - `category` (VARCHAR, Not Null)

- **Quotes Table**
  - `id` (Primary Key, Auto-increment)
  - `quote` (TEXT, Not Null)
  - `author_id` (Foreign Key → authors.id)
  - `category_id` (Foreign Key → categories.id)

## Setup Instructions
1. Clone the repository:
   ```bash
   git clone https://github.com/yourusername/QuotesAPI.git
   cd QuotesAPI

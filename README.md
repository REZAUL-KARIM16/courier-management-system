# Courier Management System

This is a database lab project. It is a simple web app for managing a courier service — customers, parcels, branches, delivery agents, deliveries, and payments.

## About

The project uses MySQL for the database and PHP for the website. There are 6 tables connected with Primary Key and Foreign Key relationships. The project also uses JOIN and GROUP BY queries to show combined data (for example, showing a customer's name instead of just their ID number on the parcel page).

## Main Tables

- Customers
- Branches
- Delivery_Agents
- Parcels
- Deliveries
- Payments

## Table Relationships

- One Branch can have many Delivery Agents
- One Branch can have many Parcels
- One Customer can have many Parcels
- One Parcel can have many Deliveries
- One Delivery Agent can handle many Deliveries
- One Parcel can have many Payments

## Features

- Add, edit, delete customers
- Book and track parcels
- Assign delivery agents to branches
- Update delivery status (Pending, In Transit, Delivered, Failed)
- Record payments and payment status
- Manage branches

## Tools Used

- Database: MySQL
- Database tool: phpMyAdmin / MySQL Workbench
- Frontend: HTML, CSS
- Backend: PHP
- Server: XAMPP

## How to Run

1. Download/clone this project.
2. Put the `courier_easy` folder inside your XAMPP `htdocs` folder.
3. Start Apache and MySQL from XAMPP control panel.
4. Open `http://localhost/phpmyadmin`, go to Import, and select `database.sql`. Click Go.
5. Open `http://localhost/courier_easy/` in your browser.

## Folder Structure

```
courier_easy/
  database.sql
  index.php
  customers.php
  branches.php
  delivery_agents.php
  parcels.php
  deliveries.php
  payments.php
  includes/
    db_connect.php
    nav.php
    style.css
```

## Author

Razaul Karim
GitHub: https://github.com/REZAUL-KARIM16

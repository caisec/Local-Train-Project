# 🚆 Local Train Booking System

## 📌 Project Overview

**Local Train Booking System** is a university web application developed using **PHP with MVC architecture**.
The project simulates a local train ticket booking system where users can register, log in, view train schedules, and book tickets. An admin panel is also provided to manage users, schedules, balances, and bookings.

This project was developed for **academic purposes** to demonstrate proper system design, backend architecture, routing, authentication, and frontend integration.

---

## 🎯 Project Objectives

* Apply **MVC Architecture** in a real PHP project
* Implement a **Front Controller** routing mechanism
* Practice **authentication and authorization**
* Use **PDO** to connect PHP with **SQL Server**
* Separate backend logic from frontend presentation
* Simulate a real-world booking system

---

## 🧱 System Architecture

The project follows the **Model–View–Controller (MVC)** pattern:

* **Model:** Handles database logic and SQL queries
* **View:** Responsible for the user interface (HTML, CSS, Bootstrap, JavaScript)
* **Controller:** Handles requests, business logic, and responses

All requests are handled through a **Front Controller** (`public/index.php`) using:

```
index.php?action=...
```

---

## 🛠 Technologies Used

* **Backend:** PHP (MVC)
* **Frontend:** HTML, CSS, Bootstrap, JavaScript
* **Database:** SQL Server (PDO)
* **Real-Time Simulation:** WebSocket (Ratchet)
* **QR Code Generation:** Endroid QR Code Library

---

## 👤 User Features

* User registration and login
* View train schedules
* Book train tickets
* Automatic balance check and deduction
* Ticket receipt generation with QR code

---

## 🛠 Admin Features

* Admin authentication
* View all users and bookings
* Recharge user balances
* Add train schedules
* Delete booked tickets
* Monitor system activity via dashboard

---

## 🔄 Application Workflow

1. User registers and logs in
2. User views available train schedules
3. User books a ticket
4. System checks balance and deducts the fare
5. Ticket receipt is generated with a QR code
6. Admin manages users, schedules, and tickets

---

## 📂 Folder Structure

```
config/        → Database configuration  
controllers/  → Application controllers  
models/       → Database models (PDO)  
views/        → UI and HTML templates  
public/       → Public entry point (index.php, assets)  
websocket/    → WebSocket server for real-time simulation  
```

---

## ⚠️ Known Limitations

* No online payment gateway (balance is manually recharged by admin)
* No ticket refund or cancellation system
* WebSocket real-time updates are simulated and not fully connected to the UI
* Database transactions and rollback handling are not implemented

---

## 🚀 Future Improvements

* Online payment integration
* Full real-time train tracking
* Ticket cancellation and refund functionality
* Improved error handling and logging
* Enhanced UI and mobile responsiveness

---

## 🎓 Academic Note

This project was developed as part of a **university course** to demonstrate practical knowledge of:

* MVC architecture
* PHP backend development
* Database interaction using PDO
* Authentication and role-based access control
* Backend and frontend integration

---

## 👨‍🎓 Author

Developed by a university student as an academic project.

Just tell me and I’ll prepare it for you 👌

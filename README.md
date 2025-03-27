# E-Mjeku

E-Mjeku is a web-based platform built using **HTML, CSS, PHP, and MySQL**. This system allows users to search for and find doctors based on different criteria, such as name, city, or specialization. Users can access detailed information about registered doctors, including location, working hours, and contact details.

One of the key features of this platform is the **integrated chat system**, which enables users to communicate directly with doctors. Patients can send messages to registered doctors in real time, facilitating quick consultations, questions, or information sharing.

The platform is designed to be **simple and intuitive**, making it accessible for both regular users and those looking for a quick and efficient way to find and contact specialists.

---

## **Prerequisites**
Before you begin, ensure that you have the following installed on your system:

- **Web Server**: Apache (You can use XAMPP, WAMP, or MAMP for easy setup).
- **PHP**: PHP 7.x or later.
- **Database**: MySQL or MariaDB.
- **Code Editor**: Visual Studio Code (VS Code) or any other preferred editor.
- **Browser**: Any modern browser (e.g., Chrome, Firefox, Edge).

---

## Features
- **Doctor Search**: Find doctors based on specialization, city, or name.
- **Doctor Profiles**: View detailed profiles, including social media links.
- **Real-time Chat**: Communicate securely with doctors.
- **Secure Login System**: Ensures privacy and data security.
- **User-friendly Dashboard**: Easy navigation for patients and doctors.

## Screenshots
![E-Mjeku](https://github.com/RinesaJ/E-Mjeku/blob/c4b93b9eb73a2af0bf17ff799605c0c017b8fcd4/198.png)  
![Doctor Menu](https://github.com/RinesaJ/E-Mjeku/blob/c4b93b9eb73a2af0bf17ff799605c0c017b8fcd4/Screenshot%202025-03-27%20at%209.11.08%20PM.png)  
![Home Page](https://github.com/RinesaJ/E-Mjeku/blob/c4b93b9eb73a2af0bf17ff799605c0c017b8fcd4/ballina.png)  
![Admin Page](https://github.com/RinesaJ/E-Mjeku/blob/c4b93b9eb73a2af0bf17ff799605c0c017b8fcd4/admin1.png)  
![Chat - Doctor with User](https://github.com/RinesaJ/E-Mjeku/blob/c4b93b9eb73a2af0bf17ff799605c0c017b8fcd4/chati_doktori.png)  
![Chat - User with Doctor](https://github.com/RinesaJ/E-Mjeku/blob/c4b93b9eb73a2af0bf17ff799605c0c017b8fcd4/chati-perdorues.png)  
![Log In](https://github.com/RinesaJ/E-Mjeku/blob/c4b93b9eb73a2af0bf17ff799605c0c017b8fcd4/kycu.png)  
![List of users who have contacted the doctor via chat](https://github.com/RinesaJ/E-Mjeku/blob/c4b93b9eb73a2af0bf17ff799605c0c017b8fcd4/lista%20.png)  
![Admin Contact Page: Displays all messages sent by users, and the admin can reply directly via email](https://github.com/RinesaJ/E-Mjeku/blob/c4b93b9eb73a2af0bf17ff799605c0c017b8fcd4/mesazhet.png)  
![Doctor Registration](https://github.com/RinesaJ/E-Mjeku/blob/c4b93b9eb73a2af0bf17ff799605c0c017b8fcd4/regjistrohu2.png)  


## Installation
1. Clone the repository:
   ```bash
   git clone https://github.com/RinesaJ/E-Mjeku.git
2. Set Up the Database
Open your MySQL server (via phpMyAdmin or MySQL CLI).

Create a new database for the project:

sql
CREATE DATABASE smd;
Import the provided SQL file to populate the database:

Locate the SQL file (e.g., database.sql or smd.sql) in the project.

Import it using phpMyAdmin or MySQL CLI.

3. Configure the Project
Place the project files in your web server's directory (e.g., htdocs for XAMPP or www for WAMP).

Example: If using XAMPP, move the project folder to:

makefile
C:\xampp\htdocs\e-mjeku
If you modified the database name, username, or password, update the configuration file (config.php or konfigurimi.php) with the correct details.

4. Start Apache & MySQL
Open XAMPP, WAMP, or MAMP.

Start the Apache and MySQL services.

5. Access the Project in the Browser
Open your browser and enter the following URL:

Main Project URL:

arduino
http://localhost/e-mjeku/
Home Page:

bash
http://localhost/E-Mjeku/Faqjauser/ballina.php
6. Open the Project in VS Code
Open VS Code.

Go to File > Open Folder and select the project folder.

Now, you can edit and work on the project.

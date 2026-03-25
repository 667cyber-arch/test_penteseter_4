# 🛡️ Full-Stack Web App Penetration Testing Lab (PayGearPlan)

## 📖 Overview
This project is a custom-built, full-stack e-commerce environment designed to simulate real-world web vulnerabilities. The objective of this lab was to execute a complete offensive attack chain (Reconnaissance to Post-Exploitation) and follow up with defensive Blue Team remediation and secure coding patches. 

The target application, **PayGearPlan**, was developed from scratch using PHP and MySQL, and deployed on dedicated, hardened hardware to replicate a production small-business environment.

### 🔍 Quick Stats
* **Attacker Machine:** Kali Linux (Dell Latitude 5530)
* **Target Server:** Windows 10 (HP ZBook) hosting Apache & MySQL 8.0
* **Vulnerabilities Exploited:** SQL Injection (UNION & Error-based), Stored & Reflected XSS, Session Hijacking, Information Disclosure (CWE-522).

---

## 🚀 The Penetration Test Report
The full documentation details a 12-stage attack chain, starting from initial Nmap network mapping, escalating to administrative authentication bypass via SQLi, and culminating in a weaponized XSS payload to seamlessly exfiltrate session cookies over the network for total account takeover.

### [👉 Click Here to Read the Full 12-Stage Penetration Testing Report](Full-Stack_Web_App_Penetration_testing.md)

---

## 🛠️ Technologies & Tools Used
* **Languages:** PHP 8, SQL, HTML/CSS, JavaScript, Markdown
* **Database:** MySQL Community Server 8.0
* **Offensive Tools:** Nmap, Gobuster, cURL, Python (HTTP Listener), Burp Suite

---

## ⚠️ Disclaimer
*This lab environment was created strictly for educational purposes within an isolated, private subnet. Do not replicate these configurations or execute these attacks against live or production environments without explicit, written authorization.*# test_penteseter_4

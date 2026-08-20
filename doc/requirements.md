# Requirements – Pickleball Coaching System

## 1. Functional Requirements

FR01. The system shall allow users to register and log in with three roles: Student (PLAYER), Coach (COACH), and Admin (ADMIN).  
FR02. Students shall be able to search and filter coaches by relevant criteria (area, level, rating, etc.).  
FR03. Students shall be able to view coach profiles (information, experience, ratings, schedule).  
FR04. Students shall be able to book training sessions with a coach.  
FR05. Students shall be able to rate and review a coach after a completed session.  
FR06. Students shall be able to view their booking/learning history.  
FR07. Coaches shall be able to create and update their professional profiles.  
FR08. Coaches shall be able to manage teaching schedules (add, edit, cancel time slots).  
FR09. Coaches shall be able to view the list of students who booked their sessions.  
FR10. Admins shall be able to approve or reject coach profiles.  
FR11. Admins shall be able to manage user accounts (activate/lock).  
FR12. Admins shall be able to view basic statistics (number of coaches, students, bookings).  
FR13. The system shall support search/filter functions.  
FR14. The system shall enforce clear role-based access control.

## 2. Non-functional Requirements

NFR01. The system shall be a web application accessible via modern browsers.  
NFR02. The interface shall be clear, usable, and basically responsive.  
NFR03. Passwords shall be hashed; sessions shall protect authenticated access.  
NFR04. User inputs shall be validated.  
NFR05. The system shall run on a local environment and be ready for online deployment.  
NFR06. Common operations shall respond within an acceptable time.

## 3. Business Rules

BR01. Only admin-approved coaches are visible to students.  
BR02. A time slot cannot be double-booked.  
BR03. Only students who completed a session may submit a review.  
BR04. Each account has one role at a time.

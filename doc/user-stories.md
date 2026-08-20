# User Stories – Pickleball Coaching System

## Student (PLAYER)

US01. As a student, I want to register/log in so that I can use the system.  
US02. As a student, I want to search for coaches so that I can find a suitable trainer.  
US03. As a student, I want to view a coach profile so that I can check experience and ratings.  
US04. As a student, I want to book a training session so that I can schedule practice.  
US05. As a student, I want to rate a coach after a session so that I can give feedback.  
US06. As a student, I want to view my booking history so that I can track my training.

## Coach (COACH)

US07. As a coach, I want to create/update my profile so that students can find me.  
US08. As a coach, I want to manage my teaching schedule so that I can organize my time.  
US09. As a coach, I want to view booked students so that I can prepare for sessions.

## Admin (ADMIN)

US10. As an admin, I want to approve coach profiles so that only trusted coaches are listed.  
US11. As an admin, I want to manage user accounts so that I can control system access.  
US12. As an admin, I want to view basic statistics so that I can monitor platform activity.

## Acceptance Criteria (key stories)

### US04 – Book a training session
- Student can select a coach and an available time slot  
- System saves the booking successfully  
- Coach can see the new booking  
- System prevents booking an already taken slot  

### US07 – Create coach profile
- Coach can enter basic information (experience, rate, bio, etc.)  
- Profile starts as pending approval  
- After admin approval, the profile becomes visible to students  

### US10 – Approve coaches
- Admin can view pending coach profiles  
- Admin can approve or reject  
- Status updates correctly in the system  

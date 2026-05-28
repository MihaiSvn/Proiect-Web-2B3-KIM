
INSERT INTO USERS (first_name, last_name, email, password_hash, role, phone, birth_date) VALUES 
('Super', 'Admin', 'admin@kim.com', 'hash_12345', 'admin', '0700000000', '1985-10-15'),
('Alex', 'Smith', 'alex@kim.com', 'hash_12345', 'trainer', '0711111111', '1992-05-20'),
('Maria', 'Johnson', 'maria@kim.com', 'hash_12345', 'trainer', '0722222222', '1994-08-10'),
('John', 'Client', 'john@kim.com', 'hash_12345', 'member', '0733333333', '1998-02-25'),
('Anna', 'Davis', 'anna@kim.com', 'hash_12345', 'member', '0744444444', '1995-11-30'),
('William', 'Test', 'william@kim.com', 'hash_12345', 'member', '0755555555', '1990-04-12');

INSERT INTO TRAINERS (user_id, specialization, bio, work_schedule) VALUES 
(2, 'fitness', 'Personal trainer with 5 years of experience in hypertrophy.', 'Mon-Fri: 08:00 - 16:00'),
(3, 'physiotherapy', 'Physiotherapist specialized in post-operative recovery.', 'Tue-Sat: 10:00 - 18:00');

INSERT INTO SUBSCRIPTIONS (name, type, price, validity_days, description, max_suspending_days) VALUES 
('Standard Fitness Plan', 'fitness', 150.00, 30, 'Unlimited access to the weight room.', 5),
('Medical Recovery 10 Sessions', 'physiotherapy', 300.00, 45, 'Includes initial evaluation and 10 sessions.', 0),
('Premium Mixed Package', 'mixed', 400.00, 30, 'Fitness access + 5 physiotherapy sessions.', 7);

INSERT INTO USER_SUBSCRIPTIONS (user_id, subscription_id, start_date, end_date, status, suspending_days_left) VALUES 
(4, 1, '2026-05-01 00:00:00', '2026-05-30 23:59:59', 'active', 5),  -- John has active Fitness
(5, 2, '2026-05-15 00:00:00', '2026-06-29 23:59:59', 'active', 0),  -- Anna has active Physio
(6, 3, '2026-01-10 00:00:00', '2026-02-09 23:59:59', 'expired', 0); -- William has expired sub

INSERT INTO ROOMS (name, capacity, type, is_active) VALUES 
('Open Space Weight Room', 30, 'fitness', TRUE),
('Physiotherapy Room A', 2, 'physiotherapy', TRUE),
('Functional Training Studio', 15, 'mixed', TRUE);

INSERT INTO EQUIPMENT (room_id, name, is_functional) VALUES 
(1, 'Incline Chest Press Bench', TRUE),
(1, 'Dumbbell Set 5-30kg', TRUE),
(1, 'Pull-up Machine', FALSE),
(2, 'Professional Massage Table', TRUE),
(3, 'TRX Suspension Bands', TRUE);

INSERT INTO SESSIONS (trainer_id, room_id, title, type, start_time, end_time, max_capacity, status) VALUES 
(1, 1, 'Leg Day Training', 'fitness', '2026-06-05 18:00:00', '2026-06-05 19:30:00', 10, 'planned'),
(2, 2, 'Postural Evaluation', 'physiotherapy', '2026-06-10 10:00:00', '2026-06-10 11:00:00', 1, 'planned'),
(1, 3, 'HIIT Circuit (Cardio)', 'mixed', '2026-05-20 19:00:00', '2026-05-20 20:00:00', 15, 'completed');

INSERT INTO BOOKINGS (user_id, session_id, status) VALUES 
(4, 1, 'confirmed'), -- John goes to Leg Day
(5, 2, 'confirmed'), -- Anna goes to Evaluation
(4, 3, 'canceled'),  -- John canceled the past circuit
(6, 3, 'confirmed'); -- William attended the circuit
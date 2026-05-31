
INSERT INTO USERS (first_name, last_name, email, password_hash, role) VALUES 
-- TOATE PAROLELE NEHASHUITE SUNT hash_12345
('Super', 'Admin', 'admin@kim.com', '$2y$10$tnKszUW3PPeOHsTQ2ZzOcuQOBJenizfG2XM9amdcUprf2c/BYF7GK', 'admin'),
('Alex', 'Smith', 'alex@kim.com', '$2y$10$tnKszUW3PPeOHsTQ2ZzOcuQOBJenizfG2XM9amdcUprf2c/BYF7GK', 'trainer'),
('Maria', 'Johnson', 'maria@kim.com', '$2y$10$tnKszUW3PPeOHsTQ2ZzOcuQOBJenizfG2XM9amdcUprf2c/BYF7GK', 'trainer'),
('John', 'Client', 'john@kim.com', '$2y$10$tnKszUW3PPeOHsTQ2ZzOcuQOBJenizfG2XM9amdcUprf2c/BYF7GK', 'member'),
('Anna', 'Davis', 'anna@kim.com', '$2y$10$tnKszUW3PPeOHsTQ2ZzOcuQOBJenizfG2XM9amdcUprf2c/BYF7GK', 'member'),
('William', 'Test', 'william@kim.com', '$2y$10$tnKszUW3PPeOHsTQ2ZzOcuQOBJenizfG2XM9amdcUprf2c/BYF7GK', 'member');

INSERT INTO TRAINERS (user_id, specialization) VALUES 
(2, 'fitness'),
(3, 'physiotherapy');

INSERT INTO SUBSCRIPTIONS (name, type, price, validity_days, sessions, description, max_suspending_days) VALUES
('Standard Fitness Plan', 'fitness', 150.00, 30, 999, 'Unlimited access to the weight room.', 5),
('Medical Recovery 10 Sessions', 'physiotherapy', 300.00, 45, 10, 'Includes initial evaluation and 10 sessions.', 0),
('Premium Full Package', 'all', 400.00, 30, 15, 'Fitness access + 15 strength/physio sessions.', 7);

INSERT INTO USER_SUBSCRIPTIONS (user_id, subscription_id, start_date, end_date, status, suspending_days_left, sessions_left) VALUES
(4, 1, '2026-05-01 00:00:00', '2026-05-30 23:59:59', 'active', 5, 999),  -- John has active Fitness
(5, 2, '2026-05-15 00:00:00', '2026-06-29 23:59:59', 'active', 0, 9),  -- Anna has active Physio
(6, 3, '2026-01-10 00:00:00', '2026-02-09 23:59:59', 'expired', 0, 0); -- William has expired sub


INSERT INTO ROOMS (name, capacity, type, is_active) VALUES 
('Open Space Weight Room', 30, 'fitness', TRUE),
('Physiotherapy Room A', 2, 'physiotherapy', TRUE),
('Functional Training Studio', 15, 'strength', TRUE);

INSERT INTO EQUIPMENT (room_id, name, is_functional) VALUES 
(1, 'Incline Chest Press Bench', TRUE),
(1, 'Dumbbell Set 5-30kg', TRUE),
(1, 'Pull-up Machine', FALSE),
(2, 'Professional Massage Table', TRUE),
(3, 'TRX Suspension Bands', TRUE);

INSERT INTO SESSIONS (trainer_id, room_id, title, type, start_time, end_time, max_capacity, status) VALUES 
(1, 1, 'Leg Day Training', 'fitness', '2026-06-05 18:00:00', '2026-06-05 19:30:00', 10, 'planned'),
(2, 2, 'Postural Evaluation', 'physiotherapy', '2026-06-10 10:00:00', '2026-06-10 11:00:00', 1, 'planned'),
(1, 3, 'HIIT Circuit (Cardio)', 'strength', '2026-05-20 19:00:00', '2026-05-20 20:00:00', 15, 'completed'),
(1, 3, 'HIIT Circuit 2 (Cardio)', 'strength', '2026-06-11 19:00:00', '2026-06-11 20:00:00', 15, 'planned');

INSERT INTO BOOKINGS (user_id, session_id) VALUES
(4, 1), -- John goes to all
(4, 2),
(4,3),
(4,4),
(5, 2), -- Anna goes to Evaluation
(6, 3); -- William attended the circuit



INSERT INTO NOTIFICATIONS (user_id, title, message) VALUES
(
    4,
    'Session Confirmed',
    'Strength Training • Strength Zone • Tomorrow 18:00'
);
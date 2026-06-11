
INSERT INTO USERS (first_name, last_name, email, password_hash, role)
VALUES
('Super', 'Admin', 'admin@kim.com', '$2y$10$tnKszUW3PPeOHsTQ2ZzOcuQOBJenizfG2XM9amdcUprf2c/BYF7GK', 'admin'),
('Alex', 'Smith', 'alex@kim.com', '$2y$10$tnKszUW3PPeOHsTQ2ZzOcuQOBJenizfG2XM9amdcUprf2c/BYF7GK', 'trainer'),
('Maria', 'Johnson', 'maria@kim.com', '$2y$10$tnKszUW3PPeOHsTQ2ZzOcuQOBJenizfG2XM9amdcUprf2c/BYF7GK', 'trainer'),
('David', 'Miller', 'david@kim.com', '$2y$10$tnKszUW3PPeOHsTQ2ZzOcuQOBJenizfG2XM9amdcUprf2c/BYF7GK', 'trainer'),
('Emma', 'Wilson', 'emma@kim.com', '$2y$10$tnKszUW3PPeOHsTQ2ZzOcuQOBJenizfG2XM9amdcUprf2c/BYF7GK', 'trainer'),
('Oliver', 'Brown', 'oliver@kim.com', '$2y$10$tnKszUW3PPeOHsTQ2ZzOcuQOBJenizfG2XM9amdcUprf2c/BYF7GK', 'trainer'),
-- Membri (Clienți)
('John', 'Client', 'john@kim.com', '$2y$10$tnKszUW3PPeOHsTQ2ZzOcuQOBJenizfG2XM9amdcUprf2c/BYF7GK', 'member'),
('Anna', 'Davis', 'anna@kim.com', '$2y$10$tnKszUW3PPeOHsTQ2ZzOcuQOBJenizfG2XM9amdcUprf2c/BYF7GK', 'member'),
('William', 'Test', 'william@kim.com', '$2y$10$tnKszUW3PPeOHsTQ2ZzOcuQOBJenizfG2XM9amdcUprf2c/BYF7GK', 'member'),
('Sophia', 'Taylor', 'sophia@kim.com', '$2y$10$tnKszUW3PPeOHsTQ2ZzOcuQOBJenizfG2XM9amdcUprf2c/BYF7GK', 'member'),
('James', 'Anderson', 'james@kim.com', '$2y$10$tnKszUW3PPeOHsTQ2ZzOcuQOBJenizfG2XM9amdcUprf2c/BYF7GK', 'member'),
('Isabella', 'Thomas', 'isabella@kim.com', '$2y$10$tnKszUW3PPeOHsTQ2ZzOcuQOBJenizfG2XM9amdcUprf2c/BYF7GK', 'member'),
('Lucas', 'Jackson', 'lucas@kim.com', '$2y$10$tnKszUW3PPeOHsTQ2ZzOcuQOBJenizfG2XM9amdcUprf2c/BYF7GK', 'member'),
('Mia', 'White', 'mia@kim.com', '$2y$10$tnKszUW3PPeOHsTQ2ZzOcuQOBJenizfG2XM9amdcUprf2c/BYF7GK', 'member'),
('Benjamin', 'Harris', 'benjamin@kim.com', '$2y$10$tnKszUW3PPeOHsTQ2ZzOcuQOBJenizfG2XM9amdcUprf2c/BYF7GK', 'member'),
('Charlotte', 'Martin', 'charlotte@kim.com', '$2y$10$tnKszUW3PPeOHsTQ2ZzOcuQOBJenizfG2XM9amdcUprf2c/BYF7GK', 'member');


INSERT INTO TRAINERS (user_id, specialization)
VALUES (2, 'fitness'),
       (3, 'physiotherapy'),
       (4, 'strength'),
       (5, 'fitness'),
       (6, 'physiotherapy');


INSERT INTO SUBSCRIPTIONS (name, type, price, validity_days, sessions, description, max_suspending_days)
VALUES ('Fitness Starter', 'fitness', 89, 60, 4, 'Fitness starter package', 2),
       ('Fitness Standard', 'fitness', 79, 60, 8, 'Fitness standard package', 4),
       ('Fitness Premium', 'fitness', 71, 60, 12, 'Fitness premium package', 7),
       ('Strength Starter', 'strength', 99, 60, 4, 'Strength starter package', 2),
       ('Strength Standard', 'strength', 89, 60, 8, 'Strength standard package', 4),
       ('Strength Premium', 'strength', 79, 60, 12, 'Strength premium package', 7),
       ('Recovery Basic', 'physiotherapy', 110, 60, 4, 'Recovery basic package', 0),
       ('Recovery Plus', 'physiotherapy', 100, 60, 8, 'Recovery plus package', 0),
       ('Recovery Complete', 'physiotherapy', 90, 60, 12, 'Recovery complete package', 0),
       ('Full Access Starter', 'all', 129, 60, 4, 'Full access starter package', 3),
       ('Full Access Plus', 'all', 115, 60, 8, 'Full access plus package', 5),
       ('Full Access Premium', 'all', 105, 60, 12, 'Full access premium package', 7);

INSERT INTO SUBSCRIPTION_FEATURES (subscription_id, feature_text)
VALUES (1, '4 booked sessions'), (1, 'Facility access'),
       (2, '8 booked sessions'), (2, 'Nutrition guide'),
       (3, '12 booked sessions'), (3, 'Nutrition guide'), (3, 'Recovery plan'),
       (4, '4 strength sessions'), (4, 'Gym access'),
       (5, '8 strength sessions'), (5, 'Technique coaching'),
       (6, '12 strength sessions'), (6, 'Advanced coaching'), (6, 'Recovery support'),
       (7, '4 physio sessions'), (7, 'Treatment plan'),
       (8, '8 physio sessions'), (8, 'Manual therapy'),
       (9, '12 physio sessions'), (9, 'Manual therapy'), (9, 'Priority support'),
       (10, '4 mixed sessions'), (10, 'Facility access'),
       (11, '8 mixed sessions'), (11, 'Nutrition guidance'),
       (12, '12 mixed sessions'), (12, 'Nutrition guidance'), (12, 'Dedicated specialist');


INSERT INTO USER_SUBSCRIPTIONS (user_id, subscription_id, start_date, end_date, status, suspending_days_left, sessions_left)
VALUES
(4, 12, '2026-05-15 00:00:00', '2026-07-14 23:59:59', 'active', 7, 10), -- John (Full Premium)
(5, 8, '2026-05-20 00:00:00', '2026-07-19 23:59:59', 'active', 0, 6),   -- Anna (Recovery Plus)
(7, 3, '2026-06-01 00:00:00', '2026-07-31 23:59:59', 'active', 7, 12),  -- Sophia (Fitness Premium)
(8, 6, '2026-05-10 00:00:00', '2026-07-09 23:59:59', 'active', 7, 4),   -- James (Strength Premium)
(10, 11, '2026-06-05 00:00:00', '2026-08-04 23:59:59', 'active', 5, 8), -- Lucas (Full Plus)

(6, 2, '2026-01-10 00:00:00', '2026-03-10 23:59:59', 'expired', 0, 0),  -- William (Fitness Standard)
(9, 5, '2026-02-15 00:00:00', '2026-04-16 23:59:59', 'expired', 4, 2),  -- Isabella (Strength Standard)
(4, 1, '2025-12-01 00:00:00', '2026-01-30 23:59:59', 'expired', 0, 0),  -- John abonament vechi

(11, 2, '2026-05-01 00:00:00', '2026-07-05 23:59:59', 'suspended', 0, 5), -- Mia
(12, 9, '2026-05-25 00:00:00', '2026-07-28 23:59:59', 'suspended', 0, 10);-- Benjamin


INSERT INTO ROOMS (name, capacity, type, is_active)
VALUES ('Main Fitness Floor', 40, 'fitness', TRUE),
       ('Physio Clinic A', 2, 'physiotherapy', TRUE),
       ('Physio Clinic B', 2, 'physiotherapy', TRUE),
       ('Heavy Weights Zone', 20, 'strength', TRUE),
       ('Cardio Studio', 25, 'fitness', TRUE),
       ('Crossfit Arena', 15, 'strength', TRUE);

INSERT INTO EQUIPMENT (room_id, name, is_functional)
VALUES (1, 'Treadmill Matrix', TRUE), (1, 'Treadmill Matrix', FALSE), (1, 'Leg Press Machine', TRUE),
       (2, 'Therapy Bed Pro', TRUE), (2, 'Ultrasound Machine', TRUE),
       (3, 'Therapy Bed Basic', TRUE), (3, 'TENS Unit', TRUE),
       (4, 'Squat Rack Alpha', TRUE), (4, 'Squat Rack Beta', TRUE), (4, 'Dumbbell Rack 5-50kg', TRUE),
       (5, 'Concept2 Rower', TRUE), (5, 'Assault Bike', TRUE),
       (6, 'Kettlebell Set', TRUE), (6, 'Plyo Boxes', TRUE);


INSERT INTO SESSIONS (trainer_id, room_id, title, type, start_time, end_time, max_capacity, status) VALUES
(1, 1, 'Morning Burn', 'fitness', '2026-05-10 08:00:00', '2026-05-10 09:00:00', 15, 'completed'),
(3, 4, 'Heavy Lifts 101', 'strength', '2026-05-12 18:00:00', '2026-05-12 19:30:00', 10, 'completed'),
(2, 2, 'Spine Recovery', 'physiotherapy', '2026-05-15 10:00:00', '2026-05-15 11:00:00', 1, 'completed'),
(4, 5, 'Cardio Blast', 'fitness', '2026-05-20 19:00:00', '2026-05-20 20:00:00', 20, 'canceled'),
(5, 3, 'Post-Op Knee', 'physiotherapy', '2026-05-25 14:00:00', '2026-05-25 15:00:00', 1, 'completed'),

(1, 1, 'Leg Day Focus', 'fitness', '2026-06-08 18:00:00', '2026-06-08 19:30:00', 15, 'completed'),
(3, 6, 'Crossfit WOD', 'strength', '2026-06-10 19:00:00', '2026-06-10 20:00:00', 12, 'completed'),
(2, 2, 'Posture Check', 'physiotherapy', '2026-06-11 10:00:00', '2026-06-11 11:00:00', 1, 'planned'), -- AZI
(4, 5, 'Zumba Party', 'fitness', '2026-06-11 19:00:00', '2026-06-11 20:00:00', 25, 'planned'),        -- AZI
(3, 4, 'Deadlift Workshop', 'strength', '2026-06-12 18:00:00', '2026-06-12 19:30:00', 10, 'planned'),

(1, 1, 'Summer Shred', 'fitness', '2026-07-01 09:00:00', '2026-07-01 10:00:00', 20, 'planned'),
(2, 2, 'Mobility Flow', 'physiotherapy', '2026-07-05 11:00:00', '2026-07-05 12:00:00', 1, 'planned'),
(3, 6, 'Olympic Weightlifting', 'strength', '2026-07-10 17:00:00', '2026-07-10 18:30:00', 8, 'planned'),
(5, 3, 'Shoulder Rehab', 'physiotherapy', '2026-07-15 15:00:00', '2026-07-15 16:00:00', 1, 'planned');


INSERT INTO BOOKINGS (user_id, session_id, user_subscription_id) VALUES
(4, 1, 1), (7, 1, 3), (10, 1, 5), -- Morning Burn (Fit)
(8, 2, 4), (10, 2, 5),            -- Heavy Lifts (Str)
(5, 3, 2),                        -- Spine Recovery (Phys)

(4, 6, 1), (7, 6, 3),             -- Leg Day (Fit)
(8, 7, 4),                        -- Crossfit WOD (Str)
(5, 8, 2),                        -- Posture Check (Phys - AZI)
(4, 9, 1), (7, 9, 3), (10, 9, 5), -- Zumba (Fit - AZI)
(8, 10, 4),                       -- Deadlift Workshop (Str - Mâine)

(4, 11, 1), (7, 11, 3),           -- Summer Shred (Fit)
(5, 12, 2),                       -- Mobility Flow (Phys)
(8, 13, 4);                       -- Olympic Lifting (Str)

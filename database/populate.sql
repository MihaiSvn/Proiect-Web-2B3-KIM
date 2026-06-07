INSERT INTO USERS (first_name, last_name, email, password_hash, role)
VALUES
-- TOATE PAROLELE NEHASHUITE SUNT hash_12345
('Super', 'Admin', 'admin@kim.com', '$2y$10$tnKszUW3PPeOHsTQ2ZzOcuQOBJenizfG2XM9amdcUprf2c/BYF7GK', 'admin'),
('Alex', 'Smith', 'alex@kim.com', '$2y$10$tnKszUW3PPeOHsTQ2ZzOcuQOBJenizfG2XM9amdcUprf2c/BYF7GK', 'trainer'),
('Maria', 'Johnson', 'maria@kim.com', '$2y$10$tnKszUW3PPeOHsTQ2ZzOcuQOBJenizfG2XM9amdcUprf2c/BYF7GK', 'trainer'),
('John', 'Client', 'john@kim.com', '$2y$10$tnKszUW3PPeOHsTQ2ZzOcuQOBJenizfG2XM9amdcUprf2c/BYF7GK', 'member'),
('Anna', 'Davis', 'anna@kim.com', '$2y$10$tnKszUW3PPeOHsTQ2ZzOcuQOBJenizfG2XM9amdcUprf2c/BYF7GK', 'member'),
('William', 'Test', 'william@kim.com', '$2y$10$tnKszUW3PPeOHsTQ2ZzOcuQOBJenizfG2XM9amdcUprf2c/BYF7GK', 'member');

INSERT INTO TRAINERS (user_id, specialization)
VALUES (2, 'fitness'),
       (3, 'physiotherapy');


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
VALUES (1, '4 booked sessions'),
       (1, 'Progress check-in'),
       (1, 'Facility access'),
       (1, 'Specialist assignment'),

       (2, '8 booked sessions'),
       (2, 'Detailed progress report'),
       (2, 'Priority scheduling'),
       (2, 'Specialist assignment'),
       (2, 'Nutrition guide'),

       (3, '12 booked sessions'),
       (3, 'Monthly progress review'),
       (3, 'Flexible rescheduling'),
       (3, 'Specialist assignment'),
       (3, 'Nutrition guide'),
       (3, 'Recovery plan'),

       (4, '4 strength sessions'),
       (4, 'Movement assessment'),
       (4, 'Gym access'),
       (4, 'Specialist assignment'),

       (5, '8 strength sessions'),
       (5, 'Progress tracking'),
       (5, 'Priority booking'),
       (5, 'Technique coaching'),
       (5, 'Nutrition guidance'),

       (6, '12 strength sessions'),
       (6, 'Monthly review'),
       (6, 'Flexible scheduling'),
       (6, 'Advanced coaching'),
       (6, 'Nutrition guidance'),
       (6, 'Recovery support'),

       (7, '4 physio sessions'),
       (7, 'Initial assessment'),
       (7, 'Recovery exercises'),
       (7, 'Treatment plan'),

       (8, '8 physio sessions'),
       (8, 'Progress monitoring'),
       (8, 'Manual therapy'),
       (8, 'Recovery plan'),
       (8, 'Priority scheduling'),

       (9, '12 physio sessions'),
       (9, 'Advanced treatment'),
       (9, 'Manual therapy'),
       (9, 'Recovery programme'),
       (9, 'Flexible booking'),
       (9, 'Priority support'),

       (10, '4 mixed sessions'),
       (10, 'Facility access'),
       (10, 'Specialist assignment'),
       (10, 'Progress tracking'),

       (11, '8 mixed sessions'),
       (11, 'Nutrition guidance'),
       (11, 'Recovery support'),
       (11, 'Priority booking'),
       (11, 'Progress reviews'),

       (12, '12 mixed sessions'),
       (12, 'Nutrition guidance'),
       (12, 'Recovery programme'),
       (12, 'Manual therapy'),
       (12, 'Flexible scheduling'),
       (12, 'Dedicated specialist');

INSERT INTO USER_SUBSCRIPTIONS (user_id, subscription_id, start_date, end_date, status, suspending_days_left,
                                sessions_left)
VALUES (4, 12, '2026-05-15 00:00:00', '2026-07-14 23:59:59', 'active', 7, 10), -- John are Full Access Premium activ
       (5, 8, '2026-05-15 00:00:00', '2026-07-14 23:59:59', 'active', 0, 6),   --  Anna are Recovery Plus activ
       (6, 2, '2026-01-10 00:00:00', '2026-03-10 23:59:59', 'expired', 0, 0); --  William are Fitness Standard expirat

INSERT INTO ROOMS (name, capacity, type, is_active)
VALUES ('Open Space Weight Room', 30, 'fitness', TRUE),
       ('Physiotherapy Room A', 2, 'physiotherapy', TRUE),
       ('Functional Training Studio', 15, 'strength', TRUE);

INSERT INTO EQUIPMENT (room_id, name, is_functional)
VALUES (1, 'Incline Chest Press Bench', TRUE),
       (1, 'Dumbbell Set 5-30kg', TRUE),
       (1, 'Pull-up Machine', FALSE),
       (2, 'Professional Massage Table', TRUE),
       (3, 'TRX Suspension Bands', TRUE);

INSERT INTO SESSIONS (trainer_id, room_id, title, type, start_time, end_time, max_capacity, status)
VALUES (1, 1, 'Leg Day Training', 'fitness', '2026-06-05 18:00:00', '2026-06-05 19:30:00', 10, 'planned'),
       (2, 2, 'Postural Evaluation', 'physiotherapy', '2026-06-10 10:00:00', '2026-06-10 11:00:00', 1, 'planned'),
       (1, 3, 'HIIT Circuit (Cardio)', 'strength', '2026-05-20 19:00:00', '2026-05-20 20:00:00', 15, 'completed'),
       (1, 3, 'HIIT Circuit 2 (Cardio)', 'strength', '2026-06-11 19:00:00', '2026-06-11 20:00:00', 15, 'planned');

INSERT INTO BOOKINGS (user_id, session_id, user_subscription_id)
VALUES (4, 1, 1),
       (4, 2, 1),
       (4, 3, 1),
       (4, 4, 1),
       (5, 2, 2),
       (6, 3, 3);

INSERT INTO SESSIONS (trainer_id, room_id, title, type, start_time, end_time, max_capacity, status) VALUES
-- Sesiuni din TRECUT (pentru a testa istoricul)
(1, 1, 'Morning Cardio', 'fitness', '2026-06-01 08:00:00', '2026-06-01 09:30:00', 10, 'completed'),     -- ID 5
(2, 2, 'Kineto Recovery', 'physiotherapy', '2026-06-03 14:00:00', '2026-06-03 15:00:00', 1, 'completed'), -- ID 6
(1, 3, 'Core Strength', 'strength', '2026-06-04 18:00:00', '2026-06-04 19:00:00', 10, 'canceled'),       -- ID 7

-- Sesiune "AZI" / Săptămâna curentă (pentru a testa calendarul activ)
(1, 1, 'Zumba Class', 'fitness', '2026-06-06 19:00:00', '2026-06-06 20:00:00', 20, 'planned'),            -- ID 8

-- Sesiuni în VIITOR (pentru a testa programările viitoare)
(1, 3, 'Crossfit Intro', 'strength', '2026-06-08 17:00:00', '2026-06-08 18:30:00', 12, 'planned'),        -- ID 9
(2, 2, 'Spine Evaluation', 'physiotherapy', '2026-06-15 10:00:00', '2026-06-15 11:00:00', 1, 'planned'),  -- ID 10
(1, 1, 'Endurance Run', 'fitness', '2026-06-20 09:00:00', '2026-06-20 10:00:00', 15, 'planned');          -- ID 11


-- 2. O PROGRAMĂM PE ANNA (user_id = 5) LA TOATE ACESTE SESIUNI
-- Folosim user_subscription_id = 2 (Abonamentul de Physio din scriptul tău inițial)
INSERT INTO BOOKINGS (user_id, session_id, user_subscription_id) VALUES
                                                                     (5, 5, 2),  -- A participat la Fitness (Trecut)
                                                                     (5, 6, 2),  -- A participat la Physio (Trecut)
                                                                     (5, 7, 2),  -- Sesiunea de Strength a fost anulată (Trecut)
                                                                     (5, 8, 2),  -- Are o programare "Azi" la Fitness
                                                                     (5, 9, 2),  -- Urmează Strength (Viitor)
                                                                     (5, 10, 2), -- Urmează Physio (Viitor)
                                                                     (5, 11, 2); -- Urmează Fitness (Viitor)

INSERT INTO NOTIFICATIONS (user_id, title, message)
VALUES
    (4, 'Session Confirmed', 'Strength Training • Strength Zone • Tomorrow 18:00'),
    (4, 'Membership Expiring Soon', 'Your Fitness membership will expire in 3 days. Renew now to keep your access.'),
    (4, 'Session Canceled', 'Unfortunately, Physiotherapy • Recovery Room • Today 16:00 has been canceled by the trainer.'),
    (4, 'Booking Reminder', 'Don''t forget! You have a Fitness • Cardio Area session in 2 hours.'),
    (4, 'New Trainer Available', 'Meet Alex, our new Strength specialist! Book a session today.'),
    (4, 'Welcome to KIM', 'Thank you for joining our gym. Get ready to achieve your fitness goals!');

INSERT INTO NOTIFICATIONS (user_id, title, message)
VALUES
    -- Notificări pentru User 1
    (1, 'Welcome to KIM', 'Thank you for joining our gym! Set up your profile to get started.'),
    (1, 'Membership Active', 'Your Full Access membership is now active. Enjoy your workouts!'),

    -- Notificări pentru User 2
    (2, 'Membership Expiring Soon', 'Your Strength membership will expire in 3 days. Renew now to keep your access.'),
    (2, 'Payment Successful', 'Your recent payment was successfully processed. Thank you!'),

    -- Notificări pentru User 3
    (3, 'Session Confirmed', 'Strength Training • Strength Zone • Tomorrow 18:00'),
    (3, 'Booking Reminder', 'Don''t forget! You have a Fitness session in 2 hours.'),

    -- Notificări pentru User 4
    (4, 'Session Canceled', 'Unfortunately, Physiotherapy • Recovery Room • Today 16:00 has been canceled by the trainer.'),
    (4, 'Refund Issued', 'A session has been refunded to your account due to the recent cancellation.'),

    -- Notificări pentru User 5
    (5, 'New Feature', 'You can now suspend your membership directly from your profile settings!'),
    (5, 'Please Review', 'How was your recent session with Trainer Alex? Please leave a review.');
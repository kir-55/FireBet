-- Create 28 random students with funny names, evaluations from 0 to 100, and fun descriptions
INSERT INTO students (name, email, password, evaluation, role, description) VALUES
('John Doe', 'john.doe@example.com', MD5('password1'), 50, 'user', 'An average Joe with a balanced approach to V-Banks.'),
('Jane Smith', 'jane.smith@example.com', MD5('password2'), 60, 'user', 'Always one step ahead, Jane has a keen sense for V-Banks.'),
('Alice Wonderland', 'alice.wonderland@example.com', MD5('password3'), 70, 'user', 'Alice navigates the V-Banks like she does Wonderland, with curiosity and insight.'),
('Bob Builder', 'bob.builder@example.com', MD5('password4'), 40, 'user', 'Bob builds his bets carefully, but sometimes misses the mark.'),
('Charlie Brown', 'charlie.brown@example.com', MD5('password5'), 30, 'user', 'Charlie tries hard but often finds himself on the losing end of bets.'),
('Dora Explorer', 'dora.explorer@example.com', MD5('password6'), 80, 'user', 'Dora explores every V-Bank opportunity with thorough research.'),
('Elmo Sesame', 'elmo.sesame@example.com', MD5('password7'), 20, 'user', 'Elmo is new to V-Banks and still learning the ropes.'),
('Frodo Baggins', 'frodo.baggins@example.com', MD5('password8'), 90, 'user', 'Frodo’s journey through V-Banks is as epic as his quest to Mordor.'),
('Gandalf Grey', 'gandalf.grey@example.com', MD5('password9'), 100, 'user', 'Gandalf’s wisdom makes him a master of V-Banks.'),
('Harry Potter', 'harry.potter@example.com', MD5('password10'), 85, 'user', 'Harry uses his magical intuition to make smart bets.'),
('Indiana Jones', 'indiana.jones@example.com', MD5('password11'), 75, 'user', 'Indiana’s adventurous spirit helps him uncover hidden V-Bank gems.'),
('Jack Sparrow', 'jack.sparrow@example.com', MD5('password12'), 65, 'user', 'Jack’s cunning and charm give him an edge in V-Banks.'),
('Katniss Everdeen', 'katniss.everdeen@example.com', MD5('password13'), 95, 'user', 'Katniss’s sharp instincts make her a top contender in V-Banks.'),
('Lara Croft', 'lara.croft@example.com', MD5('password14'), 85, 'user', 'Lara’s skills in treasure hunting translate well to V-Banks.'),
('Mickey Mouse', 'mickey.mouse@example.com', MD5('password15'), 50, 'user', 'Mickey’s cheerful disposition keeps him steady in V-Banks.'),
('Nancy Drew', 'nancy.drew@example.com', MD5('password16'), 90, 'user', 'Nancy’s detective skills help her solve the mysteries of V-Banks.'),
('Olaf Snowman', 'olaf.snowman@example.com', MD5('password17'), 20, 'user', 'Olaf is just starting to understand the world of V-Banks.'),
('Peter Pan', 'peter.pan@example.com', MD5('password18'), 60, 'user', 'Peter’s youthful exuberance makes him a daring bettor.'),
('Quasimodo Hunchback', 'quasimodo.hunchback@example.com', MD5('password19'), 40, 'user', 'Quasimodo’s resilience helps him keep trying in V-Banks.'),
('Robin Hood', 'robin.hood@example.com', MD5('password20'), 70, 'user', 'Robin’s sense of justice guides his V-Bank strategies.'),
('Sherlock Holmes', 'sherlock.holmes@example.com', MD5('password21'), 100, 'user', 'Sherlock’s analytical mind makes him unbeatable in V-Banks.'),
('Tom Sawyer', 'tom.sawyer@example.com', MD5('password22'), 55, 'user', 'Tom’s cleverness helps him find unique V-Bank opportunities.'),
('Ursula SeaWitch', 'ursula.seawitch@example.com', MD5('password23'), 45, 'user', 'Ursula’s cunning nature gives her an edge in V-Banks.'),
('Voldemort DarkLord', 'voldemort.darklord@example.com', MD5('password24'), 95, 'user', 'Voldemort’s ambition drives him to excel in V-Banks.'),
('Winnie Pooh', 'winnie.pooh@example.com', MD5('password25'), 30, 'user', 'Winnie’s love for honey sometimes distracts him from V-Banks.'),
('Xena Warrior', 'xena.warrior@example.com', MD5('password26'), 80, 'user', 'Xena’s warrior spirit makes her a fierce competitor in V-Banks.'),
('Yoda Jedi', 'yoda.jedi@example.com', MD5('password27'), 100, 'user', 'Yoda’s wisdom and patience make him a V-Banks master.'),
('Zorro Masked', 'zorro.masked@example.com', MD5('password28'), 75, 'user', 'Zorro’s stealth and skill make him a formidable V-Banks player.');

-- Create a few V-Banks dated after 06.03.2025 with unique historical titles
INSERT INTO vbanks (title, date) VALUES
('The Great Depression Fund', '2025-03-07'),
('The Renaissance Vault', '2025-03-14'),
('The Industrial Revolution Reserve', '2025-03-21'),
('The Roaring Twenties Treasury', '2025-03-28'),
('The Medieval Money Chest', '2025-04-04');

-- Create random groups and assign random students as leaders
INSERT INTO `groups` (vbank_id, leader_id, grade) VALUES
(1, 1, NULL),
(1, 2, NULL),
(1, 3, NULL),
(2, 4, NULL),
(2, 5, NULL),
(2, 6, NULL),
(3, 7, NULL),
(3, 8, NULL),
(3, 9, NULL),
(4, 10, NULL),
(4, 11, NULL),
(4, 12, NULL),
(5, 13, NULL),
(5, 14, NULL),
(5, 15, NULL);

-- Assign random students to groups
INSERT INTO student_group (group_id, student_id) VALUES
(1, 16), (1, 17), (1, 18),
(2, 19), (2, 20), (2, 21),
(3, 22), (3, 23), (3, 24),
(4, 25), (4, 26), (4, 27),
(5, 28), (5, 1), (5, 2),
(6, 3), (6, 4), (6, 5),
(7, 6), (7, 7), (7, 8),
(8, 9), (8, 10), (8, 11),
(9, 12), (9, 13), (9, 14),
(10, 15), (10, 16), (10, 17),
(11, 18), (11, 19), (11, 20),
(12, 21), (12, 22), (12, 23),
(13, 24), (13, 25), (13, 26),
(14, 27), (14, 28), (14, 1),
(15, 2), (15, 3), (15, 4);

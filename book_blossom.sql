-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 29, 2026 at 09:43 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `book_blossom`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `year` smallint(6) NOT NULL,
  `genre` varchar(100) NOT NULL,
  `cover` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `title`, `author`, `year`, `genre`, `cover`, `description`, `created_at`) VALUES
(1, 'The Shining', 'Stephen King', 1977, 'Horror', 'https://covers.openlibrary.org/b/isbn/9780307743657-L.jpg', 'A family faces terrifying supernatural events while staying in an isolated hotel.', '2026-10-29 19:51:29'),
(2, 'It', 'Stephen King', 1986, 'Horror', 'https://covers.openlibrary.org/b/isbn/9781501142970-L.jpg', 'A group of friends confronts an evil entity haunting their hometown.', '2026-10-29 19:51:29'),
(3, 'Dracula', 'Bram Stoker', 0, 'Horror', 'https://covers.openlibrary.org/b/isbn/9780141439846-L.jpg', 'A classic Gothic horror novel about Count Dracula.', '2026-10-29 19:51:29'),
(4, 'Frankenstein', 'Mary Shelley', 0, 'Horror', 'https://covers.openlibrary.org/b/isbn/9780486282114-L.jpg', 'A scientist creates a living being with tragic consequences.', '2026-10-29 19:51:29'),
(5, 'The Haunting of Hill House', 'Shirley Jackson', 1959, 'Horror', 'https://covers.openlibrary.org/b/isbn/9780143039983-L.jpg', 'Investigators explore a mysterious house with a disturbing history.', '2026-10-29 19:51:29'),
(6, 'Pet Sematary', 'Stephen King', 1983, 'Horror', 'https://covers.openlibrary.org/b/isbn/9781501156700-L.jpg', 'A family discovers a burial ground with frightening powers.', '2026-10-29 19:51:29'),
(7, 'The Exorcist', 'William Peter Blatty', 1971, 'Horror', 'https://covers.openlibrary.org/b/isbn/9780061007224-L.jpg', 'A mother seeks help when her daughter experiences terrifying changes.', '2026-10-29 19:51:29'),
(8, 'Carrie', 'Stephen King', 1974, 'Horror', 'https://covers.openlibrary.org/b/isbn/9780307743664-L.jpg', 'A bullied teenager discovers she has extraordinary powers.', '2026-10-29 19:51:29'),
(9, 'The Silence of the Lambs', 'Thomas Harris', 1988, 'Horror', 'https://covers.openlibrary.org/b/isbn/9780312924584-L.jpg', 'An FBI trainee seeks help from an imprisoned serial killer.', '2026-10-29 19:51:29'),
(10, 'Interview with the Vampire', 'Anne Rice', 1976, 'Horror', 'https://covers.openlibrary.org/b/isbn/9780345337665-L.jpg', 'A vampire recounts his long and complicated existence.', '2026-10-29 19:51:29'),
(11, 'Atomic Habits', 'James Clear', 2018, 'Motivational', 'https://covers.openlibrary.org/b/isbn/9780735211292-L.jpg', 'A practical guide to building good habits and changing behavior.', '2026-10-29 19:51:29'),
(12, 'The 7 Habits of Highly Effective People', 'Stephen R. Covey', 1989, 'Motivational', 'https://covers.openlibrary.org/b/isbn/9781982137274-L.jpg', 'Principles for personal effectiveness, leadership, and meaningful relationships.', '2026-10-29 19:51:29'),
(13, 'How to Win Friends and Influence People', 'Dale Carnegie', 1936, 'Motivational', 'https://covers.openlibrary.org/b/isbn/9780671027032-L.jpg', 'Advice on communication, relationships, and influencing others.', '2026-10-29 19:51:29'),
(14, 'The Power of Now', 'Eckhart Tolle', 1997, 'Motivational', 'https://covers.openlibrary.org/b/isbn/9781577314806-L.jpg', 'A guide to mindfulness and living in the present moment.', '2026-10-29 19:51:29'),
(15, 'Think and Grow Rich', 'Napoleon Hill', 1937, 'Motivational', 'https://covers.openlibrary.org/b/isbn/9781585424337-L.jpg', 'A classic self-development book about goals, mindset, and achievement.', '2026-10-29 19:51:29'),
(16, 'Mindset: The New Psychology of Success', 'Carol S. Dweck', 2006, 'Motivational', 'https://covers.openlibrary.org/b/isbn/9780345472328-L.jpg', 'Explores how beliefs about ability can influence learning and achievement.', '2026-10-29 19:51:29'),
(17, 'Deep Work', 'Cal Newport', 2016, 'Motivational', 'https://covers.openlibrary.org/b/isbn/9781455586691-L.jpg', 'Strategies for focused work and producing meaningful results.', '2026-10-29 19:51:29'),
(18, 'The Subtle Art of Not Giving a F*ck', 'Mark Manson', 2016, 'Motivational', 'https://covers.openlibrary.org/b/isbn/9780062457714-L.jpg', 'A direct approach to choosing values and priorities.', '2026-10-29 19:51:29'),
(19, 'Man\'s Search for Meaning', 'Viktor E. Frankl', 1946, 'Motivational', 'https://covers.openlibrary.org/b/isbn/9780807014295-L.jpg', 'Reflections on finding meaning during hardship.', '2026-10-29 19:51:29'),
(20, 'The Four Agreements', 'Don Miguel Ruiz', 1997, 'Motivational', 'https://covers.openlibrary.org/b/isbn/9781878424310-L.jpg', 'Four principles for personal freedom and healthier relationships.', '2026-10-29 19:51:29'),
(21, 'The Great Gatsby', 'F. Scott Fitzgerald', 1925, 'Novel', 'https://covers.openlibrary.org/b/isbn/9780743273565-L.jpg', 'A story of ambition, wealth, love, and the American Dream.', '2026-10-29 19:51:29'),
(22, 'Pride and Prejudice', 'Jane Austen', 0, 'Novel', 'https://covers.openlibrary.org/b/isbn/9780141439518-L.jpg', 'Elizabeth Bennet navigates relationships, class, and first impressions.', '2026-10-29 19:51:29'),
(23, 'To Kill a Mockingbird', 'Harper Lee', 1960, 'Novel', 'https://covers.openlibrary.org/b/isbn/9780060935467-L.jpg', 'A young girl witnesses injustice and prejudice in her community.', '2026-10-29 19:51:29'),
(24, 'Jane Eyre', 'Charlotte Brontë', 0, 'Novel', 'https://covers.openlibrary.org/b/isbn/9780141441146-L.jpg', 'An orphan grows into an independent woman while facing difficult choices.', '2026-10-29 19:51:29'),
(25, 'Little Women', 'Louisa May Alcott', 0, 'Novel', 'https://covers.openlibrary.org/b/isbn/9780147514011-L.jpg', 'The March sisters grow up while navigating family, ambition, and love.', '2026-10-29 19:51:29'),
(26, 'The Kite Runner', 'Khaled Hosseini', 2003, 'Novel', 'https://covers.openlibrary.org/b/isbn/9781594631931-L.jpg', 'A man confronts memories of friendship, betrayal, and redemption.', '2026-10-29 19:51:29'),
(27, 'The Book Thief', 'Markus Zusak', 2005, 'Novel', 'https://covers.openlibrary.org/b/isbn/9780375842207-L.jpg', 'A young girl finds comfort in books during World War II.', '2026-10-29 19:51:29'),
(28, 'The Alchemist', 'Paulo Coelho', 1988, 'Novel', 'https://covers.openlibrary.org/b/isbn/9780062315007-L.jpg', 'A shepherd travels in pursuit of a dream and self-discovery.', '2026-10-29 19:51:29'),
(29, 'The Notebook', 'Nicholas Sparks', 1996, 'Novel', 'https://covers.openlibrary.org/b/isbn/9780446605236-L.jpg', 'A love story spanning years, memories, and difficult circumstances.', '2026-10-29 19:51:29'),
(30, 'The Fault in Our Stars', 'John Green', 2012, 'Novel', 'https://covers.openlibrary.org/b/isbn/9780142424179-L.jpg', 'Two teenagers form a meaningful relationship while facing illness.', '2026-10-29 19:51:29'),
(31, 'Dune', 'Frank Herbert', 1965, 'Sci-Fi', 'https://covers.openlibrary.org/b/isbn/9780441172719-L.jpg', 'A young noble becomes involved in a struggle over a desert planet.', '2026-10-29 19:51:29'),
(32, 'The Martian', 'Andy Weir', 2011, 'Sci-Fi', 'https://covers.openlibrary.org/b/isbn/9780553418026-L.jpg', 'An astronaut uses science and ingenuity to survive on Mars.', '2026-10-29 19:51:29'),
(33, '1984', 'George Orwell', 1949, 'Sci-Fi', 'https://covers.openlibrary.org/b/isbn/9780451524935-L.jpg', 'A dystopian society controls information, language, and individual freedom.', '2026-10-29 19:51:29'),
(34, 'Fahrenheit 451', 'Ray Bradbury', 1953, 'Sci-Fi', 'https://covers.openlibrary.org/b/isbn/9781451673319-L.jpg', 'A fireman questions a society that bans books.', '2026-10-29 19:51:29'),
(35, 'Ender\'s Game', 'Orson Scott Card', 1985, 'Sci-Fi', 'https://covers.openlibrary.org/b/isbn/9780812550702-L.jpg', 'A gifted child trains for a conflict that may determine humanity’s future.', '2026-10-29 19:51:29'),
(36, 'The Hitchhiker\'s Guide to the Galaxy', 'Douglas Adams', 1979, 'Sci-Fi', 'https://covers.openlibrary.org/b/isbn/9780345391803-L.jpg', 'An ordinary man embarks on a humorous journey through space.', '2026-10-29 19:51:29'),
(37, 'Foundation', 'Isaac Asimov', 1951, 'Sci-Fi', 'https://covers.openlibrary.org/b/isbn/9780553293357-L.jpg', 'A mathematician attempts to preserve knowledge during the fall of an empire.', '2026-10-29 19:51:29'),
(38, 'The Time Machine', 'H. G. Wells', 0, 'Sci-Fi', 'https://covers.openlibrary.org/b/isbn/9780451530707-L.jpg', 'An inventor travels far into the future and discovers a changed world.', '2026-10-29 19:51:29'),
(39, 'Do Androids Dream of Electric Sheep?', 'Philip K. Dick', 1968, 'Sci-Fi', 'https://covers.openlibrary.org/b/isbn/9780345404473-L.jpg', 'A bounty hunter questions humanity while tracking artificial beings.', '2026-10-29 19:51:29'),
(40, 'The Left Hand of Darkness', 'Ursula K. Le Guin', 1969, 'Sci-Fi', 'https://covers.openlibrary.org/b/isbn/9780441478125-L.jpg', 'An envoy visits a world that challenges conventional ideas about society and identity.', '2026-10-29 19:51:29'),
(41, 'Pride and Prejudice', 'Jane Austen', 1813, 'Romance', 'book.jpg', 'Elizabeth Bennet and Mr. Darcy overcome pride and prejudice in this classic love story.', '2026-10-29 20:09:26'),
(42, 'The Notebook', 'Nicholas Sparks', 1996, 'Romance', 'book.jpg', 'A lasting love survives the challenges of time and memory.', '2026-10-29 20:09:26'),
(43, 'Me Before You', 'Jojo Moyes', 2012, 'Romance', 'book.jpg', 'An unexpected relationship changes two lives.', '2026-10-29 20:09:26'),
(44, 'The Love Hypothesis', 'Ali Hazelwood', 2021, 'Romance', 'book.jpg', 'A pretend relationship between two researchers becomes complicated.', '2026-10-29 20:09:26'),
(45, 'Beach Read', 'Emily Henry', 2020, 'Romance', 'book.jpg', 'Two writers challenge each other and discover unexpected feelings.', '2026-10-29 20:09:26'),
(46, 'The Hating Game', 'Sally Thorne', 2016, 'Romance', 'book.jpg', 'Two workplace rivals discover that their feelings are more complicated than they seem.', '2026-10-29 20:09:26'),
(47, 'It Ends with Us', 'Colleen Hoover', 2016, 'Romance', 'book.jpg', 'A young woman navigates love and difficult personal choices.', '2026-10-29 20:09:26'),
(48, 'The Fault in Our Stars', 'John Green', 2012, 'Romance', 'book.jpg', 'Two teenagers find love while facing serious illness.', '2026-10-29 20:09:26'),
(49, 'Jane Eyre', 'Charlotte Bronte', 1847, 'Romance', 'book.jpg', 'An independent young woman discovers love while protecting her dignity.', '2026-10-29 20:09:26'),
(50, 'The Time Travelers Wife', 'Audrey Niffenegger', 2003, 'Romance', 'book.jpg', 'A couple struggles to maintain their relationship across unpredictable time travel.', '2026-10-29 20:09:26'),
(51, 'Harry Potter and the Sorcerers Stone', 'J. K. Rowling', 1997, 'Fantasy', 'book.jpg', 'A young wizard discovers a magical school and a hidden destiny.', '2026-10-29 20:09:26'),
(52, 'The Hobbit', 'J. R. R. Tolkien', 1937, 'Fantasy', 'book.jpg', 'A reluctant hobbit joins a quest to reclaim a lost treasure.', '2026-10-29 20:09:26'),
(53, 'The Fellowship of the Ring', 'J. R. R. Tolkien', 1954, 'Fantasy', 'book.jpg', 'A group begins a dangerous journey to destroy a powerful ring.', '2026-10-29 20:09:26'),
(54, 'A Game of Thrones', 'George R. R. Martin', 1996, 'Fantasy', 'book.jpg', 'Noble families compete for power in a dangerous kingdom.', '2026-10-29 20:09:26'),
(55, 'The Name of the Wind', 'Patrick Rothfuss', 2007, 'Fantasy', 'book.jpg', 'A gifted young man recounts the events that shaped his extraordinary life.', '2026-10-29 20:09:26'),
(56, 'The Way of Kings', 'Brandon Sanderson', 2010, 'Fantasy', 'book.jpg', 'Warriors and leaders confront a world shaped by ancient mysteries.', '2026-10-29 20:09:26'),
(57, 'The Golden Compass', 'Philip Pullman', 1995, 'Fantasy', 'book.jpg', 'A young girl travels through strange worlds to uncover a dangerous secret.', '2026-10-29 20:09:26'),
(58, 'The Lion, the Witch and the Wardrobe', 'C. S. Lewis', 1950, 'Fantasy', 'book.jpg', 'Four children enter a magical land ruled by an icy tyrant.', '2026-10-29 20:09:26'),
(59, 'The Eye of the World', 'Robert Jordan', 1990, 'Fantasy', 'book.jpg', 'Young villagers become involved in an epic struggle between good and evil.', '2026-10-29 20:09:26'),
(60, 'The Once and Future King', 'T. H. White', 1958, 'Fantasy', 'book.jpg', 'The legend of King Arthur is retold through adventures and difficult choices.', '2026-10-29 20:09:26'),
(61, 'Demon Slayer Volume 1', 'Koyoharu Gotouge', 2016, 'Anime', 'book.jpg', 'Tanjiro begins a dangerous journey to save his sister and fight demons.', '2026-10-29 20:09:26'),
(62, 'Naruto Volume 1', 'Masashi Kishimoto', 1999, 'Anime', 'book.jpg', 'A young ninja dreams of earning the respect of his village.', '2026-10-29 20:09:26'),
(63, 'One Piece Volume 1', 'Eiichiro Oda', 1997, 'Anime', 'book.jpg', 'Monkey D. Luffy begins his journey to become King of the Pirates.', '2026-10-29 20:09:26'),
(64, 'Attack on Titan Volume 1', 'Hajime Isayama', 2009, 'Anime', 'book.jpg', 'Humanity fights for survival against enormous mysterious Titans.', '2026-10-29 20:09:26'),
(65, 'My Hero Academia Volume 1', 'Kohei Horikoshi', 2014, 'Anime', 'book.jpg', 'A boy without powers dreams of becoming a great hero.', '2026-10-29 20:09:26'),
(66, 'Death Note Volume 1', 'Tsugumi Ohba', 2003, 'Anime', 'book.jpg', 'A student discovers a notebook with the power to kill anyone whose name is written in it.', '2026-10-29 20:09:26'),
(67, 'Sailor Moon Volume 1', 'Naoko Takeuchi', 1992, 'Anime', 'book.jpg', 'A schoolgirl discovers her identity as a magical guardian.', '2026-10-29 20:09:26'),
(68, 'Spy x Family Volume 1', 'Tatsuya Endo', 2019, 'Anime', 'book.jpg', 'A spy creates a pretend family for a secret mission.', '2026-10-29 20:09:26'),
(69, 'Sword Art Online Volume 1', 'Reki Kawahara', 2009, 'Anime', 'book.jpg', 'Players become trapped inside a virtual reality game.', '2026-10-29 20:09:26'),
(70, 'Your Name', 'Makoto Shinkai', 2016, 'Anime', 'Your Name.jpg', 'Two teenagers mysteriously begin switching bodies and searching for each other.', '2026-10-29 20:09:26');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(254) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`) VALUES
(1, 'chyna', 'chainabagobe@gmail.com', '$2y$10$UESVXYPQl9WCYIY/PEYYtOygifeNvqSJX7koIMw4PLL72RCtW4whO', '2026-10-29 19:38:24');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

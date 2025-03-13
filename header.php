<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<header>
    <style>
        header {
            background-color: #111111; /* Navbar Background */
            padding: 10px 0;
            position: sticky;
            top: 0;
            z-index: 1000; /* Ensure the header is on top */
        }

        nav {
            top: 0;
            height: 50px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .logo {
            font-size: 24px;
            color: #FF6B00; /* Main Accent (Fire Orange) */
            font-weight: bold;
        }

        .nav-links {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
        }

        .nav-links li {
            margin-left: 20px;
        }

        .nav-links li a {
            color: #ffffff;
            text-decoration: none;
            font-size: 18px;
        }

        .nav-links li a:hover {
            color: #FF6B00; /* Navbar Link (Hover/Active) */
            text-decoration: underline;
        }

        .menu-toggle {
            display: none;
            font-size: 24px;
            color: #FF6B00; /* Main Accent (Fire Orange) */
            cursor: pointer;
        }

        .side-menu {
            top: 70px;
            height: calc(100vh - 70px);
            position: fixed;
            width: 250px;
            
            z-index: 2000; /* Ensure the side menu is on top of the footer */
            background-color: #1F1F1F; /* Dark Background */
            box-shadow: -2px 0 5px rgba(0, 0, 0, 0.5);
            padding-top: 20px;
            transition: transform 0.3s ease;
            transform: translateX(-100%);
        }

        .side-menu.open {
            transform: translateX(0);
        }

        .side-menu ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .side-menu ul li {
            padding: 10px 20px;
            border-bottom: 1px solid #333333; /* Divider between items */
        }

        .side-menu ul li a {
            color: #ffffff;
            text-decoration: none;
            font-size: 18px;
            display: block;
        }

        .side-menu ul li a:hover {
            background-color: #333333; /* Hover Background */
            color: #FF6B00; /* Navbar Link (Hover/Active) */
            text-decoration: underline;
        }

        .overlay {
            display: none;
            position: fixed;
            top: 70px;
            height: calc(100vh - 70px);
            left: 0;
            width: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1500; /* Ensure the overlay is on top of other content */
            transition: opacity 0.3s ease;
            opacity: 0;
        }

        .overlay.open {
            display: block;
            opacity: 1;
        }

        /* Media query for smaller screens */
        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }

            .menu-toggle {
                display: block;
            }
        }
    </style>
    <nav>
        <a href="/index.php" style="text-decoration: none;" ><div class="logo">FireBet</div></a>
        <ul class="nav-links">
            <li><a href="/index.php#groups">Home</a></li>
            <li><a href="/students.php">Students</a></li>
            <li><a href="/about/index.php">About</a></li> <!-- Added About link -->
            <?php if (isset($_SESSION['role']) && ($_SESSION['role'] == "admin" || $_SESSION['role'] == "manager")): ?>
                <li><a href="/manage_panel/index.php">Manage Panel</a></li>
            <?php endif; if (isset($_SESSION['name']) && isset($_SESSION['role'])): ?>
                <li><a href="/my_bets.php">My Bets</a></li> <!-- Added My Bets link -->
                <li><a href="/logout.php">Logout</a></li>
            <?php else: ?>
                <li><a href="/login.php">Login</a></li>
            <?php endif; ?>
        </ul>
        <div class="menu-toggle" id="menu-toggle" onclick="toggleMenu()">☰</div>
    </nav>
    <div class="side-menu" id="side-menu">
        <ul>
            <li><a href="/index.php#groups" >Home</a></li>
            <li><a href="/students.php">Students</a></li>
            <li><a href="/about/index.php">About</a></li> <!-- Added About link -->
            <?php if (isset($_SESSION['role']) && ($_SESSION['role'] == "admin" || $_SESSION['role'] == "manager")): ?>
                <li><a href="/manage_panel/index.php">Manage Panel</a></li>
            <?php endif; if (isset($_SESSION['name'])): ?>
                <li><a href="/my_bets.php">My Bets</a></li> <!-- Added My Bets link -->
                <li><a href="/logout.php">Logout</a></li>
            <?php else: ?>
                <li><a href="/login.php">Login</a></li>
            <?php endif; ?>
        </ul>
    </div>
    <div class="overlay" id="overlay" onclick="toggleMenu()"></div>
    <script>
        function toggleMenu() {
            const sideMenu = document.getElementById('side-menu');
            const overlay = document.getElementById('overlay');
            if (sideMenu.classList.contains('open')) {
                sideMenu.classList.remove('open');
                overlay.classList.remove('open');
            } else {
                sideMenu.classList.add('open');
                overlay.classList.add('open');
            }
        }

        
        window.addEventListener('scroll', function() {
            const sideMenu = document.getElementById('side-menu');
            const overlay = document.getElementById('overlay');
            if (sideMenu.classList.contains('open')) {
                sideMenu.classList.remove('open');
                overlay.classList.remove('open');
            }
        });

        window.addEventListener('touchstart', function(event) {
            const sideMenu = document.getElementById('side-menu');
            const overlay = document.getElementById('overlay');
            const menuToggle = document.getElementById('menu-toggle');
            if (sideMenu.classList.contains('open') && !sideMenu.contains(event.target) && event.target !== menuToggle) {
                sideMenu.classList.remove('open');
                overlay.classList.remove('open');
            }
        });
    </script>
</header>

<?php

session_start();
require_once '../app/User.php';

$users = null;
if(isset($_GET['search']) && !empty(trim($_GET['search'])) ) {
    $search = trim($_GET['search']);
    
    $users = search_user($search); 
}
else{ 
    $users = fetch_all_user();
}

?>

<!DOCTYPE html>
<html>
    <head></head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/admin_users.css?v=<?= time() ?>" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Arvo">
        <title>Admin - Users</title>
        <style>
            @import url('css/color-palette.css');
            * {
                padding: 0;
                margin: 0;
            }
            body {
                max-width: 100vw;
                overflow-x: hidden;
                background-color: var(--bg-color);
                font-family: 'Funnel Sans', sans-serif; 
            }
            nav {
                width: 100%;
                height: 6vh;
                background-color: var(--50);
                display: flex;
                justify-content: space-between;
                margin-bottom: 2rem;
                padding :1rem;
                align-items: center;
            }
            a {
                text-decoration: none;
            }
            nav * {
                color: var(--text-color);
                font-family: 'Arvo', serif;
                margin: 0 40px 0 5px;
            }
            .search-bar {
                width: 100%;
                display: flex;
                align-items: center;
                gap: 0;
            }
            form {
                display:flex;  
                margin: 2rem 0 2rem 4rem;
                gap: 12px;
            }
            input[type="text"] {
                width: 10rem;
                height: 1.8rem;
                border: none;
                border-radius: 5px 0 0 5px;
                padding: 0 10px;
                font-size: 16px;
                outline: none;
            }
            #submit-btn {
                width: 4rem;
                height: 1.8rem;

            }
            #refresh-btn {
                width: 4rem;
                height: 1.8rem;
                margin-left: 1rem;
            }
            .container {
                width: 90%;
                margin: 0 auto;
                display:block;  
                background-color: var(--50);
                border-radius: 10px;
            }
            .user-list {
                width: 90%;
                padding: 1rem;
                margin: auto;
            }
            .list-header, .list-row{
                display: grid;
                grid-template-columns: repeat(5, 1fr);
                padding: 12px;
                font-weight: bold;
                text-align: left;
                font-size: left;
                border-bottom: 1px solid var(--bg-color);
            }
            .list-row {
                font-size: small;
                font-weight: normal;
            }
            .list-error {
                text-align: center;
                width: 100%;
                padding: 12px;
                color: red;
            }
        </style>
        <script>
            function refresh() {
                window.history.replaceState({}, document.title, window.location.pathname);
                window.location.reload();
            }
        </script>
    </head>
    <body>
        <nav>
            <h1><a href="index.php">RETAILO</a></h1>
            <h3>User Management</h3>
        </nav>
        <div class="search-bar">
            <form method="get">
                <input type="text" name="search" placeholder="Search users..." />
                <button id="submit-btn" type="submit">Search</button>
            </form>
            <button onclick="refresh()" id="refresh-btn">Refresh</button>
        </div>
        <div class="container">
            <div class="user-list">
                <div class="list-header">
                    <div>ID</div>
                    <div>Name</div>
                    <div>Email</div>
                    <div>Address</div>
                    <div>Phone</div>
                </div>
                <?php if ($users && $users->num_rows > 0): ?>
                    <?php foreach ($users as $user): ?>
                        <div class="list-row">
                            <div><?= htmlspecialchars($user['id']) ?></div>
                            <div><?= htmlspecialchars($user['name']) ?></div>
                            <div><?= htmlspecialchars($user['email']) ?></div>
                            <div><?= htmlspecialchars($user['address']) ?></div>
                            <div><?= htmlspecialchars($user['phone']) ?></div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="list-row">
                        <div class="list-error">No users found.</div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </body>
</html>
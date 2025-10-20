<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publishing Houses</title>

   <link
      href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;500;700;900&display=swap"
      rel="stylesheet"
    />

    <link rel="stylesheet" href="readers.css">
</head>
<body>
    <header class="topbar" role="banner" aria-label="Top navigation">
      <a class="brand" href="index.html">
        <span class="logo-dot">E</span>
        <span>Evgen`s Library</span>
      </a>

      <nav class="navlinks" role="navigation" aria-label="Main">
        <a href="index.html">Home</a>
        <div class="navbar-menu">
          <a href="#">Tables ›</a>
          <div class="navbar-submenu glass">
            <a href="books.php">Books</a>
            <a href="readers.php">Readers</a>
          </div>
        </div>
        <a href="readers.php?results=delays">Delays</a>
        <a href="publishing_houses.php">Publishing houses</a>
        <a href="#">Need help?</a>
      </nav>
    </header>

    <main>
        <div class="toolbar">
            <div class="icon-circle">
                <img src="https://static.wixstatic.com/media/935d30_6d2a57cfccb14cac990e64023f38f1e9~mv2.png/v1/fit/w_2500,h_1330,al_c/935d30_6d2a57cfccb14cac990e64023f38f1e9~mv2.png" alt="Book icon">
            </div>

            <div class="section-name">Publishing Houses</div>

            <form class="search-bar" id="searchForm" method="GET" action="">
                <input type="text" id="search" placeholder="What are you looking for...">
                <button class="search-icon" type="submit">🔍</button>
            </form>
        </div>

        <?php
            include 'connection.php';

            $sql = "SELECT Publishing_House, COUNT(*) AS Nr_Books FROM books GROUP BY Publishing_House";
            $result = $conn->query($sql);
        ?>

        <table id="data-table">
            <thead>
                <tr>
                    <th>Publishing House</th>
                    <th>Number of Books</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "
                            <tr class='reader-row'
                            data-name='{$row['Publishing_House']}'>
                                <td>{$row['Publishing_House']}</td>
                                <td>{$row['Nr_Books']}</td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='2' style='padding:10px'>No results</td></tr>";
                    }
                ?>
            </tbody>

        </table>

    </main>

    <script>
        const submenu = document.querySelector(".navbar-submenu");
        submenu.style.opacity = 0;
        submenu.style.display = "none";
        document
        .querySelector(".navbar-menu")
        .addEventListener("mouseenter", () => {
            submenu.style.opacity = 1;
            submenu.style.display = "flex";
        });

        document
        .querySelector(".navbar-menu")
        .addEventListener("mouseleave", () => {
            submenu.style.opacity = 0;
            submenu.style.display = "none";
        });

        const searchForm = document.getElementById("searchForm");
        searchForm.addEventListener("submit", (e) => {
            const filter = document.getElementById("filter").value.toLowerCase();
            const query = document.getElementById("search").value.toLowerCase();

            const rows = document.querySelectorAll("#data-table tbody tr");
            rows.forEach(row => {
                let text = row.cells[0].innerText.toLowerCase();

                if (text.includes(query)) row.style.display = "";
                else row.style.display = "none";  
            });

            e.preventDefault();
        });

    </script>
</body>
</html>



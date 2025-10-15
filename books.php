<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Books</title>

   <link
      href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;500;700;900&display=swap"
      rel="stylesheet"
    />

    <link rel="stylesheet" href="books.css">
</head>
<body>
    <header class="topbar" role="banner" aria-label="Top navigation">
      <a class="brand" href="index.html">
        <span class="logo-dot">E</span>
        <span>Evgen`s Library</span>
      </a>

      <nav class="navlinks" role="navigation" aria-label="Main">
        <a href="#">Home</a>
        <div class="navbar-menu">
          <a href="#">Tables ›</a>
          <div class="navbar-submenu glass">
            <a href="books.php">Books</a>
            <a href="">Readers</a>
          </div>
        </div>
        <a href="#">Delays</a>
        <a href="#">Publishing houses</a>
        <a href="#">Need help?</a>
      </nav>
    </header>

    <main>
        <div class="toolbar">
            <div class="icon-circle">
                <img src="https://cdn-icons-png.flaticon.com/512/29/29302.png" alt="Book icon">
            </div>

            <div class="section-name">Books</div>

            <div class="search-bar">
            <label for="search">Search by:</label>
            <select id="filter">
                <option>Price</option>
                <option>Title</option>
                <option>Author</option>
                <option>P. House</option>
            </select>
            <input type="text" id="search" placeholder="What are you looking for...">
            <span class="search-icon">🔍</span>
            </div>
        </div>

        <?php
            include 'connection.php';

            if (isset($_POST['delete_id'])) {
                $id = intval($_POST['delete_id']);
                $sql = "DELETE FROM books WHERE ID_Book = $id";
                $conn->query($sql);
            }

            $sql = "SELECT * FROM books";
            $result = $conn->query($sql);
        ?>

        <table id="data-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Publishing House</th>
                    <th>Price</th>
                    <th>Delete</th>
                </tr>
                <tr>
                    <td colspan="5">
                    <form action="">
                        <input type="hidden" name="add-book">
                        <button type="button" class="add-button" id="openModalBtn">Add (+)</button></td>
                    </form>    
                </tr>
            </thead>
            <tbody>
            <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "
                        <tr>
                            <td>{$row["Title"]}</td>
                            <td>{$row["Author"]}</td>
                            <td>{$row["Publishing_House"]}</td>
                            <td>{$row["Price"]} lei</td>
                            <td>
                                <form method='POST' style='margin:0'>
                                    <input type='hidden' name='delete_id' value='{$row["ID_Book"]}'>
                                    <button type='submit' class='delete_button'>❌</button>
                                </form>
                            </td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' style='padding:10px'>No results</td></tr>";
                }
            ?>
            </tbody>
        </table>

        <div id="addModal" class="modal">
            <div class="modal-content">
                <span class="close" id="closeModalBtn">&times;</span>
                <h3>Add New Book</h3>
                <form id="bookForm">
                    <label>Title:</label>
                    <input type="text" name="title" required>
                    <label>Author:</label>
                    <input type="text" name="author" required>
                    <label>Publishing House:</label>
                    <input type="text" name="publishing_house" required>
                    <label>Price:</label>
                    <input type="number" name="price" required>
                    <button type="submit" class="submit-btn">Submit</button>
                </form>
            </div>
        </div>
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

        const modal = document.getElementById("addModal");
        const openBtn = document.getElementById("openModalBtn");
        const closeBtn = document.getElementById("closeModalBtn");
        const form = document.getElementById("bookForm");
        const tableBody = document.querySelector("#data-table tbody");

        // Deschide modalul
        openBtn.addEventListener("click", () => {
            modal.style.display = "block";
        });

        // Închide modalul
        closeBtn.addEventListener("click", () => {
            modal.style.display = "none";
        });

        // Închide dacă se face clic în afara ferestrei
        window.addEventListener("click", (e) => {
            if (e.target === modal) modal.style.display = "none";
        });

        // Când se trimite formularul
        form.addEventListener("submit", async (e) => {
            e.preventDefault();

            const title = form.title.value;
            const author = form.author.value;
            const house = form.publishing_house.value;
            const price = form.price.value;

            // Trimite datele la PHP
            const response = await fetch("add_book.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: new URLSearchParams({
                    title: title,
                    author: author,
                    publishing_house: house,
                    price: price
                })
            });

            const result = await response.text();
            if (result.trim() === "success") {
                // Adaugă vizual cartea în tabel (fără reîncărcare)
                const row = document.createElement("tr");
                row.innerHTML = `
                    <td>${title}</td>
                    <td>${author}</td>
                    <td>${house}</td>
                    <td>${price}</td>
                    <td>
                        <form method='POST' style='margin:0'>
                            <input type='hidden' name='delete_id' value=''>
                            <button type='submit' class='delete_button'>❌</button>
                        </form>
                    </td>
                `;
                tableBody.appendChild(row);

                // Reset form și închide pop-up-ul
                form.reset();
                modal.style.display = "none";
                window.location.reload();
            } else {
                alert("Eroare la inserarea în baza de date!");
            }
        });

    </script>
</body>
</html>



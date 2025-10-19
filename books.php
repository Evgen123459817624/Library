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
        <a href="index.html">Home</a>
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

            <form class="search-bar" id="searchForm" method="GET" action="">
                <label for="search">Search by:</label>
                <select id="filter">
                    <option value="price">Price</option>
                    <option value="title">Title</option>
                    <option value="author">Author</option>
                    <option value="phouse">P. House</option>
                </select>
                <input type="text" id="search" placeholder="What are you looking for...">
                <button class="search-icon" type="submit">🔍</button>
            </form>
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
                        <tr class='book-row'
                        data-id='{$row['ID_Book']}'
                        data-title='{$row['Title']}'
                        data-author='{$row['Author']}'
                        data-house='{$row['Publishing_House']}'
                        data-price='{$row['Price']}'>
                            <td>{$row["Title"]}</td>
                            <td>{$row["Author"]}</td>
                            <td>{$row["Publishing_House"]}</td>
                            <td>{$row["Price"]} lei</td>
                            <td>
                                <form method='POST' style='margin:0'>
                                    <input type='hidden' name='delete_id' value='{$row["ID_Book"]}'>
                                    <button type='submit' class='delete_button' onclick=\"event.stopPropagation(); return window.confirm('Are you sure you want to delete this book? This action cannot be undone.');\">❌</button>
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
                    <input type="text" name="title" id="add-title" required>
                    <label>Author:</label>
                    <input type="text" name="author" id="add-author" required>
                    <label>Publishing House:</label>
                    <input type="text" name="publishing_house" id="add-publishing_house" required>
                    <label>Price:</label>
                    <input type="number" name="price" id="add-price" required>
                    <button type="submit" name="add_book" class="submit-btn">Submit</button>
                </form>
            </div>
        </div>

        <div id="editModal" class="modal">
            <div class="modal-content">
                <span class="close" id="closeEditModalBtn">&times;</span>
                <h3>Edit Book</h3>
                <form id="editForm">
                    <input type="hidden" name="id" id="edit-id">
                    <label>Title:</label>
                    <input type="text" name="title" id="edit-title" required>
                    <label>Author:</label>
                    <input type="text" name="author" id="edit-author" required>
                    <label>Publishing House:</label>
                    <input type="text" name="publishing_house" id="edit-house" required>
                    <label>Price:</label>
                    <input type="number" name="price" id="edit-price" required>
                    <button type="submit" class="submit-btn">Save</button>
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

            // read values from inputs (names: title, author, publishing_house, price)
            const title = document.getElementById('add-title').value;
            const author = document.getElementById('add-author').value;
            const house = document.getElementById('add-publishing_house').value;
            const price = document.getElementById('add-price').value;

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
                            <button type='submit' class='delete_button' onclick=\"event.stopPropagation(); return window.confirm('Are you sure you want to delete this book? This action cannot be undone.');\">❌</button>
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


        const rows = document.querySelectorAll(".book-row");
        const editModal = document.getElementById("editModal");
        const closeEditModalBtn = document.getElementById("closeEditModalBtn");

        rows.forEach(row => {
        row.addEventListener("click", () => {
            // preia valorile din data-*
            document.getElementById("edit-id").value = row.dataset.id;
            document.getElementById("edit-title").value = row.dataset.title;
            document.getElementById("edit-author").value = row.dataset.author;
            document.getElementById("edit-house").value = row.dataset.house;
            document.getElementById("edit-price").value = row.dataset.price;

            // deschide pop-up-ul
            editModal.style.display = "block";
        });
        });

        // închide modalul
        closeEditModalBtn.addEventListener("click", () => {
            editModal.style.display = "none";
        });

        // click în afara ferestrei → închide
        window.addEventListener("click", (e) => {
            if (e.target === editModal) editModal.style.display = "none";
        });

        // trimiterea formularului către edit_book.php
        document.getElementById("editForm").addEventListener("submit", async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);
        const response = await fetch("edit_book.php", {
            method: "POST",
            body: formData
        });
        const result = await response.text();
        if (result.trim() === "success") {
            alert("Book updated successfully!");
            editModal.style.display = "none";
            location.reload();
        } else {
            alert("Error updating book!");
        }
        });


        const searchForm = document.getElementById("searchForm");
        searchForm.addEventListener("submit", (e) => {
            const filter = document.getElementById("filter").value.toLowerCase();
            const query = document.getElementById("search").value.toLowerCase();

            const rows = document.querySelectorAll("#data-table tbody tr");
            rows.forEach(row => {
                let text = "";
                if (filter === "price") text = row.cells[3].innerText.toLowerCase();
                else if (filter === "title") text = row.cells[0].innerText.toLowerCase();
                else if (filter === "author") text = row.cells[1].innerText.toLowerCase();
                else if (filter === "p. house") text = row.cells[2].innerText.toLowerCase();

                if (text.includes(query) && filter !== "price") row.style.display = "";
                else if(filter === "price" && Number(text.split(" ")[0]) >= Number(query)) row.style.display = "";
                else row.style.display = "none";  
            });

            e.preventDefault();
        });

        const searchBySelect = document.getElementById("filter"); 
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        const searchBy = urlParams.get('searchby');
        if (searchBy) {
            if (searchBy === 'price') searchBySelect.value = 'price';
            else if (searchBy === 'title') searchBySelect.value = 'title';
            else if (searchBy === 'author') searchBySelect.value = 'author';
            else if (searchBy === 'phouse') searchBySelect.value = 'phouse';
        }

    </script>
</body>
</html>



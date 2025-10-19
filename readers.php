<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Readers</title>

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
        <a href="#">Publishing houses</a>
        <a href="#">Need help?</a>
      </nav>
    </header>

    <main>
        <div class="toolbar">
            <div class="icon-circle">
                <img src="https://cdn0.iconfinder.com/data/icons/user-38/100/user-reader-512.png" alt="Book icon">
            </div>

            <div class="section-name">Readers</div>

            <form class="search-bar" id="searchForm" method="GET" action="">
                <label for="search">Search by:</label>
                <select id="filter">
                    <option value="book">Book ID</option>
                    <option value="reader">Reader ID</option>
                </select>
                <input type="text" id="search" placeholder="What are you looking for...">
                <button class="search-icon" type="submit">🔍</button>
            </form>
        </div>

        <?php
            include 'connection.php';

            if (isset($_POST['delete_id'])) {
                $id = intval($_POST['delete_id']);
                $sql = "DELETE FROM readers WHERE ID_Book = $id";
                $conn->query($sql);
            }

            $sql = "SELECT * FROM readers";
            $result = $conn->query($sql);
        ?>

        <table id="data-table">
            <thead>
                <tr>
                    <th>Reader ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Adress</th>
                    <th>Phone</th>
                    <th>Book ID</th>
                    <th>Getting Date</th>
                    <th>Returning Date</th>
                    <th>Delete</th>
                </tr>
                <tr>
                    <td colspan="9">
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
                            <tr class='reader-row'
                            data-idread='{$row['ID_Reader']}'
                            data-fname='{$row['FirstName']}'
                            data-lname='{$row['LastName']}'
                            data-adress='{$row['Adress']}'
                            data-phone='{$row['Phone']}'
                            data-idbook='{$row['ID_Book']}'
                            data-getdate='{$row['Getting_Date']}'
                            data-returndate='{$row['Returning_Date']}'>
                                <td>{$row['ID_Reader']}</td>
                                <td>{$row['FirstName']}</td>
                                <td>{$row['LastName']}</td>
                                <td>{$row['Adress']}</td>
                                <td>{$row['Phone']}</td>
                                <td>{$row['ID_Book']}</td>
                                <td>{$row['Getting_Date']}</td>
                                <td>{$row['Returning_Date']}</td>
                                <td>
                                    <form method='POST' style='margin:0'>
                                        <input type='hidden' name='delete_id' value='{$row["ID_Book"]}'>
                                        <button type='submit' class='delete_button' onclick=\"event.stopPropagation(); return window.confirm('Are you sure you want to delete this reader? This action cannot be undone.');\">❌</button>
                                    </form>
                                </td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='9' style='padding:10px'>No results</td></tr>";
                    }
                ?>
            </tbody>

            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    const today = new Date(); // data curentă
                    const rows = document.querySelectorAll(".reader-row");

                    rows.forEach(row => {
                        const returnDateStr = row.getAttribute("data-returndate");
                        if (!returnDateStr) return;

                        // transformă în obiect Date (funcționează dacă formatul este YYYY-MM-DD)
                        const returnDate = new Date(returnDateStr);

                        // comparăm doar partea de dată (fără ore)
                        const todayDate = new Date(today.getFullYear(), today.getMonth(), today.getDate());
                        const compareDate = new Date(returnDate.getFullYear(), returnDate.getMonth(), returnDate.getDate());

                        // dacă termenul de returnare a expirat
                        if (compareDate < todayDate) {
                            row.style.backgroundColor = "#ffb3b3"; // roșu deschis
                        }
                    });
                });
            </script>

        </table>

        <div id="addModal" class="modal">
            <div class="modal-content">
                <span class="close" id="closeModalBtn">&times;</span>
                <h3>Add New Reader</h3>
                <form id="readerForm">
                    <label>ID Reader:</label>
                    <input type="text" name="id_reader" id="add-id_reader" required>
                    <label>First Name:</label>
                    <input type="text" name="first_name" id="add-first_name" required>
                    <label>Last Name:</label>
                    <input type="text" name="last_name" id="add-last_name" required>
                    <label>Adress:</label>
                    <input type="text" name="adress" id="add-adress" required>
                    <label>Phone:</label>
                    <input type="text" name="phone" id="add-phone" required>
                    <label>Book ID:</label>
                    <input type="number" name="book_id" id="add-book_id" required>             
                    <label>Getting Date:</label>
                    <input type="date" name="getting_date" id="add-getting_date" required>
                    <label>Returning Date:</label>
                    <input type="date" name="returning_date" id="add-returning_date" required>

                    <button type="submit" name="reader" class="submit-btn">Submit</button>
                </form>
            </div>
        </div>

        <div id="editModal" class="modal">
            <div class="modal-content">
                <span class="close" id="closeEditModalBtn">&times;</span>
                <h3>Edit Reader</h3>
                <form id="editForm">
                    <label>ID Reader:</label>
                    <input type="text" name="id_reader" id="edit-idreader" require>
                    <label>ID Book:</label>
                    <input type="text" name="id_book" id="edit-idbook" readonly>
                    <label>First Name:</label>
                    <input type="text" name="first_name" id="edit-first_name" required>
                    <label>Last Name:</label>
                    <input type="text" name="last_name" id="edit-last_name" required>
                    <label>Adress:</label>
                    <input type="text" name="adress" id="edit-adress" required>
                    <label>Phone:</label>
                    <input type="text" name="phone" id="edit-phone" required>
                    <label>Getting Date:</label>
                    <input type="date" name="getting_date" id="edit-getting_date" required>
                    <label>Returning Date:</label>
                    <input type="date" name="returning_date" id="edit-returning_date" required>

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
        const form = document.getElementById("readerForm");
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

            // read values from inputs     
            const readerId = document.getElementById('add-id_reader').value;   
            const bookId = document.getElementById('add-book_id').value;
            const firstName = document.getElementById('add-first_name').value;
            const lastName = document.getElementById('add-last_name').value;
            const adress = document.getElementById('add-adress').value;
            const phone = document.getElementById('add-phone').value;
            const gettingDate = document.getElementById('add-getting_date').value;
            const returningDate = document.getElementById('add-returning_date').value;

            // Trimite datele la PHP
            const response = await fetch("add_reader.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: new URLSearchParams({
                    readerid: readerId,
                    bookid: bookId,
                    firstname: firstName,
                    lastname: lastName,
                    adress: adress,
                    phone: phone,
                    gettingdate: gettingDate,
                    returningdate: returningDate
                })
            });

            const result = await response.text();
            console.log("Server response:", result); // 👈 vezi ce vine din PHP

            if (result.trim() === "success") {
                // Adaugă vizual reader în tabel (fără reîncărcare)
                const row = document.createElement("tr");
                row.innerHTML = `
                    <td>${readerId}</td>
                    <td>${firstName}</td>
                    <td>${lastName}</td>
                    <td>${adress}</td>
                    <td>${phone}</td>
                    <td>${bookId}</td>
                    <td>${gettingDate}</td>
                    <td>${returningDate}</td>

                    <td>
                        <form method='POST' style='margin:0'>
                            <input type='hidden' name='delete_id' value=''>
                            <button type='submit' class='delete_button' onclick=\"event.stopPropagation(); return window.confirm('Are you sure you want to delete this reader? This action cannot be undone.');\">❌</button>
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


        const rows = document.querySelectorAll(".reader-row");
        const editModal = document.getElementById("editModal");
        const closeEditModalBtn = document.getElementById("closeEditModalBtn");

        rows.forEach(row => {
        row.addEventListener("click", () => {
            // preia valorile din data-*
            document.getElementById("edit-idreader").value = row.dataset.idread;
            document.getElementById("edit-idbook").value = row.dataset.idbook;
            document.getElementById("edit-first_name").value = row.dataset.fname;
            document.getElementById("edit-last_name").value = row.dataset.lname;
            document.getElementById("edit-adress").value = row.dataset.adress;
            document.getElementById("edit-phone").value = row.dataset.phone;
            document.getElementById("edit-getting_date").value = row.dataset.getdate;
            document.getElementById("edit-returning_date").value = row.dataset.returndate;

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
            const response = await fetch("edit_reader.php", {
                method: "POST",
                body: formData
            });
            const result = await response.text();
            if (result.trim() === "success") {
                alert("Reader updated successfully!");
                editModal.style.display = "none";
                location.reload();
            } 
            else if (result.trim() === "connected") {
                alert("Received 'connected' from server. Check server-side code.");
            }
            else {
                console.log(result.trim());
            }
        });


        const searchForm = document.getElementById("searchForm");
        searchForm.addEventListener("submit", (e) => {
            const filter = document.getElementById("filter").value.toLowerCase();
            const query = document.getElementById("search").value.toLowerCase();

            const rows = document.querySelectorAll("#data-table tbody tr");
            rows.forEach(row => {
                let text = "";
                if (filter === "reader") text = row.cells[0].innerText.toLowerCase();
                else if (filter === "book") text = row.cells[5].innerText.toLowerCase();

                if (text.includes(query)) row.style.display = "";
                else row.style.display = "none";  
            });

            e.preventDefault();
        });


        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        const searchQuary = urlParams.get("results");
        if(searchQuary === 'delays') {
            document.getElementsByClassName("section-name")[0].innerHTML = "Delays";

            document.addEventListener("DOMContentLoaded", function() {
                const today = new Date(); // data curentă
                const rows = document.querySelectorAll(".reader-row");

                rows.forEach(row => {
                    const returnDateStr = row.getAttribute("data-returndate");
                    if (!returnDateStr) return;

                    // transformă în obiect Date (funcționează dacă formatul este YYYY-MM-DD)
                    const returnDate = new Date(returnDateStr);

                    // comparăm doar partea de dată (fără ore)
                    const todayDate = new Date(today.getFullYear(), today.getMonth(), today.getDate());
                    const compareDate = new Date(returnDate.getFullYear(), returnDate.getMonth(), returnDate.getDate());

                    if (compareDate > todayDate) {
                        row.style.display = "none";
                    }
                    else {
                        row.style.backgroundColor = "";
                    }
                })
            });
        }

    </script>
</body>
</html>



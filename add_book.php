<?php
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $house = $_POST['publishing_house'];
    $price = $_POST['price'];

    $sql = "INSERT INTO books (Title, Author, Publishing_House, Price)
            VALUES ('$title', '$author', '$house', '$price')";
    $conn->query($sql);

    echo "success";
}
?>

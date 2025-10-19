<?php
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $author = $_POST['author'];
    $house = $_POST['publishing_house'];
    $price = $_POST['price'];

    $stmt = $conn->prepare("UPDATE books 
                            SET Title=?, Author=?, Publishing_House=?, Price=? 
                            WHERE ID_Book=?");
    $stmt->bind_param("sssdi", $title, $author, $house, $price, $id);
    $stmt->execute();

    echo "success";
}
?>

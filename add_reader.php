<?php
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $idreader = $_POST['readerid'];
    $first_name = $_POST['firstname'];
    $last_name = $_POST['lastname'];
    $adress = $_POST['adress'];
    $phone = $_POST['phone'];
    $idbook = $_POST['bookid'];
    $getting_date = $_POST['gettingdate'];
    $returning_date = $_POST['returningdate'];

    $sql = "INSERT INTO readers (ID_Reader, FirstName, LastName, Adress, Phone, ID_Book, Getting_Date, Returning_Date)
            VALUES ('$idreader', '$first_name', '$last_name', '$adress', '$phone', '$idbook', '$getting_date', '$returning_date')";

    if ($conn->query($sql) === TRUE) {
        echo "success";
    } else {
        // trimitem eroarea completă spre JS
        echo "error: " . $conn->error;
    }
}
?>

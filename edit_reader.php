<?php
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $idreader = $_POST['id_reader'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $adress = $_POST['adress'];
    $idbook = $_POST['id_book'];
    $getting_date = $_POST['getting_date'];
    $returning_date = $_POST['returning_date'];

    // Actualizăm tabelul corespunzător
    $stmt = $conn->prepare("UPDATE readers 
                            SET ID_Reader=?, FirstName=?, LastName=?, Adress=?, 
                                Getting_Date=?, Returning_Date=? 
                            WHERE ID_Book=?");

    $stmt->bind_param("isssssi", 
                      $idreader, $first_name, $last_name, $adress, 
                      $getting_date, $returning_date, $idbook);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>

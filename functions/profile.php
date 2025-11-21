<?php

function getInformation($conn, $id)
{
    $sql = "SELECT username, email, password, full_name, updated_at 
            FROM finance_management.users 
            WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        return $row;
    } else {
        return false;
    }
}
function updateInformation($conn, $id, $username, $password, $full_name)
{

    if (!empty($password)) {
        $sql = "UPDATE users 
                SET username = ?, password = ?, full_name = ?, updated_at = NOW() 
                WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssi", $username, $password, $full_name, $id);
    } else {
        $sql = "UPDATE users 
                SET username = ?, full_name = ?, updated_at = NOW() 
                WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssi", $username, $full_name, $id);
    }
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}
?>
<?php
    $rowPerPage = 10; // Number of rows to display per page

    $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    if ($currentPage < 1) {
        $currentPage = 1;
    }

    $countSql = "SELECT COUNT(*) FROM alumni";
    $countResult = $conn->query($countSql);
    $totalRows = $countResult->fetch_row()[0]; // Total number of rows in the table
    $totalPages = ceil($totalRows / $rowPerPage); // Calculate total pages for pagination

    // Stay within valid page range
    if ($currentPage > $totalPages && $totalPages > 0) {
        $currentPage = $totalPages;
    }

    $startRow = ($currentPage - 1) * $rowPerPage;

    $sql = "SELECT 
                * 
            FROM 
                alumni 
            INNER JOIN 
                status ON alumni.Status_ID = status.Status_ID
            ORDER BY 
                Alum_ID ASC LIMIT ?, ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $startRow, $rowPerPage);
    $stmt->execute();
    $result = $stmt->get_result();
        
    if($result->num_rows > 0) {
        echo "<table>
        <tr>
            <th colspan='4' class='table-header'>Alumni Information</th>
        </tr>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Status</th>
        </tr>";
        while($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . $row['Alum_ID'] . "</td>
                    <td>" . $row['Alum_LastName'] . ", " . $row['Alum_FirstName'] . "</td>
                    <td>" . $row['Alum_ContactInfo'] . "</td>
                    <td>" . $row['Status_Name'] . "</td>
                </tr>";
        }
        echo "</table>";
    } else {
        echo "No records found.";
    }
?>
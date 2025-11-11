<?php
    $rowPerPage = 10; // Number of rows to display per page

    $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    if ($currentPage < 1) {
        $currentPage = 1;
    }

    $countSql = "SELECT COUNT(*) FROM employment";
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
                employment 
            INNER JOIN 
                alumni ON employment.Alum_ID = alumni.Alum_ID
            INNER JOIN
                job_position ON employment.Position_ID = job_position.Position_ID
            INNER JOIN
                company ON employment.Company_ID = company.Company_ID
            INNER JOIN
                `location` ON employment.Location_ID = location.Location_ID
            ORDER BY 
                employment.Alum_ID ASC LIMIT ?, ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $startRow, $rowPerPage);
    $stmt->execute();
    $result = $stmt->get_result();
        
    if($result->num_rows > 0) {
        echo "<table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Position</th>
            <th>Company, Location</th>
            <th>Start Date</th>
            <th>End Date</th>
        </tr>";
        while($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . $row['Alum_ID'] . "</td>
                    <td>" . $row['Alum_LastName'] . ", " . $row['Alum_FirstName'] . "</td>
                    <td>" . $row['Position_Name'] . "</td>
                    <td>" . $row['Company_Name'] . ", " . $row['Country'] . "</td>
                    <td>" . $row['Start_Date'] . "</td>
                    <td>". $row['End_Date'] . "</td>
                </tr>";
        }
        echo "</table>";
    } else {
        echo "No records found.";
    }
?>
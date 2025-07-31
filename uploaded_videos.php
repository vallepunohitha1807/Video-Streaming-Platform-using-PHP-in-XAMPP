<?php
// Database Connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "stream";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check Connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// DELETE VIDEO LOGIC
if (isset($_GET['delete_id']) && isset($_GET['table'])) {
    $delete_id = intval($_GET['delete_id']); // Ensure ID is an integer
    $table = $_GET['table']; // Get the table name from URL

    // Whitelist allowed table names to prevent SQL injection
    $allowed_tables = ['cartoon', 'gaming', 'news', 'sports', 'videos'];

    if (in_array($table, $allowed_tables)) {
        $stmt = $conn->prepare("DELETE FROM $table WHERE id = ?");
        $stmt->bind_param("i", $delete_id);
        
        if ($stmt->execute()) {
            echo "<script>alert('Video deleted successfully!'); window.location.href='uploaded_videos.php';</script>";
        } else {
            echo "<script>alert('Error deleting video.');</script>";
        }
        
        $stmt->close();
    } else {
        echo "<script>alert('Invalid table name.');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>All Videos</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(120deg, #1f1f2e, #0e0e1a);
            color: #f0f0f0;
            margin: 0;
            padding: 20px;
            text-align: center;
        }

        h2 {
            margin-bottom: 30px;
            font-size: 28px;
            color: #f9f9f9;
        }

        table {
            width: 90%;
            margin: auto;
            border-collapse: collapse;
            background-color: #1c1c2b;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.5);
            overflow: hidden;
        }

        th, td {
            padding: 14px 16px;
            border: 1px solid #2a2a3c;
            text-align: center;
        }

        th {
            background-color: #2e2e45;
            color: #ffffff;
            font-weight: 600;
        }

        tr:nth-child(even) {
            background-color: #24243a;
        }

        tr:hover {
            background-color: #333352;
            transition: 0.3s;
        }

        /* View Button */
        .view-btn {
            background: linear-gradient(135deg, #4e9af1, #60d1ff);
            color: #fff;
            padding: 8px 14px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            font-weight: bold;
            transition: 0.3s;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        }

        .view-btn:hover {
            transform: scale(1.05);
            background: linear-gradient(135deg, #5db8ff, #70e0ff);
        }

        /* Delete Button */
        .delete-btn {
            background: linear-gradient(135deg, #ff5e5e, #ff3c3c);
            color: white;
            padding: 8px 14px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
            transition: 0.3s;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        }

        .delete-btn:hover {
            background: linear-gradient(135deg, #e60000, #ff3333);
            transform: scale(1.05);
        }

        /* Dashboard Button */
        .dashboard-btn {
            background: linear-gradient(135deg, #00c851, #33b35a);
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 40px;
            transition: 0.3s;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.3);
        }

        .dashboard-btn:hover {
            background: linear-gradient(135deg, #00a244, #28a745);
            transform: scale(1.05);
        }
    </style>
</head>
<body>

<h2>All Uploaded Videos</h2>

<?php
$sql = "
    SELECT 'cartoon' AS category, id, title, uploaded_by, file_path, upload_date AS uploaded_at FROM cartoon
    UNION ALL
    SELECT 'gaming' AS category, id, title, uploaded_by, file_path, uploaded_at FROM gaming
    UNION ALL
    SELECT 'news' AS category, id, title, uploaded_by, file_path, uploaded_at FROM news
    UNION ALL
    SELECT 'sports' AS category, id, title, uploaded_by, file_path, uploaded_at FROM sports
    UNION ALL
    SELECT 'videos' AS category, id, title, uploaded_by, file_path, uploaded_at FROM videos
    ORDER BY uploaded_at DESC
";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Uploaded By</th>
                <th>Category</th>
                <th>File</th>
                <th>Uploaded At</th>
                <th>Action</th>
            </tr>";
    
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['title']}</td>
                <td>{$row['uploaded_by']}</td>
                <td>{$row['category']}</td>
                <td><a href='{$row['file_path']}' target='_blank' class='view-btn'>View</a></td>
                <td>{$row['uploaded_at']}</td>
                <td>
                    <a href='uploaded_videos.php?delete_id={$row['id']}&table={$row['category']}'
                       class='delete-btn'
                       onclick='return confirm(\"Are you sure you want to delete this video?\");'>
                       Delete
                    </a>
                </td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "<p>No videos found.</p>";
}

$conn->close();
?>

<!-- Dashboard Button -->
<br><br>
<button class="dashboard-btn" onclick="window.location.href='admin.php'">Dashboard</button>

</body>
</html>

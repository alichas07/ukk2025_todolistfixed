<?php
require_once "config.php";

// Search & Filter
$search = $_GET['search'] ?? '';
$filter_priority = $_GET['priority_filter'] ?? '';  
$filter_status = $_GET['status_filter'] ?? '';     

// Tambah Task
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_task'])) {
    $task = trim($_POST['task'] ?? '');
    $priority = trim($_POST['priority'] ?? '');
    $due_date = trim($_POST['due_date'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if (!empty($task) && !empty($priority) && !empty($due_date) && !empty($description)) {
        if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $due_date)) {
            echo "<script>alert('Format tanggal tidak valid!');</script>";
        } elseif (strtotime($due_date) < strtotime(date("Y-m-d"))) {
            echo "<script>alert('Tanggal tidak boleh lebih kecil dari hari ini!');</script>";
        } else {
            $stmt = $conn->prepare("INSERT INTO task (task, priority, due_date, description, status) VALUES (?, ?, ?, ?, 0)");
            $stmt->bind_param("ssss", $task, $priority, $due_date, $description);
            if ($stmt->execute()) {
                header("Location: index.php");
                exit;
            } else {
                echo "<script>alert('Gagal menambahkan tugas!');</script>";
            }
        }
    } else {
        echo "<script>alert('Semua kolom harus diisi!');</script>";
    }
}

// Update Status Task
if (isset($_GET['complete'])) {
    $id = intval($_GET['complete']);
    $stmt = $conn->prepare("UPDATE task SET status = '1' WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: index.php");
    exit;
}

if (isset($_GET['undo'])) {
    $id = intval($_GET['undo']);
    $stmt = $conn->prepare("UPDATE task SET status = '0' WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: index.php");
    exit;
}

// Edit Task
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_task'])) {
    $id = intval($_POST['edit_id'] ?? 0);
    $task = trim($_POST['edit_task'] ?? '');
    $priority = trim($_POST['edit_priority'] ?? '');
    $due_date = trim($_POST['edit_date'] ?? ''); // Tambahkan ini
    $description = trim($_POST['edit_description'] ?? '');

    if (!empty($task) && !empty($priority) && !empty($due_date) && !empty($description)) {
        if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $due_date)) {
            echo "<script>alert('Format tanggal tidak valid!');</script>";
        } elseif (strtotime($due_date) < strtotime(date("Y-m-d"))) {
            echo "<script>alert('Tanggal tidak boleh lebih kecil dari hari ini!');</script>";
        } else {
            $stmt = $conn->prepare("UPDATE task SET task = ?, priority = ?, due_date = ?, description = ? WHERE id = ?");
            $stmt->bind_param("ssssi", $task, $priority, $due_date, $description, $id);
            $stmt->execute();
            header("Location: index.php");
            exit;
        }
    } else {
        echo "<script>alert('Semua kolom harus diisi!');</script>";
    }
}

// Hapus Task
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM task WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: index.php");
    exit;
}

// Pagination
$limit = 10;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $limit;

// Query dengan filter
$where = "WHERE 1=1";
$params = [];
$types = "";

if (!empty($search)) {
    $where .= " AND task LIKE ?";
    $params[] = "%$search%";
    $types .= "s";
}
if (!empty($filter_priority)) {
    $where .= " AND priority = ?";
    $params[] = $filter_priority;
    $types .= "s";
}
if ($filter_status !== '') {
    $where .= " AND status = ?";
    $params[] = $filter_status;
    $types .= "s";
}

// Hitung total data untuk pagination
$total_query = "SELECT COUNT(*) as total FROM task $where";
$stmt = $conn->prepare($total_query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
$total_data = $result->fetch_assoc()['total'];
$total_tasks = $total_data; 
$total_pages = ceil($total_data / $limit);

// Ambil data dengan limit dan offset - diurutkan berdasarkan status (belum selesai dulu), lalu prioritas, lalu deadline
$query = "SELECT * FROM task $where ORDER BY 
          CASE WHEN status = 0 THEN 0 ELSE 1 END,  -- Belum selesai dulu
          CASE WHEN status = 0 THEN priority END ASC,  -- Untuk task belum selesai, urutkan prioritas
          CASE WHEN status = 0 THEN due_date END ASC,  -- Untuk task belum selesai, urutkan deadline
          CASE WHEN status = 1 THEN id END DESC  -- Untuk task selesai, urutkan yang terbaru di bawah
          LIMIT ? OFFSET ?";
$params[] = $limit;
$params[] = $offset;
$types .= "ii";

$stmt = $conn->prepare($query);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();
?>
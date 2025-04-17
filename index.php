<?php
require_once "task_functions.php";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi To Do List Fixed</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary: #5e60ce;
            --primary-dark: #4e4ec4;
            --secondary: #6930c3;
            --success: #4cc9f0;
            --danger: #f72585;
            --warning: #f8961e;
            --info: #4895ef;
            --dark: #212529;
            --light: #f8f9fa;
            --gray: #6c757d;
            --bg-color: #f0f2f5;
            --card-bg: #ffffff;
            --text-dark: #343a40;
            --text-light: #f8f9fa;
            --accent: #80ffdb;
            --muted: #adb5bd;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-dark);
            line-height: 1.6;
            font-size: 0.9rem;
        }
        
        .app-container {
            max-width: 1400px;
            margin: 1.5rem auto;
            padding: 0 15px;
        }
        
        .app-header {
            text-align: center;
            margin-bottom: 2rem;
            position: relative;
        }
        
        .app-header h1 {
            font-weight: 700;
            color: var(--primary);
            position: relative;
            display: inline-block;
            font-size: 1.5rem;
        }
        
        .app-header p {
            font-size: 0.9rem;
            color: var(--muted);
        }
        
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            background-color: var(--card-bg);
            margin-bottom: 1.25rem;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }
        
        .card-header {
            background-color: rgba(94, 96, 206, 0.05);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            font-weight: 600;
            padding: 1rem 1.25rem;
            border-radius: 12px 12px 0 0 !important;
            font-size: 0.95rem;
            color: var(--primary);
        }
        
        .card-body {
            padding: 1.25rem;
        }
        
        .form-control, .form-select {
            border-radius: 8px;
            padding: 0.6rem 0.9rem;
            border: 1px solid #e0e0e0;
            transition: all 0.3s ease;
            font-size: 0.85rem;
            background-color: var(--light);
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(94, 96, 206, 0.15);
        }
        
        .btn {
            border-radius: 8px;
            font-weight: 500;
            padding: 0.6rem 1.25rem;
            transition: all 0.3s ease;
            font-size: 0.85rem;
        }
        
        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
        }
        
        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
        }
        
        .btn-outline-primary {
            color: var(--primary);
            border-color: var(--primary);
        }
        
        .btn-outline-primary:hover {
            background-color: var(--primary);
            color: white;
        }
        
        /* Tabel */
        .table {
            font-size: 0.85rem;
        }

        .table th, .table td {
            padding: 0.8rem;
            white-space: nowrap;
            border-color: rgba(0, 0, 0, 0.05);
        }
        
        .table th {
            background-color: rgba(94, 96, 206, 0.05);
            color: var(--primary);
        }
        
        .table tr:hover td {
            background-color: rgba(94, 96, 206, 0.05);
        }
        
        .badge {
            font-weight: 500;
            padding: 0.4em 0.7em;
            border-radius: 6px;
            font-size: 0.8rem;
        }
        
        .badge.bg-primary {
            background-color: var(--primary) !important;
        }
        
        .action-buttons {
            display: flex;
            gap: 0.3rem;
            flex-wrap: nowrap;
        }

        .action-buttons .btn {
            width: 28px;
            height: 28px;
            padding: 0;
            font-size: 0.8rem;
        }

        .action-buttons .btn-sm {
            min-width: 28px;
            height: 28px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.25rem !important;
        }

        .action-buttons .bi {
            font-size: 0.9rem;
        }
        
        /* Prioritas dengan icon lebih kecil */
        .priority-high, .priority-medium, .priority-low {
            font-size: 0.85rem;
        }

        .priority-high {
            color: var(--danger);
            font-weight: 600;
        }
        
        .priority-medium {
            color: var(--warning);
            font-weight: 600;
        }
        
        .priority-low {
            color: var(--success);
            font-weight: 600;
        }
        
        .search-filter-container {
            background-color: var(--card-bg);
            padding: 1rem;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            margin-bottom: 1.25rem;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .task-completed {
            text-decoration: line-through;
            color: var(--muted);
        }
        
        .due-date {
            font-size: 0.85rem;
            color: var(--muted);
        }
        
        /* Modal */
        .modal-title {
            font-size: 1.1rem;
            color: var(--primary);
        }
        
        .modal-body {
            font-size: 0.9rem;
        }
        
        /* Pagination */
        .pagination {
            font-size: 0.85rem;
            padding: 0.4rem 0.75rem;
            color: var(--light);
        }
        
        .page-link {
            font-size: 0.85rem;
            padding: 0.4rem 0.75rem;
            color: var(--primary);
        }

        .pagination .page-item.active .page-link {
            background-color: var(--primary);
            border-color: var(--primary);
        }
        
        .input-group-text {
            background-color: var(--light);
            border-color: #e0e0e0;
        }
        
        @media (max-width: 768px) {
            .app-container {
                margin: 1rem auto;
            }
            
            .app-header h1 {
                font-size: 1.3rem;
            }
            
            .action-buttons .btn {
                margin-bottom: 5px;
            }

            .action-buttons .btn-sm {
                min-width: 24px;
                height: 24px;
            }
            
            .action-buttons .bi {
                font-size: 0.8rem;
            }

            .table-responsive {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
            
            .table {
                font-size: 0.8rem;
            }
            
            body {
                font-size: 0.85rem;
            }
        }
    </style>
</head>

<body>
    <div class="app-container">
        <div class="app-header">
            <h1><i class="title"></i>To Do List</h1>
            <p class="text-muted">Kelola tugas Anda dengan mudah dan efisien</p>
        </div>
        
        <div class="row">
            <!-- Form Tambah Task -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <i class="bi bi-plus-circle-fill me-2"></i>
                        <span>Tambah Task Baru</span>
                    </div>
                    <div class="card-body">
                        <form method="post" action="index.php">
                            <div class="mb-3">
                                <label for="task" class="form-label">Nama Task</label>
                                <input type="text" class="form-control" id="task" name="task" placeholder="Masukkan nama task" required>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Deskripsi</label>
                                <textarea class="form-control" id="description" name="description" rows="3" placeholder="Tambahkan deskripsi task" required></textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="priority" class="form-label">Prioritas</label>
                                    <select class="form-select" id="priority" name="priority">
                                        <option value="1">Tinggi</option>
                                        <option value="2" selected>Sedang</option>
                                        <option value="3">Rendah</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="due_date" class="form-label">Tanggal Deadline</label>
                                    <input type="date" class="form-control" id="due_date" name="due_date" required>
                                </div>
                            </div>
                            <button type="submit" name="add_task" class="btn btn-primary w-100 mt-2">
                                <i class="bi bi-plus-lg me-2"></i>Tambah Task
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Daftar Task -->
            <div class="col-lg-8">
                <div class="search-filter-container">
                    <form method="GET" action="" class="row g-2 align-items-center">
                        <div class="col-md-5">
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                                <input type="text" class="form-control" id="search" name="search" placeholder="Cari tugas..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="priority_filter" name="priority_filter">
                                <option value="">Semua Prioritas</option>
                                <option value="1" <?= (isset($_GET['priority_filter']) && $_GET['priority_filter'] == '1') ? 'selected' : '' ?>>Tinggi</option>
                                <option value="2" <?= (isset($_GET['priority_filter']) && $_GET['priority_filter'] == '2') ? 'selected' : '' ?>>Sedang</option>
                                <option value="3" <?= (isset($_GET['priority_filter']) && $_GET['priority_filter'] == '3') ? 'selected' : '' ?>>Rendah</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="status_filter" name="status_filter">
                                <option value="">Semua Status</option>
                                <option value="1" <?= (isset($_GET['status_filter']) && $_GET['status_filter'] == '1') ? 'selected' : '' ?>>Selesai</option>
                                <option value="0" <?= (isset($_GET['status_filter']) && $_GET['status_filter'] == '0') ? 'selected' : '' ?>>Belum Selesai</option>
                            </select>
                        </div>
                        <div class="col-md-1">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-funnel"></i>
                            </button>
                        </div>
                    </form>
                </div>
                
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <i class="bi bi-list-task me-2"></i>
                            <span>Daftar Task Anda</span>
                        </div>
                        <span class="badge bg-primary"><?= $total_data ?> Task</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="25%">Task</th>
                                        <th width="25%">Deskripsi</th>
                                        <th width="10%">Prioritas</th>
                                        <th width="15%">Deadline</th>
                                        <th width="10%">Status</th>
                                        <th width="10%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; while ($row = $result->fetch_assoc()) { ?>
                                        <tr class="<?= $row['status'] == 1 ? 'task-completed' : '' ?>">
                                            <td><?= $no++; ?></td>
                                            <td><strong><?= htmlspecialchars($row['task']); ?></strong></td>
                                            <td><?= htmlspecialchars($row['description']); ?></td>
                                            <td>
                                                <?php 
                                                    if ($row['priority'] == '1') {
                                                        echo '<span class="priority-high"><i class="bi bi-chevron-double-up me-1"></i>Tinggi</span>';
                                                    } elseif ($row['priority'] == '2') {
                                                        echo '<span class="priority-medium"><i class="bi bi-chevron-up me-1"></i>Sedang</span>';
                                                    } else {
                                                        echo '<span class="priority-low"><i class="bi bi-chevron-down me-1"></i>Rendah</span>';
                                                    }
                                                ?>
                                            </td>
                                            <td>
                                                <span class="due-date">
                                                    <i class="bi bi-calendar-event me-1"></i>
                                                    <?= date('d M Y', strtotime($row['due_date'])); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?= ($row['status'] == 1) ? 
                                                    '<span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Selesai</span>' : 
                                                    '<span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Belum</span>'; ?>
                                            </td>
                                            <td class="action-buttons">
                                                <div class="d-flex gap-1 flex-nowrap">
                                                    <?php if ($row['status'] == 0) { ?>
                                                        <a href="?complete=<?= $row['id'] ?>" class="btn btn-success btn-sm p-1" 
                                                        title="Tandai Selesai"
                                                        onclick="return confirm('Tandai task ini sebagai selesai?');">
                                                            <i class="bi bi-check-lg fs-6"></i>
                                                        </a>
                                                    <?php } else { ?>
                                                        <a href="?undo=<?= $row['id'] ?>" class="btn btn-warning btn-sm p-1"
                                                        title="Batalkan Status"
                                                        onclick="return confirm('Batalkan status selesai untuk task ini?');">
                                                            <i class="bi bi-arrow-counterclockwise fs-6"></i>
                                                        </a>
                                                    <?php } ?>
                                                    
                                                    <a href="#" class="btn btn-primary btn-sm p-1 edit-task-btn" 
                                                    title="Edit Task"
                                                    data-id="<?= $row['id']; ?>" 
                                                    data-task="<?= htmlspecialchars($row['task']); ?>" 
                                                    data-description="<?= htmlspecialchars($row['description']); ?>" 
                                                    data-priority="<?= $row['priority']; ?>" 
                                                    data-date="<?= $row['due_date']; ?>">
                                                        <i class="bi bi-pencil fs-6"></i>
                                                    </a>
                                                    
                                                    <a href="?delete=<?= $row['id'] ?>" class="btn btn-danger btn-sm p-1" 
                                                    title="Hapus Task"
                                                    onclick="return confirm('Hapus task ini secara permanen?');">
                                                        <i class="bi bi-trash fs-6"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <!-- Pagination -->
                <nav aria-label="Page navigation" class="mt-3">
                    <ul class="pagination justify-content-center">
                        <?php if ($page > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?= $page - 1; ?>" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?= ($page == $i) ? 'active' : ''; ?>">
                                <a class="page-link" href="?page=<?= $i; ?>"><?= $i; ?></a>
                            </li>
                        <?php endfor; ?>
                        
                        <?php if ($page < $total_pages): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?= $page + 1; ?>" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Edit Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="index.php" method="POST">
                        <input type="hidden" id="edit_id" name="edit_id">
                        <div class="mb-3">
                            <label class="form-label">Nama Task</label>
                            <input type="text" id="edit_task" name="edit_task" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea id="edit_description" name="edit_description" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Prioritas</label>
                                <select id="edit_priority" name="edit_priority" class="form-select" required>
                                    <option value="1">Tinggi</option>
                                    <option value="2">Sedang</option>
                                    <option value="3">Rendah</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tanggal Deadline</label>
                                <input type="date" id="edit_date" name="edit_date" class="form-control" required>
                            </div>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" name="update_task" class="btn btn-primary">
                                <i class="bi bi-save me-2"></i>Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Edit Modal Handler
            const editButtons = document.querySelectorAll(".edit-task-btn");
            
            editButtons.forEach(button => {
                button.addEventListener("click", function() {
                    document.getElementById("edit_id").value = this.getAttribute("data-id");
                    document.getElementById("edit_task").value = this.getAttribute("data-task");
                    document.getElementById("edit_description").value = this.getAttribute("data-description");
                    document.getElementById("edit_priority").value = this.getAttribute("data-priority");
                    
                    let dueDate = this.getAttribute("data-date");
                    document.getElementById("edit_date").value = dueDate;
                    
                    let today = new Date().toISOString().split('T')[0];
                    document.getElementById("edit_date").setAttribute("min", today);
                    
                    let editModal = new bootstrap.Modal(document.getElementById("editModal"));
                    editModal.show();
                });
            });
            
            // Set today's date as default for new tasks
            document.getElementById("due_date").min = new Date().toISOString().split('T')[0];
        });
    </script>
</body>
</html>
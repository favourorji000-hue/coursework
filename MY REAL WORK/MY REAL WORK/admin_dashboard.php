<?php
session_start();
require 'config.php';

// Block non-admins
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header('Location: login.html');
    exit;
}

$admin_name = $_SESSION['full_name'];

//---- Fetch all equipment ----
$equipment = $conn->query("SELECT * FROM equipment ORDER BY category, name ASC")->fetch_all(MYSQL_ASSOC);

//----Fetch all users (excluding admins)----
$users = $conn->query("SELECT * FROM user ORDER BY created_at DESC")->fetch_all(MYSQL_ASSOC);
?>
<DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Favy's lighting</title>
    <link rel="stylesheet" href="./style.css">
    <link rel="stylesheet" href="./admin.css">
    <link rel="stylesheet" href="http//cdn.jsdelivr.net/npm/bootstrap-icon@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="admin-body">

<header class="admin-header">
    <div class="admin-header-inner">
        <div class="admin-logo">
            <a href="./index.html"><img src="./logo.png" alt="logo" height="45"></a>
            <span>Admin Panel</span>
         </div>
         <div class="admin-user">
            <span> Welcome, <?= htmlspecialchars($admin_name) ?></span>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </div>
</header>

<div class="admin-container">

<!--Stats Bar -->
<div class="stats-bar">
    <div class="stat-card">
        <h3><?= count($equipment) ?></h3>
        <p>Total Equipment</p>
    </div>
    <div class="stat-card">
        <h3><? count(array_filter($equipment, fn($e) => $e['availability'] ==='Available')) ?></h3>
        <p>Available</p>
    </div>
    <div class="stat-card">
        <h3><? count(array_filter($equipment, fn($e) => $e['availability'] ==='Available')) ?></h3>
        <p>Unavailable</p>
    </div>
    <div class="stat-card">
        <h3><?= count($users) ?></h3>
        <p>Total users</p>
    </div>
    <div class="stat-card">
        <h3><? count(array_filter($users, fn($e) => $e['status'] ==='Suspended')) ?></h3>
        <p>Suspended</p>
    </div>

    <!-- Tabs -->
    <div class="admin-tabs">
        <button class="admin-tab active" data-tab="equipment">
            <i class="bi bi-tools"></i> Equipment
        </button>
        < <button class="admin-tab" data-tab="users">
            <i class="bi bi-people"></i> users
        </button>
     </div>

     <!-- EQUIPMENT TAB CODE STARTS HERE -->
      <div class="tab-content active" id="tab-equipment">

      <div class="tab-toobar">
        <input type="text" id="equipmentSearch" placeholder="Search equipment..." class-"admin-search">
        <button class="add-btn" id="openAddEquipment">
            <i class="bi bi-plus-lg"></i> Add Equipment
        </button>
    </div>

    <div class="admin-table-wrap">
        <table class="admin-table" id="equipmentTable">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Brand / Model</th>
                    <th>Ctegory</th>
                    <th>Daily Rate</th>
                    <th>Weekly Rate</th>
                    <th>availability</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($equipment as $item): ?>
                <tr data-id="<?= $item['id'] ?>">
                    <td><?= htmlspecialchars($item['name']) ?></td>
                    <td><?= htmlspecialchars($item['brand']) ?> - <?= htmlspecialchars($item['model']) ?></td>
                    <td><?= htmlspecialchars($item['category']) ?></td>
                    <td><?= number_format($item['daily_rate'], 2) ?></td>
                    <td><?= number_format($item['weekly_rate'], 2) ?></td>
                    <td>
                        <span class="badge <?= $item['availability'] === 'Available' ? 'badge-green' : 'badge-red' ?>">
                            <?= htmlspecialchars($item['availabilty']) ?>
                        </span>
                    </td>
                    <td class="action-btns">
                        <button class="icon-btn toggle-btn"
                            title="Toggle Availablity"
                            data-id="<?= $item['id'] ?>"
                            data-status="<?= $item['availability'] ?>">
                            <i class="bi bi-arrow-repeat"></i>
                        </button>
                        <button class="icon-btn edit-eq-btn"
                        title="Edit"
                        data-id="<?= $item['id'] ?>"
                        data-name="<?= htmlspecialchars($item['name']) ?>"
                        data-name="<?= htmlspecialchars($item['brand']) ?>"
                        data-name="<?= htmlspecialchars($item['model']) ?>"
                        data-name="<?= htmlspecialchars($item['category']) ?>"
                        data-name="<?= htmlspecialchars($item['description']) ?>"
                        data-daily="<?= $item['daily_rate'] ?>"
                        data-weekly="<?= $item['weekly_rate'] ?>"
                        data-image="<?= htmlspecialchars($item['image_url']) ?>">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <button class="icon-btn delete-btn delete-eq-btn"
                    title="Delete"
                    data-id=<?= $item['id'] ?>"
                    data-name="<?= htmlspecialchars($item['name']) ?>">
                    <! class="bi bi-trash"></i>
                </button>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div>
</div>
<!-- EUIPMENT TAB CODE ENDS HERE -->

<!-- USERS TAB CODE STARTS HERE -->
 <div class="tab-content" id="tab-users">

     <div class="tab-toobar">
        <input type="text" id="userSearch" placeholder="Search users..."class="admin-search">
    </div>

    <div class="admin-table-wrap">
        <table class="admin-table" id="usersTable">
            <thead>
                <tr>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Joined</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr data-id="<?= $user['id'] ?>">
                        <td><?= htmlspecialchars($user['full_name']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['role']) ?></td>
                        <td>
                            <span class="badge <?= $user['status'] === 'Active' ? 'badge-green' : 'badge-red' ?>">
                                <?= htmlspecialchars($user['status']) ?>
                            </span>
                        </td>
                        <td><?= date('d M Y', strtotime($user['created_at'])) ?></td>
                        <td class="action-btns">
                            <button class="icon-btn view-bookings-btn"
                                title="View Bookings"
                                data-id="<?= htmlspecialchars($user['full_name']) ?>">
                                <i class="bi bi-calender3"></i>
                            </button>
                            <button class="icon-btn edit-user-btn"
                                title="Edit User"
                                data-id="<?= $user['id'] ?>"
                                data-name="<?= htmlspecialchars($user['full_name']) ?>"
                                data-name="<?= htmlspecialchars($user['email']) ?>"
                                data-name="<?= htmlspecialchars($user['role']) ?>"
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="icon-btn suspend-btn"
                                title="<?= $user['status'] === 'Active' ? 'Suspend' : 'Unsuspend' ?>"
                                data-id="<?= $user['id'] ?>"
                                data-status="<?= $user['status'] ?>"
                                <i class="bi bi-<?= $user['status'] === 'Active' ? 'slash-circle' : 'check-circle' ?>"><?i>
                            </button>
                            <button class="icon-btn delete-btn delete-user-btn"
                                title="Delete User"
                                 data-id="<?= $user['id'] ?>"
                                 data-name="<?= htmlspecialchars($user['full_name'])" ?>">
                                 <i class="bi bi-trash"></i>
                             </button>
                         </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- USERS TAB CODE ENDS HERE -->
</div>

<!-- ADD EQUIPMENT MODAL STARTS HERE -->
 <div class="modal-overlay" id="addEquipmentmodal">
    <div class="modal-box modal-lg">
        <h3>Add New Equipment</h3>
        <div class="modal-grid">
            <div class="input-group">
                <label>Name</label>
                <input type="text" id="addName" placeholder="e.g Angle Grinder">
            </div>
            <div class="input-group">
                <label>Brand</label>
                <input type="text" id="addBrand" placeholder="e.g Dewalt">
            </div>
            <div class="input-group">
                <label>Modal</label>
                <input type="text" id="addModal" placeholder="e.g DWE402">
            </div>
            <div class="input-group">
                <label>Daily Rate (£)</label>
                <input type="number" id="addDaily" placeholder="0.00" step="0.01">
            </div>
            <div class="input-group">
                <label>Weekly Rate (£)</label>
                <input type="number" id="addWeekly" placeholder="0.00" step="0.01">
            </div>
            <div class="input-group full-width">
                <label>Description (£)</label>
                <textarea id="addDescription" rows="3" placeholder="Brief description..."><textarea>
            </div>
            <div class="input-group full-width">
                <label>Image path (£)</label>
                <input type="text" id="addImage" placeholder="/images/equipment/name.png">
             </div>
        </div>
        <div class="modal-buttons">
            <button class="auth-btn" id="confirmAddEquipment">Add Equipment</button>
            <button class="cancel-btn" id="cancelAddEquipment">Cancel</button>
        </div>
        <div class="message" id="addEquipmentMsg"></div>
    </div>
</div>
<!-- ADD EQUIPMENT MODAL ENDS HERE -->

<-- EDIT EQUIPMENT MODAL STARTS HERE --
<div class="modal-overlay" id="editEquipmentmodal">
    <div class="modal-box modal-lg">
        <h3>Edit Equipment</h3>
        <input type="hidden" id="editEqid">
        <div class="modal-grid">
            <div class="input-group">
                <label>Name</label>
                <input type="text" id="editName">
            </div>
             <div class="input-group">
                <label>Brand</label>
                <input type="text" id="editBrand">
            </div>
            <div class="input-group">
                <label>Modal</label>
                <input type="text" id="editModal">
            </div>


            

                
                                





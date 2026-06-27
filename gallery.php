<?php
include "db.php";

$search = $_GET['search'] ?? '';

$query = "SELECT * FROM media_files WHERE file_type='image'";

if ($search != '') {
    $searchSafe = pg_escape_string($conn, $search);
    $query .= " AND file_name ILIKE '%$searchSafe%'";
}

$query .= " ORDER BY id DESC";

$result = pg_query($conn, $query);

$countResult = pg_query(
    $conn,
    "SELECT COUNT(*) as total FROM media_files WHERE file_type='image'"
);

$totalImages = pg_fetch_assoc($countResult)['total'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gallery Dashboard</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #0f172a;
            color: white;
        }

        .navbar {
            background: rgba(30,41,59,0.95);
            backdrop-filter: blur(10px);
            padding: 20px 30px;
            position: sticky;
            top: 0;
            z-index: 100;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }

        .navbar h1 {
            font-size: 24px;
        }

        .container {
            padding: 30px;
        }

        .top-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 20px;
        }

        .card {
            background: rgba(30,41,59,0.9);
            padding: 20px;
            border-radius: 16px;
            min-width: 220px;
        }

        .card h2 {
            font-size: 28px;
            margin-top: 10px;
        }

        .search-box input {
            padding: 12px 15px;
            width: 300px;
            border: none;
            border-radius: 10px;
            outline: none;
        }

        .action-top {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .gallery {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 25px;
        }

        .media-card {
            background: rgba(30,41,59,0.95);
            border-radius: 18px;
            overflow: hidden;
            transition: 0.3s;
        }

        .media-card:hover {
            transform: translateY(-6px);
        }

        .media-card img {
            width: 100%;
            height: 240px;
            object-fit: cover;
        }

        .content {
            padding: 15px;
        }

        .file-name {
            font-size: 14px;
            margin-bottom: 10px;
            word-break: break-word;
        }

        .timestamp {
            font-size: 12px;
            color: #94a3b8;
            margin-bottom: 15px;
        }

        .actions {
            display: flex;
            gap: 10px;
        }

        .btn {
            flex: 1;
            padding: 10px;
            text-align: center;
            border-radius: 8px;
            text-decoration: none;
            color: white;
            font-size: 14px;
            cursor: pointer;
        }

        .view {
            background: #16a34a;
        }

        .download {
            background: #2563eb;
        }

        .delete {
            background: #dc2626;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 999;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.95);
        }

        .modal-content {
            margin: auto;
            display: block;
            max-width: 85%;
            max-height: 85%;
            margin-top: 40px;
        }

        .close {
            position: absolute;
            top: 20px;
            right: 30px;
            font-size: 35px;
            cursor: pointer;
        }
    </style>
</head>
<body>

<div class="navbar">
    <h1>Gallery Dashboard</h1>
</div>

<div class="container">

    <div class="top-section">

        <div class="card">
            Total Images
            <h2><?php echo $totalImages; ?></h2>
        </div>

        <form class="search-box" method="GET">
            <input
                type="text"
                name="search"
                placeholder="Search images..."
                value="<?php echo htmlspecialchars($search); ?>"
            >
        </form>

        <div class="action-top">

            <a href="download_all.php"
               class="btn download"
               style="width:180px;">
                Download All ZIP
            </a>

            <a href="delete_all.php"
               class="btn delete"
               style="width:180px;"
               onclick="return confirm('Delete all images?')">
                Delete All
            </a>

        </div>

    </div>

    <div class="gallery">

        <?php while($row = pg_fetch_assoc($result)) { ?>

            <div class="media-card">

                <img src="<?php echo htmlspecialchars($row['file_url']); ?>">

                <div class="content">

                    <div class="file-name">
                        <?php echo htmlspecialchars($row['file_name']); ?>
                    </div>

                    <div class="timestamp">
                        Uploaded ID: #<?php echo $row['id']; ?>
                    </div>

                    <div class="actions">

                        <a class="btn view"
                           href="javascript:void(0)"
                           onclick="openModal('<?php echo htmlspecialchars($row['file_url']); ?>')">
                            View
                        </a>

                        <a class="btn download"
                           href="<?php echo htmlspecialchars($row['file_url']); ?>"
                           download>
                            Download
                        </a>

                        <a class="btn delete"
                           href="delete.php?id=<?php echo $row['id']; ?>"
                           onclick="return confirm('Delete this image?')">
                            Delete
                        </a>

                    </div>

                </div>

            </div>

        <?php } ?>

    </div>

</div>

<div id="previewModal" class="modal">
    <span class="close" onclick="closeModal()">&times;</span>
    <img class="modal-content" id="modalImage">
</div>

<script>
function openModal(src) {
    document.getElementById("previewModal").style.display = "block";
    document.getElementById("modalImage").src = src;
}

function closeModal() {
    document.getElementById("previewModal").style.display = "none";
}
</script>

</body>
</html>
<?php include('partial_backend/navbar.php'); ?>

<div class="main-content">
    <div class="container">
        <h1>QR Code Generator</h1>
        <br>

        <form action="" method="POST" class="add_food_form" style="max-width: 600px; margin: 0;">
            <h3>Generate Table QR Codes</h3>
            <div class="form-grid grid-2">
                <div class="form-group">
                    <label>Start Table No</label>
                    <input type="number" name="start_table" class="box" value="1" min="1" required>
                </div>
                <div class="form-group">
                    <label>End Table No</label>
                    <input type="number" name="end_table" class="box" value="10" min="1" required>
                </div>
            </div>
            <div class="form-actions">
                <input type="submit" name="generate" value="GENERATE QR" class="add_button">
            </div>
        </form>

        <br><hr><br>

        <?php
            if(isset($_POST['generate'])) {
                $start = $_POST['start_table'];
                $end   = $_POST['end_table'];

                // Detect Dynamic URL
                $path = explode(DIRECTORY_SEPARATOR, dirname(__DIR__)); // Go up one level from 'admin'
                $root_folder = end($path);
                $base_url = "http://" . $_SERVER['HTTP_HOST'] . "/" . $root_folder . "/index.php";

                echo "<div class='qr-grid'>";
                
                for($i = $start; $i <= $end; $i++) {
                    $data = $base_url . "?table=" . $i;
                    $qr_api = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode($data);
                    
                    echo "
                    <div class='qr-card'>
                        <h4>MEJA $i</h4>
                        <img src='$qr_api' alt='QR Meja $i'>
                        <p>Scan to Order</p>
                    </div>
                    ";
                }

                echo "</div>";
                
                echo "
                <br>
                <button onclick='window.print()' class='add_button' style='background: #333;'>PRINT QR CODES</button>
                ";
            }
        ?>

    </div>
</div>

<style>
/* Simple Grid for QP Display */
.qr-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
}

.qr-card {
    border: 1px solid #ddd;
    padding: 20px;
    background: white;
    text-align: center;
    border-radius: 10px;
    width: 200px;
}

.qr-card h4 {
    margin-bottom: 10px;
    font-size: 18px;
    color: var(--tosca);
}

.qr-card p {
    margin-top: 10px;
    font-size: 12px;
    color: #666;
}

/* Print Styles */
@media print {
    body * {
        visibility: hidden;
    }
    .qr-grid, .qr-grid * {
        visibility: visible;
    }
    .qr-grid {
        position: absolute;
        left: 0;
        top: 0;
    }
}
</style>

</body>
</html>

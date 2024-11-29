<?php
// item_details.php - For showing detailed information about a specific item
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Item Details</title>
    <style>
        .details-container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 20px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
        }

        .item-gallery {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }

        .gallery-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
            cursor: pointer;
            transition: transform 0.2s ease;
        }

        .gallery-image:hover {
            transform: scale(1.05);
        }

        .item-info {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
        }

        .info-main h1 {
            margin: 0 0 15px 0;
            color: #2c3e50;
        }

        .info-details {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
        }

        .info-details dl {
            margin: 0;
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 10px;
        }

        .info-details dt {
            font-weight: 600;
            color: #666;
        }

        .info-details dd {
            margin: 0;
        }

        .swap-request {
            background: white;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        .swap-request h3 {
            margin-top: 0;
        }

        .swap-form textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            margin-bottom: 15px;
            resize: vertical;
        }

        .swap-button {
            background: #3498db;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            width: 100%;
        }

        .swap-button:hover {
            background: #2980b9;
        }

        @media (max-width: 768px) {
            .item-info {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <?php
    include 'db_config.php';
    
    $item_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    
    $sql = "SELECT * FROM swap_items WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $item_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($item = $result->fetch_assoc()):
        $images = json_decode($item['images'], true);
    ?>
    
    <div class="details-container">
        <div class="item-gallery">
            <?php foreach ($images as $image): ?>
            <img src="<?php echo htmlspecialchars($image); ?>" alt="Item Image" class="gallery-image">
            <?php endforeach; ?>
        </div>

        <div class="item-info">
            <div class="info-main">
                <h1><?php echo htmlspecialchars($item['name']); ?></h1>
                <div class="info-details">
                    <dl>
                        <dt>Category</dt>
                        <dd><?php echo htmlspecialchars($item['category']); ?></dd>
                        
                        <dt>Condition</dt>
                        <dd><?php echo htmlspecialchars($item['condition_status']); ?></dd>
                        
                        <dt>Brand</dt>
                        <dd><?php echo htmlspecialchars($item['brand']); ?></dd>
                        
                        <dt>Size</dt>
                        <dd><?php echo htmlspecialchars($item['size']); ?></dd>
                        
                        <dt>Color</dt>
                        <dd><?php echo htmlspecialchars($item['color']); ?></dd>
                    </dl>
                </div>
                
                <h3>Description</h3>
                <p><?php echo nl2br(htmlspecialchars($item['description'])); ?></p>
                
                <h3>What They're Looking For</h3>
                <p><?php echo nl2br(htmlspecialchars($item['swap_preferences'])); ?></p>
            </div>

            <div class="swap-request">
                <h3>Request to Swap</h3>
                <form class="swap-form" action="process_swap_request.php" method="POST">
                    <input type="hidden" name="item_id" value="<?php echo $item_id; ?>">
                    <textarea name="message" rows="4" required 
                        placeholder="Describe what you'd like to swap with..."></textarea>
                    <button type="submit" class="swap-button">Send Swap Request</button>
                </form>
            </div>
        </div>
    </div>
    
    <?php else: ?>
        <div class="details-container">
            <h1>Item Not Found</h1>
            <p>The requested item could not be found.</p>
            <a href="browse_items.php">Back to Browse</a>
        </div>
    <?php endif; ?>
</body>
</html>

<?php
// Directory where your images are stored
$directory = './admin/slidepic/';

// Get all image files in the directory (you can filter by file extensions like .jpg, .jpeg, .png, etc.)
$images = glob($directory . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);

// Check if there are any images in the directory
if (count($images) > 0) {
    // Shuffle images to avoid a fixed order
    shuffle($images);
} else {
    $images = [];
}

$slides = [];
foreach ($images as $image) {
    // Create slide array with image and description
    $slides[] = [
        'description' =>'', // You can customize this as needed
        'background_image' => $image,
        'button_text' => 'Shop Now',
        'button_link' => 'index.php?p=shop'
    ];
}

// Limit the number of slides to 4
$slides = array_slice($slides, 0, 4);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slideshow</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
        }

        .slideshow-container {
            max-width: 100%;
            margin: auto;
        }

        .slides {
            display: flex;
            transition: transform 1s ease-in-out;
        }

        .slide {
            min-width: 100%;
            height: 655px;
            background-position: center;
            background-size: cover;
            position: relative;
            overflow: hidden;
        }

        .slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: brightness(70%);
        }

        .text {
            position: absolute;
            top: 50%;
            left: 20px;
            transform: translateY(-50%);
            color: white;
            font-size: 35px;
            font-weight: bold;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.6);
            width: 60%;
            padding: 10px;
        }

        .button {
            position: absolute;
            top: 435%; /* Center vertically */
            left: 81%; /* Center horizontally */
            transform: translate(-50%, -50%); /* Offset the position to center the button */
            background-color: rgb(232, 73, 68);
            color: white;
            font-size: 18px;
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .button:hover {
            background-color:rgb(240, 41, 23);
        }

        /* Optional: Add fade-in effect for text */
        .slide .text {
            animation: fadeIn 1s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        /* Style for mobile responsiveness */
        @media (max-width: 768px) {
            .text {
                font-size: 20px;
                width: 80%;
            }

            .button {
                font-size: 16px;
                padding: 10px 20px;
            }
        }

        /* Style for navigation dots */
        .dots-container {
            text-align: center;
            top:-20px;
            position: relative;
        }

        .dot {
            display:inline-block;
            margin: 0 5px;
            width: 12px;
            height: 12px;
            background-color: rgba(255, 24, 24, 0.5);
            border-radius: 50%;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .dot.active {
            background-color: white;
        }
    </style>
</head>
<body>

    <div class="slideshow-container">
        <div class="slides">
            <?php foreach ($slides as $slide): ?>
                <div class="slide" style="background-image: url('<?= htmlspecialchars($slide['background_image']) ?>');">
                    <div class="text">
                        <?= htmlspecialchars($slide['description']) ?>
                        <br>
                        <a href="<?= htmlspecialchars($slide['button_link']) ?>" class="button"><?= htmlspecialchars($slide['button_text']) ?></a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Dots for navigation -->
    <div class="dots-container">
        <?php for ($i = 0; $i < count($slides); $i++): ?>
            <span class="dot" onclick="changeSlide(<?= $i ?>)"></span>
        <?php endfor; ?>
    </div>

    <script>
        let currentSlide = 0;
        const slides = document.querySelectorAll('.slide');
        const dots = document.querySelectorAll('.dot');

        // Function to change slide
        function changeSlide(n) {
            currentSlide = n;
            updateSlidePosition();
            updateActiveDot();
        }

        // Function to update the slide position
        function updateSlidePosition() {
            const slidesContainer = document.querySelector('.slides');
            slidesContainer.style.transform = `translateX(-${currentSlide * 100}%)`;
        }

        // Function to update the active dot
        function updateActiveDot() {
            dots.forEach((dot, index) => {
                if (index === currentSlide) {
                    dot.classList.add('active');
                } else {
                    dot.classList.remove('active');
                }
            });
        }

        // Autoplay the slideshow every 3 seconds
        setInterval(() => {
            currentSlide = (currentSlide + 1) % slides.length; // Move to the next slide
            updateSlidePosition();
            updateActiveDot();
        }, 3000); // Change slide every 3 seconds

        // Initial active dot
        updateActiveDot();
    </script>

</body>
</html>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Point of Sales</title>
    <!-- Google Font: Poppins -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;700&display=swap">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Slick Slider CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #01153E;
            color: #3C3C3B;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
            background-size: cover;
            background-position: center;
            background-blend-mode: overlay;
            
            overflow: hidden;
            flex-direction: column;
        }

        h1 {
            font-size: 56px;
            margin-bottom: 10px;
            color: #FAF3E0;
        }

        p {
            font-size: 20px;
            margin-bottom: 40px;
            color: #857575;
        }

        .btn {
            display: inline-block;
            padding: 15px 30px;
            font-size: 18px;
            background-color: #FAF3E0;
            color: #563C5C;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 0 10px;
            text-decoration: none;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .btn:hover {
            background-color: #8C7297;
            transform: translateY(-5px);
        }

        .btn-login {
            background-color: #01153E;
            border: 2px solid #FAF3E0;
            color: #FAF3E0;
        }

        .btn-login:hover {
            background-color: #1E3A5F;
        }

        .btn-register {
            background-color: #FAF3E0;
        }

        .container {
            max-width: 700px;
            opacity: 0;
            transform: translateY(20px);
        }

        .footer {
            width: 100%;
            text-align: center;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .footer a {
            color: #FAF3E0;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }

        .slider {
            width: 20%;
            margin: 20px auto;
        }

        .slick-slide img {
            width: 100%;
            border-radius: 10px;
        }
    </style>
</head>

<body>

    <div class="container">
        <h1><marquee> Point of Sales </marquee></h1>
        <p> Welcome to Landing Page </p>
        <a href="{{ url('login') }}" class="btn btn-login">Login</a>
        <a href="{{ url('register') }}" class="btn btn-register">Register</a>
    </div>

    <div class="footer">
        <p>&copy; 2025 <a href="#">Privacy Policy</a> | <a href="#">Terms & Conditions</a></p>
        <p> DEVIN I'ZAZ RADIN DEWANTORO | SIB 2A / 10</p>
    </div>

    <!-- Slider Section (moved below footer) -->

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Slick Slider JS -->
    <script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <script>
        $(document).ready(function() {
            // Slider Initialization
            $('.slider').slick({
                autoplay: true,
                autoplaySpeed: 2000,
                dots: true,
                arrows: true,
                fade: true,
                cssEase: 'linear'
            });

            // Animate the container on page load
            $(".container").animate({
                opacity: 1,
                transform: "translateY(0)"
            }, 800);
        });
    </script>
</body>

</html>
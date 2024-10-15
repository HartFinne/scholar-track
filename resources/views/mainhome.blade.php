<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tzu Chi Philippines - EAP</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/mainhome.css') }}">
</head>

<body>
    <header>
        <div class="navbar">
            <div class="logo">
                <img src="{{ asset('images/logo.png') }}" alt="Logo">
                <h6 class="fw-bold">Tzu Chi Philippines<br>Educational Assistance Program</h6>
            </div>
            <a href="{{ route('roleselection') }}" class="action-btn fw-bold">Login</a>
        </div>
    </header>

    <div class="ctn-main">
        <div class="content">
            <h1 class="text-1">Become a</h1>
            <h1 class="text-2">SCHOLAR</h1>
            <p>Tzu Chi strives to nurture the youth with aspirations, sound character, and healthy minds and bodies,
                through the teaching of moral values and living skills, and providing a holistic education.</p>
            <a href="{{ route('appinstructions') }}"><span>LEARN MORE</span></a>
        </div>
    </div>
</body>
<footer>
    <div class="main-1">
        <div class="text">
            <h5><i>"Gratitude is the world's most<br>
                    beautiful language and the most<br>
                    genuine way for people to interact."</i></h5>
            <p>— Jing Si Aphorism by Master Cheng Yen</p>
        </div>
    </div>
    <div class="main-2">
        <div class="text">
            <h3>Tzu Chi Philippines</h3>
            <p class="fw-bold">Buddhist Compassion Relief Tzu Chi Foundation, Philippines - Jing Si Hall</p>
            <p>1000 Cordillera cor. Lubiran Sts., Bacood, Sta. Mesa, Manila 1016</p>
            <p>(632) 8714 - 1188</p>
            <p><a href="">info@tzuchi.org.ph</a></p>
        </div>
    </div>
    <div class="soc-med">
        <a href="https://www.facebook.com/TzuChiPH/" target="_blank"><i class="fa-brands fa-facebook"></i></a>
        <a href="https://x.com/TzuChiPH" target="_blank"><i class="fa-brands fa-twitter"></i></a>
        <a href="https://www.instagram.com/tzuchiph" target="_blank"><i class="fa-brands fa-instagram"></i></a>
        <a href="https://www.youtube.com/user/tzuchiphil" target="_blank"><i class="fa-brands fa-youtube"></i></a>
        <a href="https://www.tiktok.com/@tzuchiphilippines" target="_blank"><i class="fa-brands fa-tiktok"></i></a>
    </div>
    <h5 class="copyright">Copyright © 2024 Tzu Chi Philippines All Rights Reserved.
        <a href="#">Privacy Policy</a> | <a href="#">Terms & Conditions</a>
    </h5>
</footer>

</html>

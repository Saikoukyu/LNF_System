<?php 
    include("../php/connect.php");
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NU Dasmariñas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="/css/nu_frontpage.css">
    
</head>
<body>
    <div class="header">
        <div class="logo">
            <img src="../html/assets/nu shield.png" alt="NU Logo"> 
            <div class="title">NU DASMARIÑAS</div>
        </div>
        <div class="welcome"><span style="font-weight: normal;">WELCOME TO</span> <span style="font-weight: bold; color:#dbbb33;">NU DASMARIÑAS</span></div>
        <div class="yellow-line"></div>
    </div>

    <div class="banner">
        <div class="banner-content">
            <img src="..html/assets/nu bulldog 2.png" alt="NU Bulldogs Logo" class="banner-logo"> 
            <div class="banner-text">EDUCATION THAT WORKS</div>
        </div>
    </div>

    <div class="content">
        <h2>Online Services</h2>
        <div class="services">
            <div class="service-box">
                
            </div>	
            <div class="service-box">
                <img src="assets/lostnfound.png" alt="Lost and Found Management System" class="service-img"> 
                <p>Easily report and track lost items on campus</p>
                <div class="login-button" onclick="window.location.href='NU_LoginPage.php'">LOGIN</div>
            </div>
        </div>
    </div>

    <div class="footer">
        <div class="logo-footer">
            <img src="assets/nu shield.png" alt="NU Logo">
            <div class="footer-text">NATIONAL<br>UNIVERSITY</div>
        </div>
        <div class="footer-info">
            <h4><i class="fas fa-info-circle"></i> ABOUT NU ONLINE SERVICES</h4>
            <p>All Rights Reserved. National University</p><hr>
            <p>NU Online Services is a portal of all online application National University <br>offers to its clients to
			extend its support and services and bring the satisfaction they deserve.</p>
        </div>
        <div class="contact">
            <h4><i class="fas fa-phone-alt"></i> CONTACT US</h4>
            <p><i class="fas fa-map-marker-alt"></i> Governor's Drive, Sampaloc 1, City of Dasmariñas, Cavite 4114</p>
            <p><i class="fas fa-phone"></i> 09389151581 (Smart) / 09681381357 (Globe)</p>
            <p><i class="fas fa-envelope"></i> admissions@nu-dasma.edu.ph</p>
            <p><i class="fas fa-clock"></i> Service Hours: Monday to Friday (8:30AM – 5:30PM), Saturday (8:30AM – 12:30PM)</p>
        </div>
    </div>

</body>
</html>
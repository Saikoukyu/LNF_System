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
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #e3e5e0;
        }

        .header {
            background-color: #35408e;
            color: white;
            padding: 5px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative; 
        }

        .header .logo {
            display: flex;
            align-items: center;
        }

        .header .welcome {
            margin-right: 20px;
			font-size: 20px;
        }

        .header .logo img {
            height: 80px;
            margin-right: 15px;
        }

        .header .title {
            font-size: 20px;
            margin-left: -15px;
        }
        
        .yellow-line {
            height: 5px;
            background-color: #e1bd59;
            width: 100%;
            position: absolute;
            bottom: 0;
            left: 0;
        }
        
        .banner {
            position: relative;
            background-image: url('assets/nu banner.png'); 
            background-size: cover;
            background-position: center;
            height: 250px;
            display: flex;
            align-items: flex-end; 
            color: white;
        }

        .banner-content {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: flex-start; 
            padding: 10px 30px;
            background-color: rgba(0, 0, 0, 0.5); 
        }

        .banner-logo {
            height: 50px;
            margin-right: 15px; 
        }

        .banner-text {
            font-size: 24px; 
            font-weight: bold;
            margin: 0 auto; 
            text-transform: uppercase;
        }

        .content {
            background-color: #e8e8e8;
            padding: 30px 20px;
            text-align: left; 
        }

        .content h2 {
            color: #2f3b82;
            margin-bottom: 20px;
            margin-top: 0;
        }

        .content .services {
            display: flex;
            justify-content: center; 
            gap: 50px; 
            margin-top: 20px;
        }

        .service-box {
            background-color: white;
            padding: 20px;
            border-radius: 0px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: left;
        }

        .service-box h3 {
            color: #2f3b82;
            margin-bottom: 10px;
        }

        .service-box p {
            margin: 10px 0;
        }

        .service-box .login-button {
            background-color: #28a745; 
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 0; 
            cursor: pointer;
            text-align: center;
            display: block; 
            margin: 20px auto 0 auto; 
            width: fit-content; 
        }

        .service-box .login-button:hover {
            background-color: #218838;
            transform: scale(1.05); 
        }


        .service-img {
            display: block;
            margin: 0 auto 10px auto; 
            max-width: 100%;
            height: auto; 
        }

        .footer {
            background-color: #35408e;
            color: white;
            padding: 20px 30px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-wrap: wrap;
        }

        .logo-footer {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .logo-footer img {
            height: 200px;
            margin-right: -20px;
        }

        .logo-footer .footer-text {
            font-size: 30px;
            line-height: 1.2;
			font-weight: bold;
            text-transform: uppercase;
        }

        .footer-info,
        .contact {
            flex: 1;
            min-width: 250px;
            margin-bottom: 20px;
        }

        .footer-info {
            padding-right: 50px;
			padding-left: 40px;
        }

        .footer-info h4,
        .contact h4 {
            font-size: 12px;
            margin-bottom: 10px;
			margin-top: 60px; 
        }

        .footer-info p,
        .contact p {
            font-size: 12px;
            margin: 5px 0;
            line-height: 1.2;
        }

        .footer-info p i,
        .contact p i {
            margin-right: 10px;
        }

        .footer-info p:first-child,
        .contact p:first-child {
            font-weight: bold;
            font-size: 12px;
            margin-bottom: 10px;
        }

        .contact p {
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">
            <img src="assets/nu shield.png" alt="NU Logo"> 
            <div class="title">NU DASMARIÑAS</div>
        </div>
        <div class="welcome"><span style="font-weight: normal;">WELCOME TO</span> <span style="font-weight: bold; color:#dbbb33;">NU DASMARIÑAS</span></div>
        <div class="yellow-line"></div>
    </div>

    <div class="banner">
        <div class="banner-content">
            <img src="assets/nu bulldog 2.png" alt="NU Bulldogs Logo" class="banner-logo"> 
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
                <div class="login-button" onclick="window.location.href='NU_LoginPage.html'">LOGIN</div>
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
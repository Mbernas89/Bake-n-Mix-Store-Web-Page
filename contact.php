<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Contact Bake N' Mix</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        /* RED HERO SECTION */
        .contact-hero {
            background-color: #c1121f; 
            height: 300px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
            padding: 0 20px;
        }

        .contact-card {
            border-radius: 12px;
            padding: 25px;
            background: #ffffff;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .icon-circle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: #c1121f;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px; 
            margin-right: 15px;
            flex-shrink: 0;
        }

        a {
            text-decoration: none;
            color: #c1121f;
            font-weight: 500;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

<?php include 'navbar.php'; ?>

<div class="contact-hero mb-5">
    <div>
        <h1 class="fw-bold display-5">Contact Us</h1>
        <p class="lead mt-2">We’d love to hear from you!</p>
    </div>
</div>

<div class="container mb-5">

    <div class="row g-4">

        <div class="col-md-5">
            <div class="contact-card">

                <h3 class="fw-bold mb-4">Get in Touch</h3>

                <div class="d-flex align-items-start mb-4">
                    <div class="icon-circle"><i class="fas fa-map-marker-alt"></i></div>
                    <p class="mb-0">
                        Unit C TLM Apartments, 82 Avenida St. cor Fullon St.<br>
                        Bahayang Pag-asa Subdivision, Molino V,<br>
                        Bacoor, Cavite, Philippines, 4102
                    </p>
                </div>

                <div class="d-flex align-items-center mb-4">
                    <div class="icon-circle"><i class="fas fa-phone"></i></div>
                    <p class="mb-0">0912-345-6789</p>
                </div>

                <div class="d-flex align-items-center mb-4">
                    <div class="icon-circle"><i class="fas fa-envelope"></i></div>
                    <p class="mb-0">bakenmixbakingsupplies@gmail.com</p>
                </div>

                <div class="d-flex align-items-center mb-4">
                    <div class="icon-circle"><i class="fab fa-facebook-f"></i></div>
                    <p class="mb-0">
                        <a href="https://www.facebook.com/BakeAndMixBakingSupplies" target="_blank">
                            Facebook Page
                        </a>
                    </p>
                </div>

            </div>
        </div>

        <div class="col-md-7">
            <div class="contact-card">

                <h3 class="fw-bold mb-3">Send Us a Message</h3>
                <p class="mb-4">Have questions or inquiries? We'd love to hear from you.</p>

                <form>
                    <div class="mb-3">
                        <label for="fullName" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="fullName" placeholder="Juan Dela Cruz">
                    </div>

                    <div class="mb-3">
                        <label for="emailAddress" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="emailAddress" placeholder="name@example.com">
                    </div>

                    <div class="mb-3">
                        <label for="messageBody" class="form-label">Message</label>
                        <textarea class="form-control" id="messageBody" rows="4" placeholder="Write your message here..."></textarea>
                    </div>

                    <button class="btn btn-danger w-100 py-2" type="submit">Send Message</button>
                </form>

            </div>
        </div>

    </div>
</div>

<footer class="bg-dark text-white py-4 mt-5">
    <div class="container text-center">
        <h5 class="mb-1">Bake N' Mix</h5>
        <p class="mb-2">Your one-stop shop for baking supplies</p>

        <div class="mb-3">
            <a href="https://www.facebook.com/BakeAndMixBakingSupplies" class="text-white fs-4 me-3" aria-label="Bake N' Mix Facebook Page">
                <i class="fa-brands fa-facebook"></i>
            </a>
        </div>

        <small>
            &copy; <?php echo date('Y'); ?> Bake N' Mix. All rights reserved.
        </small>
    </div>
</footer>

</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/style_about.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:ital,wght@1,300&display=swap" rel="stylesheet">
</head>
<body class="body">
    <div class="section">
        <div class="container">
            <div class="content-section">
                <div class="title">
                    <h1>Despre noi</h1>
                </div>
                <div class="content">
                    <p>Echipa noastră are ca scop aducerea autenticității tradițiilor românești în casele oamenilor din întreaga lume.<br> Suntem pasionați de cultura noastră și ne-am angajat să promovăm produsele tradiționale românești, atât mâncarea autentică și delicioasă, cât și obiectele artizanale deosebite. Prin acest magazin online, ne străduim să oferim o experiență ușoară și convenabilă în achiziționarea acestor produse minunate. Echipa noastră selectează cu grijă cele mai bune produse tradiționale de la producătorii și artizanii locali, pentru ca fiecare client să poată savura autenticitatea și valoarea culturală a României. Ne dorim să aducem un strop de Românie în fiecare cămin din lume.</p>
                </div>
                <div class="contact">
                    <span class="contact-text"><b>Contact</b></span>
                </div>
                <div class="social">
                    <a href="https://www.facebook.com/robert.boldura"><i class="fab fa-facebook-f"><img src="imagini/facebook.png" alt="" width="25"></i></a>
                    <a href="https://twitter.com/RBoldura"><i class="fab fa-twitter"> <img src="imagini/twitter.png" alt="" width="25"></i></a>
                    <a href="https://www.instagram.com/robiboldura/"><i class="fab fa-instagram"><img src="imagini/insta.png" alt="" width="25"></i></a>
                    <a href="https://mail.google.com/mail/u/0/?view=cm&fs=1&to=robertboldura@gmail.com" target="_blank"><img src="imagini/gmail.png" alt="Email" width="25" style="cursor:pointer;"></a>
                </div>

                <!-- Adaugarea intrebarilor predefinite si a raspunsurilor -->
                <div class="assistant-buttons">
                    <button onclick="askQuestion('Ce produse oferiți?')">Ce produse oferiți?</button>
                    <button onclick="askQuestion('Care sunt orele de funcționare?')">Care sunt orele de funcționare?</button>
                    <button onclick="askQuestion('Care sunt metodele de plată disponibile?')">Care sunt metodele de plată disponibile?</button>
                    <!-- Adaugă altele după necesități -->
                </div>
                <div id="assistantResponse"></div>
                <!-- Sfarsitul adaugarii de intrebari si raspunsuri -->

            </div>
            <div class="img-sec">
                <img src="imagini/about.jpg" alt="">
            </div>
        </div>
    </div>
    <a href="index.php" class="go-home-button">Acasă</a>

    <script>
        function askQuestion(question) {
            var assistantResponse = document.getElementById('assistantResponse');

            switch (question.toLowerCase()) {
                case 'ce produse oferiți?':
                    assistantResponse.innerHTML = 'Oferim o gamă variată de produse tradiționale. Vă invităm să explorați magazinul nostru!';
                    break;
                case 'care sunt orele de funcționare?':
                    assistantResponse.innerHTML = 'Fiind un magazin online și nu unul fizic, putem fi contactați oricând, iar comenzile pot fi plasate oricând. Vă vom răspunde în cel mai scurt timp posibil.';
                    break;
                case 'care sunt metodele de plată disponibile?':
                    assistantResponse.innerHTML = 'În momentul de față, acceptăm doar plata la livrare, dar în viitor vom adăuga și opțiunea de a plăti cu cardul.';
                    break;
                default:
                    assistantResponse.innerHTML = 'Nu am informații despre această întrebare. Te rog să întrebi altceva.';
            }
        }
    </script>
</body>
</html>

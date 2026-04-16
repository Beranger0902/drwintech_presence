<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Connexion administrateur</title>

<!-- Google Font -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:'Poppins', sans-serif;
        }

        body {
            background: #ffffff;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }


        .container {
            width: 85%;
            height: 80vh;
            display: flex;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            animation: fadeIn 0.8s ease-in-out;
        }

        /* LEFT */
        .left{
            width:45%;
            background:url('https://images.unsplash.com/photo-1550751827-4bd374c3f58b') no-repeat center;
            background-size: cover;
            position: relative;
        }

        .left::before {
            content: "";
            position: absolute;
            inset: 0;
            background: rgba(0,0,0,0.5);
            animation: zoomBg 20s infinite alternate ease-in-out;
        }

        .left-content {
            position: absolute;
            color: white;
            z-index: 2;
            padding: 40px;
            bottom: 50px;
        }

        .left h1{
            font-size:32px;
            margin-bottom:10px;
        }

        .left p{
            opacity:0.9;
            line-height:1.5;
        }

        .info{
            margin-top:30px;
            display:flex;
            align-items:center;
            gap:10px;
        }

        /* RIGHT */
         .right {
            width: 55%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fff;
        }

        .form-box {
            width: 70%;
            animation: slideUp 0.6s ease;
        }


         .logo {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo-circle{
            width:45px;
            height:45px;
            background:#5b7cfa;
            border-radius:50%;
            display:flex;
            align-items:center;
            justify-content:center;
            color:white;
            font-weight:bold;
        }

        .logo img {
            width: 50px;
        }

        .logo h2 {
            margin-top: 10px;
            color: #2c3e50;
        }

        h2{
            margin-bottom:10px;
             text-align: center;
        }

        .right p {
            text-align: center;
            color: gray;
            margin-bottom: 20px;
        }


        
        .subtitle{
            color:#777;
            margin-bottom:25px;
        }

        .input-group{
            position:relative;
            margin-bottom:20px;
        }

        .input-group i{
            position:absolute;
            top:50%;
            left:15px;
            transform:translateY(-50%);
            color:#aaa;
        }

        .input-group input{
            width:100%;
            padding:12px 40px;
            border-radius:10px;
            border:1px solid #ddd;
            outline:none;
            transition:0.3s;
        }

        .input-group input:focus{
            border-color:#5b7cfa;
        }

        .eye{
            position:absolute;
            right:15px;
            top:50%;
            transform:translateY(-50%);
            cursor:pointer;
        }

        .btn{
            width:100%;
            padding:12px;
            border:none;
            border-radius:10px;
            background:#5b7cfa;
            color:white;
            font-weight:bold;
            cursor:pointer;
            transition:0.3s;
        }

        .btn:hover{
            background:#4a6cf7;
        }

        .otp-section{
            display:none;
            animation:fadeIn 0.5s ease;
        }

        .otp-section.active{
            display:block;
        }

        .resend{
            text-align:right;
            margin-top:10px;
            color:#5b7cfa;
            cursor:pointer;
            font-size:14px;
        }

        .timer{
            margin-top:10px;
            font-size:13px;
            color:#777;
        }

        @keyframes fadeIn{
            from{opacity:0}
            to{opacity:1}
        }

        @keyframes slideUp{
            from{transform:translateY(30px);opacity:0}
            to{transform:translateY(0);opacity:1}
        }

         @keyframes zoomBg {
            from { transform: scale(1); }
            to { transform: scale(1.1); }
        }
</style>
</head>

<body>

<div class="container">

    <!-- LEFT -->
    <div class="left">
        <div class="left-content">
            <h1>Connexion administrateur</h1>
            <p>Connectez-vous avec une double sécurité réservée aux administrateurs.</p>

            <div class="info">
                <i class="fa fa-check-circle"></i>
                <span>Un code sera envoyé à votre email</span>
            </div>
        </div>
    </div>

    <!-- RIGHT -->
    <div class="right">

        <div class="form-box">

                <div class="logo">
                    <img src="/Images/drwintech-logo.jpeg" alt="drwintech">
                    <h2>DRWINTECH</h2>
                </div>

                <h2>Connexion administrateur</h2>
                <p class="subtitle">Veuillez saisir vos identifiants</p>

                <form method="POST" action="{{ route('admin.login') }}">
                    @csrf

                    <div class="input-group">
                        <i class="fa fa-user"></i>
                        <input type="email" name="email" placeholder="Email" required>
                    </div>

                    <div class="input-group">
                        <i class="fa fa-lock"></i>
                        <input type="password" id="password" name="password" placeholder="Mot de passe" required>
                        <i class="fa fa-eye eye" onclick="togglePassword()"></i>
                    </div>

                    <!-- OTP -->
                    <div class="otp-section" id="otpSection">
                        <div class="input-group">
                            <i class="fa fa-key"></i>
                            <input type="text" name="otp" placeholder="Code de sécurité">
                        </div>

                        <div class="resend" onclick="resendCode()">Renvoyer le code</div>
                        <div class="timer" id="timer">Expire dans : 05:00</div>
                    </div>

                    <button type="submit" class="btn">Vérifier et se connecter</button>
                </form>
            </div>
    </div>
</div>

<script>
// afficher OTP si Laravel le demande
@if(session('showOtp'))
    document.getElementById('otpSection').classList.add('active');
@endif

// toggle password
function togglePassword(){
    let input = document.getElementById('password');
    input.type = input.type === 'password' ? 'text' : 'password';
}

// timer OTP
let time = 300;
let timerEl = document.getElementById('timer');

function countdown(){
    let min = Math.floor(time/60);
    let sec = time%60;
    timerEl.innerText = "Expire dans : " + min + ":" + (sec<10?"0":"") + sec;
    if(time > 0) time--;
}

setInterval(countdown,1000);

// renvoyer code
function resendCode(){
    fetch("{{ route('admin.resend.otp') }}",{
        method:'POST',
        headers:{
            'X-CSRF-TOKEN':'{{ csrf_token() }}'
        }
    }).then(()=>{
        time = 300;
        alert('Code renvoyé');
    });
}
</script>

</body>
</html>
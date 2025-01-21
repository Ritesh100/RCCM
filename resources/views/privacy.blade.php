<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Disclosure</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}"> <!-- Link to your app CSS -->
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 2rem;
            line-height: 1.6;
        }
        .container {
            max-width: 800px;
            margin: auto;
        }
        .header {
            text-align: center;
            margin-bottom: 2rem;
            position: relative;
        }
        .header h1 {
            font-size: 2rem;
            font-weight: bold;
            margin-left: 80px; /* Adjust margin to account for logo */
        }
        .logo {
            position: absolute;
            top: 0;
            left: 0;
            width: 60px; /* Adjust size as needed */
            height: auto;
        }
        .content {
            background: #f9f9f9;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .contact-info {
            margin-top: 1.5rem;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <img src="{{ asset('logo.png') }}" alt="Company Logo" class="logo">
        <h1>Privacy Disclosure</h1>
    </div>
    <div class="content">
        <p>By signing in on this platform, you acknowledge and agree to our Privacy Policy.</p>

        <p><strong>Remote Colleagues, the Company (RCC)</strong> is collecting personal information about you. This notice is to
            inform you of your rights under the Privacy Act.</p>

        <p>The information you provide will be held by RCC. You can gain access to the information we hold about you by
            contacting us at:</p>

        <div class="contact-info">
            <p>Email: <a href="mailto:support@remotecolleagues.com">support@remotecolleagues.com</a></p>
            <p>Phone: 0452548517</p>
        </div>

        <p>We may use the personal information you provide for the purposes to fulfill our duties/obligations/requirements
            in the terms mentioned in the Master Agreement or Product Schedule or Employment Offer signed by you or also, for direct marketing of products and other services offered by RCC or direct marketing relating to our products, services, etc. You have the right to request not to receive direct marketing material.</p>

        <p>We may disclose personal information to any industry body, tribunal, court, or otherwise in connection with
            any complaints — for example, if a complaint is lodged about us or you.</p>

        <p>We may disclose personal information about you as required by law, or to any organization involved with
            assisting the fulfillment of the terms mentioned in the Master Agreement or Product Schedule or Employment Offers, or any other associates or contractors of RCC including and not limited to, statement printing houses, mail houses, lawyers, accountants, etc.</p>

        <p>If you do not provide your personal information, we may be unable to assist you. You agree that RCC may
            collect and use your personal information as specified above.</p>

        <p>Initials of The Company Representative/ Date of Initials:</p>
    </div>
</div>
</body>
</html>

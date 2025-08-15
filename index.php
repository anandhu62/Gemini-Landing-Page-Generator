<!DOCTYPE html>
<html>
<head>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <title>Landing Page Generator</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif !important;
            text-align: center;
            padding-top: 100px;
            background: linear-gradient(-45deg,rgb(54, 20, 210), #fad0c4, #fad0c4,rgb(211, 8, 32));
            background-size: 400% 400%;
            animation: gradientBG 10s ease infinite;
        }

        /* Background Animation */
        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        *{
            font-family: 'Poppins', sans-serif !important;
        }
 
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // $('#business_idea').on('input', function() {
            //     var businessIdea = $(this).val();
            //     $.ajax({
            //         url: 'preview.php', // Create a separate preview.php
            //         type: 'POST',
            //         data: { business_idea: businessIdea },
            //         success: function(data) {
            //             $('#preview').html(data);
            //         }
            //     });
            // });
        });
    </script>
</head>
<style>
    .p-form-holder{
        margin: 0 auto;
        min-height:0px;
        min-width:0px;
        padding:40px 40px;
        border-radius:6px;
        box-shadow:0px 6px 6px rgba(0,0,0,0.1);
        overflow:hidden;
        width: 400px;
        background: white;
        border: solid 5px black;
    }
    .p-form-text, .p-form-button{
        float:left;
        width: calc(100% - 20px) !important;
    }
    .p-form-text{
        padding:5px;
        margin:5px 0px;
        padding:6px;
        text-align:left;
        border:solid 1px gray;
        border-radius:3px;
        box-shadow:inset 0px 0px 6px rgba(0,0,0,0.1);
        resize: none;
        font-size: 16px;
    }
    .p-form-button{
        font-weight:bold;
        font-size:14px;
        text-transform: uppercase;
        margin-top:10px;
        padding:10px 20px !important;
        width: 100% !important;
        background: black;
        color: white;
        border-radius: 6px;
        box-shadow:inset 0px 2px 2px rgba(0,0,0,0.1);
        cursor:pointer;
    }
</style>
<body>
    <h1>Landing Page Generator</h1>
    <div class="p-form-holder">
        <input class="p-form-text" type="text" id="business_name" placeholder="Business Name, Eg: XYZ Business" required /><br><br>
        <input class="p-form-text" type="text" id="business_type" placeholder="Business Type, Eg: Taxi Service" required /><br><br>
        <textarea class="p-form-text" type="text" id="business_services" placeholder="Explain your services" required></textarea><br><br>
        <textarea class="p-form-text" type="text" id="business_address" placeholder="Business Address" required></textarea><br><br>    
        <button class="p-form-button" id="sub-button" onclick="generatePage()">Generate Landing Page</button>
    </div>
    <!-- <div id="preview">
        
    </div> -->

    <script>
    function generatePage() {
        // Submit form data for full page generation

        var businessName = document.getElementById('business_name').value;
        var businessType = document.getElementById('business_type').value;
        var businessServices = document.getElementById('business_services').value;
        var businessAddress = document.getElementById('business_address').value;
        
        if (businessName === "" || businessType === "" || businessServices === "" || businessAddress === "") {
            alert("Please fill in all fields before generating the landing page.");
            return; // Stop function execution if validation fails
        }

        var subButton = document.getElementById('sub-button');
        subButton.textContent = "Generating..."; 
        subButton.removeAttribute("onclick");
        // Create a hidden form and submit it programmatically
        var form = document.createElement('form');
        form.action = 'generate.php';
        form.method = 'post';
 
        // Busines name
        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'business_name';
        input.value = businessName;
        // Busines name
        var input2 = document.createElement('input');
        input2.type = 'hidden';
        input2.name = 'business_type';
        input2.value = businessType;

        // Busines name
        var input3 = document.createElement('input');
        input3.type = 'hidden';
        input3.name = 'business_services';
        input3.value = businessServices;

        var input4 = document.createElement('input');
        input4.type = 'hidden';
        input4.name = 'business_address';
        input4.value = businessAddress;

        form.appendChild(input);
        form.appendChild(input2);
        form.appendChild(input3);
        form.appendChild(input4);
        document.body.appendChild(form);
        form.submit();
    }
    </script>
</body>
</html>
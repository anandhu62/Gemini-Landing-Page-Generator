<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set("display_errors", 1);

function predictText($prompt)
{
    $url =
        "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=AIzaSyA2i9IZsGKj6zz8nDkkjS7A98WlDe1wbD4";
    $data = [
        "contents" => [["parts" => [["text" => $prompt]]]],
    ];
    $headers = ["Content-Type: application/json"];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);
    return $result["candidates"][0]["content"]["parts"][0]["text"] ??
        "Error generating content.";
}

// Function to format services into boxes
function formatServices($servicesContent)
{
    $services = explode("\n", $servicesContent);
    $formattedServices = "";
    foreach ($services as $service) {
        $service = trim($service);
        if (!empty($service)) {
            $formattedServices .= "<div class='service-box'><p>{$service}</p></div>";
        }
    }
    return $formattedServices;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $businessName = htmlspecialchars($_POST["business_name"]);
    $businessType = htmlspecialchars($_POST["business_type"]);
    $businessServices = htmlspecialchars($_POST["business_services"]);
    $businessAddress = htmlspecialchars($_POST["business_address"]);
    
    $businessInfoGeneral = " Business Name : " . $businessName 
                    . ", Business Type : ". $businessType;
    $businessInfo = " Business Name : " . $businessName 
                    . ", Business Type : ". $businessType
                    . ", Business Services : ". $businessServices
                    . ", Business Address : ". $businessAddress; 
    

    echo " POST CONTENT IS 0";
    print_r($_POST);

    $aboutUsContent = predictText(
        "Write a short and engaging 'About Us' section for a business based on: " .
        $businessInfo . ". I just need only the assumed content, don't share any instructions with the content."
    );
   echo "About Us : ". $aboutUsContent;
    $servicesContent = predictText(
        "Expand the services offered by a business based on " .
        $businessInfo .
            ".List each service in new line,Make sure to list less than 15 services. I just need only the content based on the business type, don't share any instructions with the content. Don't generate any services not related to the business type"
    );
    echo "Services : ". $servicesContent;
    $contactContent = predictText(
        "This is the business address given by the user : ". $businessAddress .
        "Create a brief contact information website footer text (phone number, email, address)." . 
        "Make up any fields if any of the (phone number, email, address) is not available. Don't share any instructions with the returned content."
    );
    echo "Contact Us : ". $contactContent;
    $formattedServices = formatServices($servicesContent);
    $layouts = [
        "layout1" => [
            "header" => "<header class='bg-primary text-white py-2'>
 <div class='container'>
 <div class='d-flex justify-content-between align-items-center'>
 <div><img src='https://placehold.co/60x60' alt='Logo' class='logo'></div>
 <nav>
 <ul class='nav'>
<li class='nav-item'><a class='nav-link text-white' href='#about'>About</a></li>
<li class='nav-item'><a class='nav-link text-white' href='#services'>Services</a></li>
                                            <li class='nav-item'><a class='nav-link text-white' href='#contact'>Contact</a></li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                         </header>
                         <div class='text-center py-5'><h1>{$businessName}</h1></div>",
            "content" => "<section id='about' class='container my-5'><h2>About Us</h2><p>{$aboutUsContent}</p></section>
 <section id='services' class='container my-5'><h2>Our Services</h2>{$formattedServices}</section>",
            "footer" => "<footer class='bg-dark text-white text-center py-3'><p>{$contactContent}</p></footer>",
        ],
        "layout2" => [
            "header" => "<header class='bg-dark text-white py-2'>
                            <div class='container'>
                                <div class='d-flex justify-content-between align-items-center'>
                                    <div><img src='https://placehold.co/60x60' alt='Logo' class='logo'></div>
                                    <nav>
                                        <ul class='nav'>
                                            <li class='nav-item'><a class='nav-link text-white' href='#about'>About</a></li>
                                            <li class='nav-item'><a class='nav-link text-white' href='#services'>Services</a></li>
                                            <li class='nav-item'><a class='nav-link text-white' href='#contact'>Contact</a></li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                         </header>
                         <div class='text-center py-5'><h1>Welcome to {$businessName}</h1></div>",
            "content" => "<section id='services' class='container my-5'><h2>Our Services</h2>{$formattedServices}</section>
 <section id='about' class='container my-5'><h2>About Us</h2><p>{$aboutUsContent}</p></section>",
            "footer" => "<footer class='bg-primary text-white text-center py-3'><p>Contact us: {$contactContent}</p></footer>",
        ],
        "layout3" => [
            "header" => "<header class='bg-success text-white py-3'>
                            <div class='container'>
                                <div class='row align-items-center'>
                                    <div class='col-md-4'><img src='https://placehold.co/60x60' alt='Logo' class='logo'></div>
                                    <div class='col-md-8 text-end'>
                                        <nav>
                                            <ul class='nav justify-content-end'>
                                                <li class='nav-item'><a class='nav-link text-white' href='#about'>About Us</a></li>
                                                <li class='nav-item'><a class='nav-link text-white' href='#services'>Services</a></li>
                                                <li class='nav-item'><a class='nav-link text-white' href='#contact'>Contact</a></li>
                                            </ul>
                                        </nav>
                                    </div>
                                </div>
                            </div>
                        </header>",
            "content" => "<div class='container'><div class='row'><div class='col-md-6'><section id='about' class='my-5'><h2>About Us</h2><p>{$aboutUsContent}</p></section></div><div class='col-md-6'><section id='services' class='my-5'><h2>Our Services</h2>{$formattedServices}</section></div></div></div>",
            "footer" => "<footer class='bg-secondary text-white text-center py-3'><p>{$contactContent}</p></footer>",
        ],
        "layout4" => [
            "header" => "<header class='bg-info text-dark py-3'>
                            <div class='container'>
                                <div class='row align-items-center'>
                                    <div class='col-md-3'><img src='https://placehold.co/60x60' alt='Logo' class='logo'></div>
                                    <div class='col-md-9'>
                                        <h1>{$businessName}</h1>
                                    </div>
                                </div>
                            </div>
                        </header>",
            "content" => "<div class='container my-5'><div class='row'><div class='col-lg-8'><section id='about'><h2>About Us</h2><p>{$aboutUsContent}</p></section></div><div class='col-lg-4'><section id='services'><h2>Our Services</h2>{$formattedServices}</section></div></div></div>",
            "footer" => "<footer class='bg-light text-center py-3'><p>{$contactContent}</p></footer>",
        ],
    ];

    $layoutKeys = array_keys($layouts);
    $randomLayoutKey = $layoutKeys[array_rand($layoutKeys)];
    $randomLayout = $layouts[$randomLayoutKey];

    // Create project directories
    $timestamp = time();
    $projectDir = "generated_files/" . $timestamp;
    $cssDir = $projectDir . "/css";
    $jsDir = $projectDir . "/js";

    if (
        !mkdir($projectDir, 0755, true) ||
        !mkdir($cssDir, 0755, true) ||
        !mkdir($jsDir, 0755, true)
    ) {
        die("Failed to create project directories.");
    }

    // HTML Content
    $htmlContent = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$businessName} - Landing Page</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <div class="owl-carousel owl-theme">
        <div class="item"><img src="https://placehold.co/1200x400" alt="Placeholder"></div>
        <div class="item"><img src="https://placehold.co/1200x400" alt="Placeholder"></div>
        <div class="item"><img src="https://placehold.co/1200x400" alt="Placeholder"></div>
    </div>

    {$randomLayout["header"]}
    {$randomLayout["content"]}
    {$randomLayout["footer"]}

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script src="js/script.js"></script>

    <script>
        $(document).ready(function(){
            $('.owl-carousel').owlCarousel({
                loop:true,
                margin:10,
                nav:true,
                items: 1
            });
        });
    </script>
</body>
</html>
HTML;

    // CSS Content
    $cssContent = <<<CSS
body { font-family: 'Arial', sans-serif; }
h2 { color: #333; }
.container { padding: 20px; }
footer { font-size: 14px; }
.owl-carousel .item img {
    height: 400px; /* Fixed height for carousel images */
    object-fit: cover; /* Ensure images cover the area */
}
.service-box {
    border: 1px solid #ccc;
    padding: 10px;
    margin-bottom: 10px;
    border-radius: 5px;
    background-color: #f9f9f9;
}
.logo {
    max-height: 50px; /* Adjust logo height */
}
.nav-link {
    padding: 0.5rem 1rem;
}
/* Additional CSS for different layouts */
.bg-success { background-color: #28a745 !important; }
.bg-info { background-color: #17a2b8 !important; }
.text-end { text-align: right !important; }
.row { display: flex; flex-wrap: wrap; }
.col-md-6 { flex: 0 0 50%; max-width: 50%; }
.col-md-8 { flex: 0 0 66.666667%; max-width: 66.666667%; }
.col-md-4 { flex: 0 0 33.333333%; max-width: 33.333333%; }
.col-lg-8 { flex: 0 0 66.666667%; max-width: 66.666667%; }
.col-lg-4 { flex: 0 0 33.333333%; max-width: 33.333333%; }
CSS;

    // JavaScript Content
    $jsContent = <<<JS
console.log('Landing page generated successfully!');
JS;

    // Save files
    file_put_contents($projectDir . "/index.html", $htmlContent);
    file_put_contents($cssDir . "/style.css", $cssContent);
    file_put_contents($jsDir . "/script.js", $jsContent);

    echo "<p>Landing page generated successfully! View it <a href='" .
        $projectDir .
        "/index.html'>here</a>.</p>";
    header("Location: " . $projectDir);
}
?>



//Gemini key:'AIzaSyA2i9IZsGKj6zz8nDkkjS7A98WlDe1wbD4'

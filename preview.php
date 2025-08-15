<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
require 'vendor/autoload.php';

use Google\Cloud\AIPlatform\V1\EndpointServiceClient;
use Google\Cloud\AIPlatform\V1\PredictRequest;
use Google\Protobuf\Value;
use Google\Protobuf\Struct;

function predictText($prompt) {
    $projectId = 'your-project-id'; // Replace with your project ID
    $location = 'us-central1'; // Replace with your location
    $endpointId = 'your-endpoint-id'; // Replace with your endpoint ID

    $endpointServiceClient = new EndpointServiceClient([
        'credentials' => '/path/to/your/service-account.json', // Replace with the path to your service account file
    ]);

    $endpointName = $endpointServiceClient->endpointName($projectId, $location, $endpointId);

    $parameters = new Struct();
    $parameters->getFields()['temperature'] = new Value(['number_value' => 0.2]);
    $parameters->getFields()['maxOutputTokens'] = new Value(['number_value' => 256]);
    $parameters->getFields()['topP'] = new Value(['number_value' => 0.8]);
    $parameters->getFields()['topK'] = new Value(['number_value' => 40]);

    $instance = new Struct();
    $instance->getFields()['prompt'] = new Value(['string_value' => $prompt]);

    $request = (new PredictRequest())
        ->setEndpoint($endpointName)
        ->setInstances([$instance])
        ->setParameters($parameters);

    $response = $endpointServiceClient->predict($request);
    $predictions = $response->getPredictions();
    $prediction = $predictions[0]->getFields()['content']->getStringValue();

    return $prediction;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $businessIdea = htmlspecialchars($_POST["business_idea"]);

    // --- Gemini API Integration ---
    $geminiAboutPrompt = "Write a short and engaging 'About Us' section for a business that is based on the idea: " . $businessIdea;
    $aboutUsContent = predictText($geminiAboutPrompt);

    echo "<section id='about'><h2>About Us (Preview)</h2><p>{$aboutUsContent}</p></section>";
}
?>
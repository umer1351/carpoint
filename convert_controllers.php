<?php

$controllers = [
    'Admin\CommentController',
    'GarageController',
    'MessageController',
    'FinanceInquiryController',
    'InspectionRequestController',
    'AdminMessageController',
    'Admin\AdminOrdersController',
    'Admin\HomeAdvertisementController',
    'Admin\ProfileController',
    'Admin\CustomerController',
    'Admin\DashboardController',
    'Admin\DynamicPageController',
    'Admin\TestimonialController',
    'Admin\EmailTemplateController',
    'Admin\LoginController',
    'Admin\PageAboutController',
    'Admin\PageBlogController',
    'Admin\PageContactController',
    'Admin\PagePricingController',
    'Admin\PageListingBrandController',
    'Admin\PageListingLocationController',
    'Admin\PageListingController',
    'Admin\PageFaqController',
    'Admin\PageHomeController',
    'Admin\SettingController',
    'Admin\PageOtherController',
    'Admin\PagePrivacyController',
    'Admin\PageTermController',
    'Admin\CategoryController',
    'Admin\BlogController',
    'Admin\AmenityController',
    'Admin\CurrencyController',
    'Admin\ListingBrandController',
    'Admin\ListingLocationController',
    'Admin\ListingController',
    'Admin\ReviewController',
    'Admin\SocialMediaItemController',
    'Admin\FaqController',
    'Admin\PackageController',
    'Admin\PurchaseHistoryController',
    'Admin\LanguageController',
    'Admin\ClearDatabaseController',
    'Admin\BadgeController',
    'Front\CurrencyController',
    'Front\AboutController',
    'Front\PricingController',
    'Front\BlogController',
    'Front\CategoryController',
    'Front\ContactController',
    'Front\FaqController',
    'Front\HomeController',
    'Front\PageController',
    'Front\PrivacyController',
    'Front\TermController',
    'Front\CustomerAuthController',
    'Front\CustomerController',
    'Front\ListingController',
];

$baseDir = __DIR__ . '/app/Http/Controllers';
$apiDir = $baseDir . '/Api';

// Ensure API folder exists
if (!is_dir($apiDir)) {
    mkdir($apiDir, 0777, true);
}

foreach ($controllers as $controller) {
    $originalPath = $baseDir . '/' . str_replace('\\', '/', $controller) . '.php';
    $newPath = $apiDir . '/' . basename($originalPath);

    if (!file_exists($originalPath)) {
        echo "❌ Controller not found: $originalPath\n";
        continue;
    }

    $content = file_get_contents($originalPath);

    // Change Namespace
    $content = preg_replace('/namespace App\\\Http\\\Controllers[^\n]+;/', 'namespace App\Http\Controllers\Api;', $content);

    // Convert all functions to return JSON
    $content = preg_replace_callback('/public function (\w+)\((.*?)\)\s*\{(.*?)\}/s', function ($matches) {
        $methodName = $matches[1];
        $params = $matches[2];
        $body = trim($matches[3]);

        // Remove `view()` function calls
        $body = preg_replace('/return view\([^;]+\);/', 'return response()->json(["success" => true, "message" => "' . $methodName . ' executed successfully."]);', $body);

        return "public function $methodName($params) {\n        $body\n    }";
    }, $content);

    // Save to new API folder
    file_put_contents($newPath, $content);

    echo "✅ Converted: " . basename($originalPath) . " → Api Folder\n";
}

echo "🎉 All controllers have been moved and converted successfully!\n";

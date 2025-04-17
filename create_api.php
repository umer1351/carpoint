<?php
$routes = [
    [
        'method' => 'GET',
        'url' => 'https://listing.kmshopnow.com/api/profile/profile',
        'name' => 'Get Profile',
        'auth' => true
    ],
    [
        'method' => 'POST',
        'url' => 'https://listing.kmshopnow.com/api/profile/profile-update',
        'name' => 'Update Profile',
        'auth' => true,
        'body' => [
            'name' => 'required|string',
            'email' => 'required|email'
        ]
    ],
    [
        'method' => 'GET',
        'url' => 'https://listing.kmshopnow.com/api/profile/password',
        'name' => 'Get Password Info',
        'auth' => true
    ],
    [
        'method' => 'POST',
        'url' => 'https://listing.kmshopnow.com/api/profile/password-update',
        'name' => 'Update Password',
        'auth' => true,
        'body' => [
            'password' => 'required|string',
            're_password' => 'required|string|same:password'
        ]
    ],
    [
        'method' => 'GET',
        'url' => 'https://listing.kmshopnow.com/api/profile/photo',
        'name' => 'Get Photo Info',
        'auth' => true
    ],
    [
        'method' => 'POST',
        'url' => 'https://listing.kmshopnow.com/api/profile/photo-update',
        'name' => 'Update Photo',
        'auth' => true,
        'body' => [
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]
    ],
    [
        'method' => 'GET',
        'url' => 'https://listing.kmshopnow.com/api/profile/banner',
        'name' => 'Get Banner Info',
        'auth' => true
    ],
    [
        'method' => 'POST',
        'url' => 'https://listing.kmshopnow.com/api/profile/banner-update',
        'name' => 'Update Banner',
        'auth' => true,
        'body' => [
            'banner' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]
    ],
    [
        'method' => 'GET',
        'url' => 'https://listing.kmshopnow.com/api/listing/detail/{slug}',
        'name' => 'Get Listing Detail',
        'auth' => false
    ],
    [
        'method' => 'GET',
        'url' => 'https://listing.kmshopnow.com/api/listing/brand-all',
        'name' => 'Get All Brands',
        'auth' => false
    ],
    [
        'method' => 'GET',
        'url' => 'https://listing.kmshopnow.com/api/listing/brand-detail/{slug}',
        'name' => 'Get Brand Detail',
        'auth' => false
    ],
    [
        'method' => 'GET',
        'url' => 'https://listing.kmshopnow.com/api/listing/location-all',
        'name' => 'Get All Locations',
        'auth' => false
    ],
    [
        'method' => 'GET',
        'url' => 'https://listing.kmshopnow.com/api/listing/location-detail/{slug}',
        'name' => 'Get Location Detail',
        'auth' => false
    ],
    [
        'method' => 'GET',
        'url' => 'https://listing.kmshopnow.com/api/listing/agent-detail/{type}/{id}',
        'name' => 'Get Agent Detail',
        'auth' => false
    ],
    [
        'method' => 'POST',
        'url' => 'https://listing.kmshopnow.com/api/listing/listing-result',
        'name' => 'Post Listing Result',
        'auth' => false,
        'body' => [
            'search_criteria' => 'required|array'
        ]
    ],
    [
        'method' => 'POST',
        'url' => 'https://listing.kmshopnow.com/api/inspection-request/{listingId}',
        'name' => 'Request Inspection',
        'auth' => true
    ],
    [
        'method' => 'GET',
        'url' => 'https://listing.kmshopnow.com/api/seller/inspection-requests',
        'name' => 'Get Seller Inspection Requests',
        'auth' => true
    ],
    [
        'method' => 'PUT',
        'url' => 'https://listing.kmshopnow.com/api/inspection-request/{id}/status/{status}',
        'name' => 'Update Inspection Status',
        'auth' => true
    ],
    [
        'method' => 'GET',
        'url' => 'https://listing.kmshopnow.com/api/home',
        'name' => 'Get Home Data',
        'auth' => false
    ],
    [
        'method' => 'GET',
        'url' => 'https://listing.kmshopnow.com/api/garages',
        'name' => 'Get Garages',
        'auth' => true
    ],
    [
        'method' => 'POST',
        'url' => 'https://listing.kmshopnow.com/api/garages',
        'name' => 'Store Garage',
        'auth' => true,
        'body' => [
            'name' => 'required|string',
            'location' => 'required|string'
        ]
    ],
    [
        'method' => 'POST',
        'url' => 'https://listing.kmshopnow.com/api/garages/{garageId}/services',
        'name' => 'Add Service to Garage',
        'auth' => true,
        'body' => [
            'service_name' => 'required|string'
        ]
    ],
    [
        'method' => 'POST',
        'url' => 'https://listing.kmshopnow.com/api/submit-inquiry/{listingId}',
        'name' => 'Submit Finance Inquiry',
        'auth' => true
    ],
    [
        'method' => 'GET',
        'url' => 'https://listing.kmshopnow.com/api/admin-inquiries',
        'name' => 'Get Admin Inquiries',
        'auth' => true
    ],
    [
        'method' => 'GET',
        'url' => 'https://listing.kmshopnow.com/api/faqs',
        'name' => 'Get FAQs',
        'auth' => false
    ],
    [
        'method' => 'GET',
        'url' => 'https://listing.kmshopnow.com/api/category/{slug}',
        'name' => 'Get Category Detail',
        'auth' => false
    ],
    [
        'method' => 'GET',
        'url' => 'https://listing.kmshopnow.com/api/badges',
        'name' => 'Get Badges',
        'auth' => true
    ],
    [
        'method' => 'GET',
        'url' => 'https://listing.kmshopnow.com/api/badge/{id}',
        'name' => 'Get Badge Detail',
        'auth' => true
    ],
    [
        'method' => 'POST',
        'url' => 'https://listing.kmshopnow.com/api/badge',
        'name' => 'Store Badge',
        'auth' => true,
        'body' => [
            'badge_name' => 'required|string'
        ]
    ],
    [
        'method' => 'PUT',
        'url' => 'https://listing.kmshopnow.com/api/badge/{id}',
        'name' => 'Update Badge',
        'auth' => true,
        'body' => [
            'badge_name' => 'required|string'
        ]
    ],
    [
        'method' => 'DELETE',
        'url' => 'https://listing.kmshopnow.com/api/badge/{id}',
        'name' => 'Delete Badge',
        'auth' => true
    ],
    [
        'method' => 'POST',
        'url' => 'https://listing.kmshopnow.com/api/badge/{id}/status',
        'name' => 'Change Badge Status',
        'auth' => true
    ],
    [
        'method' => 'GET',
        'url' => 'https://listing.kmshopnow.com/api/amenities',
        'name' => 'Get Amenities',
        'auth' => true
    ],
    [
        'method' => 'GET',
        'url' => 'https://listing.kmshopnow.com/api/amenity/{id}',
        'name' => 'Get Amenity Detail',
        'auth' => true
    ],
    [
        'method' => 'POST',
        'url' => 'https://listing.kmshopnow.com/api/amenity',
        'name' => 'Store Amenity',
        'auth' => true,
        'body' => [
            'amenity_name' => 'required|string'
        ]
    ],
    [
        'method' => 'PUT',
        'url' => 'https://listing.kmshopnow.com/api/amenity/{id}',
        'name' => 'Update Amenity',
        'auth' => true,
        'body' => [
            'amenity_name' => 'required|string'
        ]
    ],
    [
        'method' => 'DELETE',
        'url' => 'https://listing.kmshopnow.com/api/amenity/{id}',
        'name' => 'Delete Amenity',
        'auth' => true
    ],
    [
        'method' => 'GET',
        'url' => 'https://listing.kmshopnow.com/api/admin/orders',
        'name' => 'Get Admin Orders',
        'auth' => true
    ],
    [
        'method' => 'PUT',
        'url' => 'https://listing.kmshopnow.com/api/admin/orders/{order}/approve',
        'name' => 'Approve Admin Order',
        'auth' => true
    ],
    [
        'method' => 'PUT',
        'url' => 'https://listing.kmshopnow.com/api/admin/orders/{order}/reject',
        'name' => 'Reject Admin Order',
        'auth' => true
    ],
    [
        'method' => 'GET',
        'url' => 'https://listing.kmshopnow.com/api/terms-and-conditions',
        'name' => 'Get Terms and Conditions',
        'auth' => false
    ]
];

function createPostmanCollection($routes) {
    $collection = [
        "info" => [
            "name" => "API Collection",
            "schema" => "https://schema.getpostman.com/json/collection/v2.1.0/collection.json"
        ],
        "item" => []
    ];

    foreach ($routes as $route) {
        $item = [
            "name" => $route['name'],
            "request" => [
                "method" => $route['method'],
                "url" => [
                    "raw" => $route['url'],
                    "host" => [parse_url($route['url'], PHP_URL_HOST)],
                    "path" => [parse_url($route['url'], PHP_URL_PATH)],
                ],
                "header" => $route['auth'] ? [["key" => "Authorization", "value" => "Bearer {token}"]] : [],
            ]
        ];

        if (isset($route['body'])) {
            $item['request']['body'] = [
                "mode" => "formdata",
                "formdata" => []
            ];

            foreach ($route['body'] as $key => $value) {
                $item['request']['body']['formdata'][] = [
                    "key" => $key,
                    "value" => "",
                    "type" => "text"
                ];
            }
        }

        $collection['item'][] = $item;
    }

    // Save to JSON file
    file_put_contents('postman_collection.json', json_encode($collection, JSON_PRETTY_PRINT));
}

// Call function to create collection
createPostmanCollection($routes);
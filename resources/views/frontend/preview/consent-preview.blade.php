<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consent Banner Preview</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        
        .preview-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .preview-header {
            background-color: #fff;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .preview-header h1 {
            margin: 0 0 10px 0;
            font-size: 24px;
        }
        
        .preview-header p {
            margin: 0;
            color: #666;
            font-size: 16px;
        }
        
        .preview-site {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            min-height: 500px;
        }
        
        .fake-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
            margin-bottom: 20px;
        }
        
        .fake-logo {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }
        
        .fake-nav {
            display: flex;
            gap: 20px;
        }
        
        .fake-nav-item {
            color: #333;
            text-decoration: none;
            font-size: 16px;
        }
        
        .fake-content {
            display: flex;
            gap: 20px;
        }
        
        .fake-main {
            flex: 2;
        }
        
        .fake-sidebar {
            flex: 1;
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 6px;
        }
        
        .fake-title {
            font-size: 22px;
            margin: 0 0 15px 0;
        }
        
        .fake-text {
            font-size: 16px;
            line-height: 1.6;
            color: #444;
            margin: 0 0 15px 0;
        }
        
        .fake-image {
            width: 100%;
            height: 200px;
            background-color: #eee;
            margin-bottom: 15px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="preview-content">
        <div class="preview-header">
            <h1>Consent Banner Preview for {{ $configuration->name }}</h1>
            <p>This is how your consent banner will appear on your website with the current settings.</p>
        </div>
        
        <div class="preview-site">
            <div class="fake-header">
                <div class="fake-logo">Website Title</div>
                <div class="fake-nav">
                    <a href="#" class="fake-nav-item">Home</a>
                    <a href="#" class="fake-nav-item">About</a>
                    <a href="#" class="fake-nav-item">Services</a>
                    <a href="#" class="fake-nav-item">Contact</a>
                </div>
            </div>
            
            <div class="fake-content">
                <div class="fake-main">
                    <h2 class="fake-title">Welcome to our Website</h2>
                    <div class="fake-image">Example Image</div>
                    <p class="fake-text">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam auctor, 
                        nisl eget ultricies aliquam, nunc sapien aliquet nunc, vitae aliquam
                        nisl nunc eget nisl. Nullam auctor, nisl eget ultricies aliquam, nunc
                        sapien aliquet nunc, vitae aliquam nisl nunc eget nisl.
                    </p>
                    <p class="fake-text">
                        Donec euismod, nisl eget ultricies aliquam, nunc sapien aliquet nunc,
                        vitae aliquam nisl nunc eget nisl. Nullam auctor, nisl eget ultricies
                        aliquam, nunc sapien aliquet nunc, vitae aliquam nisl nunc eget nisl.
                    </p>
                </div>
                
                <div class="fake-sidebar">
                    <h3>Recent Posts</h3>
                    <ul>
                        <li>Example Post 1</li>
                        <li>Example Post 2</li>
                        <li>Example Post 3</li>
                        <li>Example Post 4</li>
                    </ul>
                    
                    <h3>Categories</h3>
                    <ul>
                        <li>Category 1</li>
                        <li>Category 2</li>
                        <li>Category 3</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Include the consent script with the configuration ID -->
    <script id="consentment-cmp" 
        src="{{ url('/js/cmp/loader.js') }}" 
        data-settings-id="{{ $configuration->id }}" 
        async></script>
</body>
</html>
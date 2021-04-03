<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>SSO Admin</title>

    <link rel="icon" href="/images/favicon.png"/>
    <link rel="stylesheet" href="/libs/libs.1592875210656.css"/>
    <link rel="stylesheet" href="{{ mix('/css/style.css') }}"/>
</head>

<body>
    <div id="app"></div>

    <script>
        // Đường dẫn SSO
        const SSO_CONSUMER_DOMAIN = "{{ config('services.sso.consumerDomain') }}";
        const SSO_PASSPORT_URL = "{{ config('services.sso.passportUrl') }}";
    </script>

    <script src="/libs/libs.1592875635316.js"></script>

    <!-- Summernote -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote-bs4.css"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote-bs4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/lang/summernote-vi-VN.min.js"></script>

    <script src="{{ mix('/js/script.js') }}"></script>
</body>
</html>

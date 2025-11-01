<!doctype html>
<html lang="bn">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Header from PDF screenshot</title>

    <!-- Optional: Google font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --accent: #2aa07a;
            /* green/teal like screenshot */
            --muted: #6b7280;
            --line: #2f3a46;
            --max-width: 980px;
            --gap: 18px;
            font-family: "Inter", system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
        }

        body {
            margin: 0;
            padding: 36px;
            background: #fff;
            color: #111827;
        }

        .header {
            max-width: var(--max-width);
            margin: 0 auto;
            display: flex;
            align-items: center;
            gap: 32px;
            position: relative;
            padding: 8px 12px;
        }

        /* logo */
        .logo-box {
            flex: 0 0 160px;
            /* reserve left space for logo */
            display: flex;
            align-items: center;
            justify-content: flex-start;
        }

        .logo-box img {
            width: 120px;
            /* adjust as needed */
            height: auto;
            display: block;
        }

        /* contact area */
        .contact {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 8px;
            padding-right: 8px;
        }

        .contact-list {
            display: flex;
            flex-direction: column;
            gap: 6px;
            align-items: flex-end;
            min-width: 280px;
        }

        .contact-item {
            display: inline-flex;
            gap: 10px;
            align-items: center;
            font-size: 15px;
            color: var(--muted);
        }

        .contact-item strong {
            color: #0f172a;
            font-weight: 600;
            margin-left: 4px;
        }

        .icon {
            width: 18px;
            height: 18px;
            flex: 0 0 18px;
            display: inline-block;
            vertical-align: middle;
            fill: var(--accent);
        }

        /* small thin line extending from under contact area (like screenshot) */
        .divider {
            height: 2px;
            background: var(--line);
            border-radius: 2px;
            width: 70%;
            margin-top: 20px;
            align-self: flex-end;
            opacity: 0.9;
        }

        /* Responsive */
        @media (max-width:720px) {
            .header {
                flex-direction: column;
                align-items: flex-start;
                gap: 14px;
            }

            .logo-box {
                width: 100%;
                justify-content: flex-start;
            }

            .contact {
                width: 100%;
                align-items: flex-start;
                padding-right: 0;
            }

            .contact-list {
                align-items: flex-start;
                min-width: unset;
            }

            .divider {
                width: 100%;
                align-self: flex-start;
            }
        }
    </style>
</head>

<body>

    <header class="header" role="banner">
        <!-- LEFT: logo -->
        <div class="logo-box" aria-hidden="true">
            <!-- Replace 'logo.png' with your actual image path (or embed base64 if needed) -->
            <img src="{{asset('ui/backend/assets/build/images/logo.png')}}" alt="TravHub logo">
        </div>

        <!-- RIGHT: contact -->
        <div class="contact" role="contentinfo" aria-label="Contact information">
            <nav class="contact-list" aria-label="Contact items">
                <!-- Phone -->
                <div class="contact-item">
                    <!-- phone icon (inline SVG) -->
                    <svg class="icon" viewBox="0 0 24 24" aria-hidden="true">
                        <path
                            d="M6.62 10.79a15.053 15.053 0 006.59 6.59l2.2-2.2a1 1 0 01.95-.26c1.05.26 2.19.4 3.38.4a1 1 0 011 1V20a1 1 0 01-1 1C9.94 21 3 14.06 3 5a1 1 0 011-1h3.5a1 1 0 011 1c0 1.19.14 2.33.4 3.38a1 1 0 01-.27.95l-2.01 2.46z" />
                    </svg>
                    <span><strong>+8801611482773</strong></span>
                </div>

                <!-- Email -->
                <div class="contact-item">
                    <svg class="icon" viewBox="0 0 24 24" aria-hidden="true">
                        <path
                            d="M20 4H4a2 2 0 00-2 2v12a2 2 0 002 2h16a2 2 0 002-2V6a2 2 0 00-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z" />
                    </svg>
                    <span><strong>travhubxyz@gmail.com</strong></span>
                </div>

                <!-- Address -->
                <div class="contact-item">
                    <svg class="icon" viewBox="0 0 24 24" aria-hidden="true">
                        <path
                            d="M12 2a7 7 0 00-7 7c0 5.25 7 13 7 13s7-7.75 7-13a7 7 0 00-7-7zm0 9.5A2.5 2.5 0 1112 6a2.5 2.5 0 010 5.5z" />
                    </svg>
                    <span>H# 12,R# 21, Sec# 4, Uttara, Dhaka-1230</span>
                </div>
            </nav>

            <div class="divider" aria-hidden="true"></div>
        </div>
    </header>

</body>

</html>

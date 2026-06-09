<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>

    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --secondary: #06b6d4;
            --accent: #f97316;
            --dark: #0f172a;
            --muted: #64748b;
            --soft: #f8fafc;
            --white: #ffffff;
            --border: rgba(148, 163, 184, .26);
            --shadow: 0 30px 80px rgba(15, 23, 42, .16);
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            min-height: 100%;
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif;
        }

        body {
            min-height: 100vh;
            color: var(--dark);
            overflow: hidden;
            background:
                radial-gradient(circle at top left, rgba(37, 99, 235, .22), transparent 34%),
                radial-gradient(circle at bottom right, rgba(6, 182, 212, .20), transparent 32%),
                linear-gradient(135deg, #eff6ff 0%, #ffffff 46%, #eef2ff 100%);
        }

        .error-page {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 32px;
        }

        .error-page::before,
        .error-page::after {
            content: "";
            position: absolute;
            width: 360px;
            height: 360px;
            border-radius: 999px;
            filter: blur(8px);
            opacity: .52;
            pointer-events: none;
        }

        .error-page::before {
            top: -120px;
            left: -110px;
            background: linear-gradient(135deg, rgba(37, 99, 235, .20), rgba(6, 182, 212, .14));
        }

        .error-page::after {
            right: -130px;
            bottom: -130px;
            background: linear-gradient(135deg, rgba(249, 115, 22, .16), rgba(37, 99, 235, .12));
        }

        .error-card {
            position: relative;
            z-index: 1;
            width: min(100%, 820px);
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, .86);
            border-radius: 34px;
            background: rgba(255, 255, 255, .82);
            box-shadow: var(--shadow);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
        }

        .error-card::before {
            content: "";
            position: absolute;
            inset: 0;
            background:
                linear-gradient(90deg, rgba(37, 99, 235, .12), transparent 30%, rgba(6, 182, 212, .10)),
                radial-gradient(circle at 80% 18%, rgba(249, 115, 22, .12), transparent 24%);
            pointer-events: none;
        }

        .error-content {
            position: relative;
            display: grid;
            grid-template-columns: .9fr 1.1fr;
            gap: 28px;
            align-items: center;
            padding: 58px;
        }

        .error-visual {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 280px;
        }

        .error-code-shadow {
            position: absolute;
            inset: auto;
            color: rgba(37, 99, 235, .08);
            font-size: clamp(120px, 18vw, 220px);
            font-weight: 900;
            line-height: .8;
            letter-spacing: -14px;
            user-select: none;
        }

        .error-orbit {
            position: relative;
            width: 230px;
            height: 230px;
            border: 1px solid rgba(37, 99, 235, .16);
            border-radius: 999px;
            background:
                linear-gradient(145deg, rgba(255, 255, 255, .96), rgba(255, 255, 255, .62)),
                radial-gradient(circle at 34% 28%, rgba(6, 182, 212, .18), transparent 34%);
            box-shadow:
                inset 0 1px 0 rgba(255, 255, 255, .95),
                0 22px 50px rgba(37, 99, 235, .14);
        }

        .error-orbit::before,
        .error-orbit::after {
            content: "";
            position: absolute;
            border-radius: 999px;
        }

        .error-orbit::before {
            inset: 28px;
            border: 1px dashed rgba(37, 99, 235, .24);
        }

        .error-orbit::after {
            width: 22px;
            height: 22px;
            top: 22px;
            right: 44px;
            background: linear-gradient(135deg, var(--accent), #facc15);
            box-shadow: 0 10px 26px rgba(249, 115, 22, .28);
        }

        .error-code {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-dark);
            font-size: clamp(54px, 8vw, 86px);
            font-weight: 900;
            line-height: 1;
            letter-spacing: -5px;
        }

        .error-message-area {
            position: relative;
            padding-left: 28px;
            border-left: 1px solid var(--border);
        }

        .error-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 86px;
            min-height: 42px;
            margin-bottom: 18px;
            padding: 8px 18px;
            border: 1px solid rgba(37, 99, 235, .16);
            border-radius: 999px;
            background: rgba(37, 99, 235, .07);
            color: var(--primary-dark);
            font-size: 15px;
            font-weight: 800;
            letter-spacing: .08em;
        }

        .error-message {
            margin: 0;
            color: var(--dark);
            font-size: clamp(28px, 5vw, 54px);
            font-weight: 900;
            line-height: 1.02;
            letter-spacing: -1.8px;
            text-transform: uppercase;
        }

        .error-line {
            width: 96px;
            height: 6px;
            margin-top: 26px;
            border-radius: 999px;
            background: linear-gradient(90deg, var(--primary), var(--secondary), var(--accent));
            box-shadow: 0 12px 28px rgba(37, 99, 235, .18);
        }

        .decor {
            position: absolute;
            border-radius: 999px;
            pointer-events: none;
        }

        .decor-1 {
            width: 12px;
            height: 12px;
            top: 40px;
            left: 42px;
            background: var(--secondary);
            opacity: .58;
        }

        .decor-2 {
            width: 18px;
            height: 18px;
            right: 54px;
            bottom: 48px;
            background: var(--accent);
            opacity: .48;
        }

        .decor-3 {
            width: 70px;
            height: 70px;
            right: 42px;
            top: 36px;
            border: 1px solid rgba(37, 99, 235, .14);
            background: rgba(255, 255, 255, .35);
        }

        @media (max-width: 768px) {
            body {
                overflow: auto;
            }

            .error-page {
                padding: 20px;
            }

            .error-content {
                grid-template-columns: 1fr;
                gap: 10px;
                padding: 38px 28px;
                text-align: center;
            }

            .error-visual {
                min-height: 220px;
            }

            .error-orbit {
                width: 190px;
                height: 190px;
            }

            .error-code-shadow {
                font-size: 150px;
                letter-spacing: -10px;
            }

            .error-message-area {
                padding-left: 0;
                border-left: 0;
            }

            .error-line {
                margin-left: auto;
                margin-right: auto;
            }
        }
    </style>
</head>

<body>
    <main class="error-page">
        <section class="error-card">
            <span class="decor decor-1"></span>
            <span class="decor decor-2"></span>
            <span class="decor decor-3"></span>

            <div class="error-content">
                <div class="error-visual">
                    <div class="error-code-shadow">@yield('code')</div>

                    <div class="error-orbit">
                        <div class="error-code">@yield('code')</div>
                    </div>
                </div>

                <div class="error-message-area">
                    <div class="error-badge">@yield('code')</div>
                    <h1 class="error-message">@yield('message')</h1>
                    <div class="error-line"></div>
                </div>
            </div>
        </section>
    </main>
</body>

</html>

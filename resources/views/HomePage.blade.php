<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="duck.png" type="image/x-icon">
    <title>Home Page</title>

    <style>
        * {
            border: 0;
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --hue: 223;
            --bg: hsl(var(--hue), 90%, 10%);
            --fg: hsl(var(--hue), 90%, 90%);
            --primary: hsl(var(--hue), 90%, 50%);
            --trans-dur: 0.3s;
            font-size: calc(20px + (40 - 20) * (100vw - 320px) / (2560 - 320));
        }

        body {
            background-color: var(--bg);
            color: var(--fg);
            display: flex;
            flex-direction: column;
            font: 1em/1.5 sans-serif;
            height: 100vh;
            transition:
                background-color var(--trans-dur),
                color var(--trans-dur);
        }

        .container {
            width: 100%;
            height: 100%;
            padding-top: 40px;
            justify-content: center;
            align-items: center;
        }

        .pl {
            --dur: 3s;
            margin: auto;
            position: relative;
            width: 10em;
            height: 10em;
        }

        .pl__nucleus,
        .pl__nucleus-particle {
            position: absolute;
        }

        .pl__nucleus {
            top: 50%;
            left: 50%;
            transform-style: preserve-3d;
        }

        .pl__nucleus-particle,
        .pl__ring,
        .pl__orbit,
        .pl__electron {
            animation: particleTop var(--dur) linear infinite;
        }

        .pl__nucleus-particle {
            background-color: var(--primary);
            background-image: radial-gradient(37.5% 37.5% at 37.5% 37.5%, hsla(var(--hue), 10%, 90%, 0.25) 48%, hsla(var(--hue), 10%, 90%, 0) 50%);
            border-radius: 50%;
            box-shadow: -0.125em -0.125em 0 hsla(var(--hue), 10%, 10%, 0.25) inset;
            top: calc(50% - 0.5em);
            left: calc(50% - 0.5em);
            width: 1em;
            height: 1em;
            transition: background-color var(--trans-dur);
        }

        .pl__nucleus-particle:nth-child(2n) {
            background-color: hsl(var(--hue), 90%, 70%);
        }

        .pl__nucleus-particle:nth-child(n + 4):nth-child(-n + 9) {
            animation-name: particleMiddle;
        }

        .pl__nucleus-particle:nth-child(n + 10):nth-child(-n + 12) {
            animation-name: particleBottom;
        }


        .pl__nucleus-particle:nth-child(2) {
            animation-delay: calc(var(--dur) * -0.33);
        }

        .pl__nucleus-particle:nth-child(3) {
            animation-delay: calc(var(--dur) * -0.67);
        }

        .pl__nucleus-particle:nth-child(5) {
            animation-delay: calc(var(--dur) * -0.17);
        }

        .pl__nucleus-particle:nth-child(6) {
            animation-delay: calc(var(--dur) * -0.33);
        }

        .pl__nucleus-particle:nth-child(7) {
            animation-delay: calc(var(--dur) * -0.5);
        }

        .pl__nucleus-particle:nth-child(8) {
            animation-delay: calc(var(--dur) * -0.67);
        }

        .pl__nucleus-particle:nth-child(9) {
            animation-delay: calc(var(--dur) * -0.83);
        }

        .pl__nucleus-particle:nth-child(11) {
            animation-delay: calc(var(--dur) * -0.33);
        }

        .pl__nucleus-particle:nth-child(12) {
            animation-delay: calc(var(--dur) * -0.67);
        }

        .pl__nucleus-particle:last-child {
            animation: none;
        }

        .pl__rings {
            display: block;
            width: 100%;
            height: auto;
        }

        .pl__ring {
            animation-name: ring;
            transform-origin: 64px 64px;
        }

        .pl__ring:nth-child(n + 5):nth-child(-n + 7) {
            animation-delay: calc(var(--dur) * -0.25);
        }

        .pl__orbit,
        .pl__electron {
            animation-name: orbit;
            transition: stroke var(--trans-dur);
        }

        .pl__orbit:first-child {
            stroke: hsla(var(--hue), 90%, 50%, 0);
        }

        .pl__orbit:nth-child(2) {
            stroke: hsla(var(--hue), 90%, 50%, 0.5);
        }

        .pl__orbit:nth-child(3) {
            stroke: hsla(var(--hue), 90%, 50%, 0.7);
        }

        .pl__ring:first-child .pl__orbit:first-child {
            stroke: hsla(var(--hue), 90%, 50%, 0.3);
        }

        .pl__ring:nth-child(2) .pl__orbit {
            animation-delay: calc(var(--dur) * -0.125);
        }

        .pl__ring:nth-child(3) .pl__orbit {
            animation-delay: calc(var(--dur) * -0.25);
        }

        .pl__ring:nth-child(4) .pl__orbit {
            animation-delay: calc(var(--dur) * -0.375);
        }

        .pl__ring:nth-child(5) .pl__orbit:first-child {
            stroke: hsla(var(--hue), 90%, 50%, 0.3);
        }

        .pl__ring:nth-child(6) .pl__orbit {
            animation-delay: calc(var(--dur) * -0.25);
        }

        .pl__ring:nth-child(7) .pl__electron:nth-child(2) {
            animation-delay: calc(var(--dur) * -0.25);
        }

        .pl__ring:nth-child(8) .pl__electron:nth-child(2) {
            animation-delay: calc(var(--dur) * -0.125);
        }

        .pl__ring:nth-child(8) .pl__electron:nth-child(3) {
            animation-delay: calc(var(--dur) * -0.25);
        }

        .pl__ring:nth-child(8) .pl__electron:nth-child(4) {
            animation-delay: calc(var(--dur) * -0.375);
        }

        /* Animations */
        @keyframes orbit {
            from {
                stroke-dashoffset: 0;
            }

            to {
                stroke-dashoffset: 580;
            }
        }

        @keyframes ring {
            from {
                transform: rotate(0);
            }

            to {
                transform: rotate(1turn);
            }
        }

        @keyframes particleTop {
            from {
                transform: rotateY(0) rotateZ(-35deg) translateY(-100%) rotateZ(35deg) rotateY(0);
            }

            to {
                transform: rotateY(-1turn) rotateZ(-35deg) translateY(-100%) rotateZ(35deg) rotateY(1turn);
            }
        }

        @keyframes particleMiddle {
            from {
                transform: rotateY(0) rotateZ(90deg) translateY(-100%) rotateZ(-90deg) rotateY(0);
            }

            to {
                transform: rotateY(-1turn) rotateZ(90deg) translateY(-100%) rotateZ(-90deg) rotateY(1turn);
            }
        }

        @keyframes particleBottom {
            from {
                transform: rotateY(0) rotateZ(-145deg) translateY(-100%) rotateZ(145deg) rotateY(0);
            }

            to {
                transform: rotateY(-1turn) rotateZ(-145deg) translateY(-100%) rotateZ(145deg) rotateY(1turn);
            }
        }

        .header {
            height: 95vh;
            clip-path: polygon(0 0, 100% 0, 100% 75vh, 0 100%);
        }

        .header__logo-box {
            top: 4rem;
            left: 4rem;
        }
    </style>
</head>
<header class="header">
    <div class="header__logo-box">
        <img src={{ url('https://cdn-icons-png.flaticon.com/512/2999/2999463.png', []) }} alt="Logo" width="26px"
            height="26px" class="header__logo"
            style="
            margin-top: 16px;
            margin-left: 16px;
            margin-right: 16px;
            margin-bottom: 16px;

            ">
    </div>
    <div class="header__text-box">
        <h1 class="heading-primary">
            <span class="heading-primary--main">Outdoors</span>
            <span class="heading-primary--sub">is where life happens</span>
        </h1>
        <a href="#" class="btn btn--white btn--animated">Discover our tours</a>
    </div>
</header>

<body>
    <div class="container">
        <div class="pl"><svg class="pl__rings" viewBox="0 0 128 128" width="128px" height="128px">
                <g fill="none" stroke-linecap="round" stroke-width="4">
                    <g class="pl__ring" transform="rotate(0)">
                        <ellipse class="pl__orbit" cx="64" cy="64" rx="60" ry="30"
                            stroke="hsla(223,90%,50%,0.3)" />
                        <ellipse class="pl__orbit" cx="64" cy="64" rx="60" ry="30"
                            stroke="hsla(223,90%,50%,0.5)" stroke-dasharray="50 240" />
                        <ellipse class="pl__orbit" cx="64" cy="64" rx="60" ry="30"
                            stroke="hsl(223,90%,50%)" stroke-dasharray="25 265" />
                    </g>
                    <g class="pl__ring" transform="rotate(0)">
                        <ellipse class="pl__orbit" cx="64" cy="64" rx="60" ry="30"
                            stroke="hsla(223,90%,50%,0)" />
                        <ellipse class="pl__orbit" cx="64" cy="64" rx="60" ry="30"
                            stroke="hsla(223,90%,50%,0.5)" stroke-dasharray="50 240" />
                        <ellipse class="pl__orbit" cx="64" cy="64" rx="60" ry="30"
                            stroke="hsl(223,90%,50%)" stroke-dasharray="25 265" />
                    </g>
                    <g class="pl__ring" transform="rotate(0)">
                        <ellipse class="pl__orbit" cx="64" cy="64" rx="60" ry="30"
                            stroke="hsla(223,90%,50%,0)" />
                        <ellipse class="pl__orbit" cx="64" cy="64" rx="60" ry="30"
                            stroke="hsla(223,90%,50%,0.5)" stroke-dasharray="50 240" />
                        <ellipse class="pl__orbit" cx="64" cy="64" rx="60" ry="30"
                            stroke="hsl(223,90%,50%)" stroke-dasharray="25 265" />
                    </g>
                    <g class="pl__ring" transform="rotate(0)">
                        <ellipse class="pl__orbit" cx="64" cy="64" rx="60" ry="30"
                            stroke="hsla(223,90%,50%,0)" />
                        <ellipse class="pl__orbit" cx="64" cy="64" rx="60" ry="30"
                            stroke="hsla(223,90%,50%,0.5)" stroke-dasharray="50 240" />
                        <ellipse class="pl__orbit" cx="64" cy="64" rx="60" ry="30"
                            stroke="hsl(223,90%,50%)" stroke-dasharray="25 265" />
                    </g>
                    <g class="pl__ring" transform="rotate(180)">
                        <ellipse class="pl__orbit" cx="64" cy="64" rx="60" ry="30"
                            stroke="hsla(223,90%,50%,0.3)" />
                        <ellipse class="pl__orbit" cx="64" cy="64" rx="60" ry="30"
                            stroke="hsla(223,90%,50%,0.5)" stroke-dasharray="50 240" />
                        <ellipse class="pl__orbit" cx="64" cy="64" rx="60" ry="30"
                            stroke="hsl(223,90%,50%)" stroke-dasharray="25 265" />
                    </g>
                    <g class="pl__ring" transform="rotate(180)">
                        <ellipse class="pl__orbit" cx="64" cy="64" rx="60" ry="30"
                            stroke="hsla(223,90%,50%,0)" />
                        <ellipse class="pl__orbit" cx="64" cy="64" rx="60" ry="30"
                            stroke="hsla(223,90%,50%,0.5)" stroke-dasharray="50 240" />
                        <ellipse class="pl__orbit" cx="64" cy="64" rx="60" ry="30"
                            stroke="hsl(223,90%,50%)" stroke-dasharray="25 265" />
                    </g>
                    <g class="pl__ring" transform="rotate(0)">
                        <ellipse class="pl__electron" cx="64" cy="64" rx="60" ry="30"
                            stroke="hsl(0,0%,100%)" stroke-dasharray="1 289" stroke-width="8" />
                        <ellipse class="pl__electron" cx="64" cy="64" rx="60" ry="30"
                            stroke="hsl(0,0%,100%)" stroke-dasharray="1 289" stroke-width="8" />
                    </g>
                    <g class="pl__ring" transform="rotate(180)">
                        <ellipse class="pl__electron" cx="64" cy="64" rx="60" ry="30"
                            stroke="hsl(0,0%,100%)" stroke-dasharray="1 289" stroke-width="8" />
                        <ellipse class="pl__electron" cx="64" cy="64" rx="60" ry="30"
                            stroke="hsl(0,0%,100%)" stroke-dasharray="1 289" stroke-width="8" />
                        <ellipse class="pl__electron" cx="64" cy="64" rx="60" ry="30"
                            stroke="hsl(0,0%,100%)" stroke-dasharray="1 289" stroke-width="8" />
                        <ellipse class="pl__electron" cx="64" cy="64" rx="60" ry="30"
                            stroke="hsl(0,0%,100%)" stroke-dasharray="1 289" stroke-width="8" />
                    </g>
                </g>
            </svg>
            <div class="pl__nucleus">
                <div class="pl__nucleus-particle"></div>
                <div class="pl__nucleus-particle"></div>
                <div class="pl__nucleus-particle"></div>
                <div class="pl__nucleus-particle"></div>
                <div class="pl__nucleus-particle"></div>
                <div class="pl__nucleus-particle"></div>
                <div class="pl__nucleus-particle"></div>
                <div class="pl__nucleus-particle"></div>
                <div class="pl__nucleus-particle"></div>
                <div class="pl__nucleus-particle"></div>
                <div class="pl__nucleus-particle"></div>
                <div class="pl__nucleus-particle"></div>
                <div class="pl__nucleus-particle"></div>
            </div>
        </div>
    </div>

</body>

</html>

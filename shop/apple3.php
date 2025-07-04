<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>맛있는 사과 고르는 방법</title>
    <link href="https://fonts.googleapis.com/css2?family=SUITE:wght@400;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --main-green: #6db37f;
            --main-gray: #f5f6fa;
            --main-dark: #222;
            --main-white: #fff;
            --main-border: #e0e0e0;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'SUITE', 'Noto Sans KR', system-ui, sans-serif;
            background: var(--main-gray);
            color: var(--main-dark);
            min-height: 100vh;
        }
        .nav {
            width: 100%;
            background: var(--main-white);
            border-bottom: 1px solid var(--main-border);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 64px;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .nav .logo {
            font-size: 1.6em;
            font-weight: 700;
            color: var(--main-dark);
            letter-spacing: 1px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .nav .logo span {
            color: var(--main-green);
            font-size: 1.2em;
        }
        main {
            max-width: 700px;
            margin: 48px auto 0 auto;
            background: var(--main-white);
            border-radius: 18px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.03);
            padding: 48px 32px 56px 32px;
        }
        h1 {
            font-size: 2.2em;
            font-weight: 700;
            text-align: center;
            margin-bottom: 36px;
            letter-spacing: 0.5px;
        }
        .section {
            margin-bottom: 38px;
        }
        .section-title {
            font-size: 1.25em;
            font-weight: 700;
            color: var(--main-green);
            margin-bottom: 12px;
            letter-spacing: 0.2px;
        }
        .section-content {
            font-size: 1.08em;
            color: #444;
            line-height: 1.85;
        }
        .highlight {
            background: var(--main-green);
            color: var(--main-white);
            border-radius: 4px;
            padding: 2px 7px;
            font-weight: 600;
        }
        .back-btn {
            display: inline-block;
            margin: 32px auto 0 auto;
            padding: 12px 32px;
            background: var(--main-green);
            color: var(--main-white);
            border: none;
            border-radius: 24px;
            font-size: 1em;
            font-weight: 600;
            text-decoration: none;
            text-align: center;
            transition: background 0.2s;
            box-shadow: none;
        }
        .back-btn:hover {
            background: #4e8e5f;
        }
        footer {
            width: 100%;
            background: var(--main-white);
            border-top: 1px solid var(--main-border);
            color: #888;
            text-align: center;
            font-size: 0.98em;
            padding: 18px 0 12px 0;
            margin-top: 48px;
        }
        @media (max-width: 600px) {
            main { padding: 24px 8px 40px 8px; }
            h1 { font-size: 1.3em; }
            .section-title { font-size: 1.05em; }
        }
    </style>
</head>
<body>
    <nav class="nav">
        <a href="index.php" class="logo"><span>🍏</span>AppRoad</a>
    </nav>
    <main>
        <h1>맛있는 사과 고르는 방법</h1>
        <div class="section">
            <div class="section-title">과일의 색</div>
            <div class="section-content">
                착색이 잘 된것이 단맛이 강하고 향기도 진하다. 봉지를 씌운 사과는 외관상 좋으나 맛이 떨어지므로 표면이 거칠어도 봉지를 씌우지 않은 것이 맛이 더 좋다.<br>
                녹황색 계통의 사과는 녹색이 짙을수록 덜 익은 것이다. 적색 계통의 사과는 꼭지의 반대편 부분, 녹황색 계통의 것은 껍질에 녹색이 적으며 노란 느낌이 드는 것이 잘 익은 것이다.
            </div>
        </div>
        <div class="section">
            <div class="section-title">과일의 향기</div>
            <div class="section-content">
                사과 향기의 성분은 알콜류 92%, 에스텔류 2%, 칼보닐 6%, 산류(미량)이다. 사과 특유의 향기는 에스텔류의 종류와 양에 따른다.<br>
                잘 익은 것일수록 좋은 향기를 내니 냄새를 맡아 확인한다. 덜 익거나 너무 익어서 발효하는 냄새를 풍기는 것은 맛이 좋지 않다.
            </div>
        </div>
        <div class="section">
            <div class="section-title">과일의 크기, 모양, 무게</div>
            <div class="section-content">
                그 해에 수확된 사과 중에서 크기가 중간정도인 것이 대체로 맛이 좋다. 지나치게 사과알이 큰 것은 육질이 엉성하고 맛이 얕으며,
                반대로 알이 작은 것은 덜 익었거나 단맛이 부족한 경향이 있다. 모양은 품종 고유의 형태인 것이 대체로 맛이 균일하다.<br>
                같은 크기의 사과도 무거운(비중이 큰) 것일수록 잘 익은 것이 많다.
            </div>
        </div>
        <div class="section">
            <div class="section-title">꼭지가 신선하고 소리가 맑은 것이 좋다</div>
            <div class="section-content">
                사과 꼭지가 신선하며 마르지 않은 것이 신선도가 높다. 사과를 손톱으로 가볍게 두드릴 때 맑은 소리가 나면 신선하고 비맞은 북소리가 나는 것은 신선도가 떨어진다는 것을 뜻한다.
            </div>
        </div>
        <a href="./index.php" class="back-btn">돌아가기</a>
    </main>
    <footer>
        &copy; 2024 AppRoad | 충주사과 | info@approad.com
    </footer>
</body>
</html>

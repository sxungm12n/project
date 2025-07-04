<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>충주사과</title>
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
        <h1>충주사과</h1>
        <div class="section">
            <div class="section-title">소개</div>
            <div class="section-content">
                충주사과는 <span class="highlight">맛과 향이 으뜸이고 빛깔이 고우며 과육이 단단해서 저장력이 좋습니다</span>.<br>
                충주는 일교차가 크고 일조량이 많아 전국 제일의 사과고장입니다.
            </div>
        </div>
        <div class="section">
            <div class="section-title">기후 조건</div>
            <div class="section-content">
                연평균 기온이 7~14℃, 생육기간동안(4~10월)의 평균온도 13~21℃, 여름철(6~8월) 26℃를 넘지 않는 곳이 좋고
                4~10월에 걸친 생육기 1,300㎜ 이하의 강수량이 적당합니다.<br><br>
                강수량이 너무 많으면 배수 불량한 곳에서는 습해가 발생하고 가지가 도장하여 꽃눈수가 감소하고,
                병충해가 많고, 과실 품질이 저하됩니다. 햇빛은 과수의 생장, 꽃눈형성, 착과 및 과일 발육에 큰 영향을 주게 되며
                햇빛이 부족하면 동화물질의 축적이 적어져서 꽃눈형성과 결실이 불량해지고 과일의 비대는 나빠지게 됩니다.
            </div>
        </div>
        <div class="section">
            <div class="section-title">충주사과의 잇점</div>
            <div class="section-content">
                1907년 개량종 사과가 도입되었으며, 1912년 지현동에서 식재한 조생종사과가 효시가 되어
                현재 도내에서 가장 넓은 면적을 차지하며 재배되고 있습니다. 다른 생산지에 비해 일교차가 크고 일조량이 풍부하여,
                <span class="highlight">빛깔, 당도, 향기에서 으뜸을 차지하고 있습니다</span>.<br>
                수려한 자연환경과 어울려 충주시민의 인내와 진실함을 표현하는 대표적인 특산물입니다.
            </div>
        </div>
        <a href="./index.php" class="back-btn">돌아가기</a>
    </main>
    <footer>
        &copy; 2024 AppRoad | 충주사과 | info@approad.com
    </footer>
</body>
</html>

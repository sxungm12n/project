# 💊 VitaView (비타뷰)

복용 중인 영양제 성분을 OCR 기술로 인식하여, 섭취량을 분석하고 부족·과다 여부 및 적절한 식품을 추천하는 Flutter 앱입니다.

## 📌 프로젝트 개요

### 목표
- 영양제 성분표 사진을 OCR로 인식하여 자동으로 영양소 정보 추출
- 보건복지부 권장량과 비교하여 부족/과다 분석
- 과다 시 부작용 안내, 부족 시 음식 추천
- 사용자가 안전하고 효율적으로 복용량을 관리할 수 있는 도구 제공

### 개발 배경
- 건강에 대한 관심 증가, 비타민 인기 상승
- 정보 부족으로 인한 영양제 과다/중복 복용 문제
- 사용자 친화적인 영양제 관리 도구의 필요성 증가

## 🚀 주요 기능

### 📸 OCR 기능
- 영양제 성분표 사진 업로드 → 텍스트 인식 후 영양소별 수치 추출
- 보건복지부 권장량과 비교하여 부족/과다 표시
- 과다 시 부작용 안내, 부족 시 음식 추천

### 📱 앱 기능
- **사용자 관리**: 회원가입, 로그인, 로그아웃, 정보 수정, 회원탈퇴
- **영양제 관리**: OCR로 영양제 정보 등록, 수정, 삭제
- **영양소 분석**: 부족/과다 영양소 분석 및 시각화
- **추천 시스템**: 음식 추천 및 부작용 설명
- **커뮤니티**: 리뷰 작성, 댓글, 게시글 관리
- **개인화**: 성별/나이별 맞춤 영양소 권장량 제공

## 🆚 기존 앱과의 차별점

| 구분 | 기존 앱 | VitaView |
|------|---------|----------|
| 영양제 추가 구매 유도 | ✅ | ❌ |
| 음식 기반 보충 권장 | ❌ | ✅ |
| 수동 입력 필요 | ✅ | ❌ |
| OCR로 자동 인식 | ❌ | ✅ |
| 추천 기능 제한 | ✅ | ❌ |
| 부작용 설명 제공 | ❌ | ✅ |

## ⚙️ 기술 스택

### Frontend
- **Flutter (Dart)**: 크로스 플랫폼 모바일 앱 개발
- **GetX**: 상태 관리 및 라우팅
- **HTTP**: API 통신

### Backend
- **Flask (Python)**: RESTful API 서버
- **SQLAlchemy**: ORM
- **JWT**: 인증 시스템

### Database
- **MySQL**: 메인 데이터베이스
- **데이터베이스 스키마**: 사용자, 영양제, 게시글, 댓글 등

### AI/ML
- **Google Cloud Vision API**: OCR 텍스트 인식
- **한국인 영양섭취기준(2020)**: 권장량 기준 데이터

## 📋 설치 및 설정

### 1. 환경 요구사항
- **Flutter**: 3.0 이상
- **Python**: 3.8 이상
- **MySQL**: 8.0 이상
- **Google Cloud Platform**: Vision API 활성화

### 2. Flutter 앱 설정
```bash
# 프로젝트 클론
git clone [repository-url]
cd projectvita

# 의존성 설치
flutter pub get

# 앱 실행
flutter run
```

### 3. Flask 서버 설정
```bash
cd lib/python

# Python 가상환경 생성 (권장)
python -m venv venv
source venv/bin/activate  # Windows: venv\Scripts\activate

# 의존성 설치
pip install -r requirements.txt
```

### 4. 환경 설정
1. `config.example.py` 파일을 `config.py`로 복사:
```bash
cp config.example.py config.py
```

2. `config.py` 파일에서 실제 값으로 변경:
```python
# 데이터베이스 설정
DB_USER = 'YOUR_DB_USER'          # 실제 데이터베이스 사용자명
DB_PASSWORD = 'YOUR_DB_PASSWORD'  # 실제 데이터베이스 비밀번호
DB_HOST = 'YOUR_DB_HOST'          # 실제 데이터베이스 호스트
DB_PORT = 'YOUR_DB_PORT'          # 실제 데이터베이스 포트
DB_NAME = 'YOUR_DB_NAME'          # 실제 데이터베이스 이름

# Google Cloud Vision API 설정
GOOGLE_APPLICATION_CREDENTIALS_PATH = 'credentials/google-credentials.json'

# JWT 설정
JWT_SECRET_KEY = 'YOUR_JWT_SECRET_KEY'  # 실제 JWT 비밀 키
```

### 5. Google Cloud Vision API 설정
1. [Google Cloud Console](https://console.cloud.google.com/)에서 프로젝트 생성
2. Vision API 활성화
3. 서비스 계정 키 생성 및 다운로드
4. 파일명을 `google-credentials.json`으로 변경
5. `lib/python/credentials/` 폴더에 저장

### 6. 데이터베이스 설정
1. MySQL에서 새 데이터베이스 생성
2. `lib/python/create_database.sql` 파일 실행:
```sql
mysql -u root -p < create_database.sql
```

### 7. 서버 실행
```bash
cd lib/python
python app.py
```

서버는 `http://localhost:5000`에서 실행됩니다.

## 🗂️ 프로젝트 구조

```
projectvita/
├── lib/
│   ├── python/                    # Flask 백엔드
│   │   ├── credentials/           # API 키 파일 (Git에서 제외)
│   │   │   ├── README.md
│   │   │   └── google-credentials.json
│   │   ├── config.py              # 설정 파일 (Git에서 제외)
│   │   ├── config.example.py      # 설정 예시
│   │   ├── app.py                 # Flask 메인 애플리케이션
│   │   ├── create_database.sql    # 데이터베이스 스키마
│   │   ├── requirements.txt       # Python 의존성
│   │   └── README.md              # 서버 설정 가이드
│   ├── screens/                   # Flutter 화면
│   │   ├── screen_login.dart      # 로그인 화면
│   │   ├── screen_sign.dart       # 회원가입 화면
│   │   └── screen_splash.dart     # 스플래시 화면
│   ├── tab/                       # 탭 화면들
│   │   ├── tab_home.dart          # 홈 탭
│   │   ├── tab_plus.dart          # 영양제 추가
│   │   ├── tab_community.dart     # 커뮤니티
│   │   └── tab_my_info.dart       # 내 정보
│   └── vita/                      # 영양소 정보 파일들
├── android/                       # Android 설정
├── ios/                          # iOS 설정
├── web/                          # Web 설정
└── pubspec.yaml                  # Flutter 의존성
```

## 🔧 데이터베이스 구조

### 주요 테이블
- **users**: 사용자 정보 (ID, 사용자명, 비밀번호, 이름, 생년월일, 성별)
- **tonic_detail**: 영양제 상세 정보 (영양소별 함량, 복용량)
- **posts**: 커뮤니티 게시글
- **post_comments**: 게시글 댓글
- **reviews**: 리뷰
- **review_comments**: 리뷰 댓글

## 🎨 사용된 기술

### Frontend
- **Flutter**: 크로스 플랫폼 모바일 앱
- **Dart**: 프로그래밍 언어
- **GetX**: 상태 관리 및 라우팅
- **HTTP**: API 통신

### Backend
- **Flask**: Python 웹 프레임워크
- **SQLAlchemy**: ORM
- **JWT**: 인증 시스템
- **CORS**: 크로스 오리진 리소스 공유

### Database
- **MySQL**: 관계형 데이터베이스

### AI/ML
- **Google Cloud Vision API**: OCR 텍스트 인식

## 📱 시연 흐름

1. **스플래시** → 로그인/회원가입
2. **사진 업로드**: 카메라/갤러리에서 영양제 성분표 촬영
3. **OCR 처리**: 성분 추출 후 영양소 정보 저장
4. **분석 결과**: 부족/과다/적정 상태 표시
5. **추천 시스템**: 음식 추천 및 부작용 안내
6. **커뮤니티**: 리뷰 및 게시글 작성, 댓글
7. **개인 관리**: 정보 수정, 로그아웃, 회원탈퇴

## 🔮 향후 계획

### 현재 상태
- Google OCR API 사용 중

### 향후 개발 예정
- **자체 OCR 모델 직접 구현**
- **딥러닝 기반 영양소 인식 정확도 향상**
- **컴퓨터비전 기술 고도화**
- **개인화된 영양소 추천 알고리즘 개발**

## 📝 라이선스

이 프로젝트는 교육 목적으로 제작되었습니다.

## 🤝 기여하기

1. Fork the Project
2. Create your Feature Branch (`git checkout -b feature/AmazingFeature`)
3. Commit your Changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the Branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📞 문의

프로젝트에 대한 문의사항이 있으시면 이슈를 생성해 주세요.

## 🔒 보안 주의사항

- `config.py` 파일은 Git에서 제외됩니다
- `credentials/` 폴더의 모든 파일은 Git에서 제외됩니다
- 절대 민감한 정보를 공개 저장소에 업로드하지 마세요 
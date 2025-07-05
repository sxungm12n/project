# Flask 서버

Flutter 앱과 연동할 수 있는 Flask 백엔드 서버입니다.

## 설치 및 실행

### 1. 의존성 설치
```bash
pip install -r requirements.txt
```

### 2. 환경 설정
1. `config.example.py` 파일을 `config.py`로 복사하세요:
```bash
cp config.example.py config.py
```

2. `config.py` 파일에서 다음 설정들을 실제 값으로 변경하세요:

```python
# 데이터베이스 설정
DB_USER = 'YOUR_DB_USER'          # 실제 데이터베이스 사용자명
DB_PASSWORD = 'YOUR_DB_PASSWORD'  # 실제 데이터베이스 비밀번호
DB_HOST = 'YOUR_DB_HOST'          # 실제 데이터베이스 호스트
DB_PORT = 'YOUR_DB_PORT'          # 실제 데이터베이스 포트
DB_NAME = 'YOUR_DB_NAME'          # 실제 데이터베이스 이름

# Google Cloud Vision API 설정
GOOGLE_APPLICATION_CREDENTIALS_PATH = 'credentials/google-credentials.json'  # Google API 키 파일 경로

# JWT 설정
JWT_SECRET_KEY = 'YOUR_JWT_SECRET_KEY'  # 실제 JWT 비밀 키
```

### 3. Google Cloud Vision API 설정
1. Google Cloud Console에서 서비스 계정 키를 다운로드
2. 파일명을 `google-credentials.json`으로 변경
3. `lib/python/credentials/` 폴더에 저장

### 4. 서버 실행
```bash
python app.py
```

서버는 `http://localhost:5000`에서 실행됩니다.

## API 엔드포인트

### 기본 엔드포인트
- `GET /` - 서버 상태 확인
- `GET /api/hello` - Hello 메시지
- `POST /api/data` - 데이터 수신
- `GET /api/users` - 사용자 목록 조회

## Flutter 앱과 연동

Flutter 앱에서 이 서버에 접근하려면:

```dart
// HTTP 요청 예시
final response = await http.get(Uri.parse('http://localhost:5000/api/hello'));
```

## 개발 모드

- 디버그 모드가 활성화되어 있어 코드 변경 시 자동으로 서버가 재시작됩니다.
- CORS가 설정되어 있어 Flutter 앱에서 API 호출이 가능합니다. 
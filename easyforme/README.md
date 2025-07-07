# 🚀 EasyFormy (이지포미) - 외국인을 위한 AI 민원 서비스

외국인 비전문·계절근로자를 위한 AI 기반 종합 민원 서비스 애플리케이션입니다.

## 📊 프로젝트 배경

### 문제 상황
- **체류 외국인 증가**: 2021년 196만 명 → 2024년 265만 명 (35% 증가)
- **인프라 부족**: 정부 지원 35억 원 감소, 지원센터 인력 절반 감소
- **현장 문제**: 언어 장벽, 복잡한 서식, 번거로운 절차

### 타겟 사용자
- **외국인 비전문·계절근로자**: 약 32만 5천 명 (전체 체류 외국인의 18.7%)
- **특징**: 짧은 체류 기간, 높은 민원 비중, 낮은 한국어 수준

## 📱 주요 기능

### 🤖 AI 챗봇 서비스
- **다국어 지원**: 80개 언어로 실시간 번역 및 대화
- **음성 인식/합성**: STT(Speech-to-Text) 및 TTS(Text-to-Speech) 지원
- **RAG 기반 답변**: 1,146개 문서 기반 정확한 정보 제공
- **4단계 추론 프롬프팅**: 법적 해석과 판단을 위한 고도화된 AI
- **FAQ 시스템**: 자주 묻는 질문에 대한 빠른 답변
- **공공데이터 활용**: 하이코리아 출입국/체류 안내 데이터 기반

### 📋 신청서 관리
- **E-8/E-9 비자 신청**: 외국인등록, 체류기간 연장, 근무처 변경
- **통합신청서 자동 작성**: 40개 필드 분석, 실시간 PDF 생성
- **진정서 자동 생성**: 31개 필드, 6개 핵심 질문으로 본문 작성
- **문서 업로드**: Azure Blob Storage를 통한 안전한 파일 관리
- **진행 상황 추적**: 실시간 신청 상태 확인

### 👤 사용자 관리
- **개인정보 관리**: 기본 정보, 여권, 외국인등록증 정보 관리
- **OCR 기능**: 여권 및 외국인등록증 자동 정보 추출 (Azure OCR API)
- **비자 정보 관리**: E-8/E-9 비자 정보 및 연장 관리
- **자동 만료일 계산**: 입국일 기준 체류기간 자동 계산

### 🔐 보안 및 인증
- **JWT 토큰 기반 인증**: 안전한 사용자 인증
- **관리자 권한**: 신청서 승인/거절 및 파일 관리
- **데이터 암호화**: 민감한 정보 보호

## 🛠 기술 스택

### Frontend (React Native)
- **React Native**: 크로스 플랫폼 모바일 앱
- **Expo**: 개발 환경 및 배포 플랫폼
- **Azure Translator**: 실시간 다국어 번역
- **Azure Speech Service**: 음성 인식/합성

### Backend (Python Flask)
- **Flask**: 웹 API 서버
- **SQLAlchemy**: ORM 및 데이터베이스 관리
- **Azure의 MySQL**: 관계형 데이터베이스
- **Azure OpenAI**: GPT-4o 기반 AI 챗봇
- **Azure AI Search**: RAG(Retrieval-Augmented Generation)
- **Azure Blob Storage**: 파일 저장소
- **Azure OCR**: 문서 정보 자동 추출
- **Langchain**: 대화 흐름 관리 및 품질 향상
- **ConversationalRetrievalChain**: 4단계 추론 프롬프팅 구현

### DevOps & Cloud
- **Azure Cloud**: 클라우드 인프라
- **GitHub**: 버전 관리 및 CI/CD
- **Docker**: 컨테이너화 (선택사항)
- **Gunicorn**: 프로덕션 서버 실행

## 🚀 배포 가이드

### 1. 환경 설정

#### Backend 환경변수 설정
`.env.txt` 파일을 생성하고 다음 환경변수를 설정하세요:

```env
# Database Configuration
MYSQL_USER=your_mysql_user
MYSQL_PASSWORD=your_mysql_password
MYSQL_HOST=your_mysql_host
MYSQL_PORT=3306
MYSQL_DB=your_database_name

# JWT Configuration
JWT_SECRET_KEY=your_jwt_secret_key

# Azure Storage Configuration
AZURE_ACCOUNT_NAME=your_azure_storage_account
AZURE_ACCOUNT_KEY=your_azure_storage_key
AZURE_CONTAINER=your_container_name

# Azure Translator Configuration
AZURE_TRANSLATOR_KEY=your_translator_key
AZURE_TRANSLATOR_ENDPOINT=https://api.cognitive.microsofttranslator.com
AZURE_TRANSLATOR_LOCATION=eastus

# Azure OpenAI Configuration
AZURE_OPENAI_API_KEY=your_openai_key
AZURE_OPENAI_ENDPOINT=your_openai_endpoint
AZURE_OPENAI_DEPLOYMENT_NAME=gpt-4o

# Azure AI Search Configuration
AZURE_AI_SEARCH_ENDPOINT=your_search_endpoint
AZURE_AI_SEARCH_KEY=your_search_key
AZURE_AI_SEARCH_INDEX=your_search_index

# Azure Speech Service Configuration
AZURE_SPEECH_KEY=your_speech_key
AZURE_SPEECH_REGION=eastus

# OCR Configuration
OCR_ENDPOINT=your_ocr_endpoint
OCR_KEY=your_ocr_key

# Azure Document Intelligence (for PDF processing)
AZURE_DOCUMENT_INTELLIGENCE_ENDPOINT=your_document_intelligence_endpoint
AZURE_DOCUMENT_INTELLIGENCE_KEY=your_document_intelligence_key
```

#### Frontend 환경변수 설정
`src/config/config.js` 파일에서 서버 URL을 설정하세요:

```javascript
export const SERVER_URL = 'https://your-production-server.com';
```

### 2. 데이터베이스 설정

#### MySQL 데이터베이스 생성
```sql
CREATE DATABASE your_database_name CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

#### 테이블 자동 생성
Flask 앱 실행 시 SQLAlchemy가 자동으로 테이블을 생성합니다.

### 3. Backend 배포

#### 방법 1: Azure App Service
```bash
# Azure CLI 설치 후
az login
az webapp up --name your-app-name --resource-group your-resource-group --runtime "PYTHON:3.9"
```

#### 방법 2: Docker 배포
```dockerfile
FROM python:3.9-slim

WORKDIR /app
COPY requirements.txt .
RUN pip install -r requirements.txt

COPY . .
EXPOSE 5000

CMD ["python", "app.py"]
```

#### 방법 3: 직접 서버 배포
```bash
# 서버에서 실행
pip install -r requirements.txt
python app.py
```

### 4. Frontend 배포

#### Expo 배포
```bash
# Expo CLI 설치
npm install -g @expo/cli

# 프로젝트 빌드
expo build:android  # Android
expo build:ios      # iOS

# 또는 EAS Build 사용
eas build --platform all
```

#### APK/IPA 파일 생성
```bash
# Android APK
expo build:android -t apk

# iOS IPA
expo build:ios -t archive
```

### 5. Azure 서비스 설정

#### Azure Speech Service
1. Azure Portal에서 Speech Service 생성
2. 키와 지역 정보를 환경변수에 설정

#### Azure Blob Storage
1. Storage Account 생성
2. Container 생성
3. 액세스 키를 환경변수에 설정

#### Azure AI Search
1. Search Service 생성
2. 인덱스 생성 및 데이터 업로드
3. 키와 엔드포인트를 환경변수에 설정

## 📁 프로젝트 구조

```
seongmin/
├── app.py                          # Flask 백엔드 서버
├── requirements.txt                # Python 의존성
├── .env.txt                       # 환경변수 설정
├── fonts/                         # 폰트 파일 (NotoSansKR)
├── uploads/                       # 업로드된 파일
├── 외국인등록신청서.pdf            # 신청서 템플릿
├── 진정서.pdf                     # 진정서 템플릿
├── src/
│   ├── assets/                    # 이미지 및 로고
│   ├── components/                # 재사용 가능한 컴포넌트
│   ├── config/                    # 설정 파일
│   ├── context/                   # React Context
│   ├── data/                      # 정적 데이터 (immigrationOffices.json)
│   ├── navigation/                # 네비게이션 설정
│   ├── screens/                   # 화면 컴포넌트
│   └── utils/                     # 유틸리티 함수 (translation.js, faqData.js)
├── package.json                   # Node.js 의존성
└── app.json                      # Expo 설정
```

## 🗄️ 데이터베이스 구조

### 데이터베이스 개요
- **데이터베이스**: MySQL 8.0
- **ORM**: SQLAlchemy 2.0.41
- **연결**: Azure Database for MySQL
- **문자셋**: utf8mb4 (한글 지원)

### 테이블 관계도
```
User (1) ←→ (1) Passport
User (1) ←→ (1) ResidenceCard  
User (1) ←→ (1) Visa
User (1) ←→ (N) Application
Application (1) ←→ (N) ApplicationFile
```

### 테이블 상세 구조

#### 1. User 테이블 (사용자 기본 정보)
```sql
CREATE TABLE user (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    birth_date DATE NOT NULL,
    email VARCHAR(120) UNIQUE NOT NULL,
    username VARCHAR(80) UNIQUE NOT NULL,
    password VARCHAR(200) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
```
- **용도**: 사용자 계정 및 기본 정보 관리
- **특징**: 이메일, 사용자명 중복 방지

#### 2. Passport 테이블 (여권 정보)
```sql
CREATE TABLE passport (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    surname VARCHAR(100),
    givenname VARCHAR(100),
    passport_number VARCHAR(20),
    nationality VARCHAR(100),
    sex VARCHAR(1),
    country_code VARCHAR(3),
    issue_date DATE,
    expiry_date DATE,
    is_verified BOOLEAN DEFAULT FALSE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES user(id)
);
```
- **용도**: Azure OCR로 자동 추출된 여권 정보 저장
- **특징**: 1:1 관계 (User-Passport)

#### 3. ResidenceCard 테이블 (외국인등록증 정보)
```sql
CREATE TABLE residence_card (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    name_kor VARCHAR(100),
    resident_id VARCHAR(20),
    visa_type VARCHAR(100),
    issue_date DATE,
    is_verified BOOLEAN DEFAULT FALSE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES user(id)
);
```
- **용도**: Azure OCR로 자동 추출된 외국인등록증 정보 저장
- **특징**: 1:1 관계 (User-ResidenceCard)

#### 4. Visa 테이블 (비자 정보)
```sql
CREATE TABLE visa (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    visa_type VARCHAR(10) NOT NULL,  -- E-8 or E-9
    entry_date DATE NOT NULL,
    expiry_date DATE NOT NULL,
    extension_start DATE,
    extension_end DATE,
    extension_count INT DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES user(id)
);
```
- **용도**: E-8/E-9 비자 정보 및 연장 관리
- **특징**: 자동 만료일 계산, 연장 횟수 추적

#### 5. Application 테이블 (신청서 정보)
```sql
CREATE TABLE application (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    type VARCHAR(50) NOT NULL,  -- e8Registration, e9Extension 등
    status VARCHAR(20) NOT NULL DEFAULT 'pending',  -- pending, approved, rejected
    submitted_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    notes TEXT,
    FOREIGN KEY (user_id) REFERENCES user(id)
);
```
- **용도**: 신청서 정보 및 상태 관리
- **신청 유형**: E-8/E-9 등록, 연장, 근무처 변경
- **상태**: pending → processing → approved/rejected

#### 6. ApplicationFile 테이블 (업로드된 파일 정보)
```sql
CREATE TABLE application_file (
    id INT PRIMARY KEY AUTO_INCREMENT,
    application_id INT NOT NULL,
    doc_id VARCHAR(50) NOT NULL,  -- application_form, passport 등
    file_name VARCHAR(255) NOT NULL,
    file_url VARCHAR(500) NOT NULL,
    uploaded_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (application_id) REFERENCES application(id) ON DELETE CASCADE
);
```
- **용도**: Azure Blob Storage에 업로드된 파일 정보 관리
- **특징**: CASCADE 삭제, 문서 유형별 분류

### 데이터베이스 특징

#### 1. 관리자 계정
- **관리자 ID**: 10000101 (고정)
- **관리자 계정**: ms7team/ms7team
- **권한**: 신청서 승인/거절, 파일 관리

#### 2. OCR 연동
- **Azure OCR API** 활용
- **자동 정보 추출**: 여권, 외국인등록증
- **검증 플래그**: is_verified 필드로 수동 검증 가능

#### 3. 파일 관리
- **Azure Blob Storage** 연동
- **고유 파일명**: UUID 기반 중복 방지
- **문서 유형별 분류**: doc_id로 체계적 관리

#### 4. 비자 연장 시스템
- **자동 계산**: 입국일 기준 체류기간 계산
- **연장 추적**: extension_count로 연장 횟수 관리
- **만료일 관리**: 자동 만료일 업데이트

#### 5. 신청서 워크플로우
```
신청서 작성 → 파일 업로드 → 제출 → 관리자 검토 → 승인/거절
```

### 인덱스 및 성능 최적화
```sql
-- 사용자 조회 최적화
CREATE INDEX idx_user_email ON user(email);
CREATE INDEX idx_user_username ON user(username);

-- 신청서 조회 최적화
CREATE INDEX idx_application_user_status ON application(user_id, status);
CREATE INDEX idx_application_submitted ON application(submitted_at);

-- 파일 조회 최적화
CREATE INDEX idx_application_file_app ON application_file(application_id);
CREATE INDEX idx_application_file_doc ON application_file(doc_id);
```

### 데이터 백업 및 복구
- **자동 백업**: Azure Database for MySQL 자동 백업
- **백업 주기**: 일 1회 (7일 보관)
- **복구**: Point-in-time 복구 지원

## 📚 데이터 수집 및 전처리

### 수집 데이터셋
1. **하이코리아 출입국/체류 안내 데이터**: 공식 출입국 관리 정보
2. **체류/근로 관련 법률 전문**: 관련 법령 및 규정
3. **AI-HUB 민사/행정법 LLM 데이터**: 법률 상담 데이터
4. **상황별 판례 데이터**: 실제 사례 및 판례

### 전처리 과정
- **PDF 처리**: Azure Document Intelligence를 활용한 텍스트 추출
- **구조화**: 마크다운 형식으로 일관된 구조 변환
- **인덱싱**: Azure AI Search를 통한 벡터 검색 최적화
- **청크 처리**: 2000자 오버랩, 200자 단위 분할
- **엔터티 추출**: 날짜, 조직명 등 중요 정보 식별

### AI 추론 시스템

#### 4단계 추론 프롬프팅
복잡한 법적 질문에 대한 정확한 해석과 판단을 위해 4단계 추론 과정을 구현했습니다:

1. **질문 의도 및 법적 쟁점 파악**
   - 사용자 질문의 핵심 의도 분석
   - 관련 법적 쟁점 식별
   - 질문 유형 분류 (절차 문의, 법적 해석, 구체적 사례 등)

2. **관련 법률 조항 검색**
   - Azure AI Search를 통한 관련 법령 검색
   - 하이코리아 데이터에서 관련 조항 추출
   - 법적 근거 명확화

3. **관련 사례 및 판례 제시**
   - 유사한 상황의 실제 사례 검색
   - AI-HUB 판례 데이터에서 관련 사례 추출
   - 구체적 해결 방안 제시

4. **종합적 해결방안 도출**
   - 단계별 분석 결과 종합
   - 사용자 상황에 맞는 구체적 조치사항 제시
   - 다음 단계 안내

#### Langchain Pipeline 구현
```python
# 대화 흐름 관리
memory = ConversationSummaryMemory(
    llm=llm,
    memory_key="chat_history",
    return_messages=True,
    output_key="answer"
)

# 검색기 설정
retriever = AzureCognitiveSearchRetriever(
    service_name=AZURE_AI_SEARCH_ENDPOINT.split("//")[1].split(".")[0],
    index_name=AZURE_AI_SEARCH_INDEX,
    api_key=AZURE_AI_SEARCH_KEY,
    top_k=TOP_N_DOCUMENTS
)

# 프롬프트 템플릿
prompt = ChatPromptTemplate.from_messages([
    ("system", """You are an AI assistant that helps people find information about immigration and residence in Korea. 
    Your task is to provide detailed and accurate answers based on the provided context and conversation history.
    Follow these guidelines:
    1. Use the search results as your primary source of information
    2. Consider the conversation history for context
    3. Include specific details and examples when available
    4. If the search results are not sufficient, acknowledge the limitations
    5. Structure your response in a clear and organized manner
    6. If there are multiple relevant pieces of information, present them in a logical order
    7. Always respond in Korean

    Context: {context}
    Chat History: {chat_history}
    """),
    ("human", "{question}")
])
```

#### 품질 향상 기법
- **중복 문장 제거**: SequenceMatcher를 활용한 유사 문장 필터링
- **키워드 기반 검색**: 핵심 용어 추출 및 검색 정확도 향상
- **의미 기반 검색**: 시맨틱 검색을 통한 맥락 이해
- **하이브리드 검색**: 키워드 + 벡터 검색의 조합

## 🔧 개발 환경 설정

### Backend 개발
```bash
# 가상환경 생성
python -m venv venv
source venv/bin/activate  # Windows: venv\Scripts\activate

# 의존성 설치
pip install -r requirements.txt

# 서버 실행
python app.py
```

### Frontend 개발
```bash
# 의존성 설치
npm install

# 개발 서버 실행
expo start

# 또는
npx expo start
```

## 📊 API 엔드포인트

### 인증
- `POST /login` - 사용자 로그인
- `POST /register` - 사용자 등록 (OCR 자동 정보 추출 포함)
- `GET /user-info` - 사용자 정보 조회

### 챗봇
- `POST /chat-rag` - RAG 기반 챗봇 대화 (4단계 추론 프롬프팅)
- `GET /api/config` - 클라이언트 설정 정보

### OCR 처리
- `POST /analyze-passport` - 여권 정보 자동 추출
- `POST /analyze-residence-card` - 외국인등록증 정보 자동 추출

### 신청서 관리
- `POST /submit_application` - 신청서 제출
- `GET /my/applications` - 내 신청서 조회
- `POST /upload/` - 파일 업로드

### 문서 생성
- `POST /api/generate-registration-pdf` - 통합신청서 PDF 생성
- `POST /api/generate-complaint-pdf` - 진정서 PDF 생성
- `POST /api/generate_complaint_content` - 진정 내용 AI 생성

### 관리자 기능
- `GET /admin/files` - 모든 신청서 조회
- `PUT /admin/application/{id}/status` - 신청 상태 변경
- `DELETE /admin/delete_application_type` - 신청서 삭제

## 🔒 보안 고려사항

1. **환경변수 관리**: 민감한 정보는 환경변수로 관리
2. **JWT 토큰**: 토큰 만료 시간 설정 및 안전한 저장
3. **파일 업로드**: 파일 형식 검증 및 크기 제한
4. **SQL 인젝션 방지**: SQLAlchemy ORM 사용
5. **CORS 설정**: 적절한 CORS 정책 적용
6. **개인정보 암호화**: 사용자 데이터 암호화 저장
7. **AI 답변 제한**: 출처 기반 정보만 제공하여 오답 방지

## 🐛 문제 해결

### 일반적인 문제들

#### Backend 연결 오류
- 환경변수 설정 확인
- 데이터베이스 연결 상태 확인
- Azure 서비스 키 유효성 확인

#### Frontend 빌드 오류
- Node.js 버전 확인 (16.x 이상 권장)
- Expo CLI 업데이트
- 캐시 클리어: `expo r -c`

#### 음성 서비스 오류
- Azure Speech Service 키 확인
- 네트워크 연결 상태 확인
- 마이크 권한 확인

## 🎯 기대 효과

### 사용자 측면
- **언어 장벽 해소**: 80개 언어 지원으로 정보 접근성 향상
- **절차 간소화**: 센터 방문 없이 모바일로 민원 처리
- **자동화**: OCR과 AI를 통한 서류 작성 자동화

### 시스템 측면
- **업무 효율성**: 고용주, 지자체, 지원센터의 민원 업무 부담 감소
- **정확성 향상**: AI 기반 정확한 정보 제공
- **확장성**: 다양한 민원 서비스로 확장 가능

## 🤖 책임 있는 AI

### 6가지 원칙 준수
1. **투명성**: 하이코리아 등 신뢰할 수 있는 공공데이터 사용, 모델 구현 프로세스 명시
2. **책임성**: 출처 기반 정보만 제공하여 AI 오답 가능성 최소화
3. **공정성**: 다국어 번역을 통한 외국인 근로자 배제 방지
4. **신뢰성 및 안정성**: 수집 데이터 기반 답변, 유추 답변 금지
5. **개인정보보호 및 보안**: 데이터 암호화, 환경변수 보안 관리
6. **포용성**: 직관적 UI, 음성/텍스트 기반 접근성 제공

## 📞 지원

- **이슈 리포트**: GitHub Issues 사용
- **문서**: 각 기능별 상세 문서 참조
- **커뮤니티**: 개발자 포럼 참여

## 📄 라이선스

이 프로젝트는 MIT 라이선스 하에 배포됩니다.

## 🤝 기여

1. Fork the Project
2. Create your Feature Branch (`git checkout -b feature/AmazingFeature`)
3. Commit your Changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the Branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 시연 영상  
[https://youtu.be/5AR6a0Vupv4](https://youtu.be/5AR6a0Vupv4)
---

**Made with ❤️ for foreign workers in Korea**

**세뇨린따의 미눠니 팀 (7팀)** 

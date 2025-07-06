# 과학쌤 - AI 기반 과학 교육 챗봇 🚀

🔬 **AI 기반 스마트 티칭 어시스턴트**  
**10팀: 10선비**  
**프로젝트 기간: 2025년 4월 2일 ~ 4월 18일**

따뜻하고 친근한 AI 선생님이 과학을 재미있게 가르쳐주는 웹 서비스입니다! 🌟

## 📌 프로젝트 개요

### 🎯 목적
중학생들의 개념 암기 중심 학습의 한계를 해결하기 위해 자기주도 학습 능력 강화를 돕는 AI 과학 선생님(과학쌤) 시스템 구현

### 🧩 선정 배경
- 대부분의 중학생이 개념 이해 없이 외우는 데 집중함
- 과학 개념의 구조적 이해를 돕기 위해 AI 튜터 필요

### 🛠️ 기술 구성

| 활용 도구 (Azure 기반) | 역할 |
|----------------------|------|
| Speech Studio (STT/TTS) | 음성 텍스트 변환 및 음성 출력 |
| Document Intelligence (OCR) | 교과서 이미지에서 텍스트 추출 |
| Language Studio (Translation) | 다국어 번역 제공 |
| Azure AI Search | RAG 검색 기반 질문 응답 |
| OpenAI GPT | 요약 챗봇, 질문 응답, 이미지 생성 |

### 🧩 전체 구조
- **Frontend**: 웹 UI
- **Backend**: Azure 기반 API, Flask 서버
- **RAG 구조 기반 흐름**:
  1. OCR로 과학 교과서 → JSON화 후 Azure에 저장
  2. 인덱싱하여 사용자 질문에 맞는 chunk 검색
  3. GPT 기반 요약/질문/이미지 생성 연동

## ✨ 주요 기능

### 🤖 AI 챗봇
- **친근한 과학 선생님**: 따뜻하고 격려하는 톤으로 과학 질문에 답변
- **단계별 설명**: 복잡한 개념을 쉽게 이해할 수 있도록 차근차근 설명
- **일상적 예시**: 어려운 과학 개념을 친숙한 상황에 비유하여 설명
- **과학적 호기심 자극**: 재미있는 과학 사실과 추가 정보 제공

### 📚 강의 내용 분석
- **다양한 파일 형식 지원**: WAV, TXT, DOCX, PPTX, XLSX, XLS, PDF
- **음성-텍스트 변환**: 오디오 파일을 텍스트로 자동 변환
- **스마트 요약**: 강의 내용을 마크다운 형식으로 요약
- **맥락 기반 답변**: 업로드된 강의 내용을 바탕으로 정확한 답변

### 🎨 멀티미디어 기능
- **음성 변환**: 텍스트를 자연스러운 음성으로 변환 (TTS)
- **이미지 생성**: 과학 개념을 시각적으로 표현하는 이미지 생성
- **번역 기능**: 다양한 언어로 과학 내용 번역
- **위키백과 검색**: 관련 키워드에 대한 추가 정보 검색

## 💡 핵심 기능 상세

### 1. OCR 교과서 인식
- 비상교육 중학교 과학 교과서를 OCR로 전체 인식
- 문장 단위로 JSON으로 저장
- 각 항목은 설명 단위로 분할

### 2. STT (Speech-to-Text)
- 강의 음성 또는 사용자 질문을 텍스트로 변환
- 긴 wav 파일은 청크(Chunking) 단위로 분할해 처리

### 3. 요약 챗봇
- 텍스트 요약 (Abstract 방식)
- 대화 키워드 기반 내용 요약
- 요약 후 질문 가능한 형태로 변환

### 4. 질문 챗봇
- 요약 챗봇 결과를 기반으로 사용자 질문에 RAG 방식으로 응답
- 요약이 없으면 기본 GPT 응답 사용

### 5. 나무위키 연동
- 대화 키워드 기반으로 나무위키 링크 자동 제공

### 6. Translation (다국어 번역)
- 사용자가 언어를 선택하면 GPT 응답을 자동 번역해 출력

### 7. DALL·E 이미지 생성
- GPT가 생성한 키워드 기반으로 중등 과학 교과서 스타일 이미지 생성
- 사용자가 [그림] 버튼 클릭 시 DALL·E 3가 작동

### 8. TTS (Text-to-Speech)
- 답변 텍스트를 SeoHyeonNeural 보이스로 읽어줌
- 150% 속도로 가청성 향상

## 🛠️ 기술 스택

- **Backend**: Python Flask
- **AI/ML**: Azure OpenAI, Azure Cognitive Search
- **음성 처리**: Azure Speech-to-Text, Text-to-Speech
- **번역**: Microsoft Translator API
- **이미지 생성**: DALL-E API
- **프론트엔드**: HTML, CSS, JavaScript

## 🧪 기술 구현 상세 흐름

```plaintext
1. OCR → JSON 교과서 chunk 저장
2. STT로 음성 인식 (강의/사용자)
3. 요약 챗봇: 키워드 추출 + 마크다운 요약
4. 질문 챗봇: 요약 기반 or RAG 기반 응답
5. 버튼 클릭: 뉴스 크롤링 / 이미지 생성 / 음성 출력
6. 사용자 언어 선택 → 자동 번역
```

## 🧠 기술적 포인트

| 기술 | 설명 |
|------|------|
| Chunking | 긴 강의 파일을 소단위로 나눠 인식 정확도 향상 |
| RAG 구조 | 교과서 인덱스 기반 질문 응답 가능 |
| OCR 자동화 | 교과서 전체 문장을 구조화 |
| 다국어 번역 | 다양한 사용자 대상 학습 가능 |
| DALL·E | 시각적 설명 보완 (과학적 이미지 생성) |
| 나무위키 연동 | 실시간 정보 활용 및 흥미 유발 |

## 📋 요구사항 명세서

### 1. 사용자 요구사항

#### 1.1 기본 요구사항
- 강의 내용을 업로드하고 자동으로 텍스트로 변환할 수 있어야 함
- 강의 내용을 기반으로 질문에 답변할 수 있어야 함
- 모바일 환경에서도 사용할 수 있어야 함
- 사용자 친화적인 인터페이스를 제공해야 함

#### 1.2 기능적 요구사항
- 강의 음성 파일 업로드 및 텍스트 변환
- 강의 내용 요약 및 저장
- 강의 내용 기반 질문-답변
- 마지막 답변 요약 기능
- 관련 뉴스 검색 기능
- 강의 내용 세션 관리

#### 1.3 비기능적 요구사항
- 실시간 응답 (3초 이내)
- 모바일 최적화 UI
- 안정적인 서비스 운영
- 보안성 (파일 업로드 제한, 세션 관리)

### 2. 기능 명세서

#### 2.1 강의 관리 기능

**2.1.1 강의 업로드**
- **기능**: 강의 음성 파일 업로드
- **입력**: 음성 파일 (wav)
- **처리**:
  - 파일 형식 검증
  - 임시 저장
  - STT 변환
- **출력**: 텍스트 변환 결과

**2.1.2 강의 요약**
- **기능**: 강의 내용 자동 요약
- **입력**: 변환된 텍스트
- **처리**:
  - GPT를 통한 요약 생성
  - 구조화된 형식으로 요약 (주제, 개념, 내용, 결론)
- **출력**: 요약된 강의 내용

#### 2.2 챗봇 기능

**2.2.1 질문-답변**
- **기능**: 강의 내용 기반 답변
- **입력**: 사용자 질문
- **처리**:
  - 강의 요약 확인
  - RAG 데이터 검색
  - GPT 응답 생성
- **출력**: 질문에 대한 답변

**2.2.2 답변 요약**
- **기능**: 마지막 답변 요약
- **입력**: 마지막 답변
- **처리**: GPT를 통한 한 문장 요약
- **출력**: 요약된 답변

**2.2.3 뉴스 검색**
- **기능**: 관련 뉴스 검색
- **입력**: 마지막 질문/답변
- **처리**:
  - 키워드 추출
  - 네이버 뉴스 검색
- **출력**: 관련 뉴스 목록

#### 2.3 세션 관리

**2.3.1 강의 내용 저장**
- **기능**: 강의 요약 세션 저장
- **입력**: 강의 요약
- **처리**: 세션에 저장
- **출력**: 저장 성공/실패

**2.3.2 세션 유지**
- **기능**: 세션 유지 관리
- **입력**: 세션 데이터
- **처리**: 7일간 세션 유지
- **출력**: 세션 상태

#### 2.4 UI/UX

**2.4.1 모바일 최적화**
- **기능**: 모바일 환경 지원
- **특징**:
  - 반응형 디자인
  - 터치 인터페이스
  - 모바일 화면 최적화

**2.4.2 사용자 인터페이스**
- **기능**: 사용자 친화적 UI
- **특징**:
  - 직관적인 메뉴
  - 명확한 피드백
  - 에러 메시지 표시

### 3. 시스템 구성
- **프론트엔드**: HTML, CSS, JavaScript
- **백엔드**: Flask (Python)
- **AI 서비스**: Azure OpenAI
- **검색 서비스**: Azure Cognitive Search
- **스토리지**: 로컬 파일 시스템
- **세션 관리**: Flask-Session

### 4. 제한사항
- **파일 크기**: 최대 50MB
- **파일 형식**: wav, mp3, m4a
- **세션 유지**: 7일
- **API 호출 제한**: Azure OpenAI 제한사항 준수

## 📦 설치 방법

### 1. 저장소 클론
```bash
git clone [repository-url]
cd lectur
```

### 2. 가상환경 생성 및 활성화
```bash
python -m venv venv
# Windows
venv\Scripts\activate
# macOS/Linux
source venv/bin/activate
```

### 3. 의존성 설치
```bash
pip install -r requirements.txt
```

### 4. 환경 변수 설정
`.env` 파일을 생성하고 다음 변수들을 설정하세요:

```env
# Azure OpenAI
AZURE_OPENAI_ENDPOINT=your_azure_openai_endpoint
AZURE_OPENAI_DEPLOYMENT=your_deployment_name
AZURE_OPENAI_API_KEY=your_api_key

# Azure Cognitive Search
AZURE_SEARCH_ENDPOINT=your_search_endpoint
AZURE_SEARCH_INDEX=your_index_name
AZURE_SEARCH_KEY=your_search_key
AZURE_SEARCH_SEMANTIC_CONFIG=your_semantic_config

# Azure Speech Services
AZURE_SPEECH_KEY=your_speech_key
AZURE_SPEECH_REGION=your_speech_region
TTS_ENDPOINT=your_tts_endpoint
TTS_KEY=your_tts_key

# Microsoft Translator
TRANSLATOR_ENDPOINT=your_translator_endpoint
TRANSLATOR_KEY=your_translator_key

# DALL-E
DALLE_ENDPOINT=your_dalle_endpoint
DALLE_KEY=your_dalle_key
```

### 5. 서버 실행
```bash
python app.py
```

서버가 `http://127.0.0.1:5500`에서 실행됩니다.

**💡 팁**: 
- 서버가 정상적으로 실행되면 터미널에 "Running on http://127.0.0.1:5500" 메시지가 표시됩니다
- 브라우저에서 해당 주소로 접속하면 과학쌤 웹 인터페이스를 사용할 수 있습니다
- 서버를 중지하려면 터미널에서 `Ctrl+C`를 누르세요

## 🎯 사용 방법

### 1. 데모 영상
프로젝트의 주요 기능들을 확인해보세요!

#### 🎬 기능 소개 영상
[![과학쌤 데모](미디어1.mp4)](https://youtu.be/VVkd1Tf8oAI)

#### 🎯 최종 발표 영상
[![과학쌤 최종 발표](최종.mp4)](https://youtu.be/3d1YKTy72uo)

**💡 참고**: GitHub에 업로드하면 동영상이 자동으로 재생됩니다!

### 2. 웹 인터페이스 접속
브라우저에서 `http://127.0.0.1:5500`에 접속하세요.

### 2. 강의 파일 업로드 (선택사항)
- 상단의 업로드 버튼을 클릭
- 지원되는 파일 형식 중 하나를 선택
- 파일이 자동으로 처리되어 요약 생성

### 3. AI 챗봇과 대화
- 채팅창에 과학 질문을 입력
- 친근한 AI 선생님이 답변해드립니다
- 추가 기능들 활용:
  - **음성 변환**: 답변을 음성으로 들을 수 있어요
  - **이미지 생성**: 과학 개념을 시각화해드려요
  - **번역**: 다른 언어로 번역해드려요
  - **검색**: 관련 정보를 더 찾아드려요

## 📁 프로젝트 구조

```
lectur/
├── app.py                 # 메인 Flask 애플리케이션
├── lecture_recorder.py    # 음성 처리 및 파일 분석
├── requirements.txt       # Python 의존성
├── README.md             # 프로젝트 설명
├── .gitignore            # Git 제외 파일
├── static/               # 정적 파일 (CSS, JS, 이미지)
├── templates/            # HTML 템플릿
└── .env                  # 환경 변수 (별도 생성 필요)
```

## 🔧 지원 파일 형식

### 입력 파일
- **오디오**: WAV (.wav)
- **문서**: TXT (.txt), DOCX (.docx), PPTX (.pptx)
- **스프레드시트**: XLSX (.xlsx), XLS (.xls)
- **PDF**: PDF (.pdf)

### 출력 형식
- **텍스트**: 원본 텍스트 추출
- **요약**: 마크다운 형식의 스마트 요약
- **음성**: 자연스러운 TTS 음성 파일
- **이미지**: 과학 개념 시각화

## 🌟 특징

- **따뜻한 톤**: 모든 응답이 격려와 칭찬으로 가득
- **교육적 접근**: 학생 눈높이에 맞는 설명
- **다양한 기능**: 텍스트, 음성, 이미지, 번역 등 종합적 지원
- **사용자 친화적**: 직관적이고 쉬운 인터페이스
- **확장 가능**: 새로운 기능 추가가 용이한 구조

## 🤝 기여하기

1. Fork the Project
2. Create your Feature Branch (`git checkout -b feature/AmazingFeature`)
3. Commit your Changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the Branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📄 라이선스

이 프로젝트는 MIT 라이선스 하에 배포됩니다.

## 📞 문의

프로젝트에 대한 질문이나 제안사항이 있으시면 언제든 연락주세요! 😊

## 🙏 마무리

> "지식은 한계가 있지만, 상상력은 전 세계를 감싼다." – 아인슈타인

과학쌤은 AI와 함께 상상력 기반 학습을 실현합니다.

---

**과학쌤과 함께 과학을 재미있게 배워보세요! 🚀✨** 

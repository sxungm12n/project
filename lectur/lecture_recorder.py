import os
import time
import requests
from datetime import datetime
from azure.identity import DefaultAzureCredential
from azure.storage.blob import BlobServiceClient
from dotenv import load_dotenv
import openai

# 환경 변수 로드
load_dotenv()

class LectureRecorder:
    def __init__(self):
        self.speech_key = os.getenv('AZURE_SPEECH_KEY')
        self.speech_region = os.getenv('AZURE_SPEECH_REGION')
        self.azure_storage_connection_string = os.getenv("AZURE_STORAGE_CONNECTION_STRING")
        self.openai_api_key = os.getenv("OPENAI_API_KEY")
        
        # 출력 디렉토리 설정
        self.output_dir = 'output'
        if not os.path.exists(self.output_dir):
            os.makedirs(self.output_dir)
        
        # Azure Storage 설정
        self.blob_service_client = BlobServiceClient.from_connection_string(
            self.azure_storage_connection_string
        )
        
        # OpenAI 설정
        openai.api_key = self.openai_api_key

    def read_file_in_chunks(self, file_path, chunk_size=1024*1024*10):  # 10MB 청크
        """파일을 청크 단위로 읽기"""
        with open(file_path, 'rb') as f:
            while True:
                chunk = f.read(chunk_size)
                if not chunk:
                    break
                yield chunk

    def request_STT(self, file_path):
        """Convert audio to text using Azure Speech-to-Text"""
        try:
            # 파일 경로를 UTF-8로 인코딩
            file_path = file_path.encode('utf-8').decode('utf-8')
            
            # 파일 확장자 확인
            file_ext = os.path.splitext(file_path)[1].lower()
            if file_ext != '.wav':
                return f"⚠️ 지원되지 않는 파일 형식입니다. WAV 파일만 지원됩니다."

            # 파일 크기 확인
            file_size = os.path.getsize(file_path)
            print(f"File size: {file_size / (1024*1024):.2f} MB")
            
            # 파일 크기 제한 (100MB)
            if file_size > 100 * 1024 * 1024:
                return f"⚠️ 파일 크기가 100MB를 초과합니다."

            # Azure Speech-to-Text API 엔드포인트 설정
            endpoint = f"https://{self.speech_region}.stt.speech.microsoft.com/speech/recognition/conversation/cognitiveservices/v1?language=ko-KR&format=detailed"
            headers = {
                "Content-Type": "audio/wav",
                "Ocp-Apim-Subscription-Key": self.speech_key,
                "Accept": "application/json"
            }

            print("Starting audio to text conversion...")
            all_text = []
            chunk_number = 0
            
            # 파일을 청크로 나누어 처리
            for chunk in self.read_file_in_chunks(file_path):
                try:
                    print(f"Processing chunk {chunk_number + 1}...")
                    response = requests.post(endpoint, headers=headers, data=chunk)
                    
                    if response.status_code == 200:
                        result = response.json()
                        if 'DisplayText' in result:
                            text = result['DisplayText'].strip()
                            if text:  # 빈 텍스트가 아닌 경우에만 추가
                                all_text.append(text)
                                print(f"Chunk {chunk_number + 1} processed successfully")
                    else:
                        error_msg = f"Error in chunk {chunk_number + 1}: {response.status_code} - {response.text}"
                        print(error_msg)
                        return f"⚠️ 음성 인식 중 오류가 발생했습니다. 상태 코드: {response.status_code}"
                    
                    chunk_number += 1
                except requests.exceptions.RequestException as e:
                    error_msg = f"Network error in chunk {chunk_number + 1}: {str(e)}"
                    print(error_msg)
                    return f"⚠️ 네트워크 오류가 발생했습니다: {str(e)}"
                except Exception as e:
                    error_msg = f"Error processing chunk {chunk_number + 1}: {str(e)}"
                    print(error_msg)
                    return f"⚠️ 음성 인식 중 오류가 발생했습니다: {str(e)}"

            if not all_text:
                return f"⚠️ 음성 인식 결과가 없습니다. 다음을 확인해주세요:\n1. 파일이 올바른 WAV 형식인지\n2. 오디오에 음성이 포함되어 있는지\n3. 음성의 품질이 충분한지"

            final_text = " ".join(all_text)
            print(f"Audio to text conversion completed. Total chunks: {chunk_number}")
            return final_text
            
        except Exception as e:
            error_msg = f"Error in request_STT: {str(e)}"
            print(error_msg)
            return f"⚠️ 음성 인식 중 오류가 발생했습니다: {str(e)}"

    def summarize_text(self, text_filename):
        """텍스트를 마크다운 형식으로 요약"""
        with open(text_filename, "r", encoding="utf-8") as f:
            text = f.read()
        
        # OpenAI API를 사용하여 요약 생성
        response = openai.ChatCompletion.create(
            model="gpt-4o",
            messages=[
                {"role": "system", "content": "You are a helpful assistant that summarizes lecture content in markdown format."},
                {"role": "user", "content": f"Please summarize the following lecture content in markdown format:\n\n{text}"}
            ]
        )
        
        summary = response.choices[0].message.content
        
        # 요약을 마크다운 파일로 저장
        summary_filename = os.path.join(self.output_dir, f"summary_{os.path.basename(text_filename)}")
        with open(summary_filename, "w", encoding="utf-8") as f:
            f.write(summary)
        
        print(f"요약이 생성되었습니다: {summary_filename}")
        return summary_filename

if __name__ == "__main__":
    recorder = LectureRecorder()
    
    # 사용자 입력 받기
    print("오디오 파일 업로드")
    file_path = input("업로드할 오디오 파일 경로를 입력하세요: ")
    text_filename = recorder.request_STT(file_path)
    if text_filename:
        # 텍스트 요약
        recorder.summarize_text(text_filename) 
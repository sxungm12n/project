document.addEventListener('DOMContentLoaded', function() {
    const uploadForm = document.getElementById('uploadForm');
    const uploadStatus = document.getElementById('uploadStatus');
    const textResult = document.getElementById('textResult');
    const summaryResult = document.getElementById('summaryResult');

    // 페이지 로드 시 실행
    window.onload = function() {
        console.log('페이지 로드됨');
        
        uploadForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const fileInput = document.getElementById('audioFile');
            const file = fileInput.files[0];
            const statusMessage = document.getElementById('statusMessage');
            const resultSection = document.getElementById('resultSection');
            
            if (!file) {
                statusMessage.textContent = '파일을 선택해주세요.';
                statusMessage.className = 'alert alert-warning mt-3';
                statusMessage.style.display = 'block';
                return;
            }
            
            const formData = new FormData();
            formData.append('file', file);
            
            try {
                // 상태 메시지 표시
                statusMessage.textContent = '파일을 처리 중입니다...';
                statusMessage.className = 'alert alert-info mt-3';
                statusMessage.style.display = 'block';
                
                const response = await fetch('/upload', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (response.ok) {
                    // 결과 섹션 표시
                    resultSection.style.display = 'block';
                    
                    // 텍스트 결과 표시
                    document.getElementById('textResult').textContent = result.text;
                    
                    // 요약 결과 표시
                    document.getElementById('summaryResult').innerHTML = marked.parse(result.summary);
                    
                    // 다운로드 링크 업데이트
                    const textDownload = document.getElementById('textDownload');
                    const summaryDownload = document.getElementById('summaryDownload');
                    
                    textDownload.href = `/download/${result.text_file}`;
                    textDownload.style.display = 'inline';
                    
                    summaryDownload.href = `/download/${result.summary_file}`;
                    summaryDownload.style.display = 'inline';
                    
                    // 성공 메시지 표시
                    statusMessage.textContent = '파일 처리가 완료되었습니다.';
                    statusMessage.className = 'alert alert-success mt-3';
                } else {
                    statusMessage.textContent = '오류가 발생했습니다: ' + result.error;
                    statusMessage.className = 'alert alert-danger mt-3';
                }
            } catch (error) {
                statusMessage.textContent = '업로드 중 오류가 발생했습니다: ' + error.message;
                statusMessage.className = 'alert alert-danger mt-3';
            }
        });
    };
}); 
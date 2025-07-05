import os

# 데이터베이스 설정
DB_USER = 'root'
DB_PASSWORD = '1234'
DB_HOST = 'localhost'
DB_PORT = '3306'
DB_NAME = 'dbname'

# 데이터베이스 URI 생성
SQLALCHEMY_DATABASE_URI = f'mysql+pymysql://{DB_USER}:{DB_PASSWORD}@{DB_HOST}:{DB_PORT}/{DB_NAME}?charset=utf8mb4'

# Google Cloud Vision API 설정
GOOGLE_APPLICATION_CREDENTIALS_PATH = 'credentials/google-credentials.json'

# JWT 설정
JWT_SECRET_KEY = 'your_secret_key'

# 업로드 폴더 설정
UPLOAD_FOLDER = 'C:/vita/projectvita/uploads'

# 기타 설정
SQLALCHEMY_TRACK_MODIFICATIONS = False
ALLOWED_EXTENSIONS = {'png', 'jpg', 'jpeg'} 
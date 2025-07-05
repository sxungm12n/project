from flask import Flask, request, jsonify
from flask_sqlalchemy import SQLAlchemy
from sqlalchemy.exc import SQLAlchemyError
from flask_migrate import Migrate
from datetime import date
from werkzeug.security import generate_password_hash, check_password_hash
from flask_cors import CORS  # CORS 모듈 임포트
import os
from werkzeug.utils import secure_filename
from google.cloud import vision
import io
from flask_jwt_extended import JWTManager, create_access_token, jwt_required, get_jwt_identity, get_jwt_identity
from flask import session
import pandas as pd
from flask_cors import CORS
import datetime
from flask import send_from_directory
from datetime import datetime  # datetime 모듈에서 datetime 클래스만 임포트e  # datetime 모듈의 datetime 클래스를 가져옴
from PIL import Image
import math
from flask_cors import cross_origin
import uuid 
from config import *

app = Flask(__name__)
CORS(app, resources={r"/*": {"origins": "*"}})
app.config['JWT_SECRET_KEY'] = JWT_SECRET_KEY  # config에서 가져온 비밀 키 설정
jwt = JWTManager(app)

# MySQL 데이터베이스 연결 URI 설정 (config에서 가져옴)
app.config['SQLALCHEMY_DATABASE_URI'] = SQLALCHEMY_DATABASE_URI
app.config['SQLALCHEMY_TRACK_MODIFICATIONS'] = SQLALCHEMY_TRACK_MODIFICATIONS

# 업로드 디렉토리 설정 (config에서 가져옴)
if not os.path.exists(UPLOAD_FOLDER):
    os.makedirs(UPLOAD_FOLDER)

app.config['UPLOAD_FOLDER'] = UPLOAD_FOLDER
app.config['ALLOWED_EXTENSIONS'] = ALLOWED_EXTENSIONS

def allowed_file(filename):
    return '.' in filename and filename.rsplit('.', 1)[1].lower() in ALLOWED_EXTENSIONS

# Google Cloud Vision API 인증 설정 (config에서 가져옴)
os.environ['GOOGLE_APPLICATION_CREDENTIALS'] = GOOGLE_APPLICATION_CREDENTIALS_PATH

db = SQLAlchemy(app)
migrate = Migrate(app, db)

# DB 연결 테스트
try:
    with app.app_context():
        from sqlalchemy import text
        db.session.execute(text('SELECT 1'))
    print("✅ DB 연결 성공!")
except Exception as e:
    print(f"❌ DB 연결 실패: {e}")
    import sys
    sys.exit(1)

file_path = '../../data/vita.xlsx'
df = pd.read_excel(file_path)

# CORS 설정
CORS(app)  # 모든 출처의 요청을 허용

# 기본 홈 라우트
@app.route('/')
def home():
    return jsonify({
        'message': 'Flask 서버가 정상적으로 실행 중입니다!',
        'status': 'success',
        'endpoints': [
            '/signup - 회원가입',
            '/login - 로그인',
            '/upload - 이미지 업로드',
            '/posts - 게시글 목록',
            '/reviews - 리뷰 목록'
        ]
    })

def parse_float(value):
    try:
        return float(value)
    except (TypeError, ValueError):
        return None  # 또는 0.0과 같은 기본값을 사용할 수 있습니다.
    
# User 모델 정의
class User(db.Model):
    __tablename__ = 'users'
    id = db.Column(db.Integer, primary_key=True)
    username = db.Column(db.String(80), unique=True, nullable=False)
    password = db.Column(db.String(128), nullable=False)
    name = db.Column(db.String(80), nullable=False)
    birthday = db.Column(db.Date, nullable=False)
    gender = db.Column(db.Enum('남자', '여자'), nullable=False)
    role = db.Column(db.Enum('user', 'admin'), default='user')
    created_at = db.Column(db.DateTime, default=db.func.now())
    updated_at = db.Column(db.DateTime, default=db.func.now(), onupdate=db.func.now())
    tonic_details = db.relationship('TonicDetail', back_populates='user', cascade="all, delete-orphan")
    
    def to_json(self):
        return {
            'id': self.id,
            'username': self.username,
            'name': self.name,
            'birthday': self.birthday.isoformat(),
            'gender': self.gender,
            'role': self.role,
            'created_at': self.created_at.isoformat() if self.created_at else None,
            'updated_at': self.updated_at.isoformat() if self.updated_at else None
        }

@app.route('/signup', methods=['POST'])
def signup():
    try:
        data = request.json
        username = data.get('username')
        password = data.get('password')
        name = data.get('name')
        birthday = data.get('birthday')
        gender = data.get('gender')

        if not (username and password and name and birthday and gender):
            return jsonify({"success": False, "message": "모든 필드를 입력하세요"}), 400

        try:
            birthday = date.fromisoformat(birthday)
        except ValueError:
            return jsonify({"success": False, "message": "잘못된 날짜 형식입니다. (YYYY-MM-DD)"}), 400

        existing_user = User.query.filter_by(username=username).first()
        if existing_user:
            return jsonify({"success": False, "message": "이미 존재하는 사용자입니다."}), 400

        hashed_password = generate_password_hash(password, method='pbkdf2:sha256')
        new_user = User(username=username, password=hashed_password, name=name, birthday=birthday, gender=gender)

        db.session.add(new_user)
        db.session.commit()

        return jsonify({"success": True, "message": "회원가입이 완료되었습니다."}), 200

    except SQLAlchemyError as e:
        print(f"데이터베이스 오류: {e}")
        db.session.rollback()
        return jsonify({"success": False, "message": "데이터베이스 오류가 발생했습니다."}), 500

    except Exception as e:
        print(f"서버 오류: {e}")
        return jsonify({"success": False, "message": "서버 오류가 발생했습니다."}), 500


@app.route('/login', methods=['POST'])
def login():
    data = request.json
    username = data['username']
    password = data['password']
    user = User.query.filter_by(username=username).first()
    if user and check_password_hash(user.password, password):
        # 로그인 성공 시 JWT 생성 - user.id를 문자열로 변환
        access_token = create_access_token(identity=str(user.id))  # 사용자의 ID를 문자열로 JWT에 포함
        return jsonify({
            "success": True,
            "message": "Login successful",
            "access_token": access_token,  # JWT를 클라이언트에 반환
            "user": user.to_json()
        }), 200
    else:
        return jsonify({"success": False, "message": "Invalid credentials"}), 401


class TonicDetail(db.Model):
    __tablename__ = 'tonic_detail'
    tonic_number = db.Column(db.Integer, primary_key=True, autoincrement=True)
    user_id = db.Column(db.Integer, db.ForeignKey('users.id'), nullable=False)
    title = db.Column(db.String(255), nullable=False)
    VitaminA = db.Column(db.Float)
    VitaminD = db.Column(db.Float)
    VitaminE = db.Column(db.Float)
    VitaminK = db.Column(db.Float)
    VitaminC = db.Column(db.Float)
    Thiamine = db.Column(db.Float)
    Riboflavin = db.Column(db.Float)
    Niacin = db.Column(db.Float)
    VitaminB6 = db.Column(db.Float)
    FolicAcid = db.Column(db.Float)
    VitaminB12 = db.Column(db.Float)
    PantothenicAcid = db.Column(db.Float)
    Biotin = db.Column(db.Float)
    Calcium = db.Column(db.Float)
    Phosphorus = db.Column(db.Float)
    Sodium = db.Column(db.Float)
    Chlorine = db.Column(db.Float)
    Potassium = db.Column(db.Float)
    Magnesium = db.Column(db.Float)
    Iron = db.Column(db.Float)
    Zinc = db.Column(db.Float)
    Copper = db.Column(db.Float)
    Fluorine = db.Column(db.Float)
    Manganese = db.Column(db.Float)
    Iodine = db.Column(db.Float)
    Selenium = db.Column(db.Float)
    Molybdenum = db.Column(db.Float)
    Chromium = db.Column(db.Float)
    dosage = db.Column(db.Integer)
    created_at = db.Column(db.DateTime, default=db.func.now())
    updated_at = db.Column(db.DateTime, default=db.func.now(), onupdate=db.func.now())

    # User와의 관계 설정
    user = db.relationship('User', back_populates='tonic_details')

@app.route('/upload', methods=['POST'])
def upload_image():
    if 'file' not in request.files:
        return jsonify({'error': 'No file part'}), 400

    file = request.files['file']
    if file.filename == '':
        return jsonify({'error': 'No selected file'}), 400

    if file:
        # 파일을 지정된 경로에 저장
        file_path = os.path.join(UPLOAD_FOLDER, 'uploaded_image.png')
        file.save(file_path)

        # Google Cloud Vision API 클라이언트 설정
        client = vision.ImageAnnotatorClient()
        
        with io.open(file_path, 'rb') as image_file:
            content = image_file.read()
        
        # 이미지 분석
        image = vision.Image(content=content)
        response = client.text_detection(image=image)
        texts = response.text_annotations

        if response.error.message:
            return jsonify({'error': response.error.message}), 500

        # 전체 텍스트를 하나의 문자열로 결합
        full_text = ' '.join(text.description for text in texts)

        # 이미지 파일 삭제
        os.remove(file_path)

        return jsonify({'extracted_text': full_text})
    
@app.route('/save-tonic-detail', methods=['POST'])
@jwt_required()  # JWT 인증 필수
def save_tonic_detail():
    data = request.json

    if not data:
        print("No data provided")
        return jsonify({'message': 'No data provided'}), 400

    # JWT에서 현재 로그인한 사용자의 user_id 추출
    user_id = get_jwt_identity()
    if user_id is None:
        print("No user_id from JWT")
        return jsonify({'message': 'No user_id from JWT'}), 400
        
    # 문자열로 저장된 user_id를 정수로 변환
    user_id = int(user_id)

    title = data.get('title')  # 제목 추출
    if title is None:
        print("No title provided")
        return jsonify({'message': 'No title provided'}), 400

    dosage = data.get('dosage')  # 복용횟수 추출
    if dosage is None:
        print("No dosage provided")
        return jsonify({'message': 'No dosage provided'}), 400

    try:
        print(f"Received data: {data}")  # 데이터 출력
        
        # TonicDetail 인스턴스 생성
        new_entry = TonicDetail(
            user_id=user_id,
            title=title,  # 제목 필드 추가
            dosage=dosage,  # 복용횟수 필드 추가
            VitaminA=parse_float(data.get('VitaminA', 0.0)),
            VitaminD=parse_float(data.get('VitaminD', 0.0)),
            VitaminE=parse_float(data.get('VitaminE', 0.0)),
            VitaminK=parse_float(data.get('VitaminK', 0.0)),
            VitaminC=parse_float(data.get('VitaminC', 0.0)),
            Thiamine=parse_float(data.get('Thiamine', 0.0)),
            Riboflavin=parse_float(data.get('Riboflavin', 0.0)),
            Niacin=parse_float(data.get('Niacin', 0.0)),
            VitaminB6=parse_float(data.get('VitaminB6', 0.0)),
            FolicAcid=parse_float(data.get('FolicAcid', 0.0)),
            VitaminB12=parse_float(data.get('VitaminB12', 0.0)),
            PantothenicAcid=parse_float(data.get('PantothenicAcid', 0.0)),
            Biotin=parse_float(data.get('Biotin', 0.0)),
            Calcium=parse_float(data.get('Calcium', 0.0)),
            Phosphorus=parse_float(data.get('Phosphorus', 0.0)),
            Sodium=parse_float(data.get('Sodium', 0.0)),
            Chlorine=parse_float(data.get('Chlorine', 0.0)),
            Potassium=parse_float(data.get('Potassium', 0.0)),
            Magnesium=parse_float(data.get('Magnesium', 0.0)),
            Iron=parse_float(data.get('Iron', 0.0)),
            Zinc=parse_float(data.get('Zinc', 0.0)),
            Copper=parse_float(data.get('Copper', 0.0)),
            Fluorine=parse_float(data.get('Fluorine', 0.0)),
            Manganese=parse_float(data.get('Manganese', 0.0)),
            Iodine=parse_float(data.get('Iodine', 0.0)),
            Selenium=parse_float(data.get('Selenium', 0.0)),
            Molybdenum=parse_float(data.get('Molybdenum', 0.0)),
            Chromium=parse_float(data.get('Chromium', 0.0))
        )

        db.session.add(new_entry)
        db.session.commit()

        return jsonify({'message': 'Data saved successfully'}), 201
    except SQLAlchemyError as e:
        db.session.rollback()
        print(f"SQLAlchemyError: {e}")  # SQLAlchemy 오류 출력
        return jsonify({'message': 'Error saving data', 'error': str(e)}), 500
    except Exception as e:
        db.session.rollback()
        print(f"Unexpected error: {e}")  # 예기치 않은 오류 출력
        return jsonify({'message': 'Unexpected error', 'error': str(e)}), 500

@app.route('/get-tonic-detail', methods=['GET'])
@jwt_required()  # JWT 인증 필수
def get_tonic_detail():
    try:
        # JWT에서 현재 로그인한 사용자의 user_id 추출
        user_id = get_jwt_identity()
        
        # user_id가 전달되지 않으면 에러 반환
        if not user_id:
            return jsonify({'message': 'user_id가 필요합니다.'}), 400
            
        # 문자열로 저장된 user_id를 정수로 변환
        user_id = int(user_id)

        # user_id에 해당하는 tonic_detail 테이블의 데이터를 조회
        tonic_details = TonicDetail.query.filter_by(user_id=user_id).all()
        # 데이터를 JSON 형태로 변환하여 반환
        results = [
            {
                "tonic_number": detail.tonic_number,
                "title": detail.title,  # title 추가
                "dosage": detail.dosage,  # 복용횟수 추가
                "VitaminA": detail.VitaminA,
                "VitaminD": detail.VitaminD,
                "VitaminE": detail.VitaminE,
                "VitaminK": detail.VitaminK,
                "VitaminC": detail.VitaminC,
                "Thiamine": detail.Thiamine,
                "Riboflavin": detail.Riboflavin,
                "Niacin": detail.Niacin,
                "VitaminB6": detail.VitaminB6,
                "FolicAcid": detail.FolicAcid,
                "VitaminB12": detail.VitaminB12,
                "PantothenicAcid": detail.PantothenicAcid,
                "Biotin": detail.Biotin,
                "Calcium": detail.Calcium,
                "Phosphorus": detail.Phosphorus,
                "Sodium": detail.Sodium,
                "Chlorine": detail.Chlorine,
                "Potassium": detail.Potassium,
                "Magnesium": detail.Magnesium,
                "Iron": detail.Iron,
                "Zinc": detail.Zinc,
                "Copper": detail.Copper,
                "Fluorine": detail.Fluorine,
                "Manganese": detail.Manganese,
                "Iodine": detail.Iodine,
                "Selenium": detail.Selenium,
                "Molybdenum": detail.Molybdenum,
                "Chromium": detail.Chromium
            }
            for detail in tonic_details
        ]
        return jsonify(results), 200
    except SQLAlchemyError as e:
        return jsonify({'message': 'Error fetching data', 'error': str(e)}), 500

@app.route('/delete-tonic-detail/<int:tonic_number>', methods=['DELETE'])
def delete_tonic_detail(tonic_number):
    print(f"삭제 요청: tonic_number={tonic_number}")  # 로그 추가
    try:
        entry = TonicDetail.query.get(tonic_number)
        if not entry:
            print(f"항목을 찾을 수 없습니다: {tonic_number}")  # 로그 추가
            return jsonify({'message': 'Entry not found'}), 404

        db.session.delete(entry)
        db.session.commit()
        return jsonify({'message': 'Entry deleted successfully'}), 200

    except SQLAlchemyError as e:
        db.session.rollback()
        print(f"삭제 중 오류 발생: {str(e)}")  # 로그 추가
        return jsonify({'message': 'Error deleting entry', 'error': str(e)}), 500



@app.route('/update-tonic-detail/<int:tonic_number>', methods=['PUT'])
def update_tonic_detail(tonic_number):
    data = request.json  # 요청 데이터 받기
    try:
        # tonic_number에 해당하는 항목 조회
        entry = TonicDetail.query.get(tonic_number)
        if not entry:
            return jsonify({'message': 'Entry not found'}), 404

        # 제목과 복용 횟수 업데이트
        entry.title = data.get('title', entry.title)
        entry.dosage = int(data.get('dosage', entry.dosage))

        # 영양소 업데이트
        entry.VitaminA = parse_float(data.get('VitaminA', entry.VitaminA))
        entry.VitaminD = parse_float(data.get('VitaminD', entry.VitaminD))
        entry.VitaminE = parse_float(data.get('VitaminE', entry.VitaminE))
        entry.VitaminK = parse_float(data.get('VitaminK', entry.VitaminK))
        entry.VitaminC = parse_float(data.get('VitaminC', entry.VitaminC))
        entry.Thiamine = parse_float(data.get('Thiamine', entry.Thiamine))
        entry.Riboflavin = parse_float(data.get('Riboflavin', entry.Riboflavin))
        entry.Niacin = parse_float(data.get('Niacin', entry.Niacin))
        entry.VitaminB6 = parse_float(data.get('VitaminB6', entry.VitaminB6))
        entry.FolicAcid = parse_float(data.get('FolicAcid', entry.FolicAcid))
        entry.VitaminB12 = parse_float(data.get('VitaminB12', entry.VitaminB12))
        entry.PantothenicAcid = parse_float(data.get('PantothenicAcid', entry.PantothenicAcid))
        entry.Biotin = parse_float(data.get('Biotin', entry.Biotin))
        entry.Calcium = parse_float(data.get('Calcium', entry.Calcium))
        entry.Phosphorus = parse_float(data.get('Phosphorus', entry.Phosphorus))
        entry.Sodium = parse_float(data.get('Sodium', entry.Sodium))
        entry.Chlorine = parse_float(data.get('Chlorine', entry.Chlorine))
        entry.Potassium = parse_float(data.get('Potassium', entry.Potassium))
        entry.Magnesium = parse_float(data.get('Magnesium', entry.Magnesium))
        entry.Iron = parse_float(data.get('Iron', entry.Iron))
        entry.Zinc = parse_float(data.get('Zinc', entry.Zinc))
        entry.Copper = parse_float(data.get('Copper', entry.Copper))
        entry.Fluorine = parse_float(data.get('Fluorine', entry.Fluorine))
        entry.Manganese = parse_float(data.get('Manganese', entry.Manganese))
        entry.Iodine = parse_float(data.get('Iodine', entry.Iodine))
        entry.Selenium = parse_float(data.get('Selenium', entry.Selenium))
        entry.Molybdenum = parse_float(data.get('Molybdenum', entry.Molybdenum))
        entry.Chromium = parse_float(data.get('Chromium', entry.Chromium))

        # 변경 사항 커밋
        db.session.commit()
        return jsonify({'message': 'Entry updated successfully'}), 200

    except SQLAlchemyError as e:
        # 오류가 발생하면 롤백 후 오류 메시지 반환
        db.session.rollback()
        return jsonify({'message': 'Error updating entry', 'error': str(e)}), 500

    except Exception as e:
        # 예기치 않은 오류 처리
        return jsonify({'message': 'Unexpected error', 'error': str(e)}), 500

# 연령 범위 필터링 함수
def is_age_in_range(age_range_str, age):
    try:
        start_age, end_age = map(int, age_range_str.split('~'))
        return start_age <= age <= end_age
    except ValueError:
        return False

# 영양소 정보 가져오는 함수
def get_nutrient_data(nutrient, gender, age):
    nutrient_data = df[(df['영양소'] == nutrient) & (df['성별'] == gender)]
    nutrient_data = nutrient_data[nutrient_data['연령'].apply(lambda x: is_age_in_range(x, age))]
    if nutrient_data.empty:
        return None

    return {
        "recommended": nutrient_data.iloc[0]['권장 섭취량'],
        "upper_limit": nutrient_data.iloc[0]['상한 섭취량']
    }

def safe_float(value):
    """NaN이나 None 값을 안전하게 처리하여 JSON에 사용 가능하게 변환."""
    try:
        if value is None or math.isnan(value):
            return None  # NaN을 null로 변환
        return float(value)
    except (ValueError, TypeError):
        return None

@app.route('/get_tonic_sum_detail', methods=['GET'])
@jwt_required()  # JWT 인증 필수
def get_tonic_sum_detail():
    try:
        # JWT에서 현재 로그인한 사용자의 user_id 추출
        user_id = get_jwt_identity()
        
        if not user_id:
            return jsonify({'message': 'user_id가 필요합니다.'}), 400
            
        # 문자열로 저장된 user_id를 정수로 변환
        user_id = int(user_id)
        print(f"사용자 ID: {user_id}")  # 사용자 ID 로깅

        user = db.session.get(User, user_id)
        if not user:
            print("사용자 없음")
            return jsonify({'message': '사용자를 찾을 수 없습니다.'}), 404

        age = datetime.now().year - user.birthday.year
        gender = user.gender
        print(f"사용자 정보 - 나이: {age}, 성별: {gender}")

        tonic_details = TonicDetail.query.filter_by(user_id=user_id).all()
        print(f"조회된 영양 정보 수: {len(tonic_details)}")

        # 영양소 총합 계산
        totals = {
            nutrient: safe_float(sum(getattr(detail, nutrient) or 0.0 for detail in tonic_details))
            for nutrient in [
                "VitaminA", "VitaminD", "VitaminE", "VitaminK", "VitaminC",
                "Thiamine", "Riboflavin", "Niacin", "VitaminB6", "FolicAcid",
                "VitaminB12", "PantothenicAcid", "Biotin", "Calcium",
                "Phosphorus", "Sodium", "Chlorine", "Potassium", "Magnesium",
                "Iron", "Zinc", "Copper", "Fluorine", "Manganese", "Iodine",
                "Selenium", "Molybdenum", "Chromium"
            ]
        }
        print(f"총합 데이터: {totals}")

        # 영양소 평가
        evaluations = {}
        for nutrient, intake in totals.items():
            nutrient_data = get_nutrient_data(nutrient, gender, age)
            if nutrient_data:
                recommended = safe_float(nutrient_data.get('recommended'))
                upper_limit = safe_float(nutrient_data.get('upper_limit'))

                if intake < recommended:
                    status = "부족"
                elif upper_limit and intake > upper_limit:
                    status = "과다"
                else:
                    status = "적정"
            else:
                status = "데이터 없음"
                recommended = None
                upper_limit = None

            evaluations[nutrient] = {
                "intake": intake,
                "recommended": recommended,
                "upper_limit": upper_limit,
                "status": status
            }

        print(f"응답 데이터: {evaluations}")

        return jsonify({"evaluation": evaluations}), 200

    except SQLAlchemyError as e:
        print(f"SQLAlchemyError: {str(e)}")
        return jsonify({'message': '데이터 조회 중 오류 발생', 'error': str(e)}), 500

@app.route('/get_text/<status>/<nutrient>', methods=['GET'])
def get_text(status, nutrient):
    base_path = os.path.normpath('C:/vita/projectvita/lib/vita')  # 경로 수정
    file_path = ''
    
    if status == '부족':
        file_path = os.path.join(base_path, f'{nutrient}_low.txt')
    elif status == '적정':
        file_path = os.path.join(base_path, f'{nutrient}_normal.txt')
    elif status == '과다':
        file_path = os.path.join(base_path, f'{nutrient}_high.txt')
    else:
        return jsonify({"message": "Invalid status"}), 400

    # 디버깅을 위한 경로 출력
    print(f"Trying to access file: {file_path}")

    try:
        with open(file_path, 'r', encoding='utf-8') as file:
            content = file.read()
        return jsonify({"content": content}), 200
    except FileNotFoundError:
        return jsonify({"message": f"File not found: {file_path}"}), 404

#setting 보여주기
@app.route('/user', methods=['GET'])
@jwt_required()  # JWT 인증 필요
def get_user():
    current_user_id = get_jwt_identity()  # 현재 사용자 ID 가져오기
    current_user_id = int(current_user_id)  # 문자열을 정수로 변환
    user = User.query.get(current_user_id)  # 사용자 정보 조회

    if user:
        return jsonify({
            "user_id": user.id,
            "username": user.username,
            "name": user.name,          # 사용자 이름 추가
            "birthday": user.birthday.isoformat(),  # 생일 추가
            "gender": user.gender,      # 성별 추가
            "message": "User retrieved successfully"
        }), 200
    else:
        return jsonify({"message": "User not found"}), 404
@app.route('/update_user', methods=['PUT'])
def update_user():
    data = request.json
    username = data['username']
    new_password = generate_password_hash(data['password'], method='pbkdf2:sha256')
    #new_birthday = date.fromisoformat(data['birthday'])

    user = User.query.filter_by(username=username).first()
    if user:
        user.password = new_password
        #user.birthday = new_birthday
        db.session.commit()
        return jsonify(user.to_json()), 200
    else:
        return jsonify({"success": False, "message": "User not found"}), 404

# 계정 삭제 API
@app.route('/user', methods=['DELETE'])
@jwt_required()
def delete_user():
    user_id = get_jwt_identity()  # JWT에서 사용자 ID 추출
    user_id = int(user_id)  # 문자열을 정수로 변환
    user = User.query.get(user_id)

    if not user:
        return jsonify({'message': '사용자를 찾을 수 없습니다.'}), 404

    try:
        # 해당 사용자의 게시글, 댓글, 리뷰 등 연관된 데이터 삭제
        Post.query.filter_by(user_id=user_id).delete()
        Comment.query.filter_by(user_id=user_id).delete()
        Review.query.filter_by(user_id=user_id).delete()
        TonicDetail.query.filter_by(user_id=user_id).delete()

        # 사용자를 삭제
        db.session.delete(user)
        db.session.commit()

        return jsonify({'message': '계정이 성공적으로 삭제되었습니다.'}), 200
    except Exception as e:
        db.session.rollback()
        print(f'계정 삭제 오류: {str(e)}')
        return jsonify({'message': f'계정 삭제 중 오류 발생: {str(e)}'}), 500





# Post 모델 정의
class Post(db.Model):
    __tablename__ = 'posts'
    id = db.Column(db.Integer, primary_key=True)
    user_id = db.Column(db.Integer, db.ForeignKey('users.id'), nullable=False)
    title = db.Column(db.String(255), nullable=False)
    content = db.Column(db.Text, nullable=False)
    created_at = db.Column(db.DateTime, default=db.func.now())
    updated_at = db.Column(db.DateTime, default=db.func.now(), onupdate=db.func.now())

    def to_json(self):
        return {
            'id': self.id,
            'user_id': self.user_id,
            'title': self.title,
            'content': self.content,
            'created_at': self.created_at.isoformat(),
            'updated_at': self.updated_at.isoformat()
        }
# 게시글 작성 API
@app.route('/create_post', methods=['POST'])
@jwt_required()  # JWT 인증 필수
def create_post():
    data = request.json
    # JWT에서 현재 로그인한 사용자의 user_id 추출
    user_id = get_jwt_identity()
    user_id = int(user_id)  # 문자열을 정수로 변환
    title = data['title']
    content = data['content']
    
    if user_id is None:
        return jsonify({'error': 'user_id가 필요합니다.'}), 400  # user_id가 없을 경우 에러 응답
    
    new_post = Post(user_id=user_id, title=title, content=content)
    db.session.add(new_post)
    db.session.commit()
    
    return jsonify({"success": True, "message": "Post created successfully"}), 200
# 모든 게시글 조회 API
@app.route('/posts', methods=['GET'])
def get_all_posts():
    posts = Post.query.join(User, Post.user_id == User.id).add_columns(
        Post.id, Post.user_id, Post.title, Post.content, Post.created_at, Post.updated_at, User.username).all()
    result = []
    for post in posts:
        result.append({
            'id': post.id,
            'user_id': post.user_id,
            'title': post.title,
            'content': post.content,
            'created_at': post.created_at.isoformat(),
            'updated_at': post.updated_at.isoformat(),
            'username': post.username
        })
    return jsonify(result), 200
# 사용자 글 조회 API (나의 글 보기)
@app.route('/my_posts', methods=['GET'])
@jwt_required()  # JWT 인증 필수
def get_my_posts():
    # JWT에서 현재 로그인한 사용자의 user_id 추출
    user_id = get_jwt_identity()
    user_id = int(user_id)  # 문자열을 정수로 변환
    posts = Post.query.filter_by(user_id=user_id).join(User, Post.user_id == User.id).add_columns(
        Post.id, Post.user_id, Post.title, Post.content, Post.created_at, Post.updated_at, User.username).all()
    result = []
    for post in posts:
        result.append({
            'id': post.id,
            'user_id': post.user_id,
            'title': post.title,
            'content': post.content,
            'created_at': post.created_at.isoformat(),
            'updated_at': post.updated_at.isoformat(),
            'username': post.username
        })
    return jsonify(result), 200

# URL 파라미터로 user_id를 받는 my_posts 엔드포인트 (호환성을 위해)
@app.route('/my_posts/<int:user_id>', methods=['GET'])
@jwt_required()  # JWT 인증 필수
def get_my_posts_by_id(user_id):
    # JWT에서 현재 로그인한 사용자의 user_id 추출하여 권한 확인
    current_user_id = get_jwt_identity()
    current_user_id = int(current_user_id)
    
    # 자신의 글만 조회 가능하도록 권한 확인
    if current_user_id != user_id:
        return jsonify({"error": "권한이 없습니다."}), 403
    
    posts = Post.query.filter_by(user_id=user_id).join(User, Post.user_id == User.id).add_columns(
        Post.id, Post.user_id, Post.title, Post.content, Post.created_at, Post.updated_at, User.username).all()
    result = []
    for post in posts:
        result.append({
            'id': post.id,
            'user_id': post.user_id,
            'title': post.title,
            'content': post.content,
            'created_at': post.created_at.isoformat(),
            'updated_at': post.updated_at.isoformat(),
            'username': post.username
        })
    return jsonify(result), 200

# 게시글 수정 API
@app.route('/update_post/<int:post_id>', methods=['PUT'])
def update_post(post_id):
    data = request.json
    title = data.get('title')
    content = data.get('content')

    post = Post.query.get(post_id)
    if post:
        if title:
            post.title = title
        if content:
            post.content = content
        db.session.commit()
        return jsonify(post.to_json()), 200
    else:
        return jsonify({"success": False, "message": "Post not found"}), 404
# 게시글 삭제 API
@app.route('/delete_post/<int:post_id>', methods=['DELETE'])
def delete_post(post_id):
    post = Post.query.get(post_id)
    if post:
        db.session.delete(post)
        db.session.commit()
        return jsonify({"success": True, "message": "Post deleted successfully"}), 200
    else:
        return jsonify({"success": False, "message": "Post not found"}), 404
# Comment 모델 정의
class Comment(db.Model):
    __tablename__ = 'post_comments'
    id = db.Column(db.Integer, primary_key=True)
    post_id = db.Column(db.Integer, db.ForeignKey('posts.id'), nullable=False)
    user_id = db.Column(db.Integer, db.ForeignKey('users.id'), nullable=False)
    content = db.Column(db.Text, nullable=False)
    created_at = db.Column(db.DateTime, default=db.func.now())

    def to_json(self):
        return {
            'id': self.id,
            'post_id': self.post_id,
            'user_id': self.user_id,
            'content': self.content,
            'created_at': self.created_at.isoformat()
        }
# 댓글 작성 API
@app.route('/create_comment', methods=['POST'])
@jwt_required()  # JWT 인증 필수
def create_comment():
    try:
        data = request.json
        post_id = data['post_id']
        # JWT에서 현재 로그인한 사용자의 user_id 추출
        user_id = get_jwt_identity()
        user_id = int(user_id)  # 문자열을 정수로 변환
        content = data['content']
        if not post_id or not user_id or not content:
            return jsonify({'error': '모든 필드가 필요합니다.'}), 400
        new_comment = Comment(post_id=post_id, user_id=user_id, content=content)
        db.session.add(new_comment)
        db.session.commit()
        return jsonify({"success": True, "message": "댓글이 추가되었습니다."}), 200
    except Exception as e:
        return jsonify({"error": str(e)}), 500
# 댓글 조회 API 수정
@app.route('/comments/<int:post_id>', methods=['GET'])
def get_comments(post_id):
    comments = Comment.query.filter_by(post_id=post_id).join(User, Comment.user_id == User.id).add_columns(
        Comment.id, Comment.content, Comment.created_at, User.username, Comment.user_id  # user_id 추가
    ).all()
    result = []
    for comment in comments:
        result.append({
            'id': comment.id,
            'content': comment.content,
            'created_at': comment.created_at.isoformat(),
            'username': comment.username,
            'user_id': comment.user_id  # user_id 추가
        })
    return jsonify(result), 200

# 댓글 삭제 API
@app.route('/delete_comment/<int:comment_id>', methods=['DELETE'])
def delete_comment(comment_id):
    try:
        comment = Comment.query.get(comment_id)
        if comment:
            db.session.delete(comment)
            db.session.commit()
            return jsonify({"success": True, "message": "댓글이 삭제되었습니다."}), 200
        else:
            return jsonify({"success": False, "message": "댓글을 찾을 수 없습니다."}), 404
    except Exception as e:
        return jsonify({"success": False, "message": f"오류 발생: {str(e)}"}), 500


# 리뷰 모델 정의
class Review(db.Model):
    __tablename__ = 'reviews'
    id = db.Column(db.Integer, primary_key=True)
    user_id = db.Column(db.Integer, db.ForeignKey('users.id'), nullable=False)
    title = db.Column(db.String(255), nullable=False)
    content = db.Column(db.Text, nullable=False)
    image_url = db.Column(db.String(255), nullable=True)
    created_at = db.Column(db.DateTime, default=db.func.now())
    updated_at = db.Column(db.DateTime, default=db.func.now(), onupdate=db.func.now())

# 리뷰 작성 API
@app.route('/create_review', methods=['POST'])
@jwt_required()  # JWT 인증 필수
def create_review():
    try:
        # JWT에서 현재 로그인한 사용자의 user_id 추출
        user_id = get_jwt_identity()
        user_id = int(user_id)  # 문자열을 정수로 변환
        title = request.form['title']
        content = request.form['content']
        image = request.files.get('image')
        created_at = datetime.utcnow()
        image_url = None

        if image and allowed_file(image.filename):
            img = Image.open(image)
            if img.mode == 'RGBA':
                img = img.convert('RGB')
            img = img.resize((200, 200), Image.LANCZOS)
            
            # 파일 이름에 UUID 추가하여 고유하게 생성
            filename = f"{uuid.uuid4().hex}_{secure_filename(image.filename)}"
            image_path = os.path.join(app.config['UPLOAD_FOLDER'], filename)
            img.save(image_path, format='JPEG')
            
            # image_url 저장
            image_url = f'http://{request.host}/uploads/{filename}'

        user = User.query.get(user_id)
        if user is None:
            return jsonify({"success": False, "message": "사용자를 찾을 수 없습니다."}), 404

        new_review = Review(user_id=user_id, title=title, content=content, image_url=image_url, created_at=created_at)
        db.session.add(new_review)
        db.session.commit()
        return jsonify({
            "success": True,
            "message": "리뷰가 작성되었습니다.",
            "user_name": user.username,
            "created_at": created_at.isoformat()
        }), 201
    except Exception as e:
        return jsonify({"success": False, "message": str(e)}), 500
# 업로드된 파일 제공 API
@app.route('/uploads/<filename>', methods=['GET'])
def uploaded_file(filename):
    try:
        return send_from_directory(app.config['UPLOAD_FOLDER'], filename)  # 업로드된 파일 반환
    except Exception as e:
        print(f"Error: {str(e)}")  # 에러 메시지 출력
        return jsonify({"success": False, "message": str(e)}), 500  # 예외 처리
# 모든 리뷰 조회 API
@app.route('/reviews', methods=['GET'])
def get_all_reviews():
    reviews = Review.query.join(User).add_columns(
        Review.id, Review.user_id, Review.title, Review.content, Review.image_url, 
        Review.created_at, Review.updated_at, User.username
    ).all()
    result = []
    for review in reviews:
        result.append({
            'id': review.id,
            'user_id': review.user_id,
            'title': review.title,
            'content': review.content,
            'image_url': review.image_url,
            'created_at': review.created_at.isoformat(),
            'updated_at': review.updated_at.isoformat(),
            'username': review.username
        })
    return jsonify(result), 200
# 사용자 리뷰 조회 API (나의 글 보기)
@app.route('/my_reviews', methods=['GET'])
@jwt_required()  # JWT 인증 필수
def get_my_reviews():
    # JWT에서 현재 로그인한 사용자의 user_id 추출
    user_id = get_jwt_identity()
    user_id = int(user_id)  # 문자열을 정수로 변환
    reviews = Review.query.filter_by(user_id=user_id).join(User).add_columns(
        Review.id, Review.user_id, Review.title, Review.content, Review.image_url,
        Review.created_at, Review.updated_at, User.username
    ).all()
    result = []
    for review in reviews:
        result.append({
            'id': review.id,
            'user_id': review.user_id,
            'title': review.title,
            'content': review.content,
            'image_url': review.image_url,
            'created_at': review.created_at.isoformat(),
            'updated_at': review.updated_at.isoformat(),
            'username': review.username
        })
    return jsonify(result), 200

# URL 파라미터로 user_id를 받는 my_reviews 엔드포인트 (호환성을 위해)
@app.route('/my_reviews/<int:user_id>', methods=['GET'])
@jwt_required()  # JWT 인증 필수
def get_my_reviews_by_id(user_id):
    # JWT에서 현재 로그인한 사용자의 user_id 추출하여 권한 확인
    current_user_id = get_jwt_identity()
    current_user_id = int(current_user_id)
    
    # 자신의 리뷰만 조회 가능하도록 권한 확인
    if current_user_id != user_id:
        return jsonify({"error": "권한이 없습니다."}), 403
    
    reviews = Review.query.filter_by(user_id=user_id).join(User).add_columns(
        Review.id, Review.user_id, Review.title, Review.content, Review.image_url,
        Review.created_at, Review.updated_at, User.username
    ).all()
    result = []
    for review in reviews:
        result.append({
            'id': review.id,
            'user_id': review.user_id,
            'title': review.title,
            'content': review.content,
            'image_url': review.image_url,
            'created_at': review.created_at.isoformat(),
            'updated_at': review.updated_at.isoformat(),
            'username': review.username
        })
    return jsonify(result), 200

# 게시글 수정 API
@app.route('/update_review/<int:review_id>', methods=['PUT'])
def update_review(review_id):
    try:
        data = request.form
        title = data.get('title')
        content = data.get('content')
        image = request.files.get('image')
        remove_image = data.get('remove_image')

        review = Review.query.get(review_id)
        if not review:
            return jsonify({"success": False, "message": "리뷰를 찾을 수 없습니다."}), 404

        # 제목과 내용을 수정
        if title:
            review.title = title
        if content:
            review.content = content

        # 이미지 삭제 요청이 있을 경우
        if remove_image == 'true' and review.image_url:
            # image_url에서 파일명 추출
            filename = os.path.basename(review.image_url)
            image_path = os.path.join(app.config['UPLOAD_FOLDER'], filename)
            if os.path.exists(image_path):
                os.remove(image_path)
            review.image_url = None

        # 새로운 이미지가 있을 경우 이미지를 업데이트
        if image and allowed_file(image.filename):
            img = Image.open(image)
            if img.mode == 'RGBA':
                img = img.convert('RGB')
            img = img.resize((200, 200), Image.LANCZOS)
            
            # 파일 이름에 UUID 추가하여 고유하게 생성
            filename = f"{uuid.uuid4().hex}_{secure_filename(image.filename)}"
            image_path = os.path.join(app.config['UPLOAD_FOLDER'], filename)
            img.save(image_path, format='JPEG')
            
            # image_url 업데이트
            review.image_url = f'http://{request.host}/uploads/{filename}'

        db.session.commit()
        return jsonify({"success": True, "message": "리뷰가 수정되었습니다."}), 200

    except Exception as e:
        print(f"Error during review update: {str(e)}")
        return jsonify({"success": False, "message": f"오류 발생: {str(e)}"}), 500
# 리뷰 삭제 API
@app.route('/delete_review/<int:review_id>', methods=['DELETE'])
def delete_review(review_id):
    review = Review.query.get(review_id)
    if review:
        # 이미지 파일이 있으면 삭제
        if review.image_url:
            filename = os.path.basename(review.image_url)
            image_path = os.path.join(app.config['UPLOAD_FOLDER'], filename)
            if os.path.exists(image_path):
                os.remove(image_path)
        
        db.session.delete(review)
        db.session.commit()
        return jsonify({"success": True, "message": "리뷰가 삭제되었습니다."}), 200
    else:
        return jsonify({"success": False, "message": "리뷰를 찾을 수 없습니다."}), 404
# ReviewComment 모델 정의
class RComment(db.Model):
    __tablename__ = 'review_comments'
    id = db.Column(db.Integer, primary_key=True)
    review_id = db.Column(db.Integer, db.ForeignKey('reviews.id'), nullable=False)
    user_id = db.Column(db.Integer, db.ForeignKey('users.id'), nullable=False)
    content = db.Column(db.Text, nullable=False)
    created_at = db.Column(db.DateTime, default=db.func.now())

    def to_json(self):
        return {
            'id': self.id,
            'review_id': self.review_id,
            'user_id': self.user_id,
            'content': self.content, 
            'created_at': self.created_at.isoformat()
        }

# 리뷰 댓글 작성 API
@app.route('/create_review_comment', methods=['POST'])
@jwt_required()  # JWT 인증 필수
def create_review_comment():
    try:
        data = request.json
        review_id = data['review_id']
        # JWT에서 현재 로그인한 사용자의 user_id 추출
        user_id = get_jwt_identity()
        user_id = int(user_id)  # 문자열을 정수로 변환
        content = data['content']
        if not review_id or not user_id or not content:
            return jsonify({'error': '모든 필드가 필요합니다.'}), 400
        new_review_comment = RComment(review_id=review_id, user_id=user_id, content=content)
        db.session.add(new_review_comment)
        db.session.commit()
        return jsonify({"success": True, "message": "댓글이 추가되었습니다."}), 200
    except Exception as e:
        return jsonify({"error": str(e)}), 500

# 리뷰 게시글의 댓글 조회 API
@app.route('/reviewcomments/<int:review_id>', methods=['GET'])
def get_reviewcomments(review_id):
    review_comments = (
        RComment.query.filter_by(review_id=review_id)
        .join(User, RComment.user_id == User.id)
        .add_columns(RComment.id, RComment.content, RComment.created_at, User.username, RComment.user_id)
        .all()
    )
    result = []
    for review_comment in review_comments:
        result.append({
            'id': review_comment.id,
            'content': review_comment.content,
            'created_at': review_comment.created_at.isoformat(),
            'username': review_comment.username,
            'user_id': review_comment.user_id
        })
    return jsonify(result), 200

# 댓글 삭제 API
@app.route('/delete_review_comment/<int:comment_id>', methods=['DELETE'])
@jwt_required()  # JWT 인증이 필요하도록 설정
def delete_review_comment(comment_id):
    try:
        review_comment = RComment.query.get(comment_id)
        if review_comment:
            # JWT에서 user_id 추출하여 댓글 작성자의 user_id와 비교
            current_user_id = get_jwt_identity()
            current_user_id = int(current_user_id)  # 문자열을 정수로 변환
            if review_comment.user_id != current_user_id:
                return jsonify({"success": False, "message": "댓글 삭제 권한이 없습니다."}), 403

            db.session.delete(review_comment)
            db.session.commit()
            return jsonify({"success": True, "message": "댓글이 삭제되었습니다."}), 200
        else:
            return jsonify({"success": False, "message": "댓글을 찾을 수 없습니다."}), 404
    except Exception as e:
        return jsonify({"success": False, "message": f"오류 발생: {str(e)}"}), 500

if __name__ == '__main__':
    # host를 '0.0.0.0'으로 설정하고 포트를 5000으로 설정 (원하는 포트로 변경 가능)
    app.run(host='0.0.0.0', port=5000, debug=True)

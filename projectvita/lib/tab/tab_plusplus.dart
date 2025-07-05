import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'package:shared_preferences/shared_preferences.dart';
import '../screens/screen_index.dart';

class TapPlusPlus extends StatefulWidget {
  final String extractedText;

  TapPlusPlus({required this.extractedText});

  @override
  _TapPlusPlusState createState() => _TapPlusPlusState();
}

class _TapPlusPlusState extends State<TapPlusPlus>
    with TickerProviderStateMixin {
  final TextEditingController _titleController = TextEditingController();
  final TextEditingController _dosageController = TextEditingController();
  bool _isLoading = false;

  late AnimationController _fadeController;
  late AnimationController _slideController;
  late Animation<double> _fadeAnimation;
  late Animation<Offset> _slideAnimation;

  @override
  void initState() {
    super.initState();

    _fadeController = AnimationController(
      duration: Duration(milliseconds: 1500),
      vsync: this,
    );

    _slideController = AnimationController(
      duration: Duration(milliseconds: 1200),
      vsync: this,
    );

    _fadeAnimation = Tween<double>(
      begin: 0.0,
      end: 1.0,
    ).animate(CurvedAnimation(
      parent: _fadeController,
      curve: Curves.easeInOut,
    ));

    _slideAnimation = Tween<Offset>(
      begin: Offset(0, 0.3),
      end: Offset.zero,
    ).animate(CurvedAnimation(
      parent: _slideController,
      curve: Curves.easeOutCubic,
    ));

    _fadeController.forward();
    Future.delayed(Duration(milliseconds: 300), () {
      _slideController.forward();
    });
  }

  @override
  void dispose() {
    _fadeController.dispose();
    _slideController.dispose();
    super.dispose();
  }

  Future<int> _getUserId() async {
    SharedPreferences prefs = await SharedPreferences.getInstance();
    String? token = prefs.getString('jwt_token');

    if (token == null) {
      throw Exception('JWT 토큰이 없습니다');
    }

    final response = await http.get(
      Uri.parse('http://192.168.35.173:5000/user'),
      headers: {'Authorization': 'Bearer $token'},
    );

    if (response.statusCode == 200) {
      final data = jsonDecode(response.body);
      return data['user_id'] as int;
    } else {
      throw Exception('사용자 정보를 가져오는 데 실패했습니다: ${response.body}');
    }
  }

  Future<String?> _getToken() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getString('jwt_token');
  }

  @override
  Widget build(BuildContext context) {
    final vitamins = _extractVitamins(widget.extractedText);

    return Scaffold(
      backgroundColor: Colors.white,
      appBar: AppBar(
        title: Text(
          '영양제 정보 확인',
          style: TextStyle(
            color: Colors.white,
            fontWeight: FontWeight.bold,
            fontSize: 20,
          ),
        ),
        centerTitle: true,
        backgroundColor: Color(0xFF4CAF50),
        elevation: 0,
        leading: IconButton(
          icon: Icon(Icons.arrow_back_ios, color: Colors.white),
          onPressed: () => Navigator.of(context).pop(),
        ),
      ),
      body: SafeArea(
        child: Stack(
          children: [
            // 배경 장식 요소들
            Positioned(
              top: -50,
              right: -50,
              child: Container(
                width: 200,
                height: 200,
                decoration: BoxDecoration(
                  shape: BoxShape.circle,
                  color: Color(0xFF4CAF50).withOpacity(0.05),
                ),
              ),
            ),
            Positioned(
              bottom: -100,
              left: -100,
              child: Container(
                width: 300,
                height: 300,
                decoration: BoxDecoration(
                  shape: BoxShape.circle,
                  color: Color(0xFF2E7D32).withOpacity(0.03),
                ),
              ),
            ),

            // 메인 콘텐츠
            SingleChildScrollView(
              padding: const EdgeInsets.all(30.0),
              child: Column(
                children: [
                  // 헤더 섹션
                  FadeTransition(
                    opacity: _fadeAnimation,
                    child: Column(
                      children: [
                        Container(
                          width: 100,
                          height: 100,
                          decoration: BoxDecoration(
                            shape: BoxShape.circle,
                            gradient: LinearGradient(
                              begin: Alignment.topLeft,
                              end: Alignment.bottomRight,
                              colors: [
                                Color(0xFF4CAF50),
                                Color(0xFF2E7D32),
                              ],
                            ),
                            boxShadow: [
                              BoxShadow(
                                color: Color(0xFF4CAF50).withOpacity(0.3),
                                blurRadius: 20,
                                spreadRadius: 5,
                              ),
                            ],
                          ),
                          child: Icon(
                            Icons.analytics,
                            size: 50,
                            color: Colors.white,
                          ),
                        ),
                        SizedBox(height: 20),
                        Text(
                          '영양제 정보 확인',
                          style: TextStyle(
                            fontSize: 28.0,
                            color: Colors.black87,
                            fontWeight: FontWeight.bold,
                            letterSpacing: 1.5,
                          ),
                        ),
                        SizedBox(height: 10),
                        Text(
                          '추출된 영양 정보를 확인하고 저장하세요',
                          style: TextStyle(
                            fontSize: 16.0,
                            color: Colors.grey[600],
                            fontWeight: FontWeight.w300,
                            letterSpacing: 1.0,
                          ),
                          textAlign: TextAlign.center,
                        ),
                      ],
                    ),
                  ),

                  SizedBox(height: 50),

                  // 입력 폼
                  SlideTransition(
                    position: _slideAnimation,
                    child: FadeTransition(
                      opacity: _fadeAnimation,
                      child: Container(
                        padding: EdgeInsets.all(25),
                        decoration: BoxDecoration(
                          color: Colors.white,
                          borderRadius: BorderRadius.circular(20),
                          boxShadow: [
                            BoxShadow(
                              color: Colors.black.withOpacity(0.08),
                              blurRadius: 20,
                              spreadRadius: 2,
                              offset: Offset(0, 5),
                            ),
                          ],
                        ),
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            // 제목 입력
                            Text(
                              '제목',
                              style: TextStyle(
                                fontSize: 18,
                                fontWeight: FontWeight.w600,
                                color: Colors.black87,
                              ),
                            ),
                            SizedBox(height: 12),
                            Container(
                              decoration: BoxDecoration(
                                borderRadius: BorderRadius.circular(15),
                                border: Border.all(
                                  color: Colors.grey[300]!,
                                  width: 1.5,
                                ),
                              ),
                              child: TextField(
                                controller: _titleController,
                                style: TextStyle(
                                  fontSize: 16,
                                  color: Colors.black87,
                                ),
                                decoration: InputDecoration(
                                  hintText: '제목을 입력하세요',
                                  hintStyle: TextStyle(
                                    color: Colors.grey[400],
                                    fontSize: 16,
                                  ),
                                  border: InputBorder.none,
                                  contentPadding: EdgeInsets.all(20),
                                  prefixIcon: Icon(
                                    Icons.title,
                                    color: Color(0xFF4CAF50),
                                  ),
                                ),
                              ),
                            ),

                            SizedBox(height: 25),

                            // 복용 횟수 입력
                            Text(
                              '복용 횟수',
                              style: TextStyle(
                                fontSize: 18,
                                fontWeight: FontWeight.w600,
                                color: Colors.black87,
                              ),
                            ),
                            SizedBox(height: 12),
                            Container(
                              decoration: BoxDecoration(
                                borderRadius: BorderRadius.circular(15),
                                border: Border.all(
                                  color: Colors.grey[300]!,
                                  width: 1.5,
                                ),
                              ),
                              child: TextField(
                                controller: _dosageController,
                                keyboardType: TextInputType.number,
                                style: TextStyle(
                                  fontSize: 16,
                                  color: Colors.black87,
                                ),
                                decoration: InputDecoration(
                                  hintText: '복용 횟수를 입력하세요',
                                  hintStyle: TextStyle(
                                    color: Colors.grey[400],
                                    fontSize: 16,
                                  ),
                                  border: InputBorder.none,
                                  contentPadding: EdgeInsets.all(20),
                                  prefixIcon: Icon(
                                    Icons.medical_services,
                                    color: Color(0xFF4CAF50),
                                  ),
                                ),
                              ),
                            ),
                          ],
                        ),
                      ),
                    ),
                  ),

                  SizedBox(height: 30),

                  // 영양소 테이블
                  SlideTransition(
                    position: _slideAnimation,
                    child: FadeTransition(
                      opacity: _fadeAnimation,
                      child: Container(
                        padding: EdgeInsets.all(25),
                        decoration: BoxDecoration(
                          color: Colors.white,
                          borderRadius: BorderRadius.circular(20),
                          boxShadow: [
                            BoxShadow(
                              color: Colors.black.withOpacity(0.08),
                              blurRadius: 20,
                              spreadRadius: 2,
                              offset: Offset(0, 5),
                            ),
                          ],
                        ),
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Row(
                              children: [
                                Container(
                                  padding: EdgeInsets.all(8),
                                  decoration: BoxDecoration(
                                    color: Color(0xFF4CAF50).withOpacity(0.1),
                                    borderRadius: BorderRadius.circular(8),
                                  ),
                                  child: Icon(
                                    Icons.analytics,
                                    color: Color(0xFF4CAF50),
                                    size: 20,
                                  ),
                                ),
                                SizedBox(width: 12),
                                Text(
                                  '추출된 영양소 정보',
                                  style: TextStyle(
                                    fontSize: 18,
                                    fontWeight: FontWeight.w600,
                                    color: Colors.black87,
                                  ),
                                ),
                              ],
                            ),
                            SizedBox(height: 20),
                            Container(
                              decoration: BoxDecoration(
                                borderRadius: BorderRadius.circular(15),
                                border: Border.all(
                                  color: Colors.grey[200]!,
                                  width: 1,
                                ),
                              ),
                              child: ClipRRect(
                                borderRadius: BorderRadius.circular(15),
                                child: DataTable(
                                  headingRowColor: MaterialStateProperty.all(
                                    Color(0xFF4CAF50).withOpacity(0.1),
                                  ),
                                  columns: [
                                    DataColumn(
                                      label: Text(
                                        '영양소',
                                        style: TextStyle(
                                          fontWeight: FontWeight.bold,
                                          color: Color(0xFF4CAF50),
                                        ),
                                      ),
                                    ),
                                    DataColumn(
                                      label: Text(
                                        '양 (mg)',
                                        style: TextStyle(
                                          fontWeight: FontWeight.bold,
                                          color: Color(0xFF4CAF50),
                                        ),
                                      ),
                                    ),
                                  ],
                                  rows: vitamins.entries.map((entry) {
                                    return DataRow(
                                      cells: [
                                        DataCell(
                                          Text(
                                            _getKoreanName(entry.key),
                                            style: TextStyle(
                                              fontSize: 14,
                                              color: Colors.black87,
                                            ),
                                          ),
                                        ),
                                        DataCell(
                                          Text(
                                            '${entry.value.toStringAsFixed(1)} mg',
                                            style: TextStyle(
                                              fontSize: 14,
                                              color: Colors.black87,
                                              fontWeight: FontWeight.w500,
                                            ),
                                          ),
                                        ),
                                      ],
                                    );
                                  }).toList(),
                                ),
                              ),
                            ),
                          ],
                        ),
                      ),
                    ),
                  ),

                  SizedBox(height: 30),

                  // 저장 버튼
                  SizedBox(
                    width: double.infinity,
                    height: 55,
                    child: ElevatedButton.icon(
                      onPressed: _isLoading
                          ? null
                          : () async {
                              if (_titleController.text.isEmpty ||
                                  _dosageController.text.isEmpty) {
                                _showSnackBar('제목과 복용 횟수를 모두 입력해주세요.', false);
                                return;
                              }

                              setState(() {
                                _isLoading = true;
                              });

                              try {
                                int userId = await _getUserId();
                                final response = await _saveToDatabase(
                                  userId: userId,
                                  title: _titleController.text,
                                  dosage: _dosageController.text,
                                  vitamins: vitamins,
                                );

                                setState(() {
                                  _isLoading = false;
                                });

                                if (response.statusCode == 201) {
                                  _showSnackBar('영양정보가 성공적으로 저장되었습니다!', true);
                                  Future.delayed(Duration(seconds: 2), () {
                                    Navigator.pushReplacement(
                                      context,
                                      MaterialPageRoute(
                                        builder: (context) =>
                                            IndexScreen(userId: ''),
                                      ),
                                    );
                                  });
                                } else {
                                  final responseBody =
                                      jsonDecode(response.body);
                                  final errorMessage =
                                      responseBody['message'] ??
                                          '서버 오류가 발생했습니다.';
                                  _showSnackBar(errorMessage, false);
                                }
                              } catch (e) {
                                setState(() {
                                  _isLoading = false;
                                });
                                _showSnackBar('데이터 저장 중 오류가 발생했습니다: $e', false);
                              }
                            },
                      icon: _isLoading
                          ? SizedBox(
                              width: 20,
                              height: 20,
                              child: CircularProgressIndicator(
                                strokeWidth: 2,
                                valueColor:
                                    AlwaysStoppedAnimation<Color>(Colors.white),
                              ),
                            )
                          : Icon(Icons.save, color: Colors.white),
                      label: Text(
                        _isLoading ? '저장 중...' : '영양정보 저장하기',
                        style: TextStyle(
                          fontSize: 18,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                      style: ElevatedButton.styleFrom(
                        backgroundColor: Color(0xFF4CAF50),
                        foregroundColor: Colors.white,
                        elevation: 3,
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(15),
                        ),
                      ),
                    ),
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }

  String _getKoreanName(String englishName) {
    final Map<String, String> koreanNames = {
      'VitaminA': '비타민A',
      'VitaminD': '비타민D',
      'VitaminE': '비타민E',
      'VitaminK': '비타민K',
      'VitaminC': '비타민C',
      'Thiamine': '비타민B1',
      'Riboflavin': '비타민B2',
      'Niacin': '니아신',
      'VitaminB6': '비타민B6',
      'FolicAcid': '엽산',
      'VitaminB12': '비타민B12',
      'PantothenicAcid': '판토텐산',
      'Biotin': '비오틴',
      'Calcium': '칼슘',
      'Phosphorus': '인',
      'Sodium': '나트륨',
      'Chlorine': '염소',
      'Potassium': '칼륨',
      'Magnesium': '마그네슘',
      'Iron': '철',
      'Zinc': '아연',
      'Copper': '구리',
      'Fluorine': '플루오린',
      'Manganese': '망간',
      'Iodine': '요오드',
      'Selenium': '셀레늄',
      'Molybdenum': '몰리브덴',
      'Chromium': '크롬',
    };
    return koreanNames[englishName] ?? englishName;
  }

  Map<String, double> _extractVitamins(String text) {
    return {
      'VitaminA': double.tryParse(_extractVitaminInfo(text, '비타민A')) ?? 0.0,
      'VitaminD': double.tryParse(_extractVitaminInfo(text, '비타민D')) ?? 0.0,
      'VitaminE': double.tryParse(_extractVitaminInfo(text, '비타민E')) ?? 0.0,
      'VitaminK': double.tryParse(_extractVitaminInfo(text, '비타민K')) ?? 0.0,
      'VitaminC': double.tryParse(_extractVitaminInfo(text, '비타민C')) ?? 0.0,
      'Thiamine': double.tryParse(_extractVitaminInfo(text, '비타민B1')) ?? 0.0,
      'Riboflavin': double.tryParse(_extractVitaminInfo(text, '비타민B2')) ?? 0.0,
      'Niacin': double.tryParse(_extractVitaminInfo(text, '니아신')) ?? 0.0,
      'VitaminB6': double.tryParse(_extractVitaminInfo(text, '비타민B6')) ?? 0.0,
      'FolicAcid': double.tryParse(_extractVitaminInfo(text, '엽산')) ?? 0.0,
      'VitaminB12': double.tryParse(_extractVitaminInfo(text, '비타민B12')) ?? 0.0,
      'PantothenicAcid':
          double.tryParse(_extractVitaminInfo(text, '판토텐산')) ?? 0.0,
      'Biotin': double.tryParse(_extractVitaminInfo(text, '비오틴')) ?? 0.0,
      'Calcium': double.tryParse(_extractVitaminInfo(text, '칼슘')) ?? 0.0,
      'Phosphorus': double.tryParse(_extractVitaminInfo(text, '인')) ?? 0.0,
      'Sodium': double.tryParse(_extractVitaminInfo(text, '나트륨')) ?? 0.0,
      'Chlorine': double.tryParse(_extractVitaminInfo(text, '염소')) ?? 0.0,
      'Potassium': double.tryParse(_extractVitaminInfo(text, '칼륨')) ?? 0.0,
      'Magnesium': double.tryParse(_extractVitaminInfo(text, '마그네슘')) ?? 0.0,
      'Iron': double.tryParse(_extractVitaminInfo(text, '철')) ?? 0.0,
      'Zinc': double.tryParse(_extractVitaminInfo(text, '아연')) ?? 0.0,
      'Copper': double.tryParse(_extractVitaminInfo(text, '구리')) ?? 0.0,
      'Fluorine': double.tryParse(_extractVitaminInfo(text, '플루오린')) ?? 0.0,
      'Manganese': double.tryParse(_extractVitaminInfo(text, '망간')) ?? 0.0,
      'Iodine': double.tryParse(_extractVitaminInfo(text, '요오드')) ?? 0.0,
      'Selenium': double.tryParse(_extractVitaminInfo(text, '셀레늄')) ?? 0.0,
      'Molybdenum': double.tryParse(_extractVitaminInfo(text, '몰리브덴')) ?? 0.0,
      'Chromium': double.tryParse(_extractVitaminInfo(text, '크롬')) ?? 0.0,
    };
  }

  String _extractVitaminInfo(String text, String vitaminName) {
    final regex = RegExp(
      '${RegExp.escape(vitaminName)}\\s+([0-9]+(?:\\.[0-9]+)?)',
      caseSensitive: false,
    );
    final match = regex.firstMatch(text);
    return match != null ? match.group(1) ?? '0' : '0';
  }

  Future<http.Response> _saveToDatabase({
    required int userId,
    required String title,
    required String dosage,
    required Map<String, double> vitamins,
  }) async {
    final token = await _getToken();
    return await http.post(
      Uri.parse('http://192.168.35.173:5000/save-tonic-detail'),
      headers: {
        'Content-Type': 'application/json',
        'Authorization': 'Bearer $token',
      },
      body: jsonEncode({
        'title': title,
        'dosage': dosage,
        ...vitamins.map((key, value) => MapEntry(key, value.toString())),
      }),
    );
  }

  void _showSnackBar(String message, bool isSuccess) {
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Row(
          children: [
            Icon(
              isSuccess ? Icons.check_circle : Icons.error,
              color: Colors.white,
            ),
            SizedBox(width: 8),
            Expanded(
              child: Text(
                message,
                style: TextStyle(
                  color: Colors.white,
                  fontWeight: FontWeight.w500,
                ),
              ),
            ),
          ],
        ),
        backgroundColor: isSuccess ? Color(0xFF4CAF50) : Color(0xFFF44336),
        behavior: SnackBarBehavior.floating,
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(10),
        ),
        margin: EdgeInsets.all(16),
        duration: Duration(seconds: 3),
      ),
    );
  }
}

import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'nutrition_detail_page.dart';
import 'good.dart'; // 상태 페이지 임포트
import 'package:shared_preferences/shared_preferences.dart';

class TabHome extends StatefulWidget {
  @override
  _TabHomeState createState() => _TabHomeState();
}

class _TabHomeState extends State<TabHome> with TickerProviderStateMixin {
  List<dynamic> _nutritionData = [];
  Map<String, dynamic> _nutritionTotals = {};
  bool _isLoading = true;

  late AnimationController _fadeController;
  late AnimationController _slideController;
  late Animation<double> _fadeAnimation;
  late Animation<Offset> _slideAnimation;

  final List<String> nutrientOrder = [
    "VitaminA",
    "VitaminD",
    "VitaminE",
    "VitaminK",
    "VitaminC",
    "Thiamine",
    "Riboflavin",
    "Niacin",
    "VitaminB6",
    "FolicAcid",
    "VitaminB12",
    "PantothenicAcid",
    "Biotin",
    "Calcium",
    "Phosphorus",
    "Sodium",
    "Chlorine",
    "Potassium",
    "Magnesium",
    "Iron",
    "Zinc",
    "Copper",
    "Fluorine",
    "Manganese",
    "Iodine",
    "Selenium",
    "Molybdenum",
    "Chromium"
  ];

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

    _initializeData();
  }

  @override
  void dispose() {
    _fadeController.dispose();
    _slideController.dispose();
    super.dispose();
  }

  Future<void> _initializeData() async {
    await fetchNutritionInfo();
    await fetchNutritionTotals();

    setState(() {
      _isLoading = false;
    });

    _fadeController.forward();
    Future.delayed(Duration(milliseconds: 300), () {
      _slideController.forward();
    });
  }

  Future<void> fetchNutritionInfo() async {
    try {
      final token = await _getToken();
      if (token == null) return;

      print('Request Headers: Authorization: Bearer $token');
      final response = await http.get(
        Uri.parse('http://192.168.35.173:5000/get-tonic-detail'),
        headers: {'Authorization': 'Bearer $token'},
      );

      if (response.statusCode == 200) {
        setState(() {
          _nutritionData = jsonDecode(response.body) ?? [];
        });
      } else {
        print('영양 정보 로드 실패: ${response.body}');
      }
    } catch (error) {
      print('영양 정보 가져오기 에러: $error');
    }
  }

  Future<void> fetchNutritionTotals() async {
    try {
      final token = await _getToken();
      if (token == null) return;

      final response = await http.get(
        Uri.parse('http://192.168.35.173:5000/get_tonic_sum_detail'),
        headers: {'Authorization': 'Bearer $token'},
      );

      if (response.statusCode == 200) {
        final body = jsonDecode(response.body);
        if (body != null && body['evaluation'] != null) {
          setState(() {
            _nutritionTotals = Map<String, dynamic>.from(body['evaluation']);
          });
        } else {
          setState(() {
            _nutritionTotals = {};
          });
        }
      } else {
        print('총 영양 정보 로드 실패: ${response.body}');
      }
    } catch (error) {
      print('총 영양 정보 가져오기 에러: $error');
    }
  }

  Future<String?> _getToken() async {
    final prefs = await SharedPreferences.getInstance();
    final token = prefs.getString('jwt_token');
    print('JWT Token: $token');
    return token;
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      appBar: AppBar(
        title: Text(
          '영양정보',
          style: TextStyle(
            color: Colors.white,
            fontWeight: FontWeight.bold,
            fontSize: 20,
          ),
        ),
        centerTitle: true,
        backgroundColor: Color(0xFF4CAF50),
        elevation: 0,
        automaticallyImplyLeading: false,
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
            RefreshIndicator(
              onRefresh: _refreshData,
              child: _isLoading
                  ? Center(
                      child: Column(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          Container(
                            width: 80,
                            height: 80,
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
                            ),
                            child: CircularProgressIndicator(
                              valueColor:
                                  AlwaysStoppedAnimation<Color>(Colors.white),
                              strokeWidth: 3,
                            ),
                          ),
                          SizedBox(height: 20),
                          Text(
                            '영양 정보를 불러오는 중...',
                            style: TextStyle(
                              fontSize: 16,
                              color: Colors.grey[600],
                              fontWeight: FontWeight.w500,
                            ),
                          ),
                        ],
                      ),
                    )
                  : SingleChildScrollView(
                      physics: AlwaysScrollableScrollPhysics(),
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
                                        color:
                                            Color(0xFF4CAF50).withOpacity(0.3),
                                        blurRadius: 20,
                                        spreadRadius: 5,
                                      ),
                                    ],
                                  ),
                                  child: Icon(
                                    Icons.home,
                                    size: 50,
                                    color: Colors.white,
                                  ),
                                ),
                                SizedBox(height: 20),
                                Text(
                                  '영양 정보 대시보드',
                                  style: TextStyle(
                                    fontSize: 28.0,
                                    color: Colors.black87,
                                    fontWeight: FontWeight.bold,
                                    letterSpacing: 1.5,
                                  ),
                                ),
                                SizedBox(height: 10),
                                Text(
                                  '오늘의 영양 상태를 확인하세요',
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

                          // 영양 상태 카드
                          SlideTransition(
                            position: _slideAnimation,
                            child: FadeTransition(
                              opacity: _fadeAnimation,
                              child: _buildNutritionTotalsCard(),
                            ),
                          ),

                          SizedBox(height: 30),

                          // 영양제 목록
                          if (_nutritionData.isNotEmpty) ...[
                            SlideTransition(
                              position: _slideAnimation,
                              child: FadeTransition(
                                opacity: _fadeAnimation,
                                child: Column(
                                  crossAxisAlignment: CrossAxisAlignment.start,
                                  children: [
                                    Row(
                                      children: [
                                        Container(
                                          padding: EdgeInsets.all(8),
                                          decoration: BoxDecoration(
                                            color: Color(0xFF4CAF50)
                                                .withOpacity(0.1),
                                            borderRadius:
                                                BorderRadius.circular(8),
                                          ),
                                          child: Icon(
                                            Icons.medication,
                                            color: Color(0xFF4CAF50),
                                            size: 20,
                                          ),
                                        ),
                                        SizedBox(width: 12),
                                        Text(
                                          '내 영양제 목록',
                                          style: TextStyle(
                                            fontSize: 20,
                                            fontWeight: FontWeight.bold,
                                            color: Colors.black87,
                                          ),
                                        ),
                                      ],
                                    ),
                                    SizedBox(height: 20),
                                    ListView.builder(
                                      physics: NeverScrollableScrollPhysics(),
                                      shrinkWrap: true,
                                      itemCount: _nutritionData.length,
                                      itemBuilder: (context, index) {
                                        final item = _nutritionData[index];
                                        return _buildNutritionCard(item, index);
                                      },
                                    ),
                                  ],
                                ),
                              ),
                            ),
                          ] else ...[
                            SlideTransition(
                              position: _slideAnimation,
                              child: FadeTransition(
                                opacity: _fadeAnimation,
                                child: Container(
                                  padding: EdgeInsets.all(40),
                                  decoration: BoxDecoration(
                                    color: Colors.grey[50],
                                    borderRadius: BorderRadius.circular(20),
                                    border: Border.all(
                                      color: Colors.grey[200]!,
                                      width: 1,
                                    ),
                                  ),
                                  child: Column(
                                    children: [
                                      Icon(
                                        Icons.medication_outlined,
                                        size: 60,
                                        color: Colors.grey[400],
                                      ),
                                      SizedBox(height: 20),
                                      Text(
                                        '등록된 영양제가 없습니다',
                                        style: TextStyle(
                                          fontSize: 18,
                                          color: Colors.grey[600],
                                          fontWeight: FontWeight.w500,
                                        ),
                                      ),
                                      SizedBox(height: 10),
                                      Text(
                                        '영양제를 추가해보세요!',
                                        style: TextStyle(
                                          fontSize: 14,
                                          color: Colors.grey[500],
                                        ),
                                      ),
                                    ],
                                  ),
                                ),
                              ),
                            ),
                          ],
                        ],
                      ),
                    ),
            ),
          ],
        ),
      ),
    );
  }

  Future<void> _refreshData() async {
    await fetchNutritionInfo();
    await fetchNutritionTotals();
  }

  Widget _buildNutritionCard(Map<String, dynamic> item, int index) {
    return Container(
      margin: EdgeInsets.only(bottom: 16),
      child: Material(
        color: Colors.transparent,
        child: InkWell(
          borderRadius: BorderRadius.circular(20),
          onTap: () => _navigateToDetailPage(item),
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
                      padding: EdgeInsets.all(12),
                      decoration: BoxDecoration(
                        color: Color(0xFF4CAF50).withOpacity(0.1),
                        borderRadius: BorderRadius.circular(12),
                      ),
                      child: Icon(
                        Icons.medication,
                        color: Color(0xFF4CAF50),
                        size: 24,
                      ),
                    ),
                    SizedBox(width: 15),
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            item['title'] ?? '제목 없음',
                            style: TextStyle(
                              fontSize: 18,
                              fontWeight: FontWeight.bold,
                              color: Colors.black87,
                            ),
                          ),
                          SizedBox(height: 5),
                          Text(
                            '복용 횟수: ${item['dosage']}회',
                            style: TextStyle(
                              fontSize: 14,
                              color: Colors.grey[600],
                              fontWeight: FontWeight.w500,
                            ),
                          ),
                        ],
                      ),
                    ),
                    PopupMenuButton<String>(
                      icon: Icon(Icons.more_vert, color: Colors.grey[600]),
                      onSelected: (value) {
                        if (value == 'edit') {
                          _showEditDialog(item);
                        } else if (value == 'delete') {
                          _confirmDelete(item['tonic_number']);
                        }
                      },
                      itemBuilder: (context) => [
                        PopupMenuItem(
                          value: 'edit',
                          child: Row(
                            children: [
                              Icon(Icons.edit,
                                  color: Color(0xFF4CAF50), size: 20),
                              SizedBox(width: 8),
                              Text('수정'),
                            ],
                          ),
                        ),
                        PopupMenuItem(
                          value: 'delete',
                          child: Row(
                            children: [
                              Icon(Icons.delete, color: Colors.red, size: 20),
                              SizedBox(width: 8),
                              Text('삭제', style: TextStyle(color: Colors.red)),
                            ],
                          ),
                        ),
                      ],
                    ),
                  ],
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }

  void _navigateToStatusPage() {
    Navigator.push(
      context,
      MaterialPageRoute(
        builder: (context) => NutritionStatusPage(
          nutritionTotals: _nutritionTotals,
        ),
      ),
    );
  }

  Widget _buildNutritionTotalsCard() {
    return GestureDetector(
      onTap: _navigateToStatusPage,
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
                  '영양 상태 요약',
                  style: TextStyle(
                    fontSize: 18,
                    fontWeight: FontWeight.bold,
                    color: Colors.black87,
                  ),
                ),
                Spacer(),
                Icon(
                  Icons.arrow_forward_ios,
                  color: Colors.grey[400],
                  size: 16,
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
                child: ListView.builder(
                  shrinkWrap: true,
                  physics: NeverScrollableScrollPhysics(),
                  itemCount: nutrientOrder.length,
                  itemBuilder: (context, index) {
                    final nutrient = nutrientOrder[index];
                    final entry = _nutritionTotals[nutrient];
                    final intake = entry?['intake']?.toString() ?? 'N/A';
                    final color = _getStatusColor(entry?['status'] ?? 'N/A');

                    return Container(
                      padding:
                          EdgeInsets.symmetric(horizontal: 20, vertical: 12),
                      decoration: BoxDecoration(
                        border: Border(
                          bottom: BorderSide(
                            color: Colors.grey[100]!,
                            width: 1,
                          ),
                        ),
                      ),
                      child: Row(
                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
                        children: [
                          Text(
                            _getKoreanName(nutrient),
                            style: TextStyle(
                              fontSize: 14,
                              color: Colors.black87,
                              fontWeight: FontWeight.w500,
                            ),
                          ),
                          Container(
                            padding: EdgeInsets.symmetric(
                                horizontal: 8, vertical: 4),
                            decoration: BoxDecoration(
                              color: color.withOpacity(0.1),
                              borderRadius: BorderRadius.circular(8),
                            ),
                            child: Text(
                              intake,
                              style: TextStyle(
                                fontSize: 14,
                                color: color,
                                fontWeight: FontWeight.bold,
                              ),
                            ),
                          ),
                        ],
                      ),
                    );
                  },
                ),
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

  Color _getStatusColor(String status) {
    switch (status) {
      case '부족':
        return Colors.blue;
      case '과다':
        return Colors.red;
      case '적정':
        return Color(0xFF4CAF50);
      default:
        return Colors.grey;
    }
  }

  void _showEditDialog(Map<String, dynamic> item) {
    final titleController = TextEditingController(text: item['title']);
    final dosageController =
        TextEditingController(text: item['dosage'].toString());

    showDialog(
      context: context,
      builder: (context) {
        return AlertDialog(
          shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(20),
          ),
          title: Text(
            '정보 수정',
            style: TextStyle(
              fontWeight: FontWeight.bold,
              color: Colors.black87,
            ),
          ),
          content: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              Container(
                decoration: BoxDecoration(
                  borderRadius: BorderRadius.circular(15),
                  border: Border.all(
                    color: Colors.grey[300]!,
                    width: 1.5,
                  ),
                ),
                child: TextField(
                  controller: titleController,
                  style: TextStyle(fontSize: 16),
                  decoration: InputDecoration(
                    hintText: '제목',
                    border: InputBorder.none,
                    contentPadding: EdgeInsets.all(20),
                    prefixIcon: Icon(Icons.title, color: Color(0xFF4CAF50)),
                  ),
                ),
              ),
              SizedBox(height: 20),
              Container(
                decoration: BoxDecoration(
                  borderRadius: BorderRadius.circular(15),
                  border: Border.all(
                    color: Colors.grey[300]!,
                    width: 1.5,
                  ),
                ),
                child: TextField(
                  controller: dosageController,
                  keyboardType: TextInputType.number,
                  style: TextStyle(fontSize: 16),
                  decoration: InputDecoration(
                    hintText: '복용 횟수',
                    border: InputBorder.none,
                    contentPadding: EdgeInsets.all(20),
                    prefixIcon:
                        Icon(Icons.medical_services, color: Color(0xFF4CAF50)),
                  ),
                ),
              ),
            ],
          ),
          actions: [
            TextButton(
              onPressed: () => Navigator.of(context).pop(),
              child: Text(
                '취소',
                style: TextStyle(color: Colors.grey[600]),
              ),
            ),
            ElevatedButton(
              onPressed: () async {
                await _updateNutritionItem(item['tonic_number'],
                    titleController.text, dosageController.text);
                Navigator.of(context).pop();
                await _refreshData();
              },
              style: ElevatedButton.styleFrom(
                backgroundColor: Color(0xFF4CAF50),
                shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(10),
                ),
              ),
              child: Text(
                '저장',
                style: TextStyle(color: Colors.white),
              ),
            ),
          ],
        );
      },
    );
  }

  Future<void> _updateNutritionItem(
      int tonicNumber, String newTitle, String newDosage) async {
    final token = await _getToken();
    if (token == null) return;

    final response = await http.put(
      Uri.parse('http://192.168.35.173:5000/update-tonic-detail/$tonicNumber'),
      headers: {
        'Content-Type': 'application/json',
        'Authorization': 'Bearer $token',
      },
      body: jsonEncode({'title': newTitle, 'dosage': int.tryParse(newDosage)}),
    );

    if (response.statusCode != 200) {
      _showSnackBar('업데이트 실패!', false);
    } else {
      _showSnackBar('업데이트 성공!', true);
    }
  }

  Future<void> _confirmDelete(int tonicNumber) async {
    showDialog(
      context: context,
      builder: (context) {
        return AlertDialog(
          shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(20),
          ),
          title: Text(
            '삭제 확인',
            style: TextStyle(
              fontWeight: FontWeight.bold,
              color: Colors.black87,
            ),
          ),
          content: Text(
            '이 항목을 삭제하시겠습니까?',
            style: TextStyle(fontSize: 16),
          ),
          actions: [
            TextButton(
              onPressed: () => Navigator.of(context).pop(),
              child: Text(
                '취소',
                style: TextStyle(color: Colors.grey[600]),
              ),
            ),
            ElevatedButton(
              onPressed: () async {
                await _deleteNutritionItem(tonicNumber);
                Navigator.of(context).pop();
              },
              style: ElevatedButton.styleFrom(
                backgroundColor: Colors.red,
                shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(10),
                ),
              ),
              child: Text(
                '삭제',
                style: TextStyle(color: Colors.white),
              ),
            ),
          ],
        );
      },
    );
  }

  Future<void> _deleteNutritionItem(int tonicNumber) async {
    final token = await _getToken();
    if (token == null) return;

    final response = await http.delete(
      Uri.parse('http://192.168.35.173:5000/delete-tonic-detail/$tonicNumber'),
      headers: {'Authorization': 'Bearer $token'},
    );

    if (response.statusCode == 200) {
      await _refreshData();
      _showSnackBar('삭제 성공!', true);
    } else {
      _showSnackBar('삭제 실패!', false);
    }
  }

  void _navigateToDetailPage(Map<String, dynamic> item) async {
    final updatedValues = await Navigator.push(
      context,
      MaterialPageRoute(
        builder: (context) => NutritionDetailPage(
          item: item,
          tonicNumber: item['tonic_number'],
        ),
      ),
    );

    if (updatedValues != null) {
      await _refreshData();
    }
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

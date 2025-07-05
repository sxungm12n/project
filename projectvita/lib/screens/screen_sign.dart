import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'package:fluttertoast/fluttertoast.dart';
import 'package:get/get.dart';

class SignupPage extends StatefulWidget {
  const SignupPage({Key? key}) : super(key: key);

  @override
  State<SignupPage> createState() => _SignupPageState();
}

class _SignupPageState extends State<SignupPage> with TickerProviderStateMixin {
  final formKey = GlobalKey<FormState>();
  final idController = TextEditingController();
  final userNameController = TextEditingController();
  final passwordController = TextEditingController();
  final birthYearController = TextEditingController();
  final birthMonthController = TextEditingController();
  final birthDayController = TextEditingController();

  String? selectedGender;
  List<String> genders = ['남자', '여자'];

  bool _isPasswordVisible = false;
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
    idController.dispose();
    userNameController.dispose();
    passwordController.dispose();
    birthYearController.dispose();
    birthMonthController.dispose();
    birthDayController.dispose();
    super.dispose();
  }

  // 회원가입 요청
  Future<void> registerUser() async {
    if (_isLoading) return;

    if (formKey.currentState!.validate()) {
      if (selectedGender == null) {
        Fluttertoast.showToast(msg: "성별을 선택하세요");
        return;
      }

      setState(() {
        _isLoading = true;
      });

      final birthday =
          "${birthYearController.text.padLeft(4, '0')}-${birthMonthController.text.padLeft(2, '0')}-${birthDayController.text.padLeft(2, '0')}";

      final requestBody = {
        'username': idController.text.trim(),
        'password': passwordController.text.trim(),
        'name': userNameController.text.trim(),
        'birthday': birthday,
        'gender': selectedGender,
      };

      print("회원가입 요청 데이터: $requestBody");

      try {
        var response = await http
            .post(
          Uri.parse('http://192.168.35.173:5000/signup'),
          headers: {"Content-Type": "application/json"},
          body: jsonEncode(requestBody),
        )
            .timeout(const Duration(seconds: 10), onTimeout: () {
          Fluttertoast.showToast(msg: "서버 응답 시간 초과");
          return http.Response('Error', 500);
        });

        if (response.statusCode == 200) {
          var responseBody = jsonDecode(response.body);
          if (responseBody['success'] == true) {
            Fluttertoast.showToast(msg: "회원가입 성공");
            Get.offNamed('/login');
          } else {
            Fluttertoast.showToast(msg: responseBody['message']);
          }
        } else {
          Fluttertoast.showToast(msg: "회원가입 실패: ${response.statusCode}");
        }
      } catch (e) {
        Fluttertoast.showToast(msg: "오류 발생: $e");
      } finally {
        setState(() {
          _isLoading = false;
        });
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Container(
        decoration: BoxDecoration(
          gradient: LinearGradient(
            begin: Alignment.topLeft,
            end: Alignment.bottomRight,
            colors: [
              Color(0xFF4CAF50),
              Color(0xFF2E7D32),
              Color(0xFF1B5E20),
            ],
            stops: [0.0, 0.5, 1.0],
          ),
        ),
        child: SafeArea(
          child: Stack(
            children: [
              // 배경 장식 요소들
              Positioned(
                top: -100,
                right: -100,
                child: Container(
                  width: 300,
                  height: 300,
                  decoration: BoxDecoration(
                    shape: BoxShape.circle,
                    color: Colors.white.withOpacity(0.1),
                  ),
                ),
              ),
              Positioned(
                bottom: -150,
                left: -150,
                child: Container(
                  width: 400,
                  height: 400,
                  decoration: BoxDecoration(
                    shape: BoxShape.circle,
                    color: Colors.white.withOpacity(0.05),
                  ),
                ),
              ),

              // 뒤로가기 버튼
              Positioned(
                top: 20,
                left: 20,
                child: FadeTransition(
                  opacity: _fadeAnimation,
                  child: Container(
                    decoration: BoxDecoration(
                      color: Colors.white.withOpacity(0.2),
                      borderRadius: BorderRadius.circular(12),
                    ),
                    child: IconButton(
                      icon: Icon(Icons.arrow_back, color: Colors.white),
                      onPressed: () => Get.back(),
                    ),
                  ),
                ),
              ),

              // 메인 콘텐츠
              Center(
                child: SingleChildScrollView(
                  padding: const EdgeInsets.symmetric(horizontal: 30.0),
                  child: Column(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      // 헤더 섹션
                      FadeTransition(
                        opacity: _fadeAnimation,
                        child: Column(
                          children: [
                            Text(
                              '회원가입',
                              style: TextStyle(
                                fontSize: 36.0,
                                color: Colors.white,
                                fontWeight: FontWeight.bold,
                                letterSpacing: 2.0,
                                shadows: [
                                  Shadow(
                                    color: Colors.black.withOpacity(0.3),
                                    offset: Offset(2, 2),
                                    blurRadius: 4,
                                  ),
                                ],
                              ),
                            ),
                            SizedBox(height: 10),
                            Text(
                              '건강한 삶을 위한 첫 걸음',
                              style: TextStyle(
                                fontSize: 16.0,
                                color: Colors.white.withOpacity(0.9),
                                fontWeight: FontWeight.w300,
                                letterSpacing: 1.0,
                              ),
                            ),
                          ],
                        ),
                      ),

                      SizedBox(height: 50),

                      // 회원가입 폼
                      SlideTransition(
                        position: _slideAnimation,
                        child: FadeTransition(
                          opacity: _fadeAnimation,
                          child: Container(
                            padding: EdgeInsets.all(30),
                            decoration: BoxDecoration(
                              color: Colors.white.withOpacity(0.95),
                              borderRadius: BorderRadius.circular(20),
                              boxShadow: [
                                BoxShadow(
                                  color: Colors.black.withOpacity(0.1),
                                  blurRadius: 20,
                                  spreadRadius: 5,
                                ),
                              ],
                            ),
                            child: Form(
                              key: formKey,
                              child: Column(
                                children: [
                                  _buildTextField(
                                    controller: userNameController,
                                    hintText: '사용자 이름',
                                    icon: Icons.person_outline,
                                    validator: (val) =>
                                        val!.isEmpty ? "사용자 이름을 입력하세요" : null,
                                  ),
                                  SizedBox(height: 20),
                                  _buildTextField(
                                    controller: idController,
                                    hintText: '아이디',
                                    icon: Icons.account_circle_outlined,
                                    validator: (val) =>
                                        val!.isEmpty ? "아이디를 입력하세요" : null,
                                  ),
                                  SizedBox(height: 20),
                                  _buildPasswordField(),
                                  SizedBox(height: 20),
                                  _buildBirthdayRow(),
                                  SizedBox(height: 20),
                                  _buildDropdownField(),
                                ],
                              ),
                            ),
                          ),
                        ),
                      ),

                      SizedBox(height: 30),

                      // 회원가입 버튼
                      FadeTransition(
                        opacity: _fadeAnimation,
                        child: SizedBox(
                          width: double.infinity,
                          height: 55,
                          child: ElevatedButton(
                            onPressed: _isLoading
                                ? null
                                : () {
                                    if (formKey.currentState!.validate()) {
                                      registerUser();
                                    }
                                  },
                            style: ElevatedButton.styleFrom(
                              backgroundColor: Color(0xFF2E7D32),
                              foregroundColor: Colors.white,
                              elevation: 3,
                              shadowColor: Color(0xFF2E7D32).withOpacity(0.3),
                              shape: RoundedRectangleBorder(
                                borderRadius: BorderRadius.circular(15),
                              ),
                            ),
                            child: _isLoading
                                ? SizedBox(
                                    width: 20,
                                    height: 20,
                                    child: CircularProgressIndicator(
                                      strokeWidth: 2,
                                      valueColor: AlwaysStoppedAnimation<Color>(
                                          Colors.white),
                                    ),
                                  )
                                : Text(
                                    '회원가입',
                                    style: TextStyle(
                                      fontSize: 18,
                                      fontWeight: FontWeight.bold,
                                    ),
                                  ),
                          ),
                        ),
                      ),
                    ],
                  ),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  Future<int?> _selectNumber(
      BuildContext context, int min, int max, String title) async {
    return await showDialog<int>(
      context: context,
      builder: (BuildContext context) {
        int selectedValue = min;
        return Dialog(
          backgroundColor: Colors.transparent,
          child: Container(
            decoration: BoxDecoration(
              gradient: LinearGradient(
                begin: Alignment.topLeft,
                end: Alignment.bottomRight,
                colors: [
                  Color(0xFF4CAF50),
                  Color(0xFF2E7D32),
                  Color(0xFF1B5E20),
                ],
                stops: [0.0, 0.5, 1.0],
              ),
              borderRadius: BorderRadius.circular(20),
              boxShadow: [
                BoxShadow(
                  color: Colors.black.withOpacity(0.3),
                  blurRadius: 20,
                  spreadRadius: 5,
                ),
              ],
            ),
            child: Column(
              mainAxisSize: MainAxisSize.min,
              children: [
                // 헤더
                Container(
                  padding: EdgeInsets.all(20),
                  decoration: BoxDecoration(
                    color: Colors.white.withOpacity(0.1),
                    borderRadius: BorderRadius.only(
                      topLeft: Radius.circular(20),
                      topRight: Radius.circular(20),
                    ),
                  ),
                  child: Row(
                    children: [
                      Icon(
                        Icons.calendar_today,
                        color: Colors.white,
                        size: 24,
                      ),
                      SizedBox(width: 10),
                      Text(
                        title,
                        style: TextStyle(
                          fontSize: 20,
                          fontWeight: FontWeight.bold,
                          color: Colors.white,
                        ),
                      ),
                    ],
                  ),
                ),

                // 선택 영역
                Container(
                  padding: EdgeInsets.all(20),
                  child: Column(
                    children: [
                      Container(
                        height: 200,
                        decoration: BoxDecoration(
                          color: Colors.white.withOpacity(0.95),
                          borderRadius: BorderRadius.circular(15),
                          boxShadow: [
                            BoxShadow(
                              color: Colors.black.withOpacity(0.1),
                              blurRadius: 10,
                              spreadRadius: 2,
                            ),
                          ],
                        ),
                        child: ListWheelScrollView.useDelegate(
                          itemExtent: 50,
                          perspective: 0.005,
                          diameterRatio: 2.0,
                          physics: FixedExtentScrollPhysics(),
                          onSelectedItemChanged: (index) {
                            selectedValue = min + index;
                          },
                          childDelegate: ListWheelChildBuilderDelegate(
                            builder: (context, index) {
                              if (index >= 0 && index < (max - min + 1)) {
                                return Center(
                                  child: Text(
                                    (min + index).toString(),
                                    style: TextStyle(
                                      fontSize: 24,
                                      fontWeight: FontWeight.w600,
                                      color: Color(0xFF2E7D32),
                                    ),
                                  ),
                                );
                              }
                              return null;
                            },
                            childCount: max - min + 1,
                          ),
                        ),
                      ),

                      SizedBox(height: 20),

                      // 버튼 영역
                      Row(
                        children: [
                          Expanded(
                            child: ElevatedButton(
                              onPressed: () => Navigator.of(context).pop(),
                              style: ElevatedButton.styleFrom(
                                backgroundColor: Colors.white.withOpacity(0.2),
                                foregroundColor: Colors.white,
                                elevation: 0,
                                padding: EdgeInsets.symmetric(vertical: 15),
                                shape: RoundedRectangleBorder(
                                  borderRadius: BorderRadius.circular(12),
                                ),
                              ),
                              child: Text(
                                '취소',
                                style: TextStyle(
                                  fontSize: 16,
                                  fontWeight: FontWeight.w600,
                                ),
                              ),
                            ),
                          ),
                          SizedBox(width: 15),
                          Expanded(
                            child: ElevatedButton(
                              onPressed: () =>
                                  Navigator.of(context).pop(selectedValue),
                              style: ElevatedButton.styleFrom(
                                backgroundColor: Colors.white,
                                foregroundColor: Color(0xFF2E7D32),
                                elevation: 3,
                                padding: EdgeInsets.symmetric(vertical: 15),
                                shape: RoundedRectangleBorder(
                                  borderRadius: BorderRadius.circular(12),
                                ),
                              ),
                              child: Text(
                                '확인',
                                style: TextStyle(
                                  fontSize: 16,
                                  fontWeight: FontWeight.bold,
                                ),
                              ),
                            ),
                          ),
                        ],
                      ),
                    ],
                  ),
                ),
              ],
            ),
          ),
        );
      },
    );
  }

  Widget _buildTextField({
    required TextEditingController controller,
    required String hintText,
    IconData? icon,
    bool obscureText = false,
    String? Function(String?)? validator,
    void Function()? onTap,
  }) {
    return TextFormField(
      controller: controller,
      obscureText: obscureText,
      validator: validator,
      onTap: onTap,
      readOnly: onTap != null,
      decoration: InputDecoration(
        hintText: hintText,
        hintStyle: TextStyle(color: Colors.grey[400]),
        prefixIcon: icon != null ? Icon(icon, color: Color(0xFF2E7D32)) : null,
        filled: true,
        fillColor: Colors.grey[50],
        border: OutlineInputBorder(
          borderRadius: BorderRadius.circular(15),
          borderSide: BorderSide.none,
        ),
        enabledBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(15),
          borderSide: BorderSide(color: Colors.grey[300]!, width: 1),
        ),
        focusedBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(15),
          borderSide: BorderSide(color: Color(0xFF2E7D32), width: 2),
        ),
        contentPadding: EdgeInsets.symmetric(vertical: 18.0, horizontal: 20.0),
      ),
      style: TextStyle(fontSize: 16, color: Colors.black87),
    );
  }

  Widget _buildPasswordField() {
    return TextFormField(
      controller: passwordController,
      obscureText: !_isPasswordVisible,
      validator: (val) {
        if (val!.isEmpty) return "비밀번호를 입력하세요";
        if (val.length < 6) return "비밀번호는 최소 6자 이상이어야 합니다.";
        return null;
      },
      decoration: InputDecoration(
        hintText: '비밀번호',
        hintStyle: TextStyle(color: Colors.grey[400]),
        prefixIcon: Icon(Icons.lock_outline, color: Color(0xFF2E7D32)),
        suffixIcon: IconButton(
          icon: Icon(
            _isPasswordVisible ? Icons.visibility : Icons.visibility_off,
            color: Color(0xFF2E7D32),
          ),
          onPressed: () {
            setState(() {
              _isPasswordVisible = !_isPasswordVisible;
            });
          },
        ),
        filled: true,
        fillColor: Colors.grey[50],
        border: OutlineInputBorder(
          borderRadius: BorderRadius.circular(15),
          borderSide: BorderSide.none,
        ),
        enabledBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(15),
          borderSide: BorderSide(color: Colors.grey[300]!, width: 1),
        ),
        focusedBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(15),
          borderSide: BorderSide(color: Color(0xFF2E7D32), width: 2),
        ),
        contentPadding: EdgeInsets.symmetric(vertical: 18.0, horizontal: 20.0),
      ),
      style: TextStyle(fontSize: 16, color: Colors.black87),
    );
  }

  Widget _buildBirthdayRow() {
    return Row(
      children: [
        Expanded(
          flex: 2,
          child: GestureDetector(
            onTap: () async {
              int? selectedYear = await _selectNumber(
                  context, 1900, DateTime.now().year, '출생년도');
              if (selectedYear != null) {
                birthYearController.text = selectedYear.toString();
              }
            },
            child: AbsorbPointer(
              child: _buildTextField(
                controller: birthYearController,
                hintText: '출생년도',
                icon: Icons.calendar_today_outlined,
                validator: (val) => val!.isEmpty ? "출생년도를 입력하세요" : null,
              ),
            ),
          ),
        ),
        SizedBox(width: 10),
        Expanded(
          flex: 1,
          child: GestureDetector(
            onTap: () async {
              int? selectedMonth = await _selectNumber(context, 1, 12, '월');
              if (selectedMonth != null) {
                birthMonthController.text =
                    selectedMonth.toString().padLeft(2, '0');
              }
            },
            child: AbsorbPointer(
              child: _buildTextField(
                controller: birthMonthController,
                hintText: '월',
                icon: null,
                validator: (val) => val!.isEmpty ? "월을 입력하세요" : null,
              ),
            ),
          ),
        ),
        SizedBox(width: 10),
        Expanded(
          flex: 1,
          child: GestureDetector(
            onTap: () async {
              int? selectedDay = await _selectNumber(context, 1, 31, '일');
              if (selectedDay != null) {
                birthDayController.text =
                    selectedDay.toString().padLeft(2, '0');
              }
            },
            child: AbsorbPointer(
              child: _buildTextField(
                controller: birthDayController,
                hintText: '일',
                icon: null,
                validator: (val) => val!.isEmpty ? "일을 입력하세요" : null,
              ),
            ),
          ),
        ),
      ],
    );
  }

  Widget _buildDropdownField() {
    return Container(
      decoration: BoxDecoration(
        borderRadius: BorderRadius.circular(15),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.05),
            blurRadius: 10,
            spreadRadius: 1,
          ),
        ],
      ),
      child: DropdownButtonFormField<String>(
        value: selectedGender,
        decoration: InputDecoration(
          hintText: '성별 선택',
          hintStyle: TextStyle(color: Colors.grey[400]),
          prefixIcon: Icon(Icons.wc_outlined, color: Color(0xFF2E7D32)),
          filled: true,
          fillColor: Colors.grey[50],
          border: OutlineInputBorder(
            borderRadius: BorderRadius.circular(15),
            borderSide: BorderSide.none,
          ),
          enabledBorder: OutlineInputBorder(
            borderRadius: BorderRadius.circular(15),
            borderSide: BorderSide(color: Colors.grey[300]!, width: 1),
          ),
          focusedBorder: OutlineInputBorder(
            borderRadius: BorderRadius.circular(15),
            borderSide: BorderSide(color: Color(0xFF2E7D32), width: 2),
          ),
          contentPadding:
              EdgeInsets.symmetric(vertical: 18.0, horizontal: 20.0),
        ),
        items: genders.map((String gender) {
          return DropdownMenuItem<String>(
            value: gender,
            child: Row(
              children: [
                Container(
                  padding: EdgeInsets.all(8),
                  decoration: BoxDecoration(
                    color: gender == '남자'
                        ? Colors.blue.withOpacity(0.1)
                        : Colors.pink.withOpacity(0.1),
                    borderRadius: BorderRadius.circular(8),
                  ),
                  child: Icon(
                    gender == '남자' ? Icons.male : Icons.female,
                    color: gender == '남자' ? Colors.blue : Colors.pink,
                    size: 20,
                  ),
                ),
                SizedBox(width: 12),
                Text(
                  gender,
                  style: TextStyle(
                    fontSize: 16,
                    color: Colors.black87,
                    fontWeight: FontWeight.w500,
                  ),
                ),
              ],
            ),
          );
        }).toList(),
        onChanged: (value) {
          setState(() {
            selectedGender = value;
          });
        },
        validator: (value) => value == null ? "성별을 선택하세요" : null,
        dropdownColor: Colors.white,
        style: TextStyle(color: Colors.black87, fontSize: 16),
        icon: Icon(Icons.keyboard_arrow_down, color: Color(0xFF2E7D32)),
        elevation: 3,
        menuMaxHeight: 200,
      ),
    );
  }
}

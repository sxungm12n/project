import 'package:flutter/material.dart';
import 'package:get/get.dart'; // GetX 패키지 임포트
import 'screens/screen_splash.dart';
import 'screens/screen_login.dart';
import 'screens/screen_index.dart';
import 'package:shared_preferences/shared_preferences.dart';

void main() async {
  WidgetsFlutterBinding.ensureInitialized(); // 비동기 초기화
  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return GetMaterialApp(
      debugShowCheckedModeBanner: false, // 디버그 배너 숨기기
      theme: ThemeData(primaryColor: Colors.blue),
      initialRoute: '/', // 첫 화면을 스플래시로 설정
      getPages: [
        GetPage(name: '/', page: () => SplashScreen()), // 스플래시 화면
        GetPage(name: '/login', page: () => LoginPage()), // 로그인 화면
        GetPage(
          name: '/index',
          page: () => FutureBuilder<String?>(
            future: getUserId(), // 사용자 ID를 비동기로 가져오기
            builder: (context, snapshot) {
              if (snapshot.connectionState == ConnectionState.waiting) {
                return const Center(
                  child: CircularProgressIndicator(), // 로딩 스피너
                );
              } else if (snapshot.hasError) {
                return Center(child: Text('Error: ${snapshot.error}'));
              } else {
                String userId = snapshot.data ?? ""; // 사용자 ID
                return IndexScreen(userId: userId); // IndexScreen으로 이동
              }
            },
          ),
        ),
      ],
    );
  }

  Future<String?> getUserId() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getString('user_id'); // 저장된 사용자 ID 반환
  }
}

import 'package:flutter/material.dart';
import 'package:get/get.dart'; // GetX import
import 'package:VitaView/tab/tab_home.dart';
import 'package:VitaView/tab/tab_review.dart';
import 'package:VitaView/tab/tab_plus.dart';
import 'package:VitaView/tab/tab_community.dart';
import 'package:VitaView/tab/tab_setting.dart';

class IndexScreen extends StatefulWidget {
  final String userId;

  IndexScreen({required this.userId});

  @override
  _IndexScreenState createState() => _IndexScreenState();
}

class _IndexScreenState extends State<IndexScreen> {
  int _currentIndex = Get.arguments ?? 0; // 전달된 인덱스를 사용 (기본값 0)

  final List<Widget> tabs = [
    TabHome(),
    TabReview(),
    TabPlus(),
    TabCommunity(),
    TabSetting(),
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text(
          'VitaView',
          style: TextStyle(
              color: Colors.white, fontWeight: FontWeight.bold, fontSize: 22),
        ),
        centerTitle: true,
        backgroundColor: Colors.green,
        elevation: 4,
        automaticallyImplyLeading: false, // 뒤로가기 버튼을 비활성화합니다.
      ),
      bottomNavigationBar: BottomNavigationBar(
        type: BottomNavigationBarType.fixed,
        backgroundColor: Colors.white,
        selectedFontSize: 12,
        unselectedFontSize: 11,
        iconSize: 28,
        selectedItemColor: Colors.green,
        unselectedItemColor: Colors.grey.shade600,
        showSelectedLabels: true,
        showUnselectedLabels: true,
        currentIndex: _currentIndex,
        onTap: (index) {
          setState(() {
            _currentIndex = index;
          });
        },
        items: const [
          BottomNavigationBarItem(icon: Icon(Icons.home), label: '홈'),
          BottomNavigationBarItem(icon: Icon(Icons.star), label: '리뷰'),
          BottomNavigationBarItem(
            icon: Icon(Icons.add_circle,
                size: 50, color: Colors.green), // 강조된 중앙 아이콘
            label: '',
          ),
          BottomNavigationBarItem(icon: Icon(Icons.group), label: '커뮤니티'),
          BottomNavigationBarItem(icon: Icon(Icons.settings), label: '설정'),
        ],
      ),
      body: AnimatedSwitcher(
        duration: const Duration(milliseconds: 300),
        child: tabs[_currentIndex],
      ),
    );
  }
}

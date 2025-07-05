import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';
import 'dart:convert';

class CommentsPage extends StatefulWidget {
  final int postId;
  final String postTitle;

  CommentsPage({required this.postId, required this.postTitle});

  @override
  _CommentsPageState createState() => _CommentsPageState();
}

class _CommentsPageState extends State<CommentsPage>
    with TickerProviderStateMixin {
  final TextEditingController _commentController = TextEditingController();
  List<dynamic> comments = [];
  String? token;
  int? userId;

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

    _getUserIdAndFetchComments();
  }

  @override
  void dispose() {
    _fadeController.dispose();
    _slideController.dispose();
    super.dispose();
  }

  Future<void> _getUserIdAndFetchComments() async {
    SharedPreferences prefs = await SharedPreferences.getInstance();
    token = prefs.getString('jwt_token');

    if (token == null) {
      print('JWT 토큰이 null입니다');
      Get.snackbar('오류', '로그인이 필요합니다.');
      return;
    }

    try {
      final response = await http.get(
        Uri.parse('http://192.168.35.173:5000/user'),
        headers: {
          'Authorization': 'Bearer $token',
        },
      );

      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        setState(() {
          userId = data['user_id'];
        });
        print("User ID: $userId"); // 디버깅용
        fetchComments();
      } else {
        print('API 호출 실패: 상태 코드 ${response.statusCode}');
        Get.snackbar('오류', '사용자 정보를 가져오지 못했습니다.');
      }
    } catch (e) {
      print('사용자 정보를 가져오는 중 오류 발생: $e');
      Get.snackbar('오류', '사용자 정보를 가져오는 중 오류 발생: $e');
    }
  }

  String formatDate(String dateTime) {
    try {
      DateTime dt = DateTime.parse(dateTime);
      return '${dt.year}년 ${dt.month}월 ${dt.day}일 ${dt.hour}시 ${dt.minute}분';
    } catch (e) {
      print('날짜 포맷 변환 오류: $e');
      return '날짜 형식 오류';
    }
  }

  Future<void> fetchComments() async {
    try {
      final response = await http.get(
        Uri.parse('http://192.168.35.173:5000/comments/${widget.postId}'),
        headers: {
          'Authorization': 'Bearer $token',
        },
      );
      if (response.statusCode == 200) {
        setState(() {
          comments = jsonDecode(response.body);
        });
        print("Comments fetched: $comments"); // 디버깅용
      } else {
        throw Exception('댓글을 불러오지 못했습니다. 상태 코드: ${response.statusCode}');
      }
    } catch (e) {
      Get.snackbar('오류', '댓글을 불러오는 중 오류 발생: $e');
    }
  }

  void addComment() async {
    final content = _commentController.text;
    if (content.isEmpty || userId == null || token == null) {
      Get.snackbar('오류', '내용을 입력하거나 로그인이 필요합니다.');
      return;
    }

    try {
      final response = await http.post(
        Uri.parse('http://192.168.35.173:5000/create_comment'),
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Bearer $token',
        },
        body: jsonEncode({
          'post_id': widget.postId,
          'user_id': userId,
          'content': content,
        }),
      );
      if (response.statusCode == 200) {
        _commentController.clear();
        fetchComments();
      } else {
        Get.snackbar('오류', '댓글을 추가하지 못했습니다. 상태 코드: ${response.statusCode}');
      }
    } catch (e) {
      Get.snackbar('오류', '댓글을 추가하는 중 오류 발생: $e');
    }
  }

  void deleteComment(int commentId) async {
    try {
      final response = await http.delete(
        Uri.parse('http://192.168.35.173:5000/delete_comment/$commentId'),
        headers: {
          'Authorization': 'Bearer $token',
        },
      );
      if (response.statusCode == 200) {
        fetchComments();
        Get.snackbar('성공', '댓글이 삭제되었습니다.');
      } else {
        Get.snackbar('오류', '댓글을 삭제하지 못했습니다. 상태 코드: ${response.statusCode}');
      }
    } catch (e) {
      Get.snackbar('오류', '댓글 삭제 중 오류 발생: $e');
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      appBar: AppBar(
        backgroundColor: Color(0xFF4CAF50),
        elevation: 0,
        title: Text(
          widget.postTitle,
          style: TextStyle(
            color: Colors.white,
            fontWeight: FontWeight.bold,
            fontSize: 20,
          ),
        ),
        centerTitle: true,
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
            Column(
              children: [
                // 헤더 섹션
                FadeTransition(
                  opacity: _fadeAnimation,
                  child: Container(
                    padding: EdgeInsets.all(30),
                    child: Column(
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
                            boxShadow: [
                              BoxShadow(
                                color: Color(0xFF4CAF50).withOpacity(0.3),
                                blurRadius: 20,
                                spreadRadius: 5,
                              ),
                            ],
                          ),
                          child: Icon(
                            Icons.chat_bubble,
                            size: 40,
                            color: Colors.white,
                          ),
                        ),
                        SizedBox(height: 20),
                        Text(
                          '커뮤니티 댓글',
                          style: TextStyle(
                            fontSize: 24.0,
                            color: Colors.black87,
                            fontWeight: FontWeight.bold,
                            letterSpacing: 1.5,
                          ),
                        ),
                        SizedBox(height: 10),
                        Text(
                          '다른 사용자들과 소통해보세요',
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
                ),

                Expanded(
                  child: comments.isNotEmpty
                      ? _buildCommentsList()
                      : _buildEmptyState(),
                ),
                _buildCommentInput(),
              ],
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildCommentsList() {
    return ListView.builder(
      padding: const EdgeInsets.symmetric(horizontal: 24.0, vertical: 16.0),
      itemCount: comments.length,
      itemBuilder: (context, index) {
        final comment = comments[index];
        print("Comment user ID: ${comment['user_id']}"); // 디버깅용
        bool isMyComment = comment['user_id'] == userId;

        return SlideTransition(
          position: _slideAnimation,
          child: FadeTransition(
            opacity: _fadeAnimation,
            child: Container(
              margin: EdgeInsets.only(bottom: 12),
              decoration: BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.circular(16),
                boxShadow: [
                  BoxShadow(
                    color: Colors.black.withOpacity(0.08),
                    blurRadius: 20,
                    spreadRadius: 2,
                    offset: Offset(0, 5),
                  ),
                ],
              ),
              child: Padding(
                padding: const EdgeInsets.all(20.0),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Row(
                      children: [
                        Container(
                          padding: EdgeInsets.all(8),
                          decoration: BoxDecoration(
                            gradient: LinearGradient(
                              begin: Alignment.topLeft,
                              end: Alignment.bottomRight,
                              colors: [
                                Color(0xFF4CAF50),
                                Color(0xFF2E7D32),
                              ],
                            ),
                            borderRadius: BorderRadius.circular(8),
                          ),
                          child: Icon(
                            Icons.person,
                            color: Colors.white,
                            size: 16,
                          ),
                        ),
                        SizedBox(width: 12),
                        Expanded(
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Text(
                                comment['username'],
                                style: TextStyle(
                                  fontSize: 16,
                                  fontWeight: FontWeight.bold,
                                  color: Colors.black87,
                                ),
                              ),
                              Text(
                                formatDate(comment['created_at']),
                                style: TextStyle(
                                  fontSize: 12,
                                  color: Colors.grey[600],
                                ),
                              ),
                            ],
                          ),
                        ),
                        if (isMyComment)
                          Container(
                            decoration: BoxDecoration(
                              color: Colors.red.withOpacity(0.1),
                              borderRadius: BorderRadius.circular(8),
                            ),
                            child: IconButton(
                              icon: Icon(Icons.delete,
                                  color: Colors.red, size: 20),
                              onPressed: () {
                                deleteComment(comment['id']);
                              },
                            ),
                          ),
                      ],
                    ),
                    SizedBox(height: 12),
                    Container(
                      width: double.infinity,
                      padding: EdgeInsets.all(16),
                      decoration: BoxDecoration(
                        color: Colors.grey[50],
                        borderRadius: BorderRadius.circular(12),
                        border: Border.all(
                          color: Colors.grey[200]!,
                          width: 1,
                        ),
                      ),
                      child: Text(
                        comment['content'],
                        style: TextStyle(
                          fontSize: 15,
                          height: 1.4,
                          color: Colors.black87,
                        ),
                      ),
                    ),
                  ],
                ),
              ),
            ),
          ),
        );
      },
    );
  }

  Widget _buildEmptyState() {
    return Center(
      child: FadeTransition(
        opacity: _fadeAnimation,
        child: Container(
          padding: EdgeInsets.all(40),
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
            mainAxisSize: MainAxisSize.min,
            children: [
              Container(
                width: 80,
                height: 80,
                decoration: BoxDecoration(
                  shape: BoxShape.circle,
                  color: Color(0xFF4CAF50).withOpacity(0.1),
                ),
                child: Icon(
                  Icons.chat_bubble_outline,
                  size: 40,
                  color: Color(0xFF4CAF50),
                ),
              ),
              SizedBox(height: 20),
              Text(
                '댓글이 없습니다',
                style: TextStyle(
                  fontSize: 20,
                  fontWeight: FontWeight.bold,
                  color: Colors.grey[600],
                ),
              ),
              SizedBox(height: 10),
              Text(
                '첫 번째 댓글을 작성해보세요!',
                style: TextStyle(
                  fontSize: 16,
                  color: Colors.grey[500],
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildCommentInput() {
    return Container(
      decoration: BoxDecoration(
        color: Colors.white,
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.1),
            blurRadius: 10,
            spreadRadius: 0,
            offset: Offset(0, -2),
          ),
        ],
      ),
      child: SafeArea(
        child: Padding(
          padding: const EdgeInsets.all(20.0),
          child: Row(
            children: [
              Expanded(
                child: Container(
                  decoration: BoxDecoration(
                    color: Colors.grey[50],
                    borderRadius: BorderRadius.circular(25),
                    border: Border.all(
                      color: Colors.grey[300]!,
                      width: 1,
                    ),
                  ),
                  child: TextField(
                    controller: _commentController,
                    decoration: InputDecoration(
                      hintText: '댓글을 입력하세요...',
                      hintStyle: TextStyle(
                        color: Colors.grey[500],
                        fontSize: 14,
                      ),
                      border: InputBorder.none,
                      contentPadding: EdgeInsets.symmetric(
                        horizontal: 20,
                        vertical: 15,
                      ),
                      prefixIcon: Icon(
                        Icons.comment,
                        color: Color(0xFF4CAF50),
                      ),
                    ),
                    maxLines: null,
                  ),
                ),
              ),
              SizedBox(width: 12),
              Container(
                decoration: BoxDecoration(
                  gradient: LinearGradient(
                    begin: Alignment.topLeft,
                    end: Alignment.bottomRight,
                    colors: [
                      Color(0xFF4CAF50),
                      Color(0xFF2E7D32),
                    ],
                  ),
                  borderRadius: BorderRadius.circular(25),
                  boxShadow: [
                    BoxShadow(
                      color: Color(0xFF4CAF50).withOpacity(0.3),
                      blurRadius: 10,
                      spreadRadius: 2,
                    ),
                  ],
                ),
                child: IconButton(
                  icon: Icon(Icons.send, color: Colors.white, size: 20),
                  onPressed: addComment,
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}

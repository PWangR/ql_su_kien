import React, { useState, useRef, useEffect } from 'react';
import {
  StyleSheet,
  View,
  Text,
  TextInput,
  TouchableOpacity,
  FlatList,
  KeyboardAvoidingView,
  Platform,
  ActivityIndicator,
  SafeAreaView,
  StatusBar
} from 'react-native';
import { MaterialIcons } from '@expo/vector-icons';
import api from '../services/api';
import Colors from '../constants/Colors';
import Typography from '../constants/Typography';

const ChatbotScreen = ({ navigation }) => {
  const [messages, setMessages] = useState([
    {
      id: '1',
      text: 'Xin chào! Tôi là trợ lý ảo Gemini. Tôi có thể giúp gì cho bạn về các sự kiện và thủ tục tại trường?',
      sender: 'ai',
      time: new Date(),
    },
  ]);
  const [input, setInput] = useState('');
  const [loading, setLoading] = useState(false);
  const flatListRef = useRef();

  const handleSend = async () => {
    if (!input.trim() || loading) return;

    const userMessage = {
      id: Date.now().toString(),
      text: input.trim(),
      sender: 'user',
      time: new Date(),
    };

    setMessages((prev) => [...prev, userMessage]);
    setInput('');
    setLoading(true);

    try {
      const response = await api.post('/chatbot/ask', { message: userMessage.text });
      const aiMessage = {
        id: (Date.now() + 1).toString(),
        text: response.data.reply,
        sender: 'ai',
        time: new Date(),
      };
      setMessages((prev) => [...prev, aiMessage]);
    } catch (error) {
      console.error('Lỗi chatbot:', error);
      const errorMessage = {
        id: (Date.now() + 1).toString(),
        text: error.response?.data?.message || 'Rất tiếc, tôi gặp lỗi kết nối. Vui lòng thử lại sau.',
        sender: 'ai',
        isError: true,
        time: new Date(),
      };
      setMessages((prev) => [...prev, errorMessage]);
    } finally {
      setLoading(false);
    }
  };

  const renderMessage = ({ item }) => {
    const isAi = item.sender === 'ai';
    return (
      <View style={[styles.messageWrapper, isAi ? styles.aiWrapper : styles.userWrapper]}>
        {isAi && (
          <View style={styles.aiAvatar}>
            <MaterialIcons name="auto-awesome" size={16} color={Colors.white} />
          </View>
        )}
        <View style={[
          styles.messageBubble, 
          isAi ? styles.aiBubble : styles.userBubble,
          item.isError && styles.errorBubble
        ]}>
          <Text style={[Typography.body, isAi ? styles.aiText : styles.userText]}>
            {item.text}
          </Text>
        </View>
      </View>
    );
  };

  useEffect(() => {
    if (messages.length > 0) {
      setTimeout(() => {
        flatListRef.current?.scrollToEnd({ animated: true });
      }, 100);
    }
  }, [messages]);

  return (
    <SafeAreaView style={styles.container}>
      <StatusBar barStyle="dark-content" />
      <View style={styles.header}>
        <TouchableOpacity onPress={() => navigation.goBack()}>
          <MaterialIcons name="close" size={24} color={Colors.text} />
        </TouchableOpacity>
        <View style={styles.headerTitleContainer}>
          <Text style={Typography.h3}>Trợ lý ảo Gemini</Text>
          <View style={styles.statusBadge}>
            <View style={styles.statusDot} />
            <Text style={styles.statusText}>Trực tuyến</Text>
          </View>
        </View>
        <MaterialIcons name="more-horiz" size={24} color={Colors.text} />
      </View>

      <FlatList
        ref={flatListRef}
        data={messages}
        renderItem={renderMessage}
        keyExtractor={(item) => item.id}
        contentContainerStyle={styles.messageList}
        showsVerticalScrollIndicator={false}
      />

      {loading && (
        <View style={styles.loadingBox}>
          <ActivityIndicator size="small" color={Colors.primary} />
          <Text style={[Typography.caption, { color: Colors.textMuted }]}>Gemini đang trả lời...</Text>
        </View>
      )}

      <KeyboardAvoidingView
        behavior={Platform.OS === 'ios' ? 'padding' : 'height'}
        keyboardVerticalOffset={Platform.OS === 'ios' ? 90 : 0}
        style={styles.inputArea}
      >
        <View style={styles.inputBox}>
          <TextInput
            style={styles.input}
            placeholder="Đặt câu hỏi về sự kiện..."
            value={input}
            onChangeText={setInput}
            multiline
          />
          <TouchableOpacity 
            style={[styles.sendBtn, !input.trim() && styles.sendBtnDisabled]} 
            onPress={handleSend}
            disabled={!input.trim() || loading}
          >
            <MaterialIcons name="send" size={20} color={Colors.white} />
          </TouchableOpacity>
        </View>
      </KeyboardAvoidingView>
    </SafeAreaView>
  );
};

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: '#F8FAFC' },
  header: { 
    flexDirection: 'row', 
    alignItems: 'center', 
    padding: 16, 
    backgroundColor: Colors.white, 
    borderBottomWidth: 1, 
    borderBottomColor: Colors.border,
    justifyContent: 'space-between'
  },
  headerTitleContainer: { alignItems: 'center' },
  statusBadge: { flexDirection: 'row', alignItems: 'center', gap: 4, marginTop: 2 },
  statusDot: { width: 6, height: 6, borderRadius: 3, backgroundColor: Colors.success },
  statusText: { fontSize: 10, color: Colors.textMuted, fontWeight: '700', textTransform: 'uppercase' },
  messageList: { padding: 16 },
  messageWrapper: { marginBottom: 16, maxWidth: '80%' },
  aiWrapper: { alignSelf: 'flex-start', flexDirection: 'row', gap: 8 },
  userWrapper: { alignSelf: 'flex-end' },
  aiAvatar: { width: 28, height: 28, borderRadius: 14, backgroundColor: '#4F46E5', justifyContent: 'center', alignItems: 'center' },
  messageBubble: { padding: 12, borderRadius: 12 },
  aiBubble: { backgroundColor: Colors.white, borderTopLeftRadius: 0, borderWidth: 1, borderColor: Colors.border },
  userBubble: { backgroundColor: '#4F46E5', borderTopRightRadius: 0 },
  aiText: { color: Colors.text },
  userText: { color: Colors.white },
  errorBubble: { backgroundColor: Colors.dangerBg, borderColor: Colors.danger },
  loadingBox: { flexDirection: 'row', alignItems: 'center', gap: 8, paddingHorizontal: 16, marginBottom: 16 },
  inputArea: { padding: 16, backgroundColor: Colors.white, borderTopWidth: 1, borderTopColor: Colors.border },
  inputBox: { flexDirection: 'row', alignItems: 'center', backgroundColor: '#F1F5F9', borderRadius: 24, paddingLeft: 16, paddingRight: 4, paddingVertical: 4 },
  input: { flex: 1, maxHeight: 100, fontSize: 15, paddingVertical: 8 },
  sendBtn: { width: 40, height: 40, borderRadius: 20, backgroundColor: '#4F46E5', justifyContent: 'center', alignItems: 'center' },
  sendBtnDisabled: { backgroundColor: Colors.border },
});

export default ChatbotScreen;

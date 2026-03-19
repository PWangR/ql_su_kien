import React, { useState } from 'react';
import { StyleSheet, Text, View, ScrollView, TouchableOpacity, Alert, ActivityIndicator } from 'react-native';
import api from '../services/api';

const EventDetailScreen = ({ route }) => {
  const { event } = route.params;
  const [registering, setRegistering] = useState(false);

  const handleRegister = async () => {
    setRegistering(true);
    try {
      const response = await api.post(`/registrations/${event.ma_su_kien}`);
      Alert.alert('Thành công', 'Bạn đã đăng ký tham gia sự kiện thành công!');
    } catch (error) {
      const msg = error.response?.data?.message || 'Có lỗi xảy ra khi đăng ký';
      Alert.alert('Thông báo', msg);
    } finally {
      setRegistering(false);
    }
  };

  return (
    <ScrollView style={styles.container}>
      <View style={styles.header}>
        <Text style={styles.title}>{event.ten_su_kien}</Text>
      </View>
      
      <View style={styles.content}>
        <Text style={styles.label}>🕓 Thời gian:</Text>
        <Text style={styles.text}>{new Date(event.thoi_gian_bat_dau).toLocaleString()}</Text>
        
        <Text style={styles.label}>📍 Địa điểm:</Text>
        <Text style={styles.text}>{event.dia_diem}</Text>
        
        <Text style={styles.label}>📝 Mô tả:</Text>
        <Text style={styles.text}>{event.mo_ta || 'Không có mô tả.'}</Text>
        
        <TouchableOpacity 
          style={styles.regButton} 
          onPress={handleRegister}
          disabled={registering}
        >
          {registering ? (
            <ActivityIndicator color="#fff" />
          ) : (
            <Text style={styles.regText}>Đăng Ký Tham Gia</Text>
          )}
        </TouchableOpacity>
      </View>
    </ScrollView>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#fff',
  },
  header: {
    padding: 20,
    backgroundColor: '#007bff',
  },
  title: {
    fontSize: 22,
    fontWeight: 'bold',
    color: '#fff',
  },
  content: {
    padding: 20,
  },
  label: {
    fontSize: 16,
    fontWeight: 'bold',
    marginTop: 15,
    color: '#555',
  },
  text: {
    fontSize: 16,
    color: '#333',
    lineHeight: 24,
    marginTop: 5,
  },
  regButton: {
    backgroundColor: '#28a745',
    padding: 15,
    borderRadius: 8,
    marginTop: 30,
    alignItems: 'center',
    marginBottom: 30,
  },
  regText: {
    color: '#fff',
    fontSize: 18,
    fontWeight: 'bold',
  },
});

export default EventDetailScreen;

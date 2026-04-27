import React from 'react';
import { createNativeStackNavigator } from '@react-navigation/native-stack';
import { createBottomTabNavigator } from '@react-navigation/bottom-tabs';
import { NavigationContainer } from '@react-navigation/native';
import { MaterialIcons } from '@expo/vector-icons';
import useAuthStore from '../store/authStore';
import Colors from '../constants/Colors';

// Màn hình
import LoginScreen from '../screens/LoginScreen';
import EventListScreen from '../screens/EventListScreen';
import EventDetailScreen from '../screens/EventDetailScreen';
import ProfileScreen from '../screens/ProfileScreen';
import NotificationScreen from '../screens/NotificationScreen';
import QRScannerScreen from '../screens/QRScannerScreen';
import BauCuListScreen from '../screens/BauCuListScreen';
import BauCuDetailScreen from '../screens/BauCuDetailScreen';
import ParticipationHistoryScreen from '../screens/ParticipationHistoryScreen';
import ChatbotScreen from '../screens/ChatbotScreen';
import HomeScreen from '../screens/HomeScreen';

const Stack = createNativeStackNavigator();
const Tab = createBottomTabNavigator();

// Tab Navigator cho các màn hình chính sau khi đăng nhập
const MainTabNavigator = () => {
  return (
    <Tab.Navigator
      screenOptions={({ route }) => ({
        tabBarIcon: ({ color, size }) => {
          let iconName;
          if (route.name === 'Home') iconName = 'home';
          else if (route.name === 'EventsList') iconName = 'event-note';
          else if (route.name === 'VotingList') iconName = 'how-to-vote';
          else if (route.name === 'Profile') iconName = 'person-outline';
          return <MaterialIcons name={iconName} size={24} color={color} />;
        },
        tabBarActiveTintColor: Colors.primary,
        tabBarInactiveTintColor: Colors.textMuted,
        tabBarStyle: {
          height: 60,
          paddingBottom: 10,
          paddingTop: 5,
          backgroundColor: '#fff',
          borderTopWidth: 1,
          borderTopColor: Colors.border,
        },
        tabBarLabelStyle: {
          fontSize: 11,
          fontWeight: '700',
        },
        headerShown: false,
      })}
    >
      <Tab.Screen name="Home" component={HomeScreen} options={{ title: 'Trang chủ' }} />
      <Tab.Screen name="EventsList" component={EventListScreen} options={{ title: 'Sự kiện' }} />
      <Tab.Screen name="VotingList" component={BauCuListScreen} options={{ title: 'Bầu cử' }} />
      <Tab.Screen name="Profile" component={ProfileScreen} options={{ title: 'Cá nhân' }} />
    </Tab.Navigator>
  );
};

const AppNavigator = () => {
  const { token, isLoading } = useAuthStore();

  if (isLoading) {
    return null;
  }

  return (
    <NavigationContainer>
      <Stack.Navigator>
        {token == null ? (
          <Stack.Screen
            name="Login"
            component={LoginScreen}
            options={{ headerShown: false }}
          />
        ) : (
          <>
            {/* Main Tabs */}
            <Stack.Screen
              name="Main"
              component={MainTabNavigator}
              options={{ headerShown: false }}
            />
            {/* Các màn hình chi tiết (không nằm trong tab) */}
            <Stack.Screen
              name="EventDetail"
              component={EventDetailScreen}
              options={{ 
                title: 'Chi tiết sự kiện',
                headerBackTitle: 'Quay lại'
              }}
            />
            <Stack.Screen
              name="BauCuDetail"
              component={BauCuDetailScreen}
              options={{ 
                title: 'Chi tiết bầu cử',
                headerBackTitle: 'Quay lại'
              }}
            />
            <Stack.Screen
              name="QRScanner"
              component={QRScannerScreen}
              options={{ 
                title: 'Quét QR Điểm Danh',
                headerBackTitle: 'Quay lại'
              }}
            />
            <Stack.Screen
              name="ParticipationHistory"
              component={ParticipationHistoryScreen}
              options={{ 
                title: 'Lịch sử tham gia',
                headerBackTitle: 'Quay lại'
              }}
            />
            <Stack.Screen
              name="Chatbot"
              component={ChatbotScreen}
              options={{ 
                headerShown: false
              }}
            />
          </>
        )}
      </Stack.Navigator>
    </NavigationContainer>
  );
};


export default AppNavigator;

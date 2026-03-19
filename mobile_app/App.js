import React, { useEffect } from 'react';
import { StatusBar } from 'expo-status-bar';
import AppNavigator from './src/navigation/AppNavigator';
import useAuthStore from './src/store/authStore';

export default function App() {
  const restoreToken = useAuthStore((state) => state.restoreToken);

  useEffect(() => {
    restoreToken();
  }, []);

  return (
    <>
      <StatusBar style="auto" />
      <AppNavigator />
    </>
  );
}

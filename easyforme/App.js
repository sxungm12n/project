import React from 'react';
import { NavigationContainer } from '@react-navigation/native';
import { LanguageProvider } from './src/context/LanguageContext';
import AppNavigator from './src/navigation/AppNavigator';

export default function App() {
  return (
    <LanguageProvider>
      <NavigationContainer>
        <AppNavigator />
      </NavigationContainer>
    </LanguageProvider>
  );
}

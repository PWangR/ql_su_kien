import { StyleSheet } from 'react-native';

const Typography = StyleSheet.create({
  // Serif Fonts (for Headings)
  h1: {
    fontFamily: 'PlayfairDisplay_700Bold',
    fontSize: 28,
    lineHeight: 34,
  },
  h2: {
    fontFamily: 'PlayfairDisplay_600SemiBold',
    fontSize: 22,
    lineHeight: 28,
  },
  h3: {
    fontFamily: 'PlayfairDisplay_600SemiBold',
    fontSize: 18,
    lineHeight: 24,
  },
  
  // Sans-serif Fonts (for Body)
  body: {
    fontFamily: 'Inter_400Regular',
    fontSize: 15,
    lineHeight: 22,
  },
  bodySemiBold: {
    fontFamily: 'Inter_600SemiBold',
    fontSize: 15,
    lineHeight: 22,
  },
  bodyBold: {
    fontFamily: 'Inter_700Bold',
    fontSize: 15,
    lineHeight: 22,
  },
  caption: {
    fontFamily: 'Inter_400Regular',
    fontSize: 12,
    lineHeight: 18,
  },
  label: {
    fontFamily: 'Inter_600SemiBold',
    fontSize: 11,
    letterSpacing: 0.5,
    textTransform: 'uppercase',
  }
});

export default Typography;

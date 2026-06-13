import React, { useMemo, useState } from 'react';
import { Image, StyleSheet, Text, View } from 'react-native';
import { MaterialIcons } from '@expo/vector-icons';
import Colors from '../constants/Colors';
import { BASE_URL } from '../services/api';

export const storageUrl = (path) => {
  if (!path || typeof path !== 'string') return null;

  const trimmed = path.trim();
  if (!trimmed) return null;
  if (/^https?:\/\//i.test(trimmed)) return trimmed;

  const normalized = trimmed
    .replace(/^\/+/, '')
    .replace(/^storage\//, '');

  return `${BASE_URL}/storage/${normalized}`;
};

const RemoteImage = ({
  path,
  uri,
  style,
  resizeMode = 'cover',
  fallbackIcon = 'image',
  fallbackText,
  fallbackStyle,
  iconColor = Colors.textMuted,
  children,
}) => {
  const [failed, setFailed] = useState(false);
  const resolvedUri = useMemo(() => uri || storageUrl(path), [path, uri]);

  if (!resolvedUri || failed) {
    return (
      <View style={[style, styles.fallback, fallbackStyle]}>
        <MaterialIcons name={fallbackIcon} size={40} color={iconColor} />
        {fallbackText ? <Text style={styles.fallbackText}>{fallbackText}</Text> : null}
        {children}
      </View>
    );
  }

  return (
    <Image
      source={{ uri: resolvedUri }}
      style={style}
      resizeMode={resizeMode}
      onError={() => setFailed(true)}
    />
  );
};

const styles = StyleSheet.create({
  fallback: {
    alignItems: 'center',
    justifyContent: 'center',
    backgroundColor: Colors.borderLight,
  },
  fallbackText: {
    color: Colors.textMuted,
    fontSize: 13,
    marginTop: 8,
    textAlign: 'center',
  },
});

export default RemoteImage;

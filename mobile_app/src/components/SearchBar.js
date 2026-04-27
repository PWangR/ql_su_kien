import React, { useState } from 'react';
import { StyleSheet, View, TextInput, TouchableOpacity } from 'react-native';
import { MaterialIcons } from '@expo/vector-icons';
import Colors from '../constants/Colors';

const SearchBar = ({ placeholder = 'Tìm kiếm sự kiện...', onSearch, onClear }) => {
  const [text, setText] = useState('');

  const handleSearch = () => {
    onSearch(text);
  };

  const handleClear = () => {
    setText('');
    onClear();
  };

  const handleSubmitEditing = () => {
    handleSearch();
  };

  return (
    <View style={styles.container}>
      <View style={styles.searchInputContainer}>
        <MaterialIcons name="search" size={20} color={Colors.textMuted} style={styles.searchIcon} />
        <TextInput
          style={styles.input}
          placeholder={placeholder}
          placeholderTextColor={Colors.textMuted}
          value={text}
          onChangeText={setText}
          onSubmitEditing={handleSubmitEditing}
          returnKeyType="search"
        />
        {text !== '' && (
          <TouchableOpacity
            onPress={handleClear}
            style={styles.clearButton}
            hitSlop={{ top: 10, bottom: 10, left: 10, right: 10 }}
          >
            <MaterialIcons name="close" size={18} color={Colors.textMuted} />
          </TouchableOpacity>
        )}
      </View>
      <TouchableOpacity
        style={styles.searchButton}
        onPress={handleSearch}
        activeOpacity={0.8}
      >
        <MaterialIcons name="search" size={20} color={Colors.white} />
      </TouchableOpacity>
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    flexDirection: 'row',
    gap: 10,
    paddingHorizontal: 16,
    paddingVertical: 12,
    backgroundColor: Colors.white,
    borderBottomWidth: 1,
    borderBottomColor: Colors.borderLight,
  },
  searchInputContainer: {
    flex: 1,
    flexDirection: 'row',
    alignItems: 'center',
    borderWidth: 1,
    borderColor: Colors.border,
    borderRadius: 8,
    paddingHorizontal: 12,
    backgroundColor: Colors.background,
  },
  searchIcon: {
    marginRight: 8,
  },
  input: {
    flex: 1,
    fontSize: 15,
    color: Colors.text,
    paddingVertical: 8,
  },
  clearButton: {
    padding: 4,
  },
  searchButton: {
    backgroundColor: Colors.primary,
    borderRadius: 8,
    justifyContent: 'center',
    alignItems: 'center',
    paddingHorizontal: 16,
    elevation: 2,
    shadowColor: Colors.primary,
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.2,
    shadowRadius: 4,
  },
});

export default SearchBar;

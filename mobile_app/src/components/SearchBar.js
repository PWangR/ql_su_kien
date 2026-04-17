import React, { useState } from 'react';
import { StyleSheet, View, TextInput, TouchableOpacity } from 'react-native';
import { MaterialIcons } from '@expo/vector-icons';

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
        <MaterialIcons name="search" size={20} color="#6c757d" style={styles.searchIcon} />
        <TextInput
          style={styles.input}
          placeholder={placeholder}
          placeholderTextColor="#adb5bd"
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
            <MaterialIcons name="close" size={18} color="#6c757d" />
          </TouchableOpacity>
        )}
      </View>
      <TouchableOpacity
        style={styles.searchButton}
        onPress={handleSearch}
        activeOpacity={0.7}
      >
        <MaterialIcons name="search" size={20} color="#fff" />
      </TouchableOpacity>
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    flexDirection: 'row',
    gap: 8,
    paddingHorizontal: 16,
    paddingVertical: 12,
    backgroundColor: '#fff',
  },
  searchInputContainer: {
    flex: 1,
    flexDirection: 'row',
    alignItems: 'center',
    borderWidth: 1,
    borderColor: '#dee2e6',
    borderRadius: 8,
    paddingHorizontal: 10,
    backgroundColor: '#f8f9fa',
  },
  searchIcon: {
    marginRight: 8,
  },
  input: {
    flex: 1,
    fontSize: 14,
    color: '#212529',
    paddingVertical: 10,
  },
  clearButton: {
    padding: 4,
  },
  searchButton: {
    backgroundColor: '#007bff',
    borderRadius: 8,
    justifyContent: 'center',
    alignItems: 'center',
    paddingHorizontal: 14,
  },
});

export default SearchBar;
